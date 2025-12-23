<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Service as ServiceLogic;
use think\Response;

class Service extends Base
{
    protected $service;

    public function __construct(\think\App $app, ServiceLogic $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 服务列表
     * @return Response
     */
    public function list(): Response
    {
        $hospitalId = $this->request->get('hospital_id/d');
        $page = $this->request->get('page/d', 1);
        $limit = $this->request->get('page_size/d', 10);
        
        $where = [];
        if ($hospitalId) {
            $where[] = ['hospital_id', '=', $hospitalId];
        }
        
        $data = $this->service->getServiceList($where, $page, $limit);
        return $this->success($data);
    }

    /**
     * 服务详情
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        $info = $this->service->getServiceDetail($id);
        if (!$info) {
            return $this->error('服务不存在');
        }
        return $this->success($info);
    }
}
