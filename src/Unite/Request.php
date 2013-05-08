<?php
namespace Unite;

/**
 * Default CLI reader
 * @package Unite
 */
class Request {
    public $paths = [];

    public $options = [];

    public $config_path;

    public function _getArgv() {
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