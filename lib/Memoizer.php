<?php

class Memoizer {
	private $cache;

	public function __construct(MemoizerCache $cache) {
		$this->cache = $cache;
	}

	public function memoize(callable $callback, $params) {
		$bucket = $this->getMethod($callback);
		$key = $this->getHash($params);

		return $this->cache->get($bucket.$key, function() use ($callback, $params) {
			return call_user_func_array($callback, $params);
		});
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