<?php

namespace Wzhanjun\Alipay\Exceptions;

class GatewayErrorException extends Exception
{
    public $raw = [];

    public function __construct($message = "", $code = 0, array $raw = [])
    {
        parent::__construct($message, $code);

        $this->raw = $raw;
    }

}