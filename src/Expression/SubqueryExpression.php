<?php
namespace MeticulousInquisitor\Expression;

use \PedanticQuerent\Query\SelectQuery;
use \MeticulousInquisitor\Expression;

class SubqueryExpression extends Expression {
    protected $selectQuery;

    function __construct(SelectQuery $selectQuery) {
        $this->selectQuery = $selectQuery;
    }

    function selectQuery() {
        return $this->selectQuery;
    }

    function __toString() {
        return $this->parenthesize($this->selectQuery()->getSQL());
    }

    function subparts() {
        return [];
    }

    function getBindings() {
        return $this->selectQuery()->getBindings();
    }

    function precedence() {
        return INF;
    }
}
