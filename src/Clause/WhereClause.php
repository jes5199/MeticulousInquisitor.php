<?php
namespace MeticulousInquisitor\Clause;

use \MeticulousInquisitor\Expression;

class WhereClause extends CompoundClause {
    protected $expression;

    function __construct(Expression $expression) {
        $this->expression = $expression;
    }

    function expression() {
        return $this->expression;
    }

    function name() {
        return "where";
    }

    function clauses() {
        $clauseBag = new ClauseBag();
        $clauseBag->addClause(new Keyword("WHERE"));
        $clauseBag->addClause(new PartClause("where_expression", $this->expression()));
        return $clauseBag->clauses();
    }
}
