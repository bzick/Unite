<?php

namespace {
    use Unite\ErrorException;
    use Unite\ListenerInterface;
    use Unite\Printer;
    use Unite\Test\RootSuite;

    /**
     * Unit Testing engine
     */
    class Unite {
        /**
         * @var Unite\Test\Suite[]
         */
        public $suites = array();
        /**
         * @var Unite\Test\File[]
         */
        public $files = array();
        /**
         * @var Unite\Test\TestCase[]
         */
        public $cases = array();
        /**
         * @var Unite\Test[]
         */
        public $tests = array();
        /**
         * @var Unite\Test\TestCase[]
         */
        public $classes = array();
        /**
         * @var Unite\Request
         */
        public $request;

        public $printer;

        public $listeners = [];

        /**
         * @param $request
         */
        public function __construct(Unite\Request $request) {
            $this->request = $request;
            $this->suite = new RootSuite($this);
            $this->setPrinter(new Printer($this));
            if($request->paths) {
                foreach($request->paths as $path) {
                    $path = rtrim($path, DIRECTORY_SEPARATOR);
                    if($path[0] != "/") {
                        $path = getcwd()."/".$path;
                    }
                    $this->addPath($path);
                }
            }
            set_error_handler(function ($errno, $error, $file, $line) {
                    throw new ErrorException("$error in $file:$line", $errno);
                });
        }

        public function setPrinter(ListenerInterface $printer) {
            $this->printer = $printer;
            $this->addListener($printer);
        }

        public function addListener(ListenerInterface $printer) {
            $this->listeners[] = $printer;
        }

        /**
         * @param $paths
         * @return $this
         */
        public function load($paths) {
            foreach((array)$paths as $path) {
                $this->addPath($path);
            }
            return $this;
        }

        /**
         *
         * @param string $path path to tests. Glob syntax supported
         * @throws \InvalidArgumentException
         */
        public function addPath($path) {
            foreach(new \GlobIterator($path, FilesystemIterator::CURRENT_AS_PATHNAME) as $file) {
                /* @var \splFileInfo $file */
                if(is_dir($file)) {
                    $this->suite->addSuite($file);
                } else {
                    $this->suite->addFile($file);
                }
            }
            foreach(new RecursiveIteratorIterator($this->suite, RecursiveIteratorIterator::SELF_FIRST) as $file) {
                /* @var Unite\Test\File|Unite\Test\Suite $file */
                if($file instanceof \Unite\Test\Suite) {
                    $file->setTestSuite($this);
                    $this->suites[ $file->getRealPath() ] = $file;
                } elseif($this->isTestFile($file)) {
                    $file->setTestFile($this);
                    $this->files[ $file->getRealPath() ] = $file;
                    foreach($file->classes as $class) {
                        /* @var Unite\Test\TestCase $class */
                        $this->classes[ $class->name ] = $class;
                        if($this->isTestCase($class)) {
                            $class->setTestCase($this);
                            $this->cases[ $class->name ] = $class;
                            foreach($class->methods as $name => $method) {
                                /* @var Unite\Test $method */
                                if($this->isTest($method)) {
                                    $method->setTest($this);
                                    $this->tests[ $method->real_name ] = $method;
                                } else {
                                    unset($class->methods[$name]);
                                }
                            }
                        }
                    }
                }
            }
        }

        /**
         * Check, is test file
         * @param \Unite\Test\File $file
         * @return bool
         */
        public function isTestFile(Unite\Test\File $file) {
            return (bool)stripos($file->getBasename(), "test.php");
        }

        /**
         * Check, is test case
         * @param Unite\Test\TestCase $class
         * @return bool
         */
        public function isTestCase(Unite\Test\TestCase $class) {
            return (bool)stripos($class->name, "test");
        }

        /**
         * Check, is test
         * @param Unite\Test $test
         * @return bool
         */
        public function isTest(Unite\Test $test) {
            return stripos($test->name, "test") === 0 || $test->hasParam("test");
        }

        private function _trigger($event, $arg) {
            foreach($this->listeners as $listener) {
                try {
                    $listener->$event($arg);
                } catch(\Exception $e) {
                    var_dump("Listener error $e");
                }
            }
        }

        /**
         * Run tests
         */
        public function run() {
            $this->_trigger("begin", $this);
            $prev = current($this->tests);
            /* @var \Unite\Test $prev */
            $this->_trigger("beginTestSuite", $prev->case->file->suite);
            $prev->case->file->suite->before();
            $this->_trigger("beginTestCase", $prev->case);
            $prev->case->before();
            foreach($this->tests as $test) {
                if($prev->case->suite !== $test->case->suite) { // test suite changed
                    $this->_trigger("endTestSuite", $prev->case->suite);
                    $prev->case->suite->after();
                    if(!$test->case->suite->started) {
                        $this->_trigger("beginTestSuite", $test->case->suite);
                        $test->case->suite->before();
                    }
                }
                if($prev->case !== $test->case) { // test case changed
                    $this->_trigger("endTestCase", $prev->case);
                    $prev->case->after();
                    $this->_trigger("beginTestCase", $test->case);
                    $test->case->before();
                }
                try {
                    $this->_trigger("beginTest", $test);
                    $test->before();
                    $test->run([]);
                    $this->_trigger("endTest", $test);
                    $test->success();
                } catch(\Exception $e) {
                    $test->resolve($e);
                }
                $test->after();
                $prev = $test;
            }
            $this->_trigger("endTestCase", $prev->case);
            $prev->case->after();
            $this->_trigger("endTestSuite", $prev->case->file->suite);
            $prev->case->file->suite->after();
        }
    }
}

namespace Unite {
    class FailureException extends \Exception {}
    class AssertFailureException extends FailureException {}
    class SuccessException extends \Exception {}
    class SkipException extends \Exception {}
    class SkipCaseException extends SkipException {}
    class ErrorException extends \Exception {}
}