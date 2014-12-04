<?php
namespace MeticulousInquiry\Expression;

use \MeticulousInquiry\Expression;

abstract class Scalar extends Expression {
    function precedence() {
        return INF;
    }

    function subparts() {
        return [];
    }
}
