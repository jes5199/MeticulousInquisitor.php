<?php
namespace MeticulousInquisitor\Clause;

use \MeticulousInquisitor\Tableish;

class SelectFromClause extends CompoundClause {
    protected $table;
    protected $whereClause;
    protected $groupByClause;
    protected $havingClause;
    protected $orderByClause;
    protected $limitClause;

    function __construct(Tableish $table) {
        $this->table = $table;
    }

    function name() {
        return "select_from";
    }

    function table() {
        return $this->table;
    }

    function whereClause() {
        return $this->whereClause;
    }

    function groupByClause() {
        return $this->groupByClause;
    }

    function havingClause() {
        return $this->havingClause;
    }

    function orderByClause() {
        return $this->orderByClause;
    }

    function limitClause() {
        return $this->limitClause;
    }

    function where(WhereClause $whereClause) {
        $this->whereClause = $whereClause;
    }

    function groupBy(GroupByClause $groupByClause) {
        $this->groupByClause = $groupByClause;
    }

    function having(HavingClause $havingClause) {
        $this->havingClause = $havingClause;
    }

    function orderBy(OrderByClause $orderByClause) {
        $this->orderByClause = $orderByClause;
    }

    function limit(LimitClause $limitClause) {
        $this->limitClause = $limitClause;
    }

    function clauses() {
        $clauseBag = new ClauseBag();
        $clauseBag->addClause(new Keyword("FROM"));
        $clauseBag->addClause(new PartClause("from_table", $this->table()));
        $clauseBag->maybeAddClause($this->whereClause());
        $clauseBag->maybeAddClause($this->groupByClause());
        $clauseBag->maybeAddClause($this->havingClause());
        $clauseBag->maybeAddClause($this->orderByClause());
        $clauseBag->maybeAddClause($this->limitClause());
        return $clauseBag->clauses();
    }
}
