<?php
namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function _initialize(){

        $sid = session('homeId');
        $urla = trim($_SERVER['PHP_SELF']);
        //判断用户是否登陆
        if(!isset($sid) ) {
            if ($urla === '/index.php/Index/index.html') {
                redirect(U('Login/denglu'));
            } elseif($urla === '/index.php/Index/listinfo.html') {
                redirect(U('Login/denglu'));
            } else {
                redirect(U('Login/index'));
            }
            
        }

        $member = M('member');
        $user = $member->where(array('id'=>$sid))->find();
        //验证账户是否已过期
        $create_time = $user['create_time'];
        $tianshu = $user['tianshu'];

        $dtime = strtotime(date("y-m-d h:i:s")); //当前时间
        $sjc = ceil(($dtime-$create_time)/86400); //60s*60min*24h

        if($sjc > $tianshu){
        	session('homeId',null);
        	redirect(U('Login/index'),3,'您账号已过期'.$sjc.'天，请联系超级管理员 :(');
        }

    }

}