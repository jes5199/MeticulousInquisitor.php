<?php
namespace MeticulousInquisitor\Expression;

use \MeticulousInquisitor\Expression\Scalar;

class LiteralNull extends Scalar {
    function __toString() {
        return "NULL";
    }
}

