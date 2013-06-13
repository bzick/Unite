<?php

namespace Unite;


use Unite\Test\Suite;
use Unite\Test\TestCase;

class Printer implements  ListenerInterface {
	public $i = 0 ;
	/**
	 * Start of the testing
	 * @param \Unite $unite
	 * @return mixed
	 */
	public function begin(\Unite $unite) {
		echo "Found ".count($unite->tests)." tests\n";
	}

	/**
	 * Start of the test suite
	 * @param Suite $suit
	 * @return mixed
	 */
	public function beginTestSuite(Suite $suit) {
		echo "Start suite ".$suit->name."\n";
	}

	/**
	 * Start of the test case
	 * @param TestCase $case
	 * @return mixed
	 */
	public function beginTestCase(TestCase $case) {
		echo "Start case ".$case->name."\n";
	}

	/**
	 * Start of the test
	 * @param Test $test
	 * @return mixed
	 */
	public function beginTest(Test $test) {
		echo " ".(++$this->i)." / ".count($test->unite->tests)." ".$test->name." ... ";
	}

	/**
	 * Assert
	 * @param Assert $assert
	 * @return mixed
	 */
	public function assert(Assert $assert) {
	}

	/**
	 * Event invoke if test was skipped by TestCase's reason (error or skip)
	 * @param Test $test
	 * @return mixed
	 */
	public function drainTest(Test $test) {
		echo " ".(++$this->i)." / ".count($test->unite->tests)." ".$test->name." drained.\n";
	}

	/**
	 * End of the test
	 * @param Test $test
	 * @return mixed
	 */
	public function endTest(Test $test) {
		if($test->reason) {
			echo " failed.\n";
		} else {
			echo " done.\n";
		}
	}

	/**
	 * Event invoke if TestCase was skipped by TestSuite's reason (error or skip)
	 * @param TestCase $case
	 * @return mixed
	 */
	public function drainTestCase(TestCase $case) {
		echo "Drain case ".$case->name."\n";
	}

	/**
	 * End of the test case
	 * @param TestCase $case
	 * @return mixed
	 */
	public function endTestCase(TestCase $case) {
		echo "\n";
	}

	/**
	 * End of the test suite
	 * @param Suite $suit
	 * @return mixed
	 */
	public function endTestSuite(Suite $suit) {
		echo "\n";
	}

	/**
	 * End of the testing
	 * @param \Unite $unite
	 * @return mixed
	 */
	public function end(\Unite $unite) {
		echo "Test finished\n";
	}
}