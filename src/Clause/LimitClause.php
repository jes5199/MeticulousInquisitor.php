<?php
namespace MeticulousInquisitor\Clause;

use \MeticulousInquisitor\Clause\LimitClause\LimitParam;

class LimitClause extends CompoundClause {
    protected $count;
    protected $offset;

    function name() {
        return "limit_clause";
    }

    static function assertNumeric(LimitParam $param) {
        if (!$param->isNumeric()) {
            throw new \InvalidArgumentException("Limit params must be a numeric Literal or a Placeholder");
        }
    }

    function __construct(LimitParam $count, LimitParam $offset = null) {
        static::assertNumeric($count);
        $this->count = $count;
        if ($offset) {
            static::assertNumeric($offset);
            $this->offset = $offset;
        }
    }

    function count() {
        return $this->count;
    }

    function offset() {
        return $this->offset;
    }

    function clauses() {
        $clauseBag = new ClauseBag();
        $clauseBag->addClause(new Keyword("LIMIT"));
        $clauseBag->addClause(new PartClause("limit_count", $this->count()));
        if ($this->offset()) {
            $clauseBag->addClause(new Keyword("OFFSET"));
            $clauseBag->addClause(new PartClause("limit_offset", $this->offset()));
        }
        return $clauseBag->clauses();
    }
}
