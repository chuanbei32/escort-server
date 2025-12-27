<?php
namespace app\api\model;

use think\Model;

class UserCoupon extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'ea8_user_coupon';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * 关联优惠券
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
