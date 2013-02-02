<?php

require 'lib/Memoize.php';

class MemoizeTest extends PHPUnit_Framework_TestCase {
	public function testMemoizeNoArguments() {
		$c = new SimpleCounter();
		$c->_call();
		$c->_call();

		$this->assertEquals(1, $c->calls);
	}

	public function testMemoizeWithArguments() {
		$c = new ArgumentCounter();
		$c->_call('one');
		$c->_call('two');
		$c->_call('two');

		$this->assertEquals(1, $c->calls['one']);
		$this->assertEquals(1, $c->calls['two']);
	}

	public function testBadMethodException() {
		$this->setExpectedException('BadMethodCallException');

		$c = new SimpleCounter();
		$c->_missingMethod();
	}

	public function testMemoizeFactorial() {
		$m = new Math();
		$m->_factorial(100);
		$m->_factorial(100);
		$m->_factorial(100);

		$this->assertEquals(101, $m->calls);
	}

	public function testMemoizeMagic() {
		$m = new Magic();
		$m->_dazzle();
		$m->_dazzle();
		$m->_dazzle();

		$this->assertEquals(1, $m->calls);
	}
}

class SimpleCounter {
	use Memoize;
	public $calls = 0;
	public function call() {
		$this->calls++;
	}
}

class ArgumentCounter {
	use Memoize;
	public $calls = [];
	public function call($arg) {
		if (!array_key_exists($arg, $this->calls)) {
			$this->calls[$arg] = 0;
		}
		$this->calls[$arg]++;
	}
}

class Math {
	use memoize;
	public $calls = 0;
	public function factorial($i) {
		$this->calls++;
		return ($i < 1) ? 1 : $i * $this->factorial($i - 1);
	}
}

class Magic {
	use memoize {
		__call as m;
	}
	public $calls = 0;
	public function dazzle() {
		$this->calls++;
	}

	public function __call($method, $params) {
		return $this->m($method, $params);
	}
}