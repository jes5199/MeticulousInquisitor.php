<?php
namespace MeticulousInquiry\QueryBuilder;

use \MeticulousInquiry\QueryBuilder\Part;

abstract class Clause extends Part {
    abstract function name();
}

