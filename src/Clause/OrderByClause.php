<?php
namespace MeticulousInquisitor\Clause;

use \MeticulousInquisitor\Expression;
use \MeticulousInquisitor\Util\FromArray;
use \MeticulousInquisitor\Clause\CommaSeparatedClause;
use \MeticulousInquisitor\Clause\OrderByClause\Order;

class OrderByClause extends CompoundClause {
    use FromArray;

    protected $orders;

    function __construct(Order $order) {
        $this->orders = [];
        foreach (func_get_args() as $order) {
            $this->addOrder($order);
        }
    }

    function addOrder(Order $order) {
        array_push($this->orders, $order);
    }

    function name() {
        return "order_by";
    }

    function orders() {
        return $this->orders;
    }

    function clauses() {
        $clauseBag = new ClauseBag();
        $clauseBag->addClause(new Keyword("ORDER BY"));
        $clauseBag->addClause(new CommaSeparatedClause("order_by_expression", $this->orders()));
        return $clauseBag->clauses();
    }
}

