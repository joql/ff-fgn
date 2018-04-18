<?php

namespace Home\Controller;

use Home\Controller;

/**

 * 用户管理

 */

class MemberController extends BaseController

{

    /**

     * 用户列表

     * @return [type] [description]

     */

    public function index($key="")

    {

        if($key == ""){

            $where['id'] = session('homeId');

            $model = M('member');  

        }else{

            $where['username'] = trim($key);

            $model = M('member'); 

        } 

        

        $count  = $model->where($where)->count();// 查询满足要求的总记录数

        $Page = new \Extend\Page($count,20);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出

        $member = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id asc')->select();

        $this->assign('list', $member);

        $this->assign('page',$show);

        $this->se_id = session('homeId');
        $result = $model->where(array('id'=>session('homeId')))->find();
        $this->result = $result;

        $this->display();     

    }



    public function jiekou() {

        //默认显示添加表单

        if (!IS_POST) {

            $id = session('homeId');

            $this->fid = $id;

            $this->listcount = M("list")->where(array('fid'=>$id))->count();

            $model = M('member')->find($id);

            $this->assign('result',$model);

            $this->display();

        }



    }



    /**

     * 添加用户

     */

    public function add()

    {

        //默认显示添加表单

        if (!IS_POST) {

            $this->fid = session('homeId');

            $this->display();

        }

        if (IS_POST) {

            //如果用户提交数据

            $config = M('Config');

            $con_info = $config->where('id=1')->find();

            if ($con_info['user'] === '0') {

                if (session('homename') !== 'admin') {

                    $this->error("当前暂停提交,处理问题,谢谢合作!");    

                }

            }

            

            $model = D("Member");

            if (!$model->create()) {

                // 如果创建失败 表示验证没有通过 输出错误提示信息

                $this->error($model->getError());

                exit();

            } else {

                if ($model->add()) {

                    $this->success("用户添加成功", U('index/index'));

                } else {

                    $this->error("用户添加失败");

                }

            }

        }

    }

    /**

     * 更新管理员信息

     * @param  [type] $id [管理员ID]

     * @return [type]     [description]

     */

    public function update()

    {

        //默认显示添加表单

        if (!IS_POST) {

            $id = I('id');

            $uid = session('homeId');

            if ($id !== $uid) {

                $this->error('请正确提交');

            }



            $model = M('member')->find($id);

            $this->fid = $uid;

            $this->assign('model',$model);

            $this->display();

        }

        if (IS_POST) {

            $model = D("Member");

            if (!$model->create()) {

                $this->error($model->getError());

            }else{

                //验证密码是否为空   

                $data = I();

                unset($data['password']);

                if(I('password') != ""){

                    $data['password'] = md5(I('password'));

                }

                //强制更改超级管理员用户类型

                if(C('SUPER_ADMIN_ID') == I('id')){

                    $data['type'] = 2;

                }

                $uid = session('homeId');

                if ($_POST['id'] !== $uid) {

                    $this->error('请正确提交');

                }

                //更新

                if ($model->save($data)) {

                    session('homeId',null);

                    session('homename',null);

                    $this->success("用户信息更新成功,请从新登录!", U('member/index'));

                } else {

                    $this->error("未做任何修改,用户信息更新失败");

                }        

            }

        }

    }

    /**

     * 删除管理员

     * @param  [type] $id [description]

     * @return [type]     [description]

     */

    public function delete($id)

    {

    	if(C('SUPER_ADMIN_ID') == $id) $this->error("超级管理员不可禁用!");

        $model = M('member');

        //查询status字段值

        $result = $model->find($id);

        //更新字段

        $data['id']=$id;

        if($result['status'] == 1){

        	$data['status']=0;

        }

        if($result['status'] == 0){

        	$data['status']=1;

        }

        if($model->save($data)){

            $this->success("状态更新成功", U('member/index'));

        }else{

            $this->error("状态更新失败");

        }

    }



    /**

     * 保存七牛个人配置信息

     * @param  [type] $id [description]

     * @return [type]     [description]

     */

    public function config() {

        if (IS_POST) {

            

            $uid = session('homeId');

            if ($_POST['id'] !== $uid) {

                $this->error('请正确提交');

            }

            $_POST['domain'] = str_replace(' ','',trim($_POST['domain']));

            if (substr($_POST['domain'],0,7)  !== 'http://') {
                $_POST['domain'] = 'http://'.$_POST['domain'];
            }

            if (substr($_POST['domain'],-1)  !== '/') {
                $_POST['domain'] = $_POST['domain'].'/';
            }

            $model = D("Member");

            if (!$model->create()) {

                $this->error($model->getError());

            }else{

              //更新

                if ($model->save()) {                

                    $this->success("更新成功!", U('member/jiekou'));

                } else {

                    $this->error("更新失败");

                }        

            }

        }

    }



    

}

