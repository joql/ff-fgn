<?php

namespace Home\Controller;

use Extend\ApptOnPhp;
use Extend\IpaParser;
use Home\Controller;

class IndexController extends BaseController
{


    public function index()
    {
        $member = M('Member');
        $result = $member->where(array('id' => session('homeId')))->find();
        $this->result = $result;
        if ($result['uptype'] == 1 && $result['accesskey'] == '' && $result['secretkey'] == '') {
            $this->success("当前为七牛上传，请设置七牛信息...", U('Member/jiekou'), 3);
        } else {
            $this->display();
        }


    }


    public function checkupid()
    {

        $id = trim(I("id"));

        $model = M("List");

        $where['id'] = $id;

        $where['fid'] = session('homeId');

        $this->info = $model->where($where)->find();

        $this->display();

    }


    public function updateid()
    {

        if ($_POST) {

            $upid = trim(I('post.upid'));

            $id = trim(I('post.id'));


            $list = M('List');


            $where['id'] = $id;

            $where['fid'] = session('homeId');

            $info = $list->where($where)->find();

            if (empty($info)) {

                $this->error("请输入正确id");

            }


            $list->where(array('id' => $id))->setField('upid', $upid);

            $this->success("升级成功", U('Index/listinfo'));

        } else {

            $this->error("恶意提交");

        }

    }


    public function gettoken()
    {

        $member = M('Member');

        $result = $member->where(array('id' => session('homeId')))->find();

        // 初始化签权对象

        $auth = new \Qiniu\Auth(trim($result['accesskey']), trim($result['secretkey']));

        // 生成上传Token

        $token = $auth->uploadToken(trim($result['bucket']));

        //$arr = array('token'=>$token);

        echo $token;

    }


    public function userqn()
    {


        if (!empty($_POST)) {


            $member = M('Member');

            $result = $member->where(array('id' => session('homeId')))->find();


            $uploadedsize = $_POST['iAppSize'] / 1024 / 1024;

            $member->where(array('id' => session('homeId')))->setInc('uploadedsize', round($uploadedsize, 2));


            $domain = trim($_POST['sDownLink']);

            $houzhui = trim($_POST['hext']);


            $model = M("List");

            $model->uptxt = $domain;

            $model->hslogo = $_POST['hslogo'];

            $model->qnname = $_POST['apkName'];

            $model->nickname = $_POST['happName'];

            $model->create_time = time();

            $model->zsize = $_POST['iAppSize'];

            $model->fid = session('homeId');

            $model->ext = $houzhui;


            if ($res = $model->add()) {

                //处理该上传文件

                $ctlist = array(

                    'ssl_server' => 'http://' . $_SERVER['SERVER_NAME'] . '/',

                    'bundle_identifier' => $_POST['hpackageName'],

                    'title' => $_POST['happName'],

                    'cid' => $domain,

                    'subtitle' => $_POST['hpackageName'],

                    'versionname' => $_POST['hversion'],

                );


                $where['id'] = $res;

                $where['fid'] = session('homeId');

                if ($houzhui == 'ipa') {

                    //输出plist

                    $fp = fopen('./Public/appipa/' . $res . ".plist", "w+");

                    fwrite($fp, createplist($ctlist));

                    fclose($fp);


                    $plist = 'itms-services://?action=download-manifest&url=' . 'https://' . $_SERVER['SERVER_NAME'] . '/Public/appipa/' . $res . ".plist";


                    $model->where($where)->setField('plist', $plist);


                } elseif ($houzhui == 'apk') {

                    $model->where($where)->setField('uptxt', $domain);

                }


                $model->where($where)->setField('web', '/index.php/so/' . $res);

                $member->where(array('id' => session('homeId')))->setInc('appnum', 1);


                $data['status'] = 'success';

                $data['id'] = $res;

                $this->ajaxReturn($data);


            } else {


                $data['status'] = 'error';

                $this->ajaxReturn($data);


            }


        }


    }


