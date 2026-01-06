<?php

namespace app\admin\controller;

use app\common\controller\AdminController;
use app\admin\service\annotation\ControllerAnnotation;
use app\admin\service\annotation\NodeAnnotation;
use think\App;
use app\Request;
use think\response\Json;
use app\admin\model\Service;
use app\admin\model\Hospital;
// use app\admin\model\EscortApplication;
use app\admin\model\Escort;

#[ControllerAnnotation(title: 'appointment')]
class Appointment extends AdminController
{

    private array $notes;

    public function __construct(App $app)
    {
        parent::__construct($app);
        self::$model = new \app\admin\model\Appointment();
        $notes = self::$model::$notes;
        
        $this->notes =$notes;
        $this->assign(compact('notes'));
    }

    #[NodeAnnotation(title: '列表', auth: true)]
    public function index(Request $request): Json|string
    {
        if ($request->isAjax()) {
            if (input('selectFields')) {
                return $this->selectList();
            }
            list($page, $limit, $where) = $this->buildTableParams();

            $newWhere = [];
            foreach ($where as $key => $value) {
                switch ($value[0]) {
                    case 'status':
                        $value[2] == '--全部--' ?: $newWhere['status'] = $value[2];
                        break;
                    case 'patient_name':
                        $value[2] == '--全部--' ?: $newWhere['patient_name'] = $value[2];
                        break;
                    case 'service_name':
                        $newWhere['service_id'] = Service::where('name', 'like', "%{$value[2]}%")->value('id');
                        break;
                    case 'hospital_name':
                        $newWhere['hospital_id'] = Hospital::where('name', 'like', "%{$value[2]}%")->value('id');
                        break;
                    case 'escort_name':
                        $newWhere['escort_id'] = Escort::where('name', 'like', "%{$value[2]}%")->value('id');
                        break;
                }
            }

            $count = self::$model::with(['order', 'user', 'escort', 'service', 'hospital'])->where($newWhere)->count();
            $list  = self::$model::with(['order', 'user', 'escort', 'service', 'hospital'])->where($newWhere)->page($page, $limit)->order($this->sort)->select()->toArray();
            $data  = [
                'code'  => 0,
                'msg'   => '',
                'count' => $count,
                'data'  => $list,
            ];
            return json($data);
        }
        return $this->fetch();
    }

    #[NodeAnnotation(title: '编辑', auth: true)]
    public function edit(Request $request, $id = 0): string
    {
        $row = self::$model::find($id);
        empty($row) && $this->error('数据不存在');
        if ($request->isPost()) {
            $post = $request->post();
            unset($post['service_id']);
            unset($post['hospital_id']);
            $rule = [];
            $this->validate($post, $rule);
            try {
                $save = $row->save($post);
            }catch (\Exception $e) {
                $this->error('保存失败：' . $e->getMessage());
            }
            $save ? $this->success('保存成功') : $this->error('保存失败');
        }
        $this->assign('row', $row);
        $this->assign('escort', Escort::column('name', 'id') ?? []);
        return $this->fetch();
    }
}