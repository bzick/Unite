<?php
namespace Taste\Handler;
use Taste\Parser;

class MethodHandler extends \ReflectionMethod {
	public $cls;
	public $param = array();
	public $params = array();
    public $time = 0;

	/**
	 * @param ClassHandler $class
	 * @param string $method
	 */
	public function __construct($class, $method) {
		$this->cls = $class;
		parent::__construct($class->name, $method);
		list($this->param, $this->params) = Parser::parseDocBlock($this->getDocComment());
	}

	public function run(array $args) {
		parent::invokeArgs($this->cls->object, $args);
	}

	public function getLines() {

	}
}