    /**
     * use for:上传应用
     * auth: Joql
     * date:2018-04-27 14:14
     */
    public function upload()
    {
        $member = M('Member');
        $uptype = $member->where(array('id' => session('homeId')))->getField('uptype ');

        switch ($uptype) {
            //type:1 七牛 type:2本地  type:3远程
            case '1':
                $this->userqn();
                break;
            case '2':
                $this->userup();
                break;

        }


    }


    public function userup()
    {
        if (!empty($_POST)) {

            $member = M('Member');
            $result = $member->where(array('id' => session('homeId')))->find();

            $uploadedsize = $_POST['iAppSize'] / 1024 / 1024;
            $member->where(array('id' => session('homeId')))->setInc('uploadedsize', round($uploadedsize, 2));

            $domain = trim($_POST['sDownLink']);
            $houzhui = trim($_POST['hext']);

            $model = M("List");
            $model->uptxt = $domain;
            /*$model->hslogo = $_POST['hslogo'];
            $model->nickname = $_POST['happName'];*/
            $model->create_time = time();
            $model->zsize = $_POST['iAppSize'];
            $model->fid = session('homeId');
            $model->ext = $houzhui;
            if($houzhui == 'apk'){
                if(PATH_SEPARATOR == ';'){
                    $aapt_path = 'cd '.PUBLIC_PATH.'/aapt/windows/ && aapt.exe ';
                }else{
                    $aapt_path = 'cd '.PUBLIC_PATH.'/aapt/linux/ && ./ aapt ';
                }

                $apk = new apptOnPhp(PUBLIC_PATH.'/uploads/'.$domain, $aapt_path);
                if($aprse_result = $apk->parse()){
                    $model->hslogo = $apk->getIcon();
                    $model->nickname =$apk->getAppName();
                }else{
                    $data['status'] = 'error';
                    $data['parse'] = $aprse_result;

                    $this->ajaxReturn($data);
                }
            }elseif ($houzhui == 'ipa'){
                $dir = PUBLIC_PATH.'/uploads/ios/';
                $name = time().rand(111,999).'.ipa';
                copy(PUBLIC_PATH.'/uploads/'.$domain, $dir.$name);
                $ipa = new IpaParser($dir, $name, $dir);
                if($ipa->parse()){
                    $model->nickname = $ipa->getAppName();
                    $model->hslogo = $ipa->getIcon();
                    //处理该上传文件
                    $ctlist = array(
                        'ssl_server' => 'http://' . $_SERVER['SERVER_NAME'] . '/',
                        'bundle_identifier' =>  $ipa->getBid(),
                        'title' => $ipa->getAppName(),
                        'cid' => $domain,
                        'subtitle' =>  $ipa->getBid(),
                        'versionname' => $ipa->getVersion(),
                    );
                };
            }

            if ($res = $model->where(array('id' => $_POST['iLocalId']))->save()) {
                $where['id'] = $_POST['iLocalId'];
                $where['fid'] = session('homeId');
                if ($houzhui == 'ipa') {
                    //输出plist

                    $fp = fopen('./Public/appipa/' . $_POST['iLocalId'] . ".plist", "w+");
                    fwrite($fp, createplist($ctlist));
                    fclose($fp);
                    $plist = 'itms-services://?action=download-manifest&url=' . 'https://' . $_SERVER['SERVER_NAME'] . '/Public/appipa/' . $_POST['iLocalId'] . ".plist";
                    $model->where($where)->setField('plist', $plist);
                } elseif ($houzhui == 'apk') {
                    $model->where($where)->setField('uptxt', $domain);
                }

                $model->where($where)->setField('web', '/index.php/so/' . $_POST['iLocalId']);
                $member->where(array('id' => session('homeId')))->setInc('appnum', 1);
                $data['status'] = 'success';
                $data['id'] = $_POST['iLocalId'];
                $this->ajaxReturn($data);
            } else {
                $data['status'] = 'error';
                $this->ajaxReturn($data);
            }
        }
    }

