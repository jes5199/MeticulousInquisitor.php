<?php
namespace MeticulousInquiry\QueryBuilder\Clause\SelectColumnsClause;

namespace MeticulousInquiry\QueryBuilder\Clause\SelectColumn;
use \MeticulousInquiry\Expression;

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
