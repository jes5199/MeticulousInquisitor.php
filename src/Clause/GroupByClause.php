<?php
namespace MeticulousInquisitor\Clause;

use \MeticulousInquisitor\Expression;
use \MeticulousInquisitor\Util\FromArray;
use \MeticulousInquisitor\Clause\CommaSeparatedClause;

class GroupByClause extends CompoundClause {
    use FromArray;

    protected $expressions;

    function __construct(Expression $expression) {
        $this->expressions = [];
        foreach (func_get_args() as $groupExpression) {
            $this->addExpression($groupExpression);
        }
    }

    function addExpression(Expression $expression) {
        array_push($this->expressions, $expression);
    }

    function name() {
        return "group_by";
    }

    function expressions() {
        return $this->expressions;
    }

    function clauses() {
        $clauseBag = new ClauseBag();
        $clauseBag->addClause(new Keyword("GROUP BY"));
        $clauseBag->addClause(new CommaSeparatedClause("group_by_expression", $this->expressions()));
        return $clauseBag->clauses();
    }
}

