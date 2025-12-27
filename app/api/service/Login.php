<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\User as UserModel;
use EasyWeChat\MiniApp\Application;
use thans\jwt\facade\JWTAuth;
use think\facade\Config;

class Login
{
    /**
     * 微信登录
     * @param string $code
     * @param int $pid 推荐人ID
     * @return array
     */
    public function wechatLogin(string $code, int $pid = 0): array
    {
        // 1. 获取微信配置 (实际应用中应从配置文件读取)
        $config = [
            'app_id' => Config::get('wechat.mini_program.app_id'),
            'secret' => Config::get('wechat.mini_program.secret'),
        ];

        // 2. 初始化小程序实例
        $app = new Application($config);

        // 3. 通过 code 换取 openid
        $utils = $app->getUtils();
        
        try {
            $auth = $utils->codeToSession($code);
        } catch (\Exception $e) {
            throw new \Exception('微信登录失败：' . $e->getMessage());
        }

        $openid = $auth['openid'];

        // 4. 查找或创建用户
        $user = UserModel::where('openid', $openid)->find();
        if (!$user) {
            $userData = [
                'openid' => $openid,
                'status' => 1,
                // 初始昵称和头像可以先设为空或默认值
                'nickname' => '用户' . substr($openid, -6),
            ];
            
            // 如果有推荐人ID，则绑定
            if ($pid > 0) {
                $userData['parent_id'] = $pid;
            }
            
            $user = UserModel::create($userData);
        }

        // 5. 生成 JWT Token
        $token = JWTAuth::builder([
            'uid' => $user->id,
            'openid' => $openid,
        ]);

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }
}
