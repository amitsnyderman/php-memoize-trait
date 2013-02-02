<?php

class Memoizer {
	private $memoized = [];

	public function memoize(callable $callback, $params) {
		$bucket = $this->getMethod($callback);
		$key = $this->getHash($params);
		if(!array_key_exists($bucket, $this->memoized)) {
			$this->memoized[$bucket] = [];
		}
		if (array_key_exists($key, $this->memoized[$bucket])) {
			return $this->memoized[$bucket][$key];
		}
		return ($this->memoized[$bucket][$key] = call_user_func_array($callback, $params));
	}

	private function getMethod(callable $callback) {
		if (count($callback) == 1) {
			return array_shift($callback);
		}
		list($class, $method) = $callback;
		if (is_string($class)) {
			return $class;
		}
		return get_class($class);
	}

	private function getHash($data) {
		return md5(serialize(func_get_args()));
	}
}