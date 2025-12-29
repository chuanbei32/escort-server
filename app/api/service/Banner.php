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
        $list = BannerModel::order('sort', 'desc')
            ->select()
            ->toArray();
        foreach ($list as &$item) {
            if (isset($item['image_url']) && !empty($item['image_url'])) {
                if (str_starts_with($item['image_url'], '/')) {
                    $item['image_url'] = request()->domain() . $item['image_url'];
                } elseif (!str_starts_with($item['image_url'], 'http')) {
                    $item['image_url'] = request()->domain() . '/' . $item['image_url'];
                }
            }
        }
        return $list;
    }
}
