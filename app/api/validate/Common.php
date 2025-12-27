<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;

class Common extends Validate
{
    protected $rule = [
        'page'      => 'number|min:1',
        'page_size' => 'number|between:1,100',
        'limit'     => 'number|between:1,100',
        'id'        => 'require|number',
        'type'      => 'number',
        'status'    => 'number',
    ];

    protected $message = [
        'page.number'      => '页码格式不正确',
        'page.min'         => '页码最小为1',
        'page_size.number' => '每页数量格式不正确',
        'page_size.between'=> '每页数量需在1-100之间',
        'limit.number'     => '限制数量格式不正确',
        'limit.between'    => '限制数量需在1-100之间',
        'id.require'       => 'ID不能为空',
        'id.number'        => 'ID格式不正确',
        'type.number'      => '类型格式不正确',
        'status.number'    => '状态格式不正确',
    ];

    protected $scene = [
        'paging' => ['page', 'page_size'],
        'limit'  => ['page', 'limit'],
        'id'     => ['id'],
        'type'   => ['type'],
        'status' => ['status'],
    ];
}
