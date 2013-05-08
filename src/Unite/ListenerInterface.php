<?php

namespace Unite;


use Unite\Handler\ClassHandler;
use Unite\Handler\MethodHandler;

interface ListenerInterface {

    public function begin();

    public function beginTestSuit($suit);

    public function beginTestCase(ClassHandler $case);

    public function beginTest(MethodHandler $test);

    public function assert($assert);

    public function endTest(MethodHandler $test);

    public function endTestCase(ClassHandler $case);

    public function endTestSuit($suit);

    public function end();

}