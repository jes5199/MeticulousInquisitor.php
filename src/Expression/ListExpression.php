<?php
namespace MeticulousInquisitor\Expression;

use MeticulousInquisitor\Expression;
use MeticulousInquisitor\Util\FromArray;

abstract class ListExpression extends Expression {
    use FromArray;

    abstract function separator();
    abstract function precedenceUnlessPassthrough();

    protected $elements;

    function __construct() {
        $this->elements = func_get_args();
    }

    function elements() {
        return $this->elements;
    }

    function elementsInParensIfNeeded() {
        return array_map(
            [$this, "parenIfNeeded"],
            $this->elements()
        );
    }

    function __toString() {
        if (count($this->elements()) == 0) {
            return (new NullLiteral)->__toString();
        }
        if (count($this->elements()) == 1) {
            return $this->elements()[0]->__toString();
        }
        return join($this->separator(), $this->elementsInParensIfNeeded());
    }

    function subparts() {
        return $this->elements();
    }

    function precedence() {
        if (count($this->elements()) == 1) {
            return $this->elements()[0]->precedence();
        }
        return $this->precedenceUnlessPassthrough();
    }
}
