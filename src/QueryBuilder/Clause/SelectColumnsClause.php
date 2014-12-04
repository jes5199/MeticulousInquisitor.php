<?php
namespace MeticulousInquiry\QueryBuilder\Clause;

use \MeticulousInquiry\QueryBuilder\Part;
use \MeticulousInquiry\QueryBuilder\Clause;
use \MeticulousInquiry\QueryBuilder\Clause\SelectColumnsClause\SelectColumn;

class SelectColumnsClause extends Clause {
    protected $columns;

    function __construct() {
        $this->columns = [];

        foreach (func_get_args() as $column) {
            $this->addColumn($column);
        }
    }

    function addColumn(SelectColumn $column) {
        array_push($this->columns, $column);
        return $this;
    }

    function name() {
        return "select_columns";
    }

    function columns() {
        return $this->columns;
    }

    function subparts() {
        return $this->columns();
    }

    function __toString() {
        return $this->commaSeparate($this->columns());
    }
}
