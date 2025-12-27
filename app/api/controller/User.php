<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\api\service\User as UserService;
use think\Response;

class User extends Base
{
    protected $service;

    public function __construct(\think\App $app, UserService $service)
    {
        parent::__construct($app);
        $this->service = $service;
    }

    /**
     * 获取用户信息
     * @return Response
     */
    public function info(): Response
    {
        $userId = $this->getUid();
        $info = $this->service->getUserInfo($userId);
        return $this->success($info);
    }

    /**
     * 更新用户信息
     * @return Response
     */
    public function update(): Response
    {
        $data = $this->request->only(['nickname', 'avatar', 'phone', 'gender']);
        
        try {
            $this->validate($data, \app\api\validate\User::class . '.update');
        } catch (\think\exception\ValidateException $e) {
            return $this->error($e->getError());
        }

        $res = $this->service->updateUserInfo($this->getUid(), $data);
        if ($res) {
            return $this->success(null, '更新成功');
        }
        return $this->error('更新失败');
    }
}
