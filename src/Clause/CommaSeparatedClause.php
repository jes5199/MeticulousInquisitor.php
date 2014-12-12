<?php
namespace MeticulousInquisitor\Clause;

use MeticulousInquisitor\Clause;

class CommaSeparatedClause extends Clause {
    protected $name;
    protected $content;

    function __construct($name, array $content) {
        $this->name = $name;
        $this->content = $content;
    }

    function name() {
        return $this->name;
    }

    function __toString() {
        return $this->commaSeparate($this->content);
    }

    function subparts() {
        return $this->content;
    }
}