    /**
     * use for:本地上传api
     * auth: Joql
     * date:2018-04-27 14:34
     */
    public function uploadbd()
    {
        //dd($_POST).'<br>';dd($_FILES).'<br>';die;
        $member = M('Member');
        $result = $member->where(array('id' => session('homeId')))->find();

        //$upsize = $model-> where(array('fid'=>session('homeId')))->Sum('zsize');
        $config = array(
            'maxSize' => $result['singlesize'] * 1024 * 1024, // 附件上传大小
            'rootPath' => './Public/uploads/', // 附件上传根目录
            'savePath' => '',// 附件上传（子）目录
            'exts' => array('ipa', 'apk'),// 附件上传类型
        );

        $upload = new \Think\Upload($config);// 实例化上传类
        $info = $upload->uploadOne($_FILES['file']);

        if (!$info) {
            $data['error'] = $upload->getError();
        } else {
            $model = M("List");
            $model->uptxt = $info['savepath'] . $info['savename'];
            $model->create_time = time();
            $model->zsize = $info['size'];
            $name = explode('.', $info['name']);
            $model->fid = session('homeId');
            $model->ext = end(explode('.', $info['name']));

            if(!empty($_GET['update_app_id'])){
                //更新应用
                $data['path'] = $info['savepath'] . $info['savename'];
                $data['id'] = $_GET['update_app_id'];
                $data['status'] = 'success';
                $this->ajaxReturn($data);
            }elseif ($tid = $model->add()){
                $model->where($where)->setField('web', '/index.php/so/' . $tid);
                $data['path'] = $info['savepath'] . $info['savename'];
                $data['id'] = $tid;
                $data['status'] = 'success';
                //dd($tid);
                $this->ajaxReturn($data);
            }else{
                $data['status'] = 'error';
            }
        }
        $this->ajaxReturn($data, 'JSON');
    }


    public function add()
    {

        $tid = session("tid");

        $model = M("List");

        $where['id'] = $tid;

        $where['fid'] = session('homeId');

        $this->info = $model->where($where)->find();

        $this->display();

    }


    public function update()
    {
        //默认显示添加表单
        if (IS_POST) {
            $model = M("List");
            if (!$model->create()) {
                $this->error($model->getError());
            } else {
            }
        }
    }


    public function listinfo()
    {
        if (I('post.nickname') == "") {
            if (I('get.ext') == "") {
                $where['fid'] = session('homeId');
                $where['status'] = 0;
            } else {
                $where['fid'] = session('homeId');
                $where['status'] = 0;
                $where['ext'] = I('get.ext');
            }
            $model = M('List');
        } else {
            $where['nickname'] = trim(I('nickname'));
            $where['fid'] = session('homeId');
            $where['status'] = 0;
            $model = M('List');
        }

        $num_my = 20;
        $count = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Extend\Page($count, $num_my);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $list = $model->limit($Page->firstRow . ',' . $Page->listRows)->where($where)->order('id DESC')->select();
        foreach ($list as $k=>$v){

            $merge_info = M()->query("select id from __NEWLIST__ where tweb regexp '\/".$v['id']."+(,|$)' limit 1");
            if(!empty($merge_info)){
                $list[$k]['merge_id'] = $merge_info[0]['id'];
            }

        }
        //dd($list);die;

        $dqy = I('get.p') ? I('get.p') : 1;
        $this->pp = $count - ($dqy - 1) * $num_my;
        $this->count = $count;
        $this->assign('list', $list);
        $this->assign('page', $show);
        $member = M('Member');
        $result = $member->where(array('id' => session('homeId')))->find();
        $this->result = $result;
        $this->display();
    }

    public function hebing()
    {

        if (I('post.nickname') == "") {


            if (I('get.ext') == "") {

                $where['fid'] = session('homeId');

                $where['status'] = 0;

            } else {

                $where['fid'] = session('homeId');

                $where['status'] = 0;

                $where['ext'] = I('get.ext');

            }


            $model = M('List');

        } else {

            $where['nickname'] = trim(I('nickname'));

            $where['fid'] = session('homeId');

            $where['status'] = 0;

            $model = M('List');

        }


        $num_my = 20;

        $count = $model->where($where)->count();// 查询满足要求的总记录数

        $Page = new \Extend\Page($count, $num_my);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出

        $list = $model->limit($Page->firstRow . ',' . $Page->listRows)->where($where)->order('id DESC')->select();

        //dd($list);die;

        $dqy = I('get.p') ? I('get.p') : 1;

        $this->pp = $count - ($dqy - 1) * $num_my;

        $this->count = $count;

        $this->assign('list', $list);

        $this->assign('page', $show);

        $this->display();

    }


