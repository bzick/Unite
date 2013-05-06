<?php
namespace Taste;

class CLI {

	public static function getArgv() {
		$argv = $_SERVER["argv"];
		array_shift($argv);
		$args = array();
		$tail = array();
		foreach($argv as $arg) {
			if(strpos($arg, "--") === 0) {
				$args[] = substr($arg, 2);
			} else {
				$tail[] = $arg;
			}
		}
		parse_str(implode("&", $args), $args);
		$args[0] = $tail;
		return $args;
	}
}
