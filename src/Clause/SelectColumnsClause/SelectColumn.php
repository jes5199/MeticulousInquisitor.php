<?php
namespace MeticulousInquisitor\Clause\SelectColumnsClause;

use \MeticulousInquisitor\Part;
use \MeticulousInquisitor\Expression;

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

    function asName($name = null) {
        if ($name === null) {
            return $this->asName;
        } else {
            $this->asName = $name;
        }
    }

    function __toString() {
        if ($this->asName()) {
            return $this->expression() . " AS " . $this->backtick($this->asName());
        } else {
            return $this->stringify($this->expression());
        }
    }

    function subparts() {
        return [$this->expression()];
    }
}
