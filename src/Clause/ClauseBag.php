<?php
namespace MeticulousInquisitor\Clause;

use \MeticulousInquisitor\Clause;
use \MeticulousInquisitor\Part;

class ClauseBag extends Part {
    protected $clauses;

    function addClause(Clause $clause) {
        $this->clauses[$clause->name()] = $clause;
        return $this;
    }

    function maybeAddClause(Clause $clause = null) {
        if ($clause) {
            $this->addClause($clause);
        }
        return $this;
    }

    function clauses() {
        return $this->clauses;
    }

    function __toString() {
        return join(" ", $this->clauses());
    }

    protected function subparts() {
        return $this->clauses();
    }
}
