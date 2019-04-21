<?php

namespace Wzhanjun\Alipay\Contracts;

use Wzhanjun\Alipay\Contracts\OrderInterface as Order;

interface GatewayInterface
{
    public function pay(Order $order);
}