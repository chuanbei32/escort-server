<?php
namespace app\api\model;

use think\Model;

class Appointment extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'ea8_appointment';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * 根据类型关联服务或套餐 (多态关联)
     * @return \think\model\relation\MorphTo
     */
    public function serviceInfo()
    {
        return $this->morphTo(['type', 'service_id'], [
            1 => Service::class,
            2 => ServicePackage::class
        ]);
    }
}
