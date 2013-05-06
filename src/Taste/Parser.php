<?php
namespace Taste;

class Parser {

	public static function parseDocBlock($doc) {
		$p = [ [], [] ];
		if($doc) {
			$doc = preg_replace('/^\s*\*\s*/mS', '', trim($doc, "/* \t\n\r"));
			$doc = explode("@", $doc, 2);
			if($doc[0] = trim($doc[0])) {
				$info["desc"] = $doc[0];
			}
			if($doc[1]) {
				foreach(preg_split('/\r?\n@/mS', $doc[1]) as $param) {
					$param = preg_split('/\s+/', $param, 2);
					if(!isset($param[1])) {
						$param[1] = "";
					}
					$param[0] = strtolower($param[0]);
					if(!isset($p[1][ $param[0] ])) {
						$p[0][ $param[0] ] = $param[1];
						$p[1][ $param[0] ] = [];
					}
					$p[1][ $param[0] ][] = $param[1];
					/*switch(strtolower($param[0])) {
						case 'strict':
							$flags |= self::POINTS_STRICT;
							break;
						case 'point':
							if(preg_match('/^(.*?)(?:\s*|$)/mS', $param[1], $matches)) {
								$points[$matches[2]] = true;
							} else {
								throw new \LogicException("Invalid point definition in $method");
							}
							break;
						case 'memcheck':
							$flags |= self::MEMCHECK;
							break;
						case 'partner':
							$partner = $param[1];
							break;
						case 'param':
						case 'return':
						case 'description':
						case 'since':
						case 'throws':
							break;
						default:
							throw new \LogicException("Unknown option {$param[0]}");
					}*/
				}
			}
		}

		return $p;
	}
}
