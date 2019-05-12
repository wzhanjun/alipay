<?php

namespace Wzhanjun\Alipay\Gateways;

use Wzhanjun\Alipay\Support\Config;
use Wzhanjun\Alipay\Support\AlipayRsa;
use Wzhanjun\Alipay\Support\AlipayCore;
use Wzhanjun\Alipay\Contracts\GatewayInterface;
use Wzhanjun\Alipay\Contracts\OrderInterface as Order;

class WapGateway implements GatewayInterface
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * 支付
     *
     * @param \Wzhanjun\Alipay\Order\Order $order
     *
     * @return string
     * @throws \Exception
     */
    public function pay(Order $order)
    {
        $payload = [
            'service'           => 'create_direct_pay_by_user',
            'out_trade_no'      => $order->getOutTradeNo(),
            'total_fee'         => $order->getTotalFee(),
            'goods_type'        => $order->getGoodsType(),
            'body'              => $order->getBody(),
            'subject'           => $order->getSubject(),
            'partner'           => $this->config->get('partner'),
            'seller_id'         => $this->config->get('partner'),
            '_input_charset'    => $this->config->get('input_charset', 'UTF-8'),
            'it_b_pay'          => $order->getItBPay() ?: $this->config->get('payment_time'),
            'notify_url'        => $order->getNotifyUrl() ?: $this->config->get('notify_url'),
            'return_url'        => $order->getReturnUrl() ?: $this->config->get('return_url'),
            'show_url'          => $order->getShowUrl() ?: $this->config->get('show_url', $this->config->get('return_url')),
            'payment_type'      => 1,
            'app_pay'           => $order->isH5AppPay() === false ? 0 : 1,
        ];

        $string = AlipayCore::createLinkString(AlipayCore::argSort(AlipayCore::paramFilter($payload)));

        $payload['sign'] = AlipayRsa::makeSign($string, $this->config->get('alipay_private_key'));

        return "http://mapi.alipay.com/gateway.do?" . http_build_query($payload) . '&sign=' . urlencode($payload['sign']) . '&sign_type=' . $this->config->get('sign_type', 'RSA');

    }

}