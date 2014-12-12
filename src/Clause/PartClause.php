<?php
namespace MeticulousInquisitor\Clause;

use \MeticulousInquisitor\Clause;

class PartClause extends Clause {
    protected $name;
    protected $content;

    function __construct($name, $content) {
        $this->name = $name;
        $this->content = $content;
    }

    function name() {
        return $this->name;
    }

    function __toString() {
        return $this->stringify($this->content);
    }

    function subparts() {
        return [$this->content];
    }
}
