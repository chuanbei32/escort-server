<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Banner as BannerService;
use think\Response;

class Banner extends Base
{
    protected $service;

    public function __construct(\think\App $app, BannerService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 轮播图列表
     * @return Response
     */
    public function list(): Response
    {
        $type = $this->request->get('type/d', 1);
        
        try {
            $this->validate(['type' => $type], \app\api\validate\Common::class . '.type');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $data = $this->service->getBannerList($type);
        return $this->success($data);
    }
}
