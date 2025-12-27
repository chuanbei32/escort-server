<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;

class Upload extends Validate
{
    protected $rule = [
        // 'file' => 'require|file|fileSize:2097152|fileExt:jpg,jpeg,png,gif',
        'file' => 'require|file',
    ];

    protected $message = [
        'file.require'  => '请选择上传文件',
        // 'file.file'     => '上传文件无效',
        // 'file.fileSize' => '文件大小不能超过2MB',
        // 'file.fileExt'  => '只支持jpg,jpeg,png,gif格式图片',
    ];

    protected $scene = [
        'file' => ['file'],
    ];
}
