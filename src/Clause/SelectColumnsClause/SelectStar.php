<?php
namespace MeticulousInquisitor\QueryBuilder\Clause\SelectColumnsClause;

namespace MeticulousInquisitor\QueryBuilder\Clause\SelectColumn;
use \MeticulousInquisitor\Expression;

class SelectStar extends SelectColumn {
    protected $tableName;

    function __construct($tableName = null) {
        $this->tableName = $tableName;
    }

    function __toString() {
        if ($this->tableName) {
            return $this->backtick($this->tableName) ".*";
        } else {
            return "*";
        }
    }
}
