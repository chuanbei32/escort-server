<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'code' => 'require',
    ];

    protected $message = [
        'code.require' => 'code不能为空',
    ];

    protected $scene = [
        'wechat' => ['code'],
    ];
}
