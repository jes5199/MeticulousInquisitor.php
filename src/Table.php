<?php
namespace MeticulousInquisitor;

use \MeticulousInquisitor\Tableish;
use \MeticulousInquisitor\Part;

class Table extends Part implements Tableish {
    protected $name;
    protected $alias;

    function __construct($name, $alias = null) {
        $this->name = $name;
        $this->alias = $alias;
    }

    function name() {
        return $this->name;
    }

    function alias() {
        return $this->alias;
    }

    function __toString() {
        if ($this->alias()) {
            return $this->backtick($this->name()) . " " . $this->backtick($this->alias());
        } else {
            return $this->backtick($this->name());
        }
    }

    function subparts() {
        return [];
    }
}
