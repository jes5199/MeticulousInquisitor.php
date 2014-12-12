<?php
namespace MeticulousInquisitor\Util;

trait FromArray {
    static function fromArray(array $array) {
        $klass = new \ReflectionClass(static::class);
        return $klass->newInstanceArgs($array);
    }
}
