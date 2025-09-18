<?php

namespace SlmQueue\Job;

use Laminas\Stdlib\Message;
use ReflectionClass;
use ReflectionException;

/**
 * This class is supposed to be extended. To create a job, just implements the missing "execute" method. If a queueing
 * system needs more information, you can extend this class (but for both Beanstalk and SQS this is enough)
 */
abstract class AbstractJob extends Message implements JobInterface {


	/** @var string|array|null */
	protected $content = null;


	public function setId($id): JobInterface {
		$this->setMetadata('__id__', $id);

		return $this;
	}


	/**
	 * {@inheritDoc}
	 */
	public function getId() {
		return $this->getMetadata('__id__');
	}


	/**
	 * This is a utility method that one can use to create a job without passing the constructor arguments.
	 *
	 * This is useful if you want to push a job on the queue, without having to load, or set it's dependencies. For
	 * example in a controller you can now run:
	 *
	 * > $this->queue('default')->push(SomeJob::create($someData));
	 *
	 * And that static method can use `self::createEmptyJob` internally.
	 *
	 * @param mixed $content
	 * @return static
	 *
	 * TODO Add static as return type, as soon as we only support PHP8.
	 * TODO Make sure $content is always an array?
	 * @throws ReflectionException
	 * @throws ReflectionException
	 */
	protected static function createEmptyJob($content = null) {
		$job = new ReflectionClass(static::class)->newInstanceWithoutConstructor();
		$job->setContent($content);

		return $job;
	}


}
