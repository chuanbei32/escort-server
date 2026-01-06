<?php

namespace app\admin\model;

use app\common\model\TimeModel;

class Order extends TimeModel
{

    protected function getOptions(): array
    {
        return [
            'name'       => "order",
            'table'      => "",
            'deleteTime' => false,
        ];
    }

    public static array $notes = [
      'is_coupon' => [
        0 => '否',
        1 => '是',
      ],
      'status' => [
    0 => '待支付',
    1 => '已支付',
    2 => '已退款',
    3 => '已完成',
  ],
];

  protected function getIsCouponAttr($value, $data): string
    {
        return $data['coupon_id'] != 0 ? 1 : 0;
    }

    protected function getStatusAttr($value, $data): string
    {
        return $this::$notes['status'][$value] ?? '';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }

    public function userCoupon()
    {
        return $this->belongsTo(UserCoupon::class, 'coupon_id', 'id');
    }

    

}