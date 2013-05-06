<?php
namespace Taste;

class Runner {
	public $files = array();
    public $cases = array();
    public $tests = array();

    public function load($paths) {
        foreach((array)$paths as $path) {
	        $this->addPath($path);
        }
	    return $this;
    }

    /**
     *
     * @param $path
     * @return array
     * @throws \InvalidArgumentException
     */
    public function addPath($path) {
		$path = rtrim($path, DIRECTORY_SEPARATOR);
	    if($path[0] != "/") {
		    $path = getcwd()."/".$path;
	    }

	    $list = array();

	    foreach(new \GlobIterator($path) as $file) {
		    /* @var \splFileInfo $file*/
		    if($file->isDir()) {
				$list += $this->_scanDir($file->getRealPath());
		    } elseif($file->isFile() && $file->getExtension() === "php") {
			    $list[$file->getRealPath()] = $file->getFileInfo('Taste\Handler\FileHandler');
		    }
	    }

	    foreach($list as $file) {
		    /* @var Handler\FileHandler $file*/
		    if($this->isTestFile($file)) {
			    $this->files[ $file->getRealPath() ] = $file;
			    foreach($file->getClasses() as $class) {
				    /* @var Handler\ClassHandler $class */
				    if($this->isTestClass($class)) {
					    $this->cases[ $class->name ] = $file->classes[ $class->name ] = $class;

					    foreach($class->getPublicMethods() as $method) {
						    $class->methods[ $method->name ] = $method;
						    /* @var Handler\MethodHandler $method */
						    if($this->isTest($method)) {
								$this->tests[ $method->name ] = $method;
						    }
					    }
				    }
			    }
		    }
	    }
    }

	private function _scanDir($path) {
		$list = array();
		$files = iterator_to_array(new \FilesystemIterator($path, \FilesystemIterator::KEY_AS_FILENAME));
		if(file_exists($path."/.order")) {

			$order = file($path."/.order", FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
			foreach($order as $item) {
				if(!realpath($path."/".$item)) {
					throw new \InvalidArgumentException("Invalid item '$item' in order list $path/.order");
				}
			}
			$files = array_merge(array_flip($order), $files);
		}

		foreach($files as $file) {
			/* @var \splFileInfo $file*/
			if($file->isFile()) {
				if($file->getExtension() === "php") {
					$list[$file->getRealPath()] = $file->getFileInfo('Taste\Handler\FileHandler');
				}
			} elseif($file->isDir()) {
				$list += $this->_scanDir($file->getRealPath());
			}
		}

		return $list;
	}

	public function isTestFile(Handler\FileHandler $file) {
		return (bool)stripos($file->getBasename(), "test.php");
	}

	public function isTestClass(Handler\ClassHandler $class) {
		return (bool)stripos($class->name, "test");
	}

	public function isTest(Handler\MethodHandler $test) {
		return stripos($test->name, "test") === 0 || isset($test->param["test"]);
	}

	public function run() {
        foreach($this->cases as $case) {
            /* @var Handler\ClassHandler $case */
            $class_name = $case->name;
            $case->object = new $class_name($this);
            $case->object->before();
        }
		foreach($this->tests as $test) {
			/* @var Handler\MethodHandler $test */
			if(isset($test->param["depend"])) {
				if(!strpos($test->param["depend"], "::")) {
					
				}
			}
			try {
                $args = array();
                $test->cls->object->beforeTest($test);
                $time = microtime(true);
                $test->run($args);
                $test->time = microtime(true) - $time;
                $test->cls->object->afterTest($test);
                if(isset($test->param["memcheck"])) {
                    $memory = 0;
                    $leaks = array_pad(array(), 3, 0);
                    for($i=0; $i<3; $i++) {
                        $test->cls->object->beforeTest($test);
                        $memory = memory_get_usage();
                        $test->run($args);
                        $memory = memory_get_usage() - $memory;
                        $test->cls->object->afterTest($test);
                        if($memory > 0) {
                            $leaks[$i] = $memory;
                        }
                    }
                    if(array_sum($leaks)) {
                        $test->cls->object->fail("Memory leak detected: ".implode(" B, ", $leaks));
                    }
                }
			} catch (\Exception $e) {
                $test->fail = $e;
			}
		}
        foreach($this->cases as $case) {
            /* @var Handler\ClassHandler $case */
            $case->object->after();
        }
	}

}

class FailureException extends \Exception {}
class AssertFailureException extends FailureException {}
class SkipException extends \Exception {}
class SkipCaseException extends SkipException {}