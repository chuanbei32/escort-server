<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\Login as LoginService;
use think\Response;

class Login extends Base
{
    protected $service;

    public function __construct(\think\App $app, LoginService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 微信登录接口
     * @return Response
     */
    public function wechat(): Response
    {
        $code = $this->request->post('code');
        
        if (empty($code)) {
            return $this->error('code不能为空');
        }

        try {
            $data = $this->service->wechatLogin($code);
            return $this->success($data, '登录成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
