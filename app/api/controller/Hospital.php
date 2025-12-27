<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Hospital as HospitalService;
use think\Response;

class Hospital extends Base
{
    protected $service;

    public function __construct(\think\App $app, HospitalService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 医院列表
     * @return Response
     */
    public function list(): Response
    {
        $page = $this->request->get('page/d', 1);
        $limit = $this->request->get('page_size/d', 10);
        
        try {
            $this->validate(['page' => $page, 'page_size' => $limit], \app\api\validate\Common::class . '.paging');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $data = $this->service->getHospitalList($page, $limit);
        return $this->success($data);
    }

    /**
     * 医院详情
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        try {
            $this->validate(['id' => $id], \app\api\validate\Common::class . '.id');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $info = $this->service->getHospitalDetail($id);
        if (!$info) {
            return $this->error('医院不存在');
        }
        return $this->success($info);
    }
}
