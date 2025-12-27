<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'code' => 'require',
        'pid'  => 'number',
    ];

    protected $message = [
        'code.require' => 'code不能为空',
        'pid.number'   => '推荐人ID必须是数字',
    ];

    protected $scene = [
        'wechat' => ['code', 'pid'],
    ];
}
