<?php
namespace MeticulousInquisitor;

interface Tableish {
    function __toString();
    function getBindings();
    function alias();
}
