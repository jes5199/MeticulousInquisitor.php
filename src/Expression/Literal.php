<?php
namespace MeticulousInquisitor\Expression;

use \MeticulousInquisitor\Expression\Scalar;
use \MeticulousInquisitor\Clause\LimitClause\LimitParam;

/*
 * Do *not* use this class to wrap data that comes from an untrusted source
 */
class Literal extends Scalar implements LimitParam {
    protected $value;

    function __construct($value) {
        $this->value = $value;
    }

    function quote($value) {
        $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
        $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

        $safeValue = str_replace($search, $replace, $value);

        return "'$safeValue'";
    }

    function isNumeric() {
        return(is_numeric($this->value));
    }

    function __toString() {
        if ($this->isNumeric()) {
            return $this->stringify($this->value);
        } else {
            return $this->quote($this->value);
        }
    }
}
