<?php
namespace MeticulousInquisitor\Expression;

use \MeticulousInquisitor\Expression;

class RawExpression extends Expression {
    protected $expressionSQL;
    protected $bindings;

    function __construct($expressionSQL, array $bindings = []) {
        $this->expressionSQL = $expressionSQL;
        $this->bindings = $bindings;
    }

    function __toString() {
        return $this->expressionSQL;
    }

    function getBindings() {
        return $this->bindings;
    }

    function subparts() {
        return [];
    }

    function precedence() {
        return -INF;
    }
}
