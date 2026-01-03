<?php

namespace app\admin\controller\user;

use app\common\controller\AdminController;
use app\admin\service\annotation\ControllerAnnotation;
use app\admin\service\annotation\NodeAnnotation;
use think\App;
use app\Request;
use think\response\Json;
use app\admin\model\User;

#[ControllerAnnotation(title: 'user_coupon')]
class Coupon extends AdminController
{

    private array $notes;

    public function __construct(App $app)
    {
        parent::__construct($app);
        self::$model = new \app\admin\model\UserCoupon();
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
                if ($value[0] == 'user_nickname') {
                    $newWhere['user_id'] = User::where('nickname', 'like', "%{$value[2]}%")->value('id');
                }
            }
            $count = self::$model::where($newWhere)->with(['user', 'coupon'])->count();
            $list  = self::$model::where($newWhere)->with(['user', 'coupon'])->page($page, $limit)->order($this->sort)->select()->toArray();
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