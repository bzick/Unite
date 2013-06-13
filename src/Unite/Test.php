<?php
namespace Unite;

class Test extends \ReflectionMethod {
    use ParserTrait;

    const DONE = 1;
    const FAILED = 2;
    const SKIPPED = 4;
    const BY_CASE = 8;
    const BY_SUITE = 16;
    /**
     * @var Test\TestCase
     */
    public $case;
    public $start_time = 0.0;
    public $time = 0.0;
    public $memory = 0;
    public $real_name;
    /**
     * @var int bit-mask
     */
    public $status = 0;
    /**
     * @var \Exception Reason of change status
     */
    public $reason;
    /**
     * @var \Unite
     */
    public $unite;
    /**
     * @param Test\TestCase $class
     * @param string $method
     */
    public function __construct($class, $method) {
        $this->case = $class;
        parent::__construct($class->name, $method);
        $this->real_name = $this->case->name."::".$this->name;
        $this->_parseComments();
    }

    /**
     * Set method as test
     */
    public function setTest(\Unite $unite) {
        $this->unite = $unite;
    }

    /**
     * Run test
     * @param array $args
     * @return mixed
     */
    public function run(array $args) {
        $this->memory = memory_get_usage();
        $this->start_time = microtime(true);

        $this->invokeArgs($this->case->object, $args);

        $this->time = microtime(true) - $this->start_time;
        $this->memory = memory_get_usage() - $this->memory;
//	    $this->case->tests_time += $this->time;
//	    $this->case->tests_time += $this->time;
    }

    public function before() {
        if($this->case->before_test) {
            $this->case->before_test->invoke($this->case->object);
        }
    }

    public function after() {
        if($this->case->after_test) {
            $this->case->after_test->invoke($this->case->object);
        }
    }

    public function __get($param) {
        return null;
    }

    public function success() {
        $this->status = self::DONE;
    }

    public function resolve(\Exception $reason) {
        var_dump("$reason");
    }

}
