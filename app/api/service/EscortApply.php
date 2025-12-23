<?php
declare (strict_types = 1);

namespace app\api\service;

use app\api\model\EscortApply as EscortApplyModel;
use app\api\model\RecruitApply as RecruitApplyModel;

class EscortApply
{
    /**
     * 申请成为陪诊师
     * @param array $data
     * @return EscortApplyModel
     */
    public function apply(array $data): EscortApplyModel
    {
        return EscortApplyModel::create($data);
    }

    /**
     * 获取陪诊师协议
     * @return string
     */
    public function getProtocol(): string
    {
        // 模拟协议内容
        return "陪诊师服务协议内容...";
    }

    /**
     * 招聘陪诊师申请
     * @param array $data
     * @return RecruitApplyModel
     */
    public function recruitApply(array $data): RecruitApplyModel
    {
        return RecruitApplyModel::create($data);
    }
}
