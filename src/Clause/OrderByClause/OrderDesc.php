<?php
namespace MeticulousInquisitor\Clause\OrderByClause;

use \MeticulousInquisitor\Part;
use \MeticulousInquisitor\Expression;

class OrderDesc extends Part implements Order {
    protected $expression;

    function __construct(Expression $expression) {
        $this->expression = $expression;
    }

    function expression() {
        return $this->expression;
    }

    function __toString() {
        return ($this->expression() . " DESC");
    }

    function subparts() {
        return [$this->expression()];
    }
}
