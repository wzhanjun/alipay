<?php

namespace Wzhanjun\Alipay\Contracts;

use Wzhanjun\Alipay\Contracts\OrderInterface as Order;

interface PayInterface
{

    public function pay($gateway, Order $order);

    public function verify(array $params);

    public function query(Order $order);

}