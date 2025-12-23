<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\Banner;
use app\api\model\ServicePackage;
use app\api\model\Hospital;

class Home
{
    /**
     * 获取首页数据
     * @return array
     */
    public function getHomeData(): array
    {
        return [
            'banners'          => $this->getBanners(),
            'service_packages' => $this->getServicePackages(),
            'hospitals'        => $this->getHospitals(),
        ];
    }

    /**
     * 获取轮播图
     * @return array
     */
    public function getBanners(): array
    {
        return Banner::where('status', 1)
            ->order('sort', 'desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取服务套餐
     * @return array
     */
    public function getServicePackages(): array
    {
        return ServicePackage::where('status', 1)
            ->order('sort', 'desc')
            ->select()
            ->toArray();
    }

    /**
     * 获取医院列表
     * @param int $limit
     * @return array
     */
    public function getHospitals(int $limit = 10): array
    {
        return Hospital::where('status', 1)
            ->order('sort', 'desc')
            ->limit($limit)
            ->select()
            ->toArray();
    }
}
