<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\ServicePackage;

class Package
{
    /**
     * 获取套餐列表
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function getPackageList(int $page = 1, int $limit = 10): array
    {
        $list = ServicePackage::page($page, $limit)
            ->select();
            
        $total = ServicePackage::where('status', 1)->count();
            
        return [
            'list'      => $list,
            'total'     => $total,
            'page'      => $page,
            'page_size' => $limit,
        ];
    }
}