    public function yeshb()
    {

        if (I('post.nickname') == "") {


            if (I('get.ext') == "") {

                $where['fid'] = session('homeId');

                $where['status'] = 0;

            } else {

                $where['fid'] = session('homeId');

                $where['status'] = 0;

                $where['ext'] = I('get.ext');

            }


            $model = M('Newlist');

        } else {

            $where['nickname'] = trim(I('nickname'));

            $where['fid'] = session('homeId');

            $where['status'] = 0;

            $model = M('Newlist');

        }


        $num_my = 20;

        $count = $model->where($where)->count();// 查询满足要求的总记录数

        $Page = new \Extend\Page($count, $num_my);// 实例化分页类 传入总记录数和每页显示的记录数(25)

        $show = $Page->show();// 分页显示输出

        $list = $model->limit($Page->firstRow . ',' . $Page->listRows)->where($where)->order('id DESC')->select();

        //dd($list);die;

        $dqy = I('get.p') ? I('get.p') : 1;

        $this->pp = $count - ($dqy - 1) * $num_my;

        $this->count = $count;

        $this->assign('list', $list);

        $this->assign('page', $show);

        $this->display();

    }


    public function hblist()
    {


        $this->display();


    }


    public function hbcheck()
    {

        if (IS_POST) {


            if (!empty($_FILES['fileToUpload']['name'])) {


                $upload = new \Think\Upload();// 实例化上传类

                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型

                $upload->rootPath = './Public/icon/'; // 设置附件上传根目录

                // 上传单个文件 

                $info = $upload->uploadOne($_FILES['fileToUpload']);

                if (!$info) {// 上传错误提示错误信息

                    $this->error($upload->getError());

                } else {// 上传成功 获取上传文件信息


                    header('Content-type:text/html;charset=utf-8');

                    //读取图片文件，转换成base64编码格式 

                    $image_file = $info['savepath'] . $info['savename'];

                    $_POST['newlogo'] = $image_file;

                }

            } else {

                $_POST['newlogo'] = '';

            }


            $tid_count = count($_POST['tid']);


            $_POST['tid'] = implode(',', $_POST['tid']);

            $_POST['tname'] = implode(',', array_slice($_POST['tname'], 0, $tid_count));

            $_POST['tweb'] = implode(',', array_slice($_POST['tweb'], 0, $tid_count));

            $_POST['fid'] = session('homeId');


            $model = M("Newlist");


            $_POST['lrtime'] = time();

            if (!$model->create()) {

                $this->error($model->getError());

            } else {


                if ($model->add()) {

                    $this->success("提交成功", U('index/yeshb'));

                } else {

                    $this->error("提交失败");

                }

            }

        }

    }


    /**
     * use for:合并应用-保存
     * auth: Joql
     * date:2018-04-27 13:50
     */
    public function hbupdate()
    {
        if (IS_POST) {
            $model = M("Newlist");
            if (!empty($_FILES['fileToUpload']['name'])) {

                $upload = new \Think\Upload();// 实例化上传类
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath = './Public/icon/'; // 设置附件上传根目录

                // 上传单个文件
                $info = $upload->uploadOne($_FILES['fileToUpload']);
                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {// 上传成功 获取上传文件信息
                    header('Content-type:text/html;charset=utf-8');
                    //读取图片文件，转换成base64编码格式
                    $image_file = $info['savepath'] . $info['savename'];
                    $_POST['newlogo'] = $image_file;
                }
            } else {
                $where['id'] = $_POST['id'];
                $newlogo = $model->where($where)->getField('newlogo');
                if (!empty($newlogo)) {
                    $_POST['newlogo'] = $newlogo;
                } else {
                    $_POST['newlogo'] = '';
                }
            }

            $tid_count = count($_POST['tid']);
            //$_POST['tid'] = implode(',', $_POST['tid']);
            //$_POST['tname'] = implode(',', array_slice($_POST['tname'], 0, $tid_count));
            //$_POST['tweb'] = implode(',', array_slice($_POST['tweb'], 0, $tid_count));
            $_POST['fid'] = session('homeId');
            $_POST['lrtime'] = time();

            if (!$model->create()) {
                $this->error($model->getError());
            } else {
                if ($model->save()) {
                    $this->success("更新成功", U('index/yeshb'));
                } else {
                    $this->error("更新失败");
                }
            }
        }
    }


