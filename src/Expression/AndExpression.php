<?php
namespace MeticulousInquisitor\Expression;

use MeticulousInquisitor\Expression;

class AndExpression extends ListExpression {
    function separator() {
        return " AND ";
    }

    function precedenceUnlessPassthrough() {
        return 400;
    }
}
