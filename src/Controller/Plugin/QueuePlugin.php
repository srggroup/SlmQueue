<?php

namespace SlmQueue\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use SlmQueue\Controller\Exception\QueueNotFoundException;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Queue\QueuePluginManager;

class QueuePlugin extends AbstractPlugin {


	/**
	 * Plugin manager for queues
	 *
	 * @var QueuePluginManager
	 */
	protected $queuePluginManager;

	/**
	 * Plugin manager for jobs
	 *
	 * @var JobPluginManager
	 */
	protected $jobPluginManager;

	/**
	 * Current selected queue
	 *
	 * @var QueueInterface
	 */
	protected $queue;


	public function __construct(QueuePluginManager $queuePluginManager, JobPluginManager $jobPluginManager) {
		$this->queuePluginManager = $queuePluginManager;
		$this->jobPluginManager = $jobPluginManager;
	}


	/**
	 * Invoke plugin and optionally set queue
	 */
	public function __invoke(?string $name = null): self {
		if ($name !== null) {
			if (!$this->queuePluginManager->has($name)) {
				throw new QueueNotFoundException(
					sprintf("Queue '%s' does not exist", $name)
				);
			}

			$this->queue = $this->queuePluginManager->get($name);
		}

		return $this;
	}


	/**
	 * Push a job by its name onto the selected queue
	 *
	 * @param string $name   Name of the job to create
	 * @param mixed $payload Payload of the job set as content
	 * @param array $options Push job options
	 * @return JobInterface    Created job by the job plugin manager
	 * @throws QueueNotFoundException If the method is called without a queue set.
	 */
	public function push(string $name, $payload = null, array $options = []): JobInterface {
		$this->assertQueueIsSet();

		$job = $this->jobPluginManager->get($name);
		if ($payload !== null) {
			$job->setContent($payload);
		}

		$this->queue->push($job, $options);

		return $job;
	}


	/**
	 * Push a job on the selected queue
	 *
	 * @param array $options Push job options
	 * @throws QueueNotFoundException If the method is called without a queue set.
	 */
	public function pushJob(JobInterface $job, array $options = []): void {
		$this->assertQueueIsSet();

		$this->queue->push($job, $options);
	}


	/**
	 * @throws QueueNotFoundException
	 */
	protected function assertQueueIsSet(): void {
		if ($this->queue === null) {
			throw new QueueNotFoundException(
				'You cannot push a job without a queue selected'
			);
		}
	}


}
