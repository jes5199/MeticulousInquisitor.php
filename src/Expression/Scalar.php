<?php
namespace MeticulousInquisitor\Expression;

use \MeticulousInquisitor\Expression;

abstract class Scalar extends Expression {
    function precedence() {
        return INF;
    }

    function subparts() {
        return [];
    }
}
