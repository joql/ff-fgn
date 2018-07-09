<?php

namespace Home\Controller;

use Think\Controller;

/**
 * 用户管理
 */
class AppipaController extends Controller

{

    //前置操作方法

    public function _before_index()
    {

        $xxid = trim(I('xxid'));


        $model = M('List');


        $where['id'] = $xxid;

        $res = $model->where($where)->find();

        if (!isset($res)) {
            $this->display('error');
            die;
        }

        $ttid = session('homeId') ? session('homeId') : $res['fid'];
        $rests = M("Member")->where(array('id' => $ttid))->find();
        $this->meminfo = $rests;

    }


    public function _empty()
    {

        redirect(U('Index/listinfo'));

    }


    public function listll()
    {


        $passid = trim(I('passid'));

        $aqpassword = session('aqpassword' . $passid);


        if (!empty($passid) && !empty($aqpassword)) {


            $model = M('List');

            $where['id'] = $passid;


            $upid = $model->where($where)->getField('upid');

            if ($upid !== '0') {

                $where['id'] = $upid;

            }


            //$where['aqpassword'] = $aqpassword;

            $list = $model->where($where)->find();


            $this->aqpassword = $aqpassword;

            $this->list = $list;


            if ($list['upid'] !== 0) {

                $web = '/index.php/so/' . $list['upid'];

                $this->qrcode = $this->qrcode('https://' . $_SERVER['SERVER_NAME'] . $web, $list['upid']);

            } else {

                $this->qrcode = $this->qrcode('https://' . $_SERVER['SERVER_NAME'] . '/' . $list['web'], $list['id']);

            }


            session('aqpassword' . $passid, null);


            $uptype = M("Member")->where(array('id' => $list['fid']))->getField('uptype');

            $this->uptype = $uptype;


            $this->display('index');

        } else {

            $this->error("请从新输入密码", U("Index/listinfo"));

        }


    }


    public function index()
    {
        $xxid = trim(I('xxid'));

        $model = M('List');

        //设备识别并自动跳转
        $merge_id = M()->query("select id,tweb,tname from __NEWLIST__ where tweb regexp '\/".$xxid."+(,|$)' limit 1");
        $os = getOS();
        if($os['platform'] == 'mac' || $os['platform'] == 'ipod' || $os['platform'] == 'ipad' || $os['platform'] =='iphone'){
            unset($os);
            $os = 'ios';

        }else{
            $os = 'android';
        }
        if(!empty($merge_id)){
            $webs = explode(',', $merge_id[0]['tweb']);
            $exts = explode(',', $merge_id[0]['tname']);
            foreach ($webs as $k=>$v){
                if(!preg_match('/\/'.$xxid.'(,|$)/', $v) && strtolower($exts[$k]) == $os){
                    header('Location: '.current(explode('/',$_SERVER['SERVER_PROTOCOL'])).'://'.$_SERVER['SERVER_NAME'].$v);
                }else{
                    continue;
                }
            }
        }

        $where['id'] = $xxid;
        $upid = $model->where($where)->getField('upid');

        if ($upid !== '0') {
            $where['id'] = $upid;
        }

        $res = $model->where($where)->setInc('xznum', '1');
        $list = $model->where($where)->find();
        $uptype = M("Member")->where(array('id' => $list['fid']))->getField('uptype');
        $this->uptype = $uptype;
        $this->list = $list;
        if ($list['upid'] !== '0') {
            $web = 'index.php/so/' . $list['upid'];
            $this->qrcode = $this->qrcode('https://' . $_SERVER['SERVER_NAME'] . '/' . $web, $list['hslogo'], $list['upid']);
        } else {
            $this->qrcode = $this->qrcode('https://' . $_SERVER['SERVER_NAME'] . '/' . $list['web'], $list['hslogo'], $list['id']);
        }
        $this->display();
    }

    public function duo()
    {


        $xxid = trim(I('xxid'));
        $model = M('Newlist');
        $where['id'] = $xxid;

        $res = $model->where($where)->setInc('xznum', '1');

        $list = $model->where($where)->find();

        if (!isset($list)) {

            $this->error("请正确输入地址", U("Index/yeshb"));

        }


        $tid = explode(',', $list['tid']);

        $tname = explode(',', $list['tname']);

        $tweb = explode(',', $list['tweb']);


        $info = array();

        for ($i = 0; $i < count($tid); $i++) {
            $info[$i]['tname'] = $tname[$i];
            $info[$i]['tweb'] = $tweb[$i];
        }


        $this->info = $info;

        $this->list = $list;


        $webqr = 'index.php/duo/' . $list['id'];

        $this->qrcode = $this->qrcode('https://' . $_SERVER['SERVER_NAME'] . '/' . $webqr, $list['id']);

        $this->display();

    }


    public function qrcode($text = 'http://xx.w6cc.com', $logo = '', $tid, $size = '10', $level = 'L', $padding = 2)
    {

        $path = './Public/png/';

        $QR = $path . $tid . '.png';

        vendor("Phpqrcode.phpqrcode");

        \QRcode::png($text, $QR, $level, $size, $padding);
        if(!empty($logo)){
            $qr_hand = imagecreatefromstring(file_get_contents($QR));

            $exploded = explode(',', $logo, 2); // limit to 2 parts, i.e: find the first comma
            $encoded = $exploded[1]; // pick up the 2nd part
            $decoded = base64_decode($encoded);
            $logo = imagecreatefromstring($decoded);

            $QR_width = imagesx($qr_hand);
            $QR_height = imagesy($qr_hand);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            imagecopyresampled($qr_hand, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
        }
        imagepng($qr_hand,$QR);

        return $tid;

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

            $this->assign('model', $model);

            $this->display();

        }

        if (IS_POST) {

            $model = D("Member");

            if (!$model->create()) {

                $this->error($model->getError());

            } else {

                //验证密码是否为空   

                $data = I();

                unset($data['password']);

                if (I('password') != "") {

                    $data['password'] = md5(I('password'));

                }

                //强制更改超级管理员用户类型

                if (C('SUPER_ADMIN_ID') == I('id')) {

                    $data['type'] = 2;

                }

                //更新

                if ($model->save($data)) {

                    $this->success("用户信息更新成功", U('member/index'));

                } else {

                    $this->error("未做任何修改,用户信息更新失败");

                }

            }

        }

    }


    public function checkpass()
    {


        if ($_POST) {

            if (empty($_POST['checkid'])) {

                $this->error('没有此APP信息');

            }


            $model = M('List');

            $where['id'] = trim($_POST['checkid']);

            $where['aqpassword'] = trim($_POST['aqpassword']);

            $list = $model->where($where)->find();

            if ($list) {

                //session('passid'.trim($_POST['checkid'],trim($_POST['checkid']));

                session('aqpassword' . trim($_POST['checkid']), trim($_POST['aqpassword']));

                $this->success("提交正确，跳转中...", U('Appipa/listll', array('passid' => trim($_POST['checkid']))));

            } else {

                $this->error("信息错误");

            }

        }


    }

}

