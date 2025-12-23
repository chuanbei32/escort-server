<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Service as ServiceModel;

class Service
{
    /**
     * 获取服务列表
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getServiceList(array $where = [], int $page = 1, int $limit = 10): array
    {
        $list = ServiceModel::where('status', 1)
            ->where($where)
            ->order('sort', 'desc')
            ->page($page, $limit)
            ->select();
            
        $total = ServiceModel::where('status', 1)
            ->where($where)
            ->count();
            
        return [
            'list'      => $list,
            'total'     => $total,
            'page'      => $page,
            'page_size' => $limit,
        ];
    }

    /**
     * 获取服务详情
     * @param int $id
     * @return ServiceModel|null
     */
    public function getServiceDetail(int $id): ?ServiceModel
    {
        return ServiceModel::where('id', $id)
            ->where('status', 1)
            ->find();
    }
}
