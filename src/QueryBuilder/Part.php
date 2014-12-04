<?php
namespace MeticulousInquiry\QueryBuilder;

abstract class Part {
    abstract function __toString();

    abstract protected function subparts();

    private function mergeArrays($arrays) {
        if (count($arrays) == 0) {
            return [];
        }
        if (count($arrays) == 1) {
            return $arrays[0];
        }
        return call_user_func_array('array_merge', $arrays);
    }

    function getBindings() {
        return $this->mergeArrays(
            array_map(
                function($obj){
                    return $obj->getBindings();
                },
                $this->subparts()
            )
        );
    }

    function parenthesize($s) {
        return "($s)";
    }

    function commaSeparate($array) {
        return implode(", ", $array);
    }

    function parenList($array) {
        return $this->parenthesize($this->commaSeparate($array));
    }

    function spaceSeparate($array) {
        return implode(" ", array_filter($array));
    }

    function backtick($s) {
        // This is MySQL-specific syntax
        $s = preg_replace("/`/", "``", $s);
        return "`$s`";
    }
}
