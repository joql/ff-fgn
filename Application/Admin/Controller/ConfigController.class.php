<?php
namespace Admin\Controller;
use Admin\Controller;
/**
 * 用户管理
 */
class ConfigController extends BaseController
{
    /**
     * 用户列表
     * @return [type] [description]
     */
    public function index($key="")
    {
        //默认显示添加表单
        if (!IS_POST) {
            $model = M('Config');
            $this->info = $model->find();
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            if ($_POST['id'] !== '1') {
                $this->error("设置错误");
            }
            $model = D("Config");
            if (!$model->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($model->getError());
                exit();
            } else {
                if ($model->save()) {
                    $this->success("设置成功", U('config/index'));
                } else {
                    $this->error("设置失败");
                }
            }
        }
    }

}
