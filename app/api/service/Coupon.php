<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Coupon as CouponModel;

class Coupon
{
    /**
     * 获取优惠券列表
     * @param int $userId
     * @param int $status
     * @return array
     */
    public function getCouponList(int $userId, int $status = 1): array
    {
        return CouponModel::where('user_id', $userId)
            ->where('status', $status)
            ->select()
            ->toArray();
    }
}
