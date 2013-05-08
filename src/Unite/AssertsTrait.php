<?php
namespace Unite;
/**
 * Collection of asserts
 * @author Ivan Shalganov <owner@bzick.net>
 */
trait AssertsTrait {
    use ConditionTrait;

    public function assert($test, $expect, $strict = false, $message = "") {
        if(($strict && $test !== $expect) || (!$strict && $test != $expect)) {
            $this->fail("expected ".var_export($expect, 1).", but ".var_export($test, 1)." given");
        }
    }

    public function assertTrue($test) {
        if($test !== true) {
            $this->fail("expected bool(true), but ".var_export($test, 1)." given");
        }
    }

    public function assertFalse($test) {
        if($test !== false) {
            $this->fail("expected bool(false), but ".var_export($test, 1)." given");
        }
    }

    public function assertNull($test) {
        if($test !== null) {
            $this->fail("expected NULL, but ".var_export($test, 1)." given");
        }
    }

	public function assertType($test, $type) {
        if(gettype($test) != $type) {
            $this->fail("expected type $type, but ".gettype($test)." given");
        }
	}

    public function assertInt($test, $value = null) {
		$this->assertType($test, "integer");
	    if(!is_null($value)) {
            if($test !== $value) {
                $this->fail("expected $value, but $test given");
            }
	    }
    }

    public function assertFloat($test, $value = null) {
        $this->assertType($test, "double");
        if(!is_null($value)) {
            $this->fail("expected $value, but $test given");
        }
    }

    public function assertArray($test, $value = null) {
        $this->assertType($test, "array");
        if(!is_null($value)) {
            $this->fail("expected ".var_export($value, 1).", but ".var_export($test, 1)." given");
        }
    }


	public function assertException($test, $message = null, $code = null) {
        if(!is_object($test)) {
            $this->fail("expected Exception object, but ".gettype($test)." given");
        }
        if(!($test instanceof \Exception)) {
            $this->fail("expected instance of exception, but ".get_class($test)." given");
        }

        if($message !== null && strpos($test->getMessage(), $message) === false) {
            $this->fail("expected exception message {$message}, but ".$test->getMessage()." given");
        }

        if($code !== null && $code != $test->getCode()) {
            $this->fail("expected exception code {$code}, buе ".$test->getCode()." given");
        }
	}

    /**
     * Assert. Ожидается значение удовлетворяющее регулярному выражению
     *
     * @param mixed $test проверяемое значение
     * @param mixed $regex - регулярное выражение
     * */
    public function assertMatch($test, $regex) {
        if(!preg_match($regex, $test)) {
            $this->fail("value is invalid by regexp $regex");
        }
    }
    /**
     * Assert. Ожидаемое значение удовлетворяет маске
     *
     * @see http://docs.php.net/manual/en/function.sscanf.php
     * @param mixed $test - проверяемое значение
     * @param string $mask - маска в формате sscanf
     * @param bool $dump_results - вывести результат sscanf на экран
     * */
    public function assertMask($test, $mask, $dump_results = false) {
        $result = sscanf($test . "\xFF\xF1\x17abc", $mask . "\xFF\xF1\x17%s");
        if($dump_results) {
            var_dump($result);
        }
        if(!$result) {
            $this->fail("the original string does not satisfy the mask \noriginal: {$test}\n\nmask: {$mask}");
        } else {
            for($i=0; $i<count($result)-1; $i++) {
                if($result[$i] === null) {
                    $this->fail(($i+1)."/".(count($result)-1)." element does not satisfy the mask \noriginal: {$test}\n\nmask: {$mask}");
                }
            }
            if($result[count($result)-1] === null) {
                $this->fail("the last part of string does not satisfy the mask \noriginal: {$test}\n\nmask: {$mask}");
            }
        }

    }
}
