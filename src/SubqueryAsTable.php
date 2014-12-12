<?php
namespace MeticulousInquisitor;

use \PedanticQuerent\Query\SelectQuery;
use \MeticulousInquisitor\Tableish;

class SubqueryAsTable extends Part implements Tableish {
    protected $selectQuery;
    protected $alias;

    function __construct(SelectQuery $selectQuery, $alias = null) {
        $this->selectQuery = $selectQuery;
        $this->alias = $alias;
    }

    function selectQuery() {
        return $this->selectQuery;
    }

    function alias() {
        return $this->alias;
    }

    function __toString() {
        $subquery = $this->parenthesize($this->selectQuery()->getSQL());
        if ($this->alias()) {
            return "$subquery {$this->alias()}";
        } else {
            return $subquery;
        }
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
