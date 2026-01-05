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
        // 首先检查并更新过期的优惠券（超过180天）
        $this->checkExpiredCoupons($userId);

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
            $expireDate = date('Y-m-d', strtotime($item->create_time . ' +180 days'));
            $data[] = [
                'id'         => $item->id,
                'coupon_id'  => $item->coupon_id,
                'title'      => $coupon->name,
                'money'      => $coupon->amount,
                'condition'  => $coupon->min_amount > 0 ? "满{$coupon->min_amount}可用" : "无门槛",
                'desc'       => "有效期至 " . $expireDate,
                'validDate'  => $expireDate,
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

    /**
     * 检查并更新过期的优惠券（超过180天）
     * @param string|int $userId
     */
    private function checkExpiredCoupons($userId)
    {
        $expireTime = date('Y-m-d H:i:s', strtotime('-180 days'));
        UserCouponModel::where('user_id', $userId)
            ->where('status', 0)
            ->where('create_time', '<', $expireTime)
            ->update(['status' => 2]);
    }
}
