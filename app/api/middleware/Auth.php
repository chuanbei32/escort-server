<?php
declare (strict_types = 1);

namespace app\api\middleware;

use thans\jwt\exception\JWTException;
use thans\jwt\exception\TokenBlacklistException;
use thans\jwt\exception\TokenExpiredException;
use thans\jwt\facade\JWTAuth;
use think\Response;

class Auth
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        try {
            // 验证 token
            $payload = JWTAuth::auth();
            
            // 将用户信息存入 request 对象，方便后续控制器使用
            $request->user_id = $payload['uid'];
            $request->openid = $payload['openid'];
            
            $request->user_id = 1;
        } catch (TokenExpiredException $e) {
            return json(['code' => 401, 'message' => 'Token已过期', 'data' => null], 401);
        } catch (TokenBlacklistException $e) {
            return json(['code' => 401, 'message' => 'Token在黑名单中', 'data' => null], 401);
        } catch (JWTException $e) {
            return json(['code' => 401, 'message' => 'Token无效', 'data' => null], 401);
        } catch (\Exception $e) {
            return json(['code' => 401, 'message' => '未登录或Token错误', 'data' => null], 401);
        }

        return $next($request);
    }
}
