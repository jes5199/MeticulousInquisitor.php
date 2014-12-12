<?php
namespace MeticulousInquisitor;

use \PedanticQuerent\Query\SelectQuery;
use \PedanticQuerent\QueryDelegate;
use \MeticulousInquisitor\Clause\SelectColumnsClause\SelectColumn;
use \MeticulousInquisitor\Clause\SelectFromClause;
use \MeticulousInquisitor\Clause\WhereClause;
use \MeticulousInquisitor\Clause\GroupByClause;
use \MeticulousInquisitor\Clause\HavingClause;
use \MeticulousInquisitor\Clause\OrderByClause;
use \MeticulousInquisitor\Clause\OrderByClause\OrderDesc;
use \MeticulousInquisitor\Clause\LimitClause;
use \MeticulousInquisitor\Expression\AndExpression;
use \MeticulousInquisitor\Expression\Column;
use \MeticulousInquisitor\Expression\EqualsExpression;
use \MeticulousInquisitor\Expression\RawExpression;
use \MeticulousInquisitor\Expression\Literal;
use \MeticulousInquisitor\Expression\Placeholder;
use \MeticulousInquisitor\Expression\SubqueryExpression;
use \MeticulousInquisitor\QueryStructure\SelectStructure;
use \MeticulousInquisitor\Tableish;

class SelectBuilder implements SelectQuery {
    use QueryDelegate;

    protected $selectColumns;
    protected $selectFromTableish;
    protected $whereExpressions;
    protected $groupByExpressions;
    protected $havingExpressions;
    protected $orderByExpressions;
    protected $limitCount;
    protected $limitOffset;

    function __construct() {
        $this->selectColumns = [];
        $this->selectFromTableish = null;
        $this->whereExpressions = [];
        $this->groupByExpressions = [];
        $this->havingExpressions = [];
        $this->orderByExpressions = [];
        $this->limitCount = null;
        $this->limitOffset = null;
    }

    function select($x, $y = null) {
        if ($x instanceof Expression) {
            return $this->selectExpression($x);
        } elseif ($x instanceof SelectQuery) {
            return $this->selectSubquery($x);
        } elseif ($x == '*') { // select('*') => SELECT *
            return $this->selectStar();
        } elseif ($y == '*') { // select("a", "*") => SELECT `a`.*
            return $this->selectStar($x);
        } else { // select("table", "col") => SELECT `table`.`col`
            return $this->selectColumnName($x, $y);
        }
    }

    function asName($name) {
        $this->selectColumns[count($this->selectColumns) - 1]->asName($name);
        return $this;
    }

    function from($table, $alias = null) {
        if ($table instanceof Tableish) {
            $this->selectFromTableish = $table;
        } elseif ($table instanceof SelectQuery) {
            $this->selectFromTableish = new SubqueryAsTable($table, $alias);
        } else {
            $this->selectFromTableish = new Table($table, $alias);
        }
        return $this;
    }

    protected function makeExpression($expression, $bindings = []) {
        if(!($expression instanceof Expression)) {
            $expression = new RawExpression($expression, $bindings);
        }
        return $expression;
    }

    function where($expression, $bindings = []) {
        $expression = $this->makeExpression($expression, $bindings);
        array_push($this->whereExpressions, $expression);
        return $this;
    }

    function whereEquals($left, $right, $bindings = []) {
        if(!($left instanceof Expression)) {
            $left = new Column($left);
        }
        if(!($right instanceof Expression)) {
            $right = new Placeholder($right);
        }

        return $this->where(new EqualsExpression($left, $right));
    }

    function groupBy($expression, $bindings = []) {
        $expression = $this->makeExpression($expression, $bindings);
        array_push($this->groupByExpressions, $expression);
        return $this;
    }

    function having($expression, $bindings = []) {
        $expression = $this->makeExpression($expression, $bindings);
        array_push($this->havingExpressions, $expression);
        return $this;
    }

    function orderBy($expression, $desc = false, $bindings = []) {
        $expression = $this->makeExpression($expression, $bindings);
        if ($desc && $desc != "ASC") {
            $expression = new OrderDesc($expression);
        }
        array_unshift($this->orderByExpressions, $expression);
        return $this;
    }

    function limit($count = null, $offset = null) {
        if (!($count instanceof Expression)) {
            $count = new Literal($count);
        }
        if ($offset && !($offset instanceof Expression)) {
            $offset = new Literal($offset);
        }
        $this->limitCount = $count;
        $this->limitOffset = $offset;
        return $this;
    }

    function getQuery() {
        $query = new SelectStructure();
        $query->selectColumns($this->selectColumns);
        if ($this->selectFromTableish) {
            $selectFromClause = new SelectFromClause($this->selectFromTableish);
            if ($this->whereExpressions) {
                $selectFromClause->where(new WhereClause(AndExpression::fromArray($this->whereExpressions)));
            }
            if ($this->groupByExpressions) {
                $selectFromClause->groupBy(GroupByClause::fromArray($this->groupByExpressions));
            }
            if ($this->havingExpressions) {
                $selectFromClause->having(new HavingClause(AndExpression::fromArray($this->havingExpressions)));
            }
            if ($this->orderByExpressions) {
                $selectFromClause->orderBy(OrderByClause::fromArray($this->orderByExpressions));
            }
            if ($this->limitCount) {
                $selectFromClause->limit(new LimitClause($this->limitCount, $this->limitOffset));
            }
            $query->selectFrom($selectFromClause);
        }
        return $query;
    }

    function selectColumn(SelectColumn $selectColumn) {
        array_push($this->selectColumns, $selectColumn);
        return $this;
    }

    function selectStar($tableName = null) {
        $this->selectStructure()->selectColumn(new SelectStar($tableName));
        return $this;
    }

    function selectExpression(Expression $expression, $as = null) {
        return $this->selectColumn(new SelectColumn($expression), $as);
    }

    function selectColumnName($columnName, $subName = null, $as = null) {
        return $this->selectExpression(new Column($columnName, $subName), $as);
    }

    function selectLiteral($literal, $as = null) {
        if (!($literal instanceof Literal)) {
            $literal = new Literal($literal);
        }
        return $this->selectExpression($literal, $as);
    }

    function selectSubquery($subquery, $as = null) {
        if (!($subquery instanceof SubqueryExpression)) {
            $subquery = new SubqueryExpression($subquery);
        }
        return $this->selectExpression($subquery, $as);
    }
}
