<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\EscortApply as EscortApplyService;
use think\Response;

class EscortApply extends Base
{
    protected $service;

    public function __construct(\think\App $app, EscortApplyService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 陪诊师申请
     * @return Response
     */
    public function apply(): Response
    {
        $data = $this->request->only(['name', 'phone', 'id_card', 'city', 'experience', 'id_card_front', 'id_card_back']);
        
        if (empty($data['name']) || empty($data['phone'])) {
            return $this->error('姓名和电话不能为空');
        }

        $res = $this->service->apply($data);
        return $this->success($res, '申请已提交');
    }

    /**
     * 获取协议
     * @return Response
     */
    public function protocol(): Response
    {
        $content = $this->service->getProtocol();
        return $this->success(['content' => $content]);
    }

    /**
     * 招聘申请
     * @return Response
     */
    public function recruitApply(): Response
    {
        $data = $this->request->only(['name', 'phone', 'city', 'remark']);
        
        if (empty($data['name']) || empty($data['phone'])) {
            return $this->error('姓名和电话不能为空');
        }

        $res = $this->service->recruitApply($data);
        return $this->success($res, '招聘申请已提交');
    }
}
