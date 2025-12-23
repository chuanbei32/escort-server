<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\BaseController;
use app\common\traits\JumpTrait;

/**
 * API基础控制器
 */
class Base extends BaseController
{
    use JumpTrait;

    /**
     * 初始化
     */
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 成功响应
     */
    protected function success($data = null, string $message = 'success'): \think\Response
    {
        return json([
            'code'    => 200,
            'message' => $message,
            'data'    => $data ?? []
        ]);
    }

    /**
     * 失败响应
     */
    protected function error(string $message = 'fail', int $code = 400, $data = null): \think\Response
    {
        return json([
            'code'    => $code,
            'message' => $message,
            'data'    => $data ?? []
        ]);
    }

    /**
     * 获取当前登录用户ID
     */
    protected function getUid(): int
    {
        return (int) $this->request->user_id;
    }

    /**
     * 获取当前响应类型
     * @return string
     */
    protected function getResponseType(): string
    {
        return 'json';
    }
}
