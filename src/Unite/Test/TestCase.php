<?php
namespace Unite\Test;

use Unite\ErrorException;
use Unite\FailureException;
use Unite\ParserTrait;
use Unite\SkipException;
use Unite\SuccessException;
use Unite\Test;

class TestCase extends \ReflectionClass {
    use ParserTrait;
    /**
     * @var File
     */
    public $file;
    /**
     * @var Suite
     */
    public $suite;
    /**
     * @var mixed
     */
    public $object;
    /**
     * @var \Unite\Test[]
     */
    public $tests = [];

    /**
     * @var \ReflectionMethod[]
     */
    public $before;

    /**
     * @var \ReflectionMethod[]
     */
    public $after;

    /**
     * @var \ReflectionMethod[]
     */
    public $before_test;

    /**
     * @var \ReflectionMethod[]
     */
    public $after_test;

    /**
     * @var \Unite
     */
    public $unite;

    public $start_time = 0.0;

    public $time = 0.0;

    /**
     * @var \ReflectionClass[]
     */
    public $traits = array();


    public function __construct($class, File $file) {
        $this->file = $file;
        $this->suite = $file->suite;
        parent::__construct($class);
        $this->_parseComments();
    }

    /**
     * Get public methods
     * @return \ReflectionMethod[]
     */
    public function getPublicMethods() {
        $methods = array();
        foreach(parent::getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $methods[$method->name] = $test = new Test($this, $method->name);
        }
        if(isset($methods["before"])) {
            $this->before = $methods["before"];
            unset($methods["before"]);
        }
        if(isset($methods["after"])) {
            $this->after = $methods["after"];
            unset($methods["after"]);
        }
        if(isset($methods["beforeTest"])) {
            $this->before_test = $methods["beforeTest"];
            unset($methods["beforeTest"]);
        }
        if(isset($methods["afterTest"])) {
            $this->after_test = $methods["afterTest"];
            unset($methods["afterTest"]);
        }
        return $methods;
    }

    /**
     * Test's exception
     * @param \Exception $e
     * @param Test|TestCase $from
     */
    public function exception(\Exception $e, $from = null) {
        if($e instanceof ErrorException) {
//            $this->status |= self::ERROR;
        } elseif($e instanceof FailureException) {
//            $this->status |= self::FAILED;
        } elseif($e instanceof SkipException) {
//            $this->status |= self::SKIPPED;
        } elseif($e instanceof SuccessException) {
//            $this->status |= self::SUCCESS;
            return;
        } else {
//            $this->status |= self::ERROR;
        }
        $this->reason = $e;
    }

    public function __toString() {
        return "TestCase({$this->name})";
    }

    /**
     * Mark class as test case
     */
    public function setTestCase(\Unite $unite) {
        $this->unite = $unite;
        $this->object = $this->newInstance();
        $this->traits = $this->collectTraits();
        foreach($this->traits as $trait) {
            /* @var \ReflectionClass $trait */
            if(preg_match('/^\s*\*\s*@module\s*(.*?)$/miS', $trait->getDocComment(), $match)) {
                $this->unite->addModule($match[1], $this);
            }
        }
    }

    public function collectTraits() {
        $c = $this;
        $traits = array();
        do {
            $traits += $this->_getTraits($c);
        } while($c = $c->getParentClass());
        return $traits;
    }

    private function _getTraits(\ReflectionClass $trait) {
        $traits = array();
        foreach($trait->getTraits() as $name => $t) {
            $traits[$name] = $t;
            $traits += $this->_getTraits($t);
        }
        return $traits;
    }

    /**
     *
     */
    public function before() {
        if($this->before) {
            $this->before->invoke($this->object);
        }
        $this->start_time = microtime(true);
    }

    /**
     *
     */
    public function after() {
        $this->total_time = microtime(true) - $this->start_time;
        if($this->after) {
            $this->after->invoke($this->object);
        }
    }
}
