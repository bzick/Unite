<?php
namespace Unite\Handler;

class FileHandler extends \splFileInfo {
	public $classes = [];

	public function getClasses() {
		$classes = [];
		$tokens = token_get_all(file_get_contents($this->getRealPath()));
		$ns = "";
		for($i=0; $i<count($tokens); $i++) {
			if(is_array($tokens[$i])) {
				if($tokens[$i][0] == T_NAMESPACE) {
					$ns = "";
					for($j=$i+2; $j<count($tokens); $j++) {
						if($tokens[$j] == ";" || $tokens[$j] == "{") {
							$i = $j;
							break;
						} elseif(isset($tokens[$j][0]) && $tokens[$j][0] == T_WHITESPACE) {
							continue;
						} else {
							$ns .= $tokens[$j][1];
						}
					}
				} elseif($tokens[$i][0] == T_CLASS &&
					$tokens[$i+1][0] == T_WHITESPACE &&
					$tokens[$i+2][0] == T_STRING) {
					$classes[] = new ClassHandler(($ns ? $ns.'\\' : '').$tokens[$i+2][1], $this);
				}
			}
		}
		return $classes;
	}

	public function getLines($from, $to) {

	}
}
