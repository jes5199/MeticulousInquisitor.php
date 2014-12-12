<?php
namespace MeticulousInquisitor\Expression;

use \MeticulousInquisitor\Expression;

class EqualsExpression extends Expression {
    protected $leftExpression;
    protected $rightExpression;

    function __construct(Expression $leftExpression, Expression $rightExpression) {
        $this->leftExpression = $leftExpression;
        $this->rightExpression = $rightExpression;
    }

    function __toString() {
        return $this->parenIfNeeded($this->leftExpression) . " = " . $this->parenIfNeeded($this->rightExpression);
    }

    function precedence() {
        return 700;
    }

    function subparts() {
        return [$this->leftExpression, $this->rightExpression];
    }
}
