<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;

class Order extends Validate
{
    protected $rule = [
        'page'      => 'number|min:1',
        'page_size' => 'number|between:1,100',
        'status'    => 'number|in:-1,0,1,2,3',
    ];

    protected $message = [
        'page.number'      => '页码格式不正确',
        'page.min'         => '页码最小为1',
        'page_size.number' => '每页数量格式不正确',
        'page_size.between'=> '每页数量需在1-100之间',
        'status.number'    => '状态格式不正确',
        'status.in'        => '状态值不正确',
    ];

    protected $scene = [
        'list' => ['page', 'page_size', 'status'],
    ];
}
