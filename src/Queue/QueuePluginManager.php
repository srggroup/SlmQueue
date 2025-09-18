<?php

namespace SlmQueue\Queue;

use Laminas\ServiceManager\AbstractPluginManager;

/**
 * @method QueueInterface get($name, ?array $options = null)
 */
class QueuePluginManager extends AbstractPluginManager {


	public function validate($instance): void {
		if ($instance instanceof QueueInterface) {
			return; // we're okay!
		}

		throw new Exception\RuntimeException(sprintf(
			'Plugin of type %s is invalid; must implement SlmQueue\Queue\QueueInterface',
			(is_object($instance) ? $instance::class : gettype($instance))
		));
	}


}
