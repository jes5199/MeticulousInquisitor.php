<?php
namespace MeticulousInquiry;

use \PedanticQuerent\Query\SelectQuery;
use \PedanticQuerent\QueryDelegate;
use \MeticulousInquiry\QueryBuilder\BuiltQuery;
use \MeticulousInquiry\QueryBuilder\Keyword;
use \MeticulousInquiry\QueryBuilder\Clause\SelectColumnsClause;
use \MeticulousInquiry\QueryBuilder\Clause\SelectColumnsClause\SelectColumn;

class SelectBuilder extends BuiltQuery implements SelectQuery {
    use QueryDelegate;

    protected $selectColumnsClause;

    function __construct() {
        $this->selectColumnsClause = new SelectColumnsClause;
    }

    protected function selectColumnsClause() {
        return $this->selectColumnsClause;
    }

    function getQuery() {
        $queryBuilder = new QueryBuilder;
        $queryBuilder->addClause(new Keyword("SELECT"));
        /* [ALL | DISTINCT | DISTINCTROW ] */
        /* [HIGH_PRIORITY] */
        /* [MAX_STATEMENT_TIME = N] */
        /* [STRAIGHT_JOIN] */
        /* [SQL_SMALL_RESULT] [SQL_BIG_RESULT] [SQL_BUFFER_RESULT] */
        /* [SQL_CACHE | SQL_NO_CACHE] [SQL_CALC_FOUND_ROWS] */
        $queryBuilder->addClause($this->selectColumnsClause());
        //$queryBuilder->addClause($this->selectFromClause());

        return $queryBuilder;
    }

    function selectColumn(SelectColumn $selectColumn) {
        $this->selectColumnsClause()->addColumn($selectColumn);
        return $this;
    }

    function subparts() {
        return [$this->selectColumnsClause()];
    }
}
