<?php
declare (strict_types = 1);

namespace app\api\service;

class Payment
{
    /**
     * 微信支付
     * @param int   $userId
     * @param array $data
     * @return array
     */
    public function wxpay(int $userId, array $data): array
    {
        // 模拟生成支付参数
        // 实际逻辑中应校验该订单是否属于该用户
        return [
            'timeStamp' => (string)time(),
            'nonceStr'  => uniqid(),
            'package'   => 'prepay_id=' . md5((string)time()),
            'signType'  => 'MD5',
            'paySign'   => md5(uniqid()),
        ];
    }
}
