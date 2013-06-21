<?php
namespace Unite;


class Test extends \ReflectionMethod {
    use ParserTrait;

    const BLANK = 0;
    const SUCCESS = 1;
    const FAILED = 2;
    const SKIPPED = 4;
    const ERROR = 8;

    const FINISHED = 15;

    const BY_CASE = 32;
    const BY_SUITE = 64;
    /**
     * @var Test\TestCase
     */
    public $case;
    /**
     * @var Stats
     */
    public $stats;
    public $real_name;
    /**
     * @var \ReflectionMethod[]
     */
    public $before = array();
    /**
     * @var \ReflectionMethod[]
     */
    public $after = array();
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


    public function __toString() {
        return "Test({$this->real_name})";
    }

    /**
     * Set method as test
     */
    public function setTest(\Unite $unite) {
        $this->unite = $unite;
        $this->before = $this->case->before_test;
        $this->after = $this->case->after_test;
        $this->stats = new Stats($this, 1);
    }

    /**
     * Run test
     * @param array $args
     * @return mixed
     */
    public function run(array $args) {
        try {
            $this->memory = memory_get_usage();
            $this->start_time = microtime(true);

            $this->invokeArgs($this->case->object, $args);

            $this->success();
        } catch(\Exception $e) {
            $this->exception($e);
        }
        $this->time = microtime(true) - $this->start_time;
        $this->memory = memory_get_usage() - $this->memory;
    }

    /**
     * Set test success
     */
    public function success() {
        $this->status = self::SUCCESS;
        $this->reason = null;
    }

    /**
     * Test's exception
     * @param \Exception $e
     * @param Test|TestCase $from
     */
    public function exception(\Exception $e, $from = null) {
        if($this->status) { // reset previous status
            $this->status = 0;
        }
        if($e instanceof ErrorException) {
            $this->status |= self::ERROR;
        } elseif($e instanceof FailureException) {
            $this->status |= self::FAILED;
        } elseif($e instanceof SkipException) {
            $this->status |= self::SKIPPED;
        } elseif($e instanceof SuccessException) {
            $this->status |= self::SUCCESS;
            return;
        } else {
            $this->status |= self::ERROR;
        }
        $this->reason = $e;
    }

    /**
     * @param \ReflectionMethod $callback
     * @param bool $prepend
     * @return $this
     */
    public function onBefore(\ReflectionMethod $callback, $prepend = false) {
        if($prepend) {
            array_unshift($this->before, $callback);
        } else {
            $this->before[] = $callback;
        }
        return $this;
    }

    /**
     * Invoke 'before' callbacks
     */
    public function before() {
        try {
            if($this->before) {
                foreach($this->before as $after) {
                    $after->invoke($this->case->object);
                }
            }
        } catch(\Exception $e) {
            $this->exception($e);
        }
    }

    /**
     * @param \ReflectionMethod $callback
     * @param bool $prepend
     * @return $this
     */
    public function onAfter(\ReflectionMethod $callback, $prepend = false) {
        if($prepend) {
            array_unshift($this->after, $callback);
        } else {
            $this->after[] = $callback;
        }
        return $this;
    }

    /**
     * invoke 'after' callbacks
     */
    public function after() {
        try {
            if($this->after) {
                foreach($this->after as $after) {
                    $after->invoke($this->case->object);
                }
            }
        } catch(\Exception $e) {
            $this->exception(new ErrorException("After test callback throw an exception: ".$e->getMessage(), 0 , $e));
        }
    }

    public function __get($param) {
        return null;
    }

    public function fail(\Exception $reason) {

    }

}
