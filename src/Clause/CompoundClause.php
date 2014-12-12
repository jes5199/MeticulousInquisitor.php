<?php
namespace MeticulousInquisitor\Clause;

use \MeticulousInquisitor\Clause;

abstract class CompoundClause extends Clause {
    abstract function clauses();

    function __toString() {
        return join(" ", $this->clauses());
    }

    function subparts() {
        return $this->clauses();
    }
}
