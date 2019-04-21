<?php

namespace Wzhanjun\Alipay\Order;

use Wzhanjun\Alipay\Contracts\OrderInterface;

class Order implements OrderInterface
{
    private $outTradeNo;

    private $subject;

    private $body;

    private $totalFee;

    private $notifyUrl;

    private $paymentType = 1;

    private $partner;

    private $inputCharset;

    private $service;

    private $sellerId;

    private $itBPay;

    private $signType;

    private $goodsType = 1;

    private $clientType = 0;

    private $clientVersion = '1.0.0';

    /**
     * @return mixed
     */
    public function getOutTradeNo()
    {
        return $this->outTradeNo;
    }

    /**
     * @param mixed $outTradeNo
     * @return Order
     */
    public function setOutTradeNo($outTradeNo)
    {
        $this->outTradeNo = $outTradeNo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     * @return Order
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     * @return Order
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTotalFee()
    {
        return $this->totalFee;
    }

    /**
     * @param mixed $totalFee
     * @return Order
     */
    public function setTotalFee($totalFee)
    {
        $this->totalFee = $totalFee;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    /**
     * @param mixed $notifyUrl
     * @return Order
     */
    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
        return $this;
    }

    /**
     * @return int
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param int $paymentType
     * @return Order
     */
    public function setPaymentType(int $paymentType)
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPartner()
    {
        return $this->partner;
    }

    /**
     * @param mixed $partner
     * @return Order
     */
    public function setPartner($partner)
    {
        $this->partner = $partner;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInputCharset()
    {
        return $this->inputCharset;
    }

    /**
     * @param mixed $inputCharset
     * @return Order
     */
    public function setInputCharset($inputCharset)
    {
        $this->inputCharset = $inputCharset;
        return $this;
    }

    /**
     * @return int
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * @param int $clientType
     * @return Order
     */
    public function setClientType(int $clientType)
    {
        $this->clientType = $clientType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientVersion()
    {
        return $this->clientVersion;
    }

    /**
     * @param mixed $clientVersion
     * @return Order
     */
    public function setClientVersion($clientVersion)
    {
        $this->clientVersion = $clientVersion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     * @return Order
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSellerId()
    {
        return $this->sellerId;
    }

    /**
     * @param mixed $sellerId
     * @return Order
     */
    public function setSellerId($sellerId)
    {
        $this->sellerId = $sellerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItBPay()
    {
        return $this->itBPay;
    }

    /**
     * @param mixed $itBPay
     * @return Order
     */
    public function setItBPay($itBPay)
    {
        $this->itBPay = $itBPay;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSignType()
    {
        return $this->signType;
    }

    /**
     * @param mixed $signType
     * @return Order
     */
    public function setSignType($signType)
    {
        $this->signType = $signType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGoodsType()
    {
        return $this->goodsType;
    }

    /**
     * @param  $goodsType
     * @return Order
     */
    public function setGoodsType($goodsType)
    {
        $this->goodsType = $goodsType;

        return $this;
    }

}