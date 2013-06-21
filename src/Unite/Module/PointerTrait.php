<?php
namespace Unite\Module;
/**
 * Class Pointer
 * @author Ivan Shalganov <bzick@megagroup.ru>
 * @copyright MegaGroup.ru
 */
trait PointerTrait {
    public $points = array();
    public $points_passed = array();

    public function point($name) {
        if(!isset($this->points[$name])) {
            throw new FailureException("Point $name not found");
        }
        if($this->flags & self::POINTS_STRICT) {
            if(key($this->points) != $name) {
                throw new FailureException("Expected point ".key($this->points).", not ".$name);
            }
        }
        $this->points_passed[$name] = microtime(1);
        unset($this->points[$name]);
        $this->log("Point $name passed");
    }
}