    public function delete($id)
    {

        $model = M('List');

        $map['id'] = $id;

        $map['fid'] = session('homeId');

        //$result = $model->where($map)->setInc('status',3);

        //$info = $model->where($map)->find();

        $result = $model->where($map)->delete();

        if ($result) {

            M('member')->where(array('id' => session('homeId')))->setDec('appnum');

            //unlink('./Public/uploads/'.$info['uptxt']);

            unlink('./Public/appipa/' . $id . '.plist');

            $this->success("删除成功", U('index/listinfo'));

        } else {

            $this->error("删除失败");

        }

    }

    public function duodelete($id)
    {

        $model = M('Newlist');

        $map['id'] = $id;

        $map['fid'] = session('homeId');

        //$result = $model->where($map)->setInc('status',3);

        //$info = $model->where($map)->find();

        $result = $model->where($map)->delete();

        if ($result) {

            //unlink('./Public/uploads/'.$info['uptxt']);

            //unlink('./Public/appipa/'.$id.'.plist');

            $this->success("删除成功", U('index/yeshb'));

        } else {

            $this->error("删除失败");

        }

    }


    public function modify($id)
    {

        $model = M("List");

        $where['id'] = $id;

        $where['fid'] = session('homeId');

        $info = $model->where($where)->find();

        $this->info = $info;

        $this->display();

    }


    public function newmodify($id)
    {

        $model = M("Newlist");

        $where['id'] = $id;

        $where['fid'] = session('homeId');

        $info = $model->where($where)->find();


        $tid = explode(',', $info['tid']);

        $tname = explode(',', $info['tname']);

        $tweb = explode(',', $info['tweb']);


        $this->tid = $tid;

        $this->tname = $tname;

        $this->tweb = $tweb;

        $this->info = $info;

        $this->display();

    }


    public function updateinfo()
    {

        //默认显示添加表单

        if (IS_POST) {


            if (!empty($_FILES['fileToUpload']['name'])) {


                $upload = new \Think\Upload();// 实例化上传类

                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型

                $upload->rootPath = './Public/icon/'; // 设置附件上传根目录

                // 上传单个文件 

                $info = $upload->uploadOne($_FILES['fileToUpload']);

                if (!$info) {// 上传错误提示错误信息

                    $this->error($upload->getError());

                } else {// 上传成功 获取上传文件信息


                    header('Content-type:text/html;charset=utf-8');

                    //读取图片文件，转换成base64编码格式 

                    $image_file = $info['savepath'] . $info['savename'];

                    $_POST['zxlogo'] = $image_file;

                }

            } else {

                $_POST['zxlogo'] = '';

            }


            $model = M("List");

            if (!$model->create()) {

                $this->error($model->getError());

            } else {


                if ($model->save()) {

                    $this->success("提交成功", U('index/listinfo'));

                } else {

                    $this->error("提交失败");

                }

            }

        }

    }

