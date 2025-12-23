<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Hospital as HospitalModel;

class Hospital
{
    /**
     * 获取医院列表
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getHospitalList(int $page = 1, int $limit = 10): array
    {
        $list = HospitalModel::where('status', 1)
            ->order('sort', 'desc')
            ->page($page, $limit)
            ->select();
            
        $total = HospitalModel::where('status', 1)->count();
            
        return [
            'list'      => $list,
            'total'     => $total,
            'page'      => $page,
            'page_size' => $limit,
        ];
    }

    /**
     * 获取医院详情
     * @param int $id
     * @return HospitalModel|null
     */
    public function getHospitalDetail(int $id): ?HospitalModel
    {
        return HospitalModel::where('id', $id)
            ->where('status', 1)
            ->find();
    }
}
