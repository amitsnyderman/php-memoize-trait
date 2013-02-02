<?php

interface MemoizerCache {
	function get($key, $callback);
	function set($key, $value);
}

class InMemoryMemoizerCache implements MemoizerCache {
	private $cache = [];

	public function get($key, $callback) {
		if (array_key_exists($key, $this->cache)) {
			return $this->cache[$key];
		}
		return $this->set($key, call_user_func($callback));
	}

	public function set($key, $value) {
		$this->cache[$key] = $value;
		return $value;
	}
}