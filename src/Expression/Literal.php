<?php
namespace MeticulousInquiry\Expression;

use \MeticulousInquiry\Expression\Scalar;

/*
 * Do *not* use this class to wrap data that comes from an untrusted source
 */
class Literal extends Scalar {
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

    function __toString() {
        if (is_numeric($this->value)) {
            return "" . $this->value;
        } else {
            return $this->quote($this->value);
        }
    }
}
