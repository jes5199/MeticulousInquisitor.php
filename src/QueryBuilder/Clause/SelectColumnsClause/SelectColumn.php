<?php
namespace MeticulousInquiry\QueryBuilder\Clause\SelectColumnsClause;

use \MeticulousInquiry\QueryBuilder\Part;
use \MeticulousInquiry\Expression;

class SelectColumn extends Part {
    protected $expression;
    protected $asName;

    function __construct(Expression $expression, $asName = null) {
        $this->expression = $expression;
        $this->asName = $asName;
    }

    function expression() {
        return $this->expression;
    }

    function asName() {
        return $this->asName;
    }

    function __toString() {
        if ($this->asName()) {
            return $this->expression() . " as " . $this->backtick($this->asName());
        } else {
            return "${$this->expression()}";
        }
    }

    function subparts() {
        return [$this->expression()];
    }
}
