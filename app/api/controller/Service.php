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
        
        try {
            $this->validate([
                'page'      => $page,
                'page_size' => $limit,
                'id'        => $hospitalId
            ], \app\api\validate\Common::class . '.paging');
            
            if ($hospitalId) {
                $this->validate(['id' => $hospitalId], \app\api\validate\Common::class . '.id');
            }
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

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
     * @param int $type
     * @return Response
     */
    public function detail(int $id, int $type): Response
    {
        try {
            $this->validate(['id' => $id, 'type' => $type], \app\api\validate\Common::class . '.id_type');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $info = $this->service->getServiceDetail($id, $type);
        if (!$info) {
            return $this->error($type == 2 ? '套餐不存在' : '服务不存在');
        }
        return $this->success($info);
    }
}
