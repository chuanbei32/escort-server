<?php

namespace app\admin\controller;

use app\common\controller\AdminController;
use app\admin\service\annotation\ControllerAnnotation;
use app\admin\service\annotation\NodeAnnotation;
use think\App;
use app\Request;
use think\response\Json;

#[ControllerAnnotation(title: 'user')]
class User extends AdminController
{

    private array $notes;

    public function __construct(App $app)
    {
        parent::__construct($app);
        self::$model = new \app\admin\model\User();
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
            
            $level = $request->param('level', 0);
            $id = $request->param('id', 0);

            if ($level && $id) {
                switch ($level) {
                    case 1:
                        $where['parent_id'] = $id;
                        break;
                    case 2:
                        $where[] = ['parent_id', 'in', implode(',', self::$model::where('parent_id', $id)->column('id'))];
                        break;
                }
            }

            $count = self::$model::where($where)->count();
            $tableName = self::$model->getTable();
            $orderTable = (new \app\admin\model\Order())->getTable();
            $list  = self::$model::where($where)
                ->field([
                    '*',
                    "IFNULL((SELECT COUNT(*) FROM {$tableName} AS first_level WHERE first_level.parent_id = {$tableName}.id), 0) AS level1",
                    "IFNULL((SELECT COUNT(*) FROM {$tableName} AS second_level WHERE second_level.parent_id IN (SELECT id FROM {$tableName} AS first_level_ids WHERE first_level_ids.parent_id = {$tableName}.id)), 0) AS level2",
                    "IFNULL((SELECT SUM(total_fee) FROM {$orderTable} WHERE {$orderTable}.user_id = {$tableName}.id AND {$orderTable}.status IN (1, 3)), 0) AS total"
                ])
                ->page($page, $limit)
                ->order($this->sort)
                // ->fetchSql(true)
                ->select()
                ->toArray();
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
}