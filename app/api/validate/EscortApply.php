<?php
declare (strict_types = 1);

namespace app\api\validate;

use think\Validate;
use app\api\model\EscortApplication;
use app\api\model\RecruitApplication;

class EscortApply extends Validate
{
    protected $rule = [
        'user_id'         => 'require',
        'name'            => 'require',
        'phone'           => 'require|mobile',
        'agree_protocol'  => 'require|accepted',
    ];

    protected $message = [
        'user_id.require'         => '用户未登录',
        'name.require'            => '姓名不能为空',
        'phone.require'           => '电话不能为空',
        'phone.mobile'            => '电话格式不正确',
        'agree_protocol.require'  => '请先同意协议',
        'agree_protocol.accepted' => '请先同意协议',
    ];

    protected $scene = [
        'apply'   => ['user_id', 'name', 'phone', 'agree_protocol'],
        'recruit' => ['user_id', 'name', 'phone', 'agree_protocol'],
    ];

    public function sceneApply()
    {
        return $this->only(['user_id', 'name', 'phone', 'agree_protocol'])
            ->append('user_id', 'checkApplyStatus');
    }

    public function sceneRecruit()
    {
        return $this->only(['user_id', 'name', 'phone', 'agree_protocol'])
            ->append('user_id', 'checkRecruitStatus');
    }

    /**
     * 检查陪诊师申请状态
     */
    protected function checkApplyStatus($value, $rule, $data = [])
    {
        // $application = EscortApplication::where('user_id', $value)->find();
        // if ($application) {
        //     if ($application->status == 0) {
        //         return '等待审核';
        //     }
        //     return '您已经提交过申请了，请勿重复提交';
        // }
        return true;
    }

    /**
     * 检查招聘申请状态
     */
    protected function checkRecruitStatus($value, $rule, $data = [])
    {
        // 这里的逻辑根据实际业务需求，如果招聘申请也需要类似限制
        // 注意：数据库脚本中 ea8_recruit_application 暂时没有 user_id 字段，
        // 但 Service 中有使用，这里先按照有 user_id 的逻辑编写，以保持一致。
        // $application = RecruitApplication::where('user_id', $value)->find();
        // if ($application) {
        //     if ($application->status == 0) {
        //         return '等待审核';
        //     }
        //     return '您已经提交过申请了，请勿重复提交';
        // }
        return true;
    }
}
