<?php

trait Memoize {
	private $memoized = [];

	public function __call($method, $params) {
		return $this->memoize($this->getMethod($method), $params);
	}

	public function memoize($method, $params) {
		if (!method_exists($this, $method)) {
			throw new BadMethodCallException();
		}
		$key = $this->getHash($params);
		if(!array_key_exists($method, $this->memoized)) {
			$this->memoized[$method] = [];
		}
		if (array_key_exists($key, $this->memoized[$method])) {
			print "using memoize\n";
			return $this->memoized[$method][$key];
		}
		return ($this->memoized[$method][$key] = call_user_func_array(array($this, $method), $params));
	}

	private function getHash($data) {
		return md5(serialize(func_get_args()));
	}

	private function getMethod($method) {
		if (strpos($method, '_') === 0) {
			return substr($method, 1);
		}
		return null;
	}
}