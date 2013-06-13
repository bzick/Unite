<?php

namespace Unite;

use Unite\Test\Suite;
use Unite\Test\TestCase;

interface ListenerInterface {

	/**
	 * Start of the testing
	 * @param \Unite $unite
	 * @return mixed
	 */
	public function begin(\Unite $unite);

	/**
	 * Start of the test suite
	 * @param Suite $suit
	 * @return mixed
	 */
	public function beginTestSuite(Suite $suit);

	/**
	 * Start of the test case
	 * @param TestCase $case
	 * @return mixed
	 */
	public function beginTestCase(TestCase $case);

	/**
	 * Start of the test
	 * @param Test $test
	 * @return mixed
	 */
	public function beginTest(Test $test);

	/**
	 * Assert
	 * @param Assert $assert
	 * @return mixed
	 */
	public function assert(Assert $assert);

	/**
	 * Event invoke if test was skipped by TestCase's reason (error or skip)
	 * @param Test $test
	 * @return mixed
	 */
	public function drainTest(Test $test);

	/**
	 * End of the test
	 * @param Test $test
	 * @return mixed
	 */
	public function endTest(Test $test);

	/**
	 * Event invoke if TestCase was skipped by TestSuite's reason (error or skip)
	 * @param TestCase $case
	 * @return mixed
	 */
	public function drainTestCase(TestCase $case);

	/**
	 * End of the test case
	 * @param TestCase $case
	 * @return mixed
	 */
	public function endTestCase(TestCase $case);

	/**
	 * End of the test suite
	 * @param Suite $suit
	 * @return mixed
	 */
	public function endTestSuite(Suite $suit);

	/**
	 * End of the testing
	 * @param \Unite $unite
	 * @return mixed
	 */
	public function end(\Unite $unite);

}