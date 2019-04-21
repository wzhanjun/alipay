<?php

namespace Wzhanjun\Alipay\Support;

class AlipayRsa
{

    /**
     * RSA签名
     *
     * @param string $data 待签名数据
     * @param string $private_key 商户私钥字符串
     * @return string  签名结果
     * @throws \Exception
     */
    public static function makeSign($data, $private_key) {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $private_key = str_replace("-----BEGIN RSA PRIVATE KEY-----", "", $private_key);
        $private_key = str_replace("-----END RSA PRIVATE KEY-----", "", $private_key);
        $private_key = str_replace("\n", "", $private_key);

        $private_key = "-----BEGIN RSA PRIVATE KEY-----" . PHP_EOL . wordwrap($private_key, 64, "\n", true);
        $private_key = $private_key . PHP_EOL . "-----END RSA PRIVATE KEY-----";

        $res = openssl_get_privatekey($private_key);
        if($res)
            openssl_sign($data, $sign, $res);
        else
            throw new \Exception('您的私钥格式不正确！');

        openssl_free_key($res);
        return base64_encode($sign);
    }


    /**
     * RSA验签
     *
     * @param string $data  待签名数据
     * @param string $alipay_public_key 支付宝的公钥字符串
     * @param string $sign 要校对的的签名结果
     * @return bool 验证结果
     * @throws \Exception
     */
    public static function verifySign($data, $alipay_public_key, $sign) {
        //以下为了初始化私钥，保证在您填写私钥时不管是带格式还是不带格式都可以通过验证。
        $alipay_public_key = str_replace("-----BEGIN PUBLIC KEY-----", "", $alipay_public_key);
        $alipay_public_key = str_replace("-----END PUBLIC KEY-----", "", $alipay_public_key);
        $alipay_public_key = str_replace("\n", "", $alipay_public_key);

        $alipay_public_key = '-----BEGIN PUBLIC KEY-----' . PHP_EOL . wordwrap($alipay_public_key, 64, "\n", true);
        $alipay_public_key = $alipay_public_key . PHP_EOL . '-----END PUBLIC KEY-----';

        $res = openssl_get_publickey($alipay_public_key);
        if($res)
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        else
            throw new \Exception('您的公钥格式不正确！');

        openssl_free_key($res);
        return $result;
    }


}