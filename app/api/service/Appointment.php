<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Appointment as AppointmentModel;
use app\api\model\Order as OrderModel;
use app\api\model\Service as ServiceModel;
use app\api\model\ServicePackage as ServicePackageModel;
use app\api\model\UserCoupon as UserCouponModel;
use think\facade\Db;

class Appointment
{
    /**
     * 创建预约并生成订单
     * @param int   $userId
     * @param array $data
     * @return AppointmentModel
     */
    public function create(int $userId, array $data): AppointmentModel
    {
        return Db::transaction(function () use ($userId, $data) {
            $type = $data['type'] ?? 1;
            $serviceId = $data['service_id'] ?? 0;

            // 1. 获取服务或套餐信息
            if ($type == 2) {
                $service = ServicePackageModel::find($serviceId);
                $errorMsg = '服务套餐不存在';
            } else {
                $service = ServiceModel::find($serviceId);
                $errorMsg = '服务不存在';
            }

            if (!$service) {
                throw new \Exception($errorMsg);
            }

            // 2. 先创建预约信息 (暂时将 order_id 设为 0)
            $data['user_id'] = $userId;
            $data['order_id'] = 0;
            $data['status'] = 0; // 待确认
            $appointment = AppointmentModel::create($data);

            // 3. 计算金额并处理优惠券
            $totalFee = $service->price;
            $couponId = $data['coupon_id'] ?? 0;
            if ($couponId > 0) {
                $userCoupon = UserCouponModel::with(['coupon'])->where([
                    ['id', '=', $couponId],
                    ['user_id', '=', $userId],
                    ['status', '=', 0]
                ])->find();

                if ($userCoupon && $userCoupon->coupon) {
                    if ($totalFee >= $userCoupon->coupon->min_amount) {
                        $totalFee = max(0, $totalFee - $userCoupon->coupon->amount);
                        // 标记优惠券已使用
                        $userCoupon->save(['status' => 1]);
                    }
                }
            }

            // 4. 生成订单，并关联预约 ID
            $orderData = [
                'order_sn'       => date('YmdHis') . str_pad((string)mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
                'user_id'        => $userId,
                'total_fee'      => $totalFee,
                'coupon_id'      => $couponId > 0 ? $couponId : null,
                'status'         => 0, // 待支付
                'used_count'     => 1,
                'expected_time'  => isset($service->valid_day) ? $service->valid_day : 0,
            ];

            if ($type == 2) {
                $orderData['package_id'] = $serviceId;
                $orderData['total_count'] = $service->total_count;
            } else {
                $orderData['package_id'] = null;
                $orderData['total_count'] = 1;
            }

            $order = OrderModel::create($orderData);

            // 5. 回填预约表中的订单 ID
            $appointment->save(['order_id' => $order->id]);

            return $appointment;
        });
    }
}
