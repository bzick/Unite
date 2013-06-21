<?php

namespace Unite;


class ToolKit {
    /**
     * Parse any doc block
     * @param $doc
     * @return array
     */
    public static function parseDoc($doc) {
        $p = [];
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
                if(!isset($p[ $param[0] ])) {
//                    $p[0][ $param[0] ] = $param[1];
                    $p[ $param[0] ] = [];
                }
                $p[ $param[0] ][] = $param[1];
            }
        }
        return $p;
    }
}