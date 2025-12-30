<?php

namespace app\admin\controller;

use app\common\controller\AdminController;
use app\admin\service\annotation\ControllerAnnotation;
use app\admin\service\annotation\NodeAnnotation;
use think\App;
use app\Request;

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
    

}