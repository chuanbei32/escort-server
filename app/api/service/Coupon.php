<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\UserCoupon as UserCouponModel;

class Coupon
{
    /**
     * 获取优惠券列表
     * @param string|int $userId
     * @param int $status
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getCouponList($userId, int $page = 1, int $pageSize = 10): array
    // public function getCouponList($userId, int $status = 0, int $page = 1, int $pageSize = 10): array
    {
        $where = [
            ['user_id', '=', $userId],
            // ['status', '=', $status],
            ['status', '=', 0],
        ];
        
        $total = UserCouponModel::where($where)->count();
        $list = UserCouponModel::with(['coupon'])
            ->where($where)
            ->page($page, $pageSize)
            ->order('create_time', 'desc')
            ->select();

        $data = [];
        foreach ($list as $item) {
            if (!$item->coupon) continue;
            
            $coupon = $item->coupon;
            $data[] = [
                'id'         => $item->id,
                'coupon_id'  => $item->coupon_id,
                'title'      => $coupon->name,
                'money'      => (int) $coupon->amount,
                'condition'  => $coupon->min_amount > 0 ? "满{$coupon->min_amount}可用" : "无门槛",
                'desc'       => "有效期至 " . substr($coupon->end_time, 0, 10),
                'validDate'  => substr($coupon->end_time, 0, 10),
                'status'     => $item->status,
                'create_time'=> $item->create_time,
            ];
        }
        
        return [
            'list'      => $data,
            'total'     => $total,
            'page'      => $page,
            'page_size' => $pageSize,
        ];
    }
}
