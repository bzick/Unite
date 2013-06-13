<?php

namespace Unite;


use Unite\Test\Suite;
use Unite\Test\TestCase;

class Listener implements ListenerInterface {
	public $start_time = 0.0;
	public $total_time = 0.0;

	public function begin() {
		$this->start_time = microtime(true);
	}

	public function beginTestSuit(Suite $suit) {
	}

	public function beginTestCase(TestCase $case) {
		// TODO: Implement beginTestCase() method.
	}

	public function beginTest(Test $test) {
		// TODO: Implement beginTest() method.
	}

	public function assert(Assert $assert) {
		// TODO: Implement assert() method.
	}

	public function endTest(Test $test) {
		// TODO: Implement endTest() method.
	}

	public function endTestCase(TestCase $case) {
		// TODO: Implement endTestCase() method.
	}

	public function endTestSuit(Suite $suit) {
		// TODO: Implement endTestSuit() method.
	}

	public function end() {
		// TODO: Implement end() method.
	}
}