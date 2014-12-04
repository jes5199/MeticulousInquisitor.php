<?php
namespace MeticulousInquiry\QueryBuilder;

use \MeticulousInquiry\QueryBuilder\Clause;

class Keyword extends Clause {
    protected $keyword;

    function __construct($keyword) {
        $this->keyword = $keyword;
    }

    function __toString() {
        return $this->keyword;
    }

    function name() {
        return "keyword_" . $this->keyword;
    }

    function subparts() {
        return [];
    }
}
