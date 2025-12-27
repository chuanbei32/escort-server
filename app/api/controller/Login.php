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
        $pid  = $this->request->post('pid', 0);
        
        try {
            $this->validate(['code' => $code, 'pid' => $pid], \app\api\validate\Login::class . '.wechat');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        try {
            $data = $this->service->wechatLogin($code, (int)$pid);
            return $this->success($data, '登录成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
