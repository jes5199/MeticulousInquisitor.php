<?php
namespace MeticulousInquisitor\QueryStructure;

use \PedanticQuerent\Query\SelectQuery;
use \PedanticQuerent\QueryDelegate;
use \MeticulousInquisitor\QueryStructure\GenericQueryStructure;
use \MeticulousInquisitor\Clause\Keyword;
use \MeticulousInquisitor\Clause\SelectColumnsClause;
use \MeticulousInquisitor\Clause\SelectColumnsClause\SelectColumn;
use \MeticulousInquisitor\Clause\SelectFromClause;

class SelectStructure implements SelectQuery {
    use QueryDelegate;

    protected $selectColumnsClause;
    protected $selectFromClause;
    protected $selectDistinct;

    function __construct() {
        $this->selectColumnsClause = new SelectColumnsClause;
        $this->selectFromClause = null;
        $this->selectDistinct = false;
    }

    protected function selectColumnsClause() {
        return $this->selectColumnsClause;
    }

    protected function selectFromClause() {
        return $this->selectFromClause;
    }

    function getQuery() {
        $queryBuilder = new GenericQueryStructure;
        $queryBuilder->addClause(new Keyword("SELECT"));
        $queryBuilder->maybeAddClause($this->selectDistinct ? new Keyword("DISTINCT") : null);
        $queryBuilder->addClause($this->selectColumnsClause());
        $queryBuilder->maybeAddClause($this->selectFromClause());

        return $queryBuilder;
    }

    function distinct() {
        $this->selectDistinct = true;
        return $this;
    }

    function all() {
        $this->selectDistinct = false;
        return $this;
    }

    function selectColumn(SelectColumn $selectColumn) {
        $this->selectColumnsClause()->addColumn($selectColumn);
        return $this;
    }

    function selectColumns(array $selectColumns) {
        foreach ($selectColumns as $selectColumn) {
            $this->selectColumn($selectColumn);
        }
        return $this;
    }

    function selectFrom(SelectFromClause $selectFromClause) {
        $this->selectFromClause = $selectFromClause;
        return $this;
    }

    function subparts() {
        return [$this->selectColumnsClause(), $this->selectFromClause()];
    }
}
