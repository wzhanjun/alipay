<?php

namespace Wzhanjun\Alipay\Gateways;

use Wzhanjun\Alipay\Support\Config;
use Wzhanjun\Alipay\Support\AlipayRsa;
use Wzhanjun\Alipay\Support\AlipayCore;
use Wzhanjun\Alipay\Contracts\GatewayInterface;
use Wzhanjun\Alipay\Contracts\OrderInterface as Order;

class AppGateway implements GatewayInterface
{
    const CLIENT_TYPE_ANDROID = 0;
    const CLIENT_TYPE_IOS     = 1;

    const CLIENT_TYPES_APP = [
        self::CLIENT_TYPE_ANDROID, self::CLIENT_TYPE_IOS
    ];

    const MOBILE_PAY_SERVICE = 'mobile.securitypay.pay';
    const DIRECT_PAY_SERVICE = 'create_direct_pay_by_user';

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }


    /**
     * 支付
     *
     * @param \Wzhanjun\Alipay\Order\Order $order
     * @throws \Exception
     */
    public function pay(Order $order)
    {
        if (empty($service = $order->getService()))
        {
            $service = in_array($order->getClientType(), self::CLIENT_TYPES_APP)
                ? self::MOBILE_PAY_SERVICE : self::DIRECT_PAY_SERVICE;
        }

        $system = $order->getClientType() == self::CLIENT_TYPE_ANDROID ? 'android' : 'ios';

        $payload = [
            'service'           => $service,
            'out_trade_no'      => $order->getOutTradeNo(),
            'total_fee'         => $order->getTotalFee(),
            'goods_type'        => $order->getGoodsType(),
            'body'              => $order->getBody(),
            'subject'           => $order->getSubject(),
            'partner'           => $this->config->get('partner'),
            'seller_id'         => $this->config->get('seller_id'),
            '_input_charset'    => $this->config->get('input_charset', 'utf-8'),
            'appenv'            => "system={$system}^version=" . $order->getClientVersion(),
            'it_b_pay'          => $order->getItBPay() ?: $this->config->get('payment_time'),
            'notify_url'        => $order->getNotifyUrl() ?: $this->config->get('notify_url'),
            'payment_type'      => $order->getPaymentType() ?: $this->config->get('payment_type'),
        ];

        $string = AlipayCore::createLinkString(AlipayCore::paramFilter($payload), true);

        $payload['sign'] = AlipayRsa::makeSign($string, $this->config->get('alipay_private_key'));

        return $string . '&sign="' . urlencode($payload['sign']) . '"&sign_type="' . $this->config->get('sign_type', 'RSA') . '"';
    }


}