    /**获取关联应用
     * use for:
     * auth: Joql
     * date:2018-04-22 17:13
     */
    public function getMergeList(){
        $map['id'] = $_POST['id'];
        $list = M('List');
        $result = $list->where($map)->find();
        empty($result) && returnAjax(0, 'id不存在');

        $ext = ($result['ext'] == 'apk' ? 'ipa':'apk');
        $data = $list->field('id, nickname, hslogo')->where([
            'nickname'=>$result['nickname'],
            'ext'     =>$ext,
            'id' => ['neq',$result['id']]
        ])->select();
        if(empty($data)){
            returnAjax(0,'无关联应用');
        }
        returnAjax(1,'success',$data);
    }
    /**
     * use for:合并应用
     * auth: Joql
     * date:2018-04-22 15:24
     */
    public function mergeApp(){

        if(empty($_POST['first_id']) || empty($_POST['sec_id'])) returnAjax(0,'id 缺失');

        $list = M('List');

        $first = $list->where([
            'id'    => $_POST['first_id']
        ])->find();
        $sec = $list->where([
            'id'    => $_POST['sec_id']
        ])->find();
        $first_name = $first['ext'] == 'apk'? 'Android':'Ios';
        $sec_name = $sec['ext'] == 'apk'? 'Android':'Ios';
        $save = [
            'newlogo' => $first['hslogo'],
            'kename'  => $first['nickname'],
            'fid'     => 1,//fid
            'tid'     => 'on,on',
            'tname'   => $first_name.','.$sec_name,
            'tweb'    => $first['web'].','.$sec['web'],
            'ttext'   => $first['nickname'],
            'lrtime'  => time()
        ];

        $new_list = M('Newlist');
        $result = $new_list->add($save);
        if ($result) {
            returnAjax(1,'success');
        } else {
            returnAjax(0,'err',$save);
        }
    }


    /**
     * 批量删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */

    public function alldel()
    {


        if (IS_POST) {

            $hobby = $_POST['hobby'];

            if (empty($hobby)) {

                $this->error("不能提交空值!");

            }

            $member = M("Member");

            $result = $member->where(array('id' => session('homeId')))->find();


            $post = M('List');

            $ii = 0;

            foreach ($hobby as $k => $val) {


                $map['status'] = 0;

                $map['id'] = $val;

                $map['fid'] = session('homeId');

                $res = $post->where($map)->delete();

                unlink('./Public/appipa/' . $val . '.plist');


                /**
                 *
                 * if ($result['uptype'] == 1) {
                 *
                 * // 初始化签权对象
                 *
                 * $auth = new \Qiniu\Auth(trim($result['accesskey']), trim($result['secretkey']));
                 *
                 *
                 *
                 * //初始化BucketManager
                 *
                 * $bucketMgr = new \Qiniu\Storage\BucketManager($auth);
                 *
                 * $err = $bucketMgr->delete(trim($result['bucket']), trim($result['qnname']));
                 *
                 * }
                 **/


                if ($result['uptype'] == 2) {

                    $uptxt = $post->where(array('id' => $val))->getField('uptxt');

                    unlink($uptxt);

                }


                $ii++;

            }

            if ($res) {

                $member->where(array('id' => session('homeId')))->setDec('appnum', $ii);

                //unlink('./Public/uploads/'.$info['uptxt']);

                $this->success("批量删除成功", U('Index/listinfo'));

            } else {

                $this->error("删除失败");

            }


        } else {

            $this->error("请不要恶意提交");

        }

    }


    /**
     * 批量删除
     * @param  [type] $id [description]
     * @return [type]     [description]
     */

    public function delnewall()
    {


        if (IS_POST) {


            $hobby = $_POST['hobby'];

            if (empty($hobby)) {

                $this->error("不能提交空值!");

            }

            $member = M("Member");

            $result = $member->where(array('id' => session('homeId')))->find();


            $post = M('Newlist');

            foreach ($hobby as $k => $val) {


                $map['id'] = $val;

                $map['fid'] = session('homeId');

                $res = $post->where($map)->delete();


            }

            if ($res) {

                $this->success("批量删除成功", U('Index/yeshb'));

            } else {

                $this->error("删除失败");

            }


        } else {

            $this->error("请不要恶意提交");

        }

    }


    public function kongbai()
    {

        $member = M('Member');

        $result = $member->where(array('id' => session('homeId')))->find();

        if ($result['uptype'] == 1 && $result['accesskey'] == '' && $result['secretkey'] == '') {


            $this->success("当前为七牛上传，请设置七牛信息...", U('Member/jiekou'), 3);


        } else {

            $this->result = $result;

            $this->display();

        }

    }

    public function test(){

    }

}

