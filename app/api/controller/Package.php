<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Package as PackageService;
use think\Response;

class Package extends Base
{
    protected $service;

    public function __construct(\think\App $app, PackageService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 套餐列表
     * @return Response
     */
    public function list(): Response
    {
        $page = $this->request->get('page/d', 1);
        $limit = $this->request->get('page_size/d', 10);
        
        $data = $this->service->getPackageList($page, $limit);
        return $this->success($data);
    }
}
