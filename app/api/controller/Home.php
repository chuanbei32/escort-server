<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Home as HomeService;
use think\Response;

class Home extends Base
{
    protected $service;

    public function __construct(\think\App $app, HomeService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 首页数据接口
     * @return Response
     */
    public function index(): Response
    {
        $data = $this->service->getHomeData();
        return json(['code' => 1, 'msg' => 'success', 'data' => $data]);
    }

    /**
     * 获取更多医院列表
     * @return Response
     */
    public function moreHospitals(): Response
    {
        $page = $this->request->get('page/d', 1);
        $limit = $this->request->get('limit/d', 10);
        
        $hospitals = \app\api\model\Hospital::where('status', 1)
            ->order('sort', 'desc')
            ->page($page, $limit)
            ->select();
            
        return json(['code' => 1, 'msg' => 'success', 'data' => $hospitals]);
    }
}
