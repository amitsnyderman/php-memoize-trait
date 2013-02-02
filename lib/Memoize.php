<?php

trait Memoize {
	private $memoizer;

	public function __call($method, $params) {
		return $this->memoize($this->getMethod($method), $params);
	}

	public function memoize($method, $params) {
		if (!isset($this->memoizer)) {
			$this->memoizer = new Memoizer();
		}
		return $this->memoizer->memoize([$this, $method], $params);
	}

	private function getMethod($method) {
		if (strpos($method, '_') === 0) {
			return substr($method, 1);
		}
		return null;
	}
}