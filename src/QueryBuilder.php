<?php
namespace MeticulousInquiry;

use \PedanticQuerent\Query;
use \MeticulousInquiry\QueryBuilder\BuiltQuery;
use \MeticulousInquiry\QueryBuilder\Clause;

class QueryBuilder extends BuiltQuery implements Query {
    protected $clauses;

    function addClause(Clause $clause) {
        $this->clauses[$clause->name()] = $clause;
    }

    function clauses() {
        return $this->clauses;
    }

    function __toString() {
        return join(" ", $this->clauses());
    }

    function getSQL() {
        return $this->__toString();
    }

    protected function subparts() {
        return $this->clauses();
    }
}
