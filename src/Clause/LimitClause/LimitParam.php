<?php
namespace MeticulousInquisitor\Clause\LimitClause;

interface LimitParam {
    function isNumeric();
    function getBindings();
    function __toString();
}
