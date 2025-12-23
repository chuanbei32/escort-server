<?php
declare (strict_types = 1);

namespace app\api\service;

class Payment
{
    /**
     * 微信支付
     * @param array $data
     * @return array
     */
    public function wxpay(array $data): array
    {
        // 模拟生成支付参数
        return [
            'timeStamp' => (string)time(),
            'nonceStr'  => uniqid(),
            'package'   => 'prepay_id=' . md5((string)time()),
            'signType'  => 'MD5',
            'paySign'   => md5(uniqid()),
        ];
    }
}
