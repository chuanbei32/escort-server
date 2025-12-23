<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\User as UserModel;

class User
{
    /**
     * 获取用户信息
     * @param int $userId
     * @return UserModel|null
     */
    public function getUserInfo(int $userId): ?UserModel
    {
        return UserModel::find($userId);
    }

    /**
     * 更新用户信息
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateUserInfo(int $userId, array $data): bool
    {
        $user = UserModel::find($userId);
        if (!$user) {
            return false;
        }
        return $user->save($data);
    }
}
