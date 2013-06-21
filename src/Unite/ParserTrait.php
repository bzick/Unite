<?php
namespace Unite;

trait ParserTrait {
    /**
     * @var array collection of parameters
     */
    public $params = array();

    /**
     *
     */
    protected function _parseComments() {
        /* @var Test\TestCase|Test|ParserTrait $this */
        $doc = $this->getDocComment();
        if($doc) {
            $this->params = ToolKit::parseDoc($doc);
            $type = 0;
            if($this instanceof TestCase) {
                $type = \Unite::TEST_CASE;
            } elseif($this instanceof Test) {
                $type = \Unite::TEST;
            }
//            if($type && $this->unite->params[$type]) {
//                foreach(array_keys($this->params) as $param) {
////                    if()
//                }
//            }
        }
    }

    public function hasParam($name) {
        return isset($this->params[$name]);
    }

    public function getParam($name, $default = null) {
        return empty($this->params[$name]) ? $default : $this->param[$name][0];
    }

    public function getParams($name) {
        return isset($this->params[$name]) ? $this->params[$name] : [];
    }
}
