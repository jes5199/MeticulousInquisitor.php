<?php
namespace MeticulousInquisitor\Clause;

use \MeticulousInquisitor\Expression;

class HavingClause extends CompoundClause {
    protected $expression;

    function __construct(Expression $expression) {
        $this->expression = $expression;
    }

    function expression() {
        return $this->expression;
    }

    function name() {
        return "having";
    }

    function clauses() {
        $clauseBag = new ClauseBag();
        $clauseBag->addClause(new Keyword("HAVING"));
        $clauseBag->addClause(new PartClause("having_expression", $this->expression()));
        return $clauseBag->clauses();
    }
}
