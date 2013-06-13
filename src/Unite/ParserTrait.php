<?php
namespace Unite;

trait ParserTrait {
    public $param = array();
    public $params = array();

    protected function _parseComments() {
        /* @var \ReflectionClass|\ReflectionMethod|ParserTrait $this */
        $doc = $this->getDocComment();
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
                    if(!isset($this->params[ $param[0] ])) {
                        $this->param[ $param[0] ] = $param[1];
                        $this->params[ $param[0] ] = [];
                    }
                    $this->params[ $param[0] ][] = $param[1];
                }
            }
        }
    }

    public function hasParam($name) {
        return isset($this->param[$name]);
    }
}
