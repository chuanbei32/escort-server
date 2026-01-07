<?php
namespace app\api\model;

use think\Model;

class Service extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'ea8_service';

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

        /**
     * image_url 获取器
     * @param $value
     * @return string
     */
    public function getImageUrlAttr($value)
    {
        if (empty($value)) return $value;
        if (str_starts_with($value, '/')) {
            return request()->domain() . $value;
        } elseif (!str_starts_with($value, 'http')) {
            return request()->domain() . '/' . $value;
        }
        return $value;
    }

    /**
     * detailimages 获取器
     * @param $value
     * @return array
     */
    public function getDetailImagesAttr($value)
    {
        if (empty($value)) return [];
        $images = explode('|', $value);
        $domain = request()->domain();
        foreach ($images as &$image) {
            if (str_starts_with($image, '/')) {
                $image = $domain . $image;
            } elseif (!str_starts_with($image, 'http')) {
                $image = $domain . '/' . $image;
            }
        }
        return $images;
    }
}
