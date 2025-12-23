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
     * @return array
     */
    public function getOrderList(int $userId, int $page = 1, int $limit = 10): array
    {
        $list = OrderModel::where('user_id', $userId)
            ->order('create_time', 'desc')
            ->page($page, $limit)
            ->select();
            
        $total = OrderModel::where('user_id', $userId)->count();
            
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
        return OrderModel::where('id', $id)
            ->where('user_id', $userId)
            ->find();
    }
}
