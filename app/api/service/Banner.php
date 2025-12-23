<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Banner as BannerModel;

class Banner
{
    /**
     * 获取轮播图列表
     * @param int $type
     * @return array
     */
    public function getBannerList(int $type = 1): array
    {
        return BannerModel::where('status', 1)
            ->where('type', $type)
            ->order('sort', 'desc')
            ->select()
            ->toArray();
    }
}
