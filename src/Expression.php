<?php
namespace MeticulousInquisitor;

use \MeticulousInquisitor\Part;
use \MeticulousInquisitor\Clause\OrderByClause\Order;

abstract class Expression extends Part implements Order {
    abstract function precedence();

    function parenIfNeeded(Expression $child) {
        if ($this->precedence() >= $child->precedence()) {
            return $this->parenthesize($child);
        } else {
            return $this->stringify($child);
        }
    }
}
