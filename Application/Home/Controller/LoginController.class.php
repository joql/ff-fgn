<?php
namespace Home\Controller;
use Think\Controller;

class LoginController extends Controller {
    //登陆主页
    public function index(){
        $this->display();
    }

    public function denglu () {
        $this->display();
    }

    public function zhuce () {
        $this->display();
    }
    //登陆验证
    public function login(){
        if(!IS_POST)$this->error("非法请求");
        $member = M('member');
        $username =I('username');
        $password =I('password','','md5');
        //$code = I('verify','','strtolower');
        /*
        //验证验证码是否正确
        if(!($this->check_verify($code))){
            $this->error('验证码错误');
        }
        */
        //验证账号密码是否正确
        $user = $member->where(array('username'=>$username,'password'=>$password))->find();

        if(!$user) {
            $this->error('账号或密码错误 :(') ;
        }

       
        if ($user['id'] !== '1') {
            //验证账户是否被禁用
            if($user['status'] == 0){
                $this->error('账号被禁用，请联系超级管理员 :(') ;
            }

            //验证账户是否已过期
            $create_time = $user['create_time'];
            $tianshu = $user['tianshu'];

            $dtime = strtotime(date("y-m-d h:i:s")); //当前时间
            $sjc = ceil(($dtime-$create_time)/86400); //60s*60min*24h

            if($sjc > $tianshu){
                $this->error('您账号已过期，请联系超级管理员 :(') ;
            }
        }
        
        //dd($user);die;
        //验证是否为管理员
        //更新登陆信息
        $data =array(
            'id' => $user['id'],
            'update_time' => time(),
            'login_ip' => get_client_ip(0,true),
        );
        
        //如果数据更新成功  跳转到后台主页
        if($member->save($data)){
            session('homeId',$user['id']);
            session('homename',$user['username']);
            $this->success("登陆成功",U('Index/index'));
        }
        //定向之后台主页
        

    }

    //注册验证
    public function reg(){
        if(!IS_POST)$this->error("非法请求");
        //默认显示添加表单

        if (IS_POST) {

            $config = M("Config")->find();
            $_POST['allowsize'] = $config['allowsize'];
            $_POST['singlesize'] = $config['singlesize'];
            $_POST['uptype'] = $config['uptype'];
            $_POST['tianshu'] = $config['tianshu'];
            //如果用户提交数据
            $model = D("Member");

            $username =I('username');
            if(strlen($username) > 25) {
                $this->error('此邮箱太长，请从新换一个邮箱！') ;
            }
            //验证账号密码是否正确
	        $user = $model->where(array('username'=>$username))->find();

	        if($user) {
	            $this->error('此邮箱已存在！') ;
	        }

            if (!$model->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($model->getError());
                exit();
            } else {
                if ($model->add()) {
                    $this->success("注册成功", U('login/denglu'));
                } else {
                    $this->error("注册失败");
                }
            }
        }
    }

    //验证码
    public function verify(){
        ob_clean();
        $Verify = new \Think\Verify();
        $Verify->codeSet = '0123456789';
        $Verify->fontSize = 25;
        $Verify->length = 4;
        $Verify->useNoise = false;  // 关闭验证码杂点
        $Verify->entry();
    }
    protected function check_verify($code){
        $verify = new \Think\Verify();
        return $verify->check($code);
    }

    public function logout(){
        session('homeId',null);
        session('homename',null);
        redirect(U('Login/index'));
    }

    public function chaxun(){
        //默认显示添加表单
        if (!IS_POST) {
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            
            $udid = I('udid');
            if (empty($udid)) {
                $this->error("请正确输入udid");
            }

            $model = M("Post");
            $where['udid'] = $udid;
            $where['status'] = array('in','1,2,3');
            $result = $model->where($where)->order('id desc')->find();
            if ($result) {
                if ($result['status'] == 3) {
                    $this->status = 3;
                    $this->pid = $result['pid'];
                    $this->web = $result['web'];
                    $this->udid = $udid;
                    $this->update_com = $result['update_com'];
                } else {
                    $this->status = '正在制作，请稍后!';
                }
            } else {
                $this->status = '没有您输入的UDID!';
            }
            $this->display();
        }
    }

    public function tw_chaxun(){
        //默认显示添加表单
        if (!IS_GET) {
            $this->display();
        }
        if (IS_GET) { 
            //如果用户提交数据
            
            $udid = I('udid');
            if (empty($udid)) {
                $this->error("请正确输入udid");
            }

            $model = M("Post");
            $result = $model->where(array('udid'=>$udid))->order('id desc')->find();
            if ($result) {
                if ($result['status'] == 3) {
                    $this->status = 3;
                    $this->uid = session('homeId');
                    $this->pid = $result['pid'];
                    $this->web = $result['web'];
                    $this->udid = $udid;
                    $this->update_com = $result['update_com'];
                } else {
                    $this->status = '正在制作，请稍后!';
                }
            } else {
                $this->status = '没有您输入的UDID!';
            }
            $this->display('chaxun');
        }
    }
}