<?php

namespace SlmQueue\Worker\Event;

use Laminas\EventManager\Event;
use SlmQueue\Worker\WorkerInterface;

abstract class AbstractWorkerEvent extends Event implements WorkerEventInterface {


	/**
	 * @param string $name
	 */
	//@phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod
	public function __construct($name, WorkerInterface $target) {
		parent::__construct($name, $target);
	}


	public function getWorker(): WorkerInterface {
		/** @var WorkerInterface $worker */
		$worker = $this->getTarget();

		return $worker;
	}


}
