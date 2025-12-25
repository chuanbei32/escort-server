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
        $data = $this->request->only(['occupation', 'name', 'phone', 'wechat', 'address', 'message', 'agree_protocol']);
        
        if (empty($data['name']) || empty($data['phone'])) {
            return $this->error('姓名和电话不能为空');
        }

        if (empty($data['agree_protocol'])) {
            return $this->error('请先同意协议');
        }

        $res = $this->service->apply($this->getUid(), $data);
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
        $data = $this->request->only([
            'name', 
            'gender', 
            'birth_date', 
            'phone', 
            'education', 
            'region', 
            'hospital', 
            'specialty', 
            'resume_url', 
            'certificate_url', 
            'agree_protocol'
        ]);
        
        if (empty($data['name']) || empty($data['phone'])) {
            return $this->error('姓名和电话不能为空');
        }

        if (empty($data['agree_protocol'])) {
            return $this->error('请先同意协议');
        }

        $res = $this->service->recruitApply($this->getUid(), $data);
        return $this->success($res, '招聘申请已提交');
    }
}
