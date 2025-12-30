<?php
namespace app\api\model;

use think\Model;

class User extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'ea8_user';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * avatar 获取器
     * @param $value
     * @return string
     */
    public function getAvatarAttr($value)
    {
        if (empty($value)) return $value;
        if (str_starts_with($value, '/')) {
            return request()->domain() . $value;
        } elseif (!str_starts_with($value, 'http')) {
            return request()->domain() . '/' . $value;
        }
        return $value;
    }
}
