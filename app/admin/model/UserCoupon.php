<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class UserCoupon extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "user_coupon",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
      // 状态：0-未使用，1-已使用，2-已过期
    'status' => [
      0 => '未使用',
      1 => '已使用',
      2 => '已过期',
    ],
];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id', 'id');
    }

}