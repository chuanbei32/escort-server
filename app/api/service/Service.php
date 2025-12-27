<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Service as ServiceModel;
use app\api\model\ServicePackage as ServicePackageModel;

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
        $list = ServiceModel::where($where)
            ->page($page, $limit)
            ->select();
            
        $total = ServiceModel::where($where)
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
     * @param int $type 1-服务 2-套餐
     * @return mixed
     */
    public function getServiceDetail(int $id, int $type = 1)
    {
        if ($type == 2) {
            return ServicePackageModel::where('id', $id)
                ->where('status', 1)
                ->find();
        }
        
        return ServiceModel::where('id', $id)
            ->where('status', 1)
            ->find();
    }
}
