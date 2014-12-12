<?php
namespace MeticulousInquisitor\Expression;

use \MeticulousInquisitor\Expression\Scalar;

class Column extends Scalar {
    protected $name;
    protected $subname;

    function __construct($name, $subname = null) {
        $this->name = $name;
        $this->subname = $subname;
    }

    function __toString() {
        if ($this->subname) {
            return $this->backtick($this->name) . "." . $this->backtick($this->subname);
        } else {
            return $this->backtick($this->name);
        }
    }
}
