<?php

namespace SlmQueue\Worker\Result;

final class ProcessStateResult {


	/** @var string */
	private $state;


	private function __construct(string $state) {
		$this->state = $state;
	}


	public function getState(): string {
		return $this->state;
	}


	public static function withState(string $state): ProcessStateResult {
		return new ProcessStateResult($state);
	}


	public function __toString(): string {
		return $this->state;
	}


}
