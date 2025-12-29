<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Order as OrderModel;

class Order
{
    /**
     * 获取订单列表
     * @param int $userId
     * @param int $page
     * @param int $limit
     * @param int $status -1:全部 0:待支付 1:已支付 2:已退款 3:已完成
     * @return array
     */
    public function getOrderList(int $userId, int $page = 1, int $limit = 10, int $status = -1): array
    {
        $where = [['user_id', '=', $userId]];

        if ($status !== -1) {
            $where[] = ['status', '=', $status];
        }

        $list = OrderModel::where($where)
            ->order('create_time', 'desc')
            ->page($page, $limit)
            ->select();
            
        $total = OrderModel::where($where)->count();
            
        return [
            'list'      => $list,
            'total'     => $total,
            'page'      => $page,
            'page_size' => $limit,
        ];
    }

    /**
     * 获取订单详情
     * @param int $id
     * @param int $userId
     * @return OrderModel|null
     */
    public function getOrderDetail(int $id, int $userId): ?OrderModel
    {
        $info = OrderModel::with(['appointments'])
            ->where('id', $id)
            ->where('user_id', $userId)
            ->find();
        if (empty($info)) {
            return [];
        }
        $info->appointments->each(function ($item) {
            $item->serviceInfo = is_object($item->serviceInfo) ? $item->serviceInfo : [];
            $item->hospital = is_object($item->hospital) ? $item->hospital : [];
            $item->escortApplication = is_object($item->escortApplication) ? $item->escortApplication : [];
        });

        return $info;
    }
}
