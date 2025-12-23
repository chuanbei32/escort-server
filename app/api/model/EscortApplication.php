<?php
namespace app\api\model;

use think\Model;

class EscortApplication extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'ea8_escort_application';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
}
