<?php
namespace Taste\Handler;
use Taste\Parser;

class ClassHandler extends \ReflectionClass {
	/**
	 * @var FileHandler
	 */
	public $file;
	/**
	 * @var \Taste\TestCase
	 */
	public $object;
	public $param   = [];
	public $params  = [];
	/**
	 * @var array of MethodHandler
	 */
	public $methods = [];

	public function __construct($class, $file) {
		$this->file = $file;
		parent::__construct($class);
		list($this->param, $this->params) = Parser::parseDocBlock($this->getDocComment());
	}

	public function getPublicMethods() {
		$methods = [];
		foreach($this->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
			$methods[] = new MethodHandler($this, $method->name);
		}
		return $methods;
	}

}
