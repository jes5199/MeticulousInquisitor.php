<?php
namespace MeticulousInquisitor\Clause\OrderByClause;

interface Order {
    function __toString();
    function getBindings();
}
