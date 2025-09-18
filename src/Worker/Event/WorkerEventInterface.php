<?php

namespace SlmQueue\Worker\Event;

use SlmQueue\Worker\WorkerInterface;

interface WorkerEventInterface {


	/**
	 * Various events you can subscribe to
	 */
	public const string EVENT_BOOTSTRAP     = 'bootstrap';
	public const string EVENT_FINISH        = 'finish';
	public const string EVENT_PROCESS_QUEUE = 'process.queue';
	public const string EVENT_PROCESS_JOB   = 'process.job';
	public const string EVENT_PROCESS_IDLE  = 'process.idle';
	public const string EVENT_PROCESS_STATE = 'process.state';


	public function getWorker(): WorkerInterface;


}
