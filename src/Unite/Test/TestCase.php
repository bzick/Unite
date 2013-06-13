<?php
namespace Unite\Test;

use Unite\ParserTrait;
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
    public $methods = [];

    /**
     * @var \ReflectionMethod
     */
    public $before;

    /**
     * @var \ReflectionMethod
     */
    public $after;

    /**
     * @var \ReflectionMethod
     */
    public $before_test;

    /**
     * @var \ReflectionMethod
     */
    public $after_test;

    /**
     * @var \Unite
     */
    public $unite;

    public $start_time = 0.0;

    public $time = 0.0;

    public function __construct($class, File $file) {
        $this->file = $file;
        $this->suite = $file->suite;
        parent::__construct($class);
        $this->_parseComments();
    }

    /**
     *
     */
    public function setTestCase() {
        $this->object = $this->newInstance();
        foreach($this->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $this->methods[$method->name] = $test = new Test($this, $method->name);
        }
        if(isset($this->methods["before"])) {
            $this->before = $this->methods["before"];
        }
        if(isset($this->methods["after"])) {
            $this->after = $this->methods["after"];
        }
        if(isset($this->methods["beforeTest"])) {
            $this->before_test = $this->methods["beforeTest"];
        }
        if(isset($this->methods["afterTest"])) {
            $this->after_test = $this->methods["afterTest"];
        }
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
