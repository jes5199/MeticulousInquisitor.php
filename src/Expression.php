<?php
namespace MeticulousInquiry;

use \MeticulousInquiry\QueryBuilder\Part;

abstract class Expression extends Part {
    abstract function precedence();
}
