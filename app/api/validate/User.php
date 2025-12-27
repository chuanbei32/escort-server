<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'nickname' => 'max:50',
        'avatar'   => 'url',
        'phone'    => 'mobile',
        'gender'   => 'in:0,1,2',
    ];

    protected $message = [
        'nickname.max'   => '昵称最多50个字符',
        'avatar.url'     => '头像地址格式不正确',
        'phone.mobile'   => '手机号格式不正确',
        'gender.in'      => '性别选择不正确',
    ];

    protected $scene = [
        'update' => ['nickname', 'avatar', 'phone', 'gender'],
    ];
}
