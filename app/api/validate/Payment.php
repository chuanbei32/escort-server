<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;

class Payment extends Validate
{
    protected $rule = [
        'order_id' => 'require|number',
    ];

    protected $message = [
        'order_id.require' => '订单ID不能为空',
        'order_id.number'  => '订单ID格式不正确',
    ];

    protected $scene = [
        'wxpay' => ['order_id'],
    ];
}
