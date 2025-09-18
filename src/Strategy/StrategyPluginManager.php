<?php

namespace SlmQueue\Strategy;

use Laminas\ServiceManager\AbstractPluginManager;
use SlmQueue\Exception\RuntimeException;

class StrategyPluginManager extends AbstractPluginManager {


	protected $shareByDefault = false;


	/**
	 * {@inheritDoc}
	 */
	public function validate($instance): void {
		if ($instance instanceof AbstractStrategy) {
			return; // we're okay
		}

		throw new RuntimeException(sprintf(
			'Plugin of type %s is invalid; must extend SlmQueue\Strategy\AbstractStrategy',
			(is_object($instance) ? $instance::class : gettype($instance))
		));
	}


}
