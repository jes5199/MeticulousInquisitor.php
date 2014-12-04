<?php
namespace MeticulousInquiry\QueryBuilder;

use \MeticulousInquiry\QueryBuilder\Part;

abstract class BuiltQuery extends Part {
    abstract function getSQL();

    function __toString() {
        return $this->getSQL();
    }
}
