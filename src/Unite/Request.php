<?php
namespace Unite;

/**
 * Default CLI reader
 * @package Unite
 */
class Request {
	public static $aliases = array(
		'on'   => true,
		'off'  => false,
		'yes'  => true,
		'no'   => false,
		'null' => null,
		'[]'   => array()
	);

	public $paths = [];

    public $options = [];

    public $config_path;

	public function __construct() {
		$args = $_SERVER["argv"];
		array_shift($args);
		$vals = [];
		foreach($args as $arg) {
			if(strpos($arg, "--") === 0) {
				$vals[] = str_replace(["%", "&"], ["%25", "%26"], substr($arg, 2));
				array_shift($args);
			} else {
				break;
			}
		}
		if($vals) {
			parse_str(implode("&", $vals), $vals);
			array_walk_recursive($vals, function (&$val) {
				if(strlen($val) < 7 && isset(self::$aliases[strtolower($val)])) {
					$val = self::$aliases[$val];
				}
			});
		}
		$this->options = $vals;
		$this->paths = $args;
		if(isset($this->options["config"])) {
			if(file_exists($this->options["config"])) {
				$this->config_path = realpath($this->options["config"]);
			} else {
				throw new \ErrorException("Config file ".$this->options["config"]." not found");
			}
		} elseif(file_exists(getcwd()."/unite.json")) {
			$this->config_path = getcwd()."/unite.json";
		} else {
			throw new \ErrorException("Please set config path");
		}
	}
}