<?php

interface MemoizerCache {
	function get($key, callable $callback = null);
	function set($key, $value);
}

class InMemoryMemoizerCache implements MemoizerCache {
	private $cache = [];

	public function get($key, callable $callback = null) {
		if (array_key_exists($key, $this->cache)) {
			return $this->cache[$key];
		}
		if (isset($callback)) {
			return $this->set($key, call_user_func($callback));
		}
		return null;
	}

	public function set($key, $value) {
		$this->cache[$key] = $value;
		return $value;
	}
}