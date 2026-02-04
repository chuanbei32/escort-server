<?php

namespace app\admin\controller;

use app\common\controller\AdminController;
use app\admin\service\annotation\ControllerAnnotation;
use app\admin\service\annotation\NodeAnnotation;
use think\App;
use app\Request;
use think\facade\Db;

#[ControllerAnnotation(title: 'service')]
class Service extends AdminController
{

    private array $notes;

    public function __construct(App $app)
    {
        parent::__construct($app);
        self::$model = new \app\admin\model\Service();
        $notes = self::$model::$notes;
        
        $this->notes =$notes;
        $this->assign(compact('notes'));

        $this->allowModifyFields = ['is_home_recommend'];
    }

    #[NodeAnnotation(title: '删除', auth: true)]
    public function delete(Request $request): void
    {
        // 如果不是id作为主键 请在对应的控制器中覆盖重写
        $id = $request->param('id', []);

        $this->checkPostRequest();
        $row = self::$model::where('id', 'not in', [1])->whereIn('id', $id)->select();
        $row->isEmpty() && $this->error('数据不存在');
        try {
            $save = $row->delete();
        }catch (\Exception $e) {
            $this->error('删除失败');
        }
        $save ? $this->success('删除成功') : $this->error('删除失败');
    }
    
    #[NodeAnnotation(title: '属性修改', auth: true)]
    public function modify(Request $request): void
    {
        $this->checkPostRequest();
        $post = $request->post();
        $rule = [
            'id|ID'      => 'require',
            'field|字段' => 'require',
            'value|值'   => 'require',
        ];
        $this->validate($post, $rule);
        $row = self::$model::find($post['id']);
        if (!$row) {
            $this->error('数据不存在');
        }
        if (!in_array($post['field'], $this->allowModifyFields)) {
            $this->error('该字段不允许修改：' . $post['field']);
        }
        try {
            Db::transaction(function() use ($post, $row) {
                if ($post['field'] == 'is_home_recommend' && $post['value'] == 0) {
                    // 判断是否没有推荐了，若没有不能取消。还需要根据当前id是否是推荐服务来判断，且每个module_name 下可以有一个推荐服务
                    $hasRecommend = self::$model::where('is_home_recommend', 1)->where('id', '<>', $post['id'])->where('module_name', $row->module_name)->find();
                    if (!$hasRecommend) {
                        throw new \Exception('当前服务未设置为首页推荐，不能取消');
                    }
                }
                // 先将所有推荐取消
                self::$model::where('module_name', $row->module_name)->update(['is_home_recommend' => 0]);
                $row->save([
                    $post['field'] => $post['value'],
                ]);
            });
        }catch (\Exception $e) {
            $this->error($e->getMessage());
        }
        $this->success('保存成功');
    }

}