<?php

namespace Wzhanjun\Alipay\Support;

class AlipayCore
{

    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
     * @param array $param 需要拼接的数组
     * @return string 拼接完成以后的字符串
     */
    public static function createLinkString(array $param)
    {
        $args = '';

        foreach ($param as $key => $val)
        {
            $args .= $key . '="' . $val . '"&';
        }

        //去掉最后一个&字符
        $args = substr($args, 0, -1);

        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc())
            $args = stripslashes($args);

        return $args;
    }


    public static function paramFilter(array $param)
    {
        $filter = [];

        foreach ($param as $key => $val)
        {
            if ($key == "sign")
                continue;

            if ($key == "sign_type")
                continue;

            if ($val === "" || is_null($val))
                continue;

            $filter[$key] = $val;
        }

        return $filter;
    }


    /**
     * 对数组排序
     *
     * @param array $param
     * @return array
     */
    public static function argSort(array $param)
    {
        ksort($param);
        reset($param);

        return $param;
    }

}