<?php

trait Memoize {
	private $memoized = [];

	public function __call($method, $params) {
		$key = $this->getHash($params);
		$method = $this->getMethod($method);
		if ($method === false) {
			throw new BadMethodCalException();
		}
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
		if (!substr($method, 0, 1) == '_') {
			return null;
		}
		$method = substr($method, 1);
		$exists = method_exists($this, $method);
		if ($exists) {
			return $method;
		}
		return null;
	}
}