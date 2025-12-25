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
        $data['user_id'] = $this->getUid();

        try {
            $this->validate($data, \app\api\validate\EscortApply::class . '.apply');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $res = $this->service->apply($data['user_id'], $data);
        return $this->success($res, '申请已提交');
    }

    // /**
    //  * 获取协议
    //  * @return Response
    //  */
    // public function protocol(): Response
    // {
    //     $content = $this->service->getProtocol();
    //     return $this->success(['content' => $content]);
    // }

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
        $data['user_id'] = $this->getUid();
        
        try {
            $this->validate($data, \app\api\validate\EscortApply::class . '.recruit');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $res = $this->service->recruitApply($data['user_id'], $data);
        return $this->success($res, '招聘申请已提交');
    }
}
