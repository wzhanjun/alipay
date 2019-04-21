<?php

namespace Wzhanjun\Alipay;

use Wzhanjun\Alipay\Support\Config;
use Wzhanjun\Alipay\Support\AlipayRsa;
use Wzhanjun\Alipay\Support\AlipayCore;
use Wzhanjun\Alipay\Support\HttpRequest;
use Wzhanjun\Alipay\Contracts\PayInterface;
use Wzhanjun\Alipay\Contracts\GatewayInterface;
use Wzhanjun\Alipay\Exceptions\GatewayErrorException;
use Wzhanjun\Alipay\Contracts\OrderInterface as Order;

class Alipay implements PayInterface
{
    /**
     * isSuccess
     */
    const IS_SUCCESS_TRUE  = 'T';
    const IS_SUCCESS_FALSE = 'F';

    /**
     * tradeStatus
     */
    const WAIT_BUYER_PAY = 'WAIT_BUYER_PAY';
    const TRADE_SUCCESS  = 'TRADE_SUCCESS';
    const TRADE_FINISHED = 'TRADE_FINISHED';

    private $config;

    private $client;

    public function __construct($config)
    {
        $this->config = new Config($config);

        $this->client = new HttpRequest();
    }


    /**
     * 支付
     *
     * @param $gateway
     * @param \Wzhanjun\Alipay\Order\Order $order
     * @return mixed
     * @throws GatewayErrorException
     */
    public function pay($gateway, Order $order)
    {
        $gateway = $this->formatGatewayClassName($gateway);

        if (!class_exists($gateway))
        {
            throw new GatewayErrorException(sprintf('Gateway "%s" not exists.', $gateway));
        }

        $app = new $gateway($this->config);

        if (!($app instanceof GatewayInterface))
        {
            throw new GatewayErrorException(sprintf('Gateway "%s" not inherited from %s.', $gateway, GatewayInterface::class));
        }

        return $app->pay($order);
    }

    /**
     * @param array $param
     * @return bool
     * @throws \Exception
     */
    public function verify(array $param)
    {
        if ($this->config->get('sign_type') == 'RSA')
        {
            $string = AlipayCore::createLinkString(AlipayCore::argSort(AlipayCore::paramFilter($param)));

            return (AlipayRsa::verifySign($string, $this->config->get('alipay_public_key'), $param['sign'] ?? '' ));
        }

        return false;
    }


    /**
     * 查询订单
     *
     * @param \Wzhanjun\Alipay\Order\Order $order
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Exception
     */
    public function query(Order $order)
    {
        $payload = [
            '_input_charset' => $this->config->get('_input_charset'),
            'sign_type'      => $this->config->get('sign_type'),
            'partner'        => $this->config->get('partner'),
            'service'        => 'single_trade_query',
            'out_trade_no'   => $order->getOutTradeNo(),
        ];

        $string = AlipayCore::createLinkString(AlipayCore::argSort(AlipayCore::paramFilter($payload)));

        $payload['sign'] = AlipayRsa::makeSign($string, $this->config->get('alipay_private_key'));

        return $this->parseTrade($this->client->get($this->config->get('endpoint'), $payload));

    }


    /**
     * Magic pay.
     *
     * @param $method
     * @param $params
     * @return mixed
     * @throws GatewayErrorException
     */
    public function __call($method, $params)
    {
        return $this->pay($method, ...$params);
    }


    protected function formatGatewayClassName($name)
    {
        if (class_exists($name))
        {
            return $name;
        }

        $name = ucfirst(str_replace(['-', '_', ''], '', $name));

        return __NAMESPACE__."\\Gateways\\{$name}Gateway";
    }


    private function parseTrade(array $detail)
    {
        $order = [];

        $success = $detail['is_success'] ?? self::IS_SUCCESS_FALSE;

        if ($success != self::IS_SUCCESS_TRUE)
        {
            return $order;
        }

        return $detail['response']['trade'] ?? [];
    }

}