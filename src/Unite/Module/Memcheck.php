<?php

namespace Unite\Module;


use Unite\FailureException;
use Unite\Test;

class MemCheck {
	const TEST = 1;
	const TEST_CASE = 2;

	public function __construct($unite) {
		$this->bindParam("memcheck", self::TEST, "after", [$this, "check"]);
	}

	public function check(Test $test) {
		$check = clone $test;
		$leaks = array_pad(array(), 3, 0);
		for($i=0; $i<3; $i++) {
			$check->before();
			$check->run([]);
			$check->after();
			if($check->memory > 0) {
				$leaks[$i] = $check->memory;
			}
		}
		if(array_sum($leaks)) {
			throw new FailureException("Memory leak detected: ".implode(" B, ", $leaks));
		}
	}

	public function bindParam($param_name, $scope, $event, $callback) {

	}
}