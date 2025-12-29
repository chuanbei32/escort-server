<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\EscortApplication as EscortApplicationModel;
use app\api\model\RecruitApplication as RecruitApplyModel;

class EscortApply
{
    /**
     * 申请成为陪诊师
     * @param int   $userId
     * @param array $data
     * @return EscortApplicationModel
     */
    public function apply(int $userId, array $data): EscortApplicationModel
    {
        $saveData = [
            'user_id'         => $userId,
            'occupation'      => $data['occupation'] ?? '',
            'name'            => $data['name'] ?? '',
            'phone'    => $data['phone'] ?? '',
            'wechat'          => $data['wechat'] ?? '',
            'address'            => $data['address'] ?? '', // 将地址暂存到城市字段
            'message'         => $data['message'] ?? '',
            'agree_agreement' => $data['agree_protocol'] ?? 0,
            'status'          => 1,
        ];
        return EscortApplicationModel::create($saveData);
    }

    /**
     * 招聘陪诊师申请
     * @param int   $userId
     * @param array $data
     * @return RecruitApplyModel
     */
    public function recruitApply(int $userId, array $data): RecruitApplyModel
    {
        $saveData = [
            'user_id'          => $userId,
            'name'             => $data['name'] ?? '',
            'gender'           => $data['gender'] ?? '',
            'birth_date'            => $data['birth_date'] ?? '',
            'phone'            => $data['phone'] ?? '',
            'education'        => $data['education'] ?? '',
            'region'         => $data['region'] ?? '',
            'hospital'         => $data['hospital'] ?? '',
            'specialty'       => $data['specialty'] ?? '',
            'resume_file'      => $data['resume_url'] ?? '',
            'resume_file_name' => basename($data['resume_url'] ?? ''),
            'agree_agreement'  => $data['agree_protocol'] ?? 0,
            'status'           => 1,
            'resume_url'        => $data['resume_url'] ?? '',
            'certificate_url' => $data['certificate_url'] ?? '',
        ];
        
        $model = RecruitApplyModel::create($saveData);

        return $model;
    }
}
