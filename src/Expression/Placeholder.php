<?php
namespace MeticulousInquisitor\Expression;

use \MeticulousInquisitor\Expression;
use \MeticulousInquisitor\Clause\LimitClause\LimitParam;

class PlaceHolder extends Scalar implements LimitParam {
    static $id = 0;
    protected $id;
    protected $binding;

    function __construct($binding = null) {
        $this->id = self::$id++;
        $this->binding = $binding;
    }

    function name() {
        return ":placeholder" . $this->id;
    }

    function __toString() {
        return $this->name();
    }

    function getBindings() {
        return [$this->name() => $this->binding];
    }

    function isNumeric() {
        // kludge for the sake of LimitClause's weird restrictions
        return true;
    }
}
