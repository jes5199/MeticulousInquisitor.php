<?php
namespace MeticulousInquisitor\QueryStructure;

use \PedanticQuerent\Query;
use \MeticulousInquisitor\Clause;
use \MeticulousInquisitor\Clause\ClauseBag;

class GenericQueryStructure implements Query {
    protected $clauseBag;

    function __construct() {
        $this->clauseBag = new ClauseBag;
    }

    protected function clauseBag() {
        return $this->clauseBag;
    }

    function addClause(Clause $clause) {
        $this->clauseBag()->addClause($clause);
        return $this;
    }

    function maybeAddClause(Clause $clause = null) {
        $this->clauseBag()->maybeAddClause($clause);
        return $this;
    }

    function getSQL() {
        return $this->clausebag()->__toString();
    }

    function getBindings() {
        return $this->clauseBag()->getBindings();
    }
}
