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
        // 假设当前用户ID为1
        $userId = 1;
        $info = $this->service->getUserInfo($userId);
        return $this->success($info);
    }

    /**
     * 更新用户信息
     * @return Response
     */
    public function update(): Response
    {
        // 假设当前用户ID为1
        $userId = 1;
        $data = $this->request->only(['nickname', 'avatar', 'phone', 'gender']);
        
        $res = $this->service->updateUserInfo($userId, $data);
        if ($res) {
            return $this->success(null, '更新成功');
        }
        return $this->error('更新失败');
    }

    /**
     * 获取编辑信息
     * @return Response
     */
    public function editInfo(): Response
    {
        // 假设当前用户ID为1
        $userId = 1;
        $info = $this->service->getUserInfo($userId);
        return $this->success($info);
    }
}
