<?php

namespace SlmQueue\Worker;

use Laminas\EventManager\EventManagerInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\Event\BootstrapEvent;
use SlmQueue\Worker\Event\FinishEvent;
use SlmQueue\Worker\Event\ProcessQueueEvent;
use SlmQueue\Worker\Event\ProcessStateEvent;
use SlmQueue\Worker\Result\ExitWorkerLoopResult;

abstract class AbstractWorker implements WorkerInterface {


	/** @var EventManagerInterface */
	protected $eventManager;


	public function __construct(EventManagerInterface $eventManager) {
		$eventManager->setIdentifiers([
			self::class,
			static::class,
			WorkerInterface::class,
		]);

		$this->eventManager = $eventManager;
	}


	public function processQueue(QueueInterface $queue, array $options = []): array {
		$this->eventManager->triggerEvent(new BootstrapEvent($this, $queue));

		$shouldExitWorkerLoop = false;
		while (! $shouldExitWorkerLoop) {
			$exitReasons = $this->eventManager->triggerEventUntil(
				static function ($response) {
					return $response instanceof ExitWorkerLoopResult;
				},
				new ProcessQueueEvent($this, $queue, $options)
			);

			if ($exitReasons->stopped() && $exitReasons->last()) {
				$shouldExitWorkerLoop = true;
			}
		}

		$this->eventManager->triggerEvent(new FinishEvent($this, $queue));

		$queueState = $this->eventManager->triggerEvent(new ProcessStateEvent($this));
		$queueState = array_filter(iterator_to_array($queueState));

		if (isset($exitReasons) && $exitReasons->last()) {
			$queueState[] = $exitReasons->last();
		}

		// cast to string
		return array_map('strval', $queueState);
	}


	public function getEventManager(): EventManagerInterface {
		return $this->eventManager;
	}


}
