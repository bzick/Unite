<?php
namespace Unite\Handler;
use Unite\Parser;

class ClassHandler extends \ReflectionClass {
	/**
	 * @var FileHandler
	 */
	public $file;
	/**
	 * @var \Unite\TestCase
	 */
	public $object;
	public $param   = [];
	public $params  = [];
	/**
	 * @var MethodHandler[]
	 */
	public $methods = [];

    public $before;

    public $after;

	public function __construct($class, $file) {
		$this->file = $file;
		parent::__construct($class);
		list($this->param, $this->params) = Parser::parseDocBlock($this->getDocComment());
        $traits = class_uses($class);
	}

    public function before() {

    }

    public function after() {

    }

	public function getPublicMethods() {
		$methods = [];
		foreach($this->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
			$methods[] = new MethodHandler($this, $method->name);
		}
		return $methods;
	}

}
