<?php
namespace Admin\Controller;
use Admin\Controller;
/**
 * 文章管理
 */
class ListController extends BaseController
{
    /**
     * 信息列表
     * @return [type] [description]
     */
    public function index()
    {   
        $key = trim(I('post.key')) ;
        $fid = trim(I('get.fid'));
        if($key == ""){
            if (!empty($fid)) {
                $where['fid'] = $fid;
            }
            $model = D('List');
          
        }else{
            $where['nickname'] = trim($key);
            $model = M('List');
        } 
        
        $count  = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Extend\Page($count,100);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出

        if ($status == 1) {
            $post = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id asc')->select();
        } else {
            $post = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id desc')->select();
        }
        
        $this->assign('model', $post);
        $this->assign('page',$show);
        $this->display();     
    }

    /**
     * 查询列表
     * @return [type] [description]
     */
    public function chaxun($key="")
    {
        if($key == ""){
            $model = D('List');
        }else{
            $where['udid'] = array('like',"%$key%");
            $model = D('List');
        } 
        
        $count  = $model->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Extend\Page($count,100);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $post = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id DESC')->select();
        $this->assign('model', $post);
        $this->assign('page',$show);
        $this->status = I('status');
        $this->display();     
    }

    /**
     * 查询列表
     * @return [type] [description]
     */
    public function memlist(){
        if (IS_GET) {
            $uid = I('get.uid');
            $status = I('get.status');
            if (!empty($uid) && !empty($status)) {
                $model = M('List');
                $where['uid'] = $uid;
                $where['status'] = $status;
               
                $count  = $model->where($where)->count();// 查询满足要求的总记录数
                $Page = new \Extend\Page($count,100);// 实例化分页类 传入总记录数和每页显示的记录数(25)
                $show = $Page->show();// 分页显示输出
                $post = $model->limit($Page->firstRow.','.$Page->listRows)->where($where)->order('id DESC')->select();
                
                $this->assign('model', $post);
                $this->assign('page',$show);
                $this->status = $status;
                $this->display('chaxun'); 
            }
        }
    }

    /**
     * 添加文章
     */
    public function add()
    {
        //默认显示添加表单
        if (!IS_POST) {
            $this->display();
        }
        if (IS_POST) {
            //如果用户提交数据
            $model = D("List");
            if (!$model->create()) {
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($model->getError());
                exit();
            } else {
                if ($tid = $model->add()) {
                    $where['id'] = $tid;
                    $model-> where($where)->setField('web','index.php/appipa/listll/xxid/'.$tid.'.html');
                    $this->success("添加成功", U('list/index'));
                } else {
                    $this->error("添加失败");
                }
            }
        }
    }

    public function addzd()
    {
            //如果用户提交数据
            $model = M("List");
            $data['create_time'] = time();
                if ($tid = $model->data($data)->add()) {
                    $where['id'] = $tid;
                    $model-> where($where)->setField('web','index.php/appipa/listll/xxid/'.$tid.'.html');
                    $model-> where($where)->setField('plist',$tid);
                    $this->success("添加成功", U('list/index'));
                } else {
                    $this->error("添加失败");
                }
    }

    /**
     * 更新文章信息
     * @param  [type] $id [文章ID]
     * @return [type]     [description]
     */
    public function update($id)
    {
        //默认显示添加表单
        if (!IS_POST) {
            $model = M('List')->where('id='.$id)->find();
            $this->assign('post',$model);
            $this->display();
        }
        if (IS_POST) {
            $model = D("List");
            if (!$model->create()) {
                $this->error($model->getError());
            }else{
                if ($model->save()) {
                    $this->success("更新成功", U('list/index'));
                } else {
                    $this->error("更新失败");
                }        
            }
        }
    }

    /**
     * 更新文章信息
     * @param  [type] $id [文章ID]
     * @return [type]     [description]
     */
    public function cx_update($id)
    {
        //默认显示添加表单
        if (!IS_POST) {
            $model = M('List')->where('id='.$id)->find();
            $this->assign('post',$model);
            $this->status = $status;
            $this->display();
        }
        if (IS_POST) {
            $model = D("List");
            if (!$model->create()) {
                $this->error($model->getError());
            }else{
                if ($model->save()) {
                    $this->success("更新成功", U('post/chaxun'));
                } else {
                    $this->error("更新失败");
                }        
            }
        }
    }

    /**
     * 删除文章
     * @param  [type] $id [description]
     * @return [type]     [description]
    */ 
    public function delete($id)
    {
        //$this->error("禁止删除");
        if (empty($id)) {
            $this->error("ID不能为空");
        }
        $model = M('List');
        $map['id'] = $id;
        $result = $model->where($map)->delete();
        //$result = $model->where("id=".$id)->delete();
        if($result){
            $this->success("删除成功", U('list/index'));
        }else{
            $this->error("删除失败");
        }
    }
    
    /**
     * 删除文章
     * @param  [type] $id [description]
     * @return [type]     [description]
     
    public function cx_delete($id,$status)
    {
        //$this->error("禁止删除");
        $model = M('post');
        $map['status']  = array('in','1,2,3');
        $map['id'] = $id;
        $result = $model->where($map)->setInc('status',3);
        //$result = $model->where("id=".$id)->delete();
        if($result){
            $this->success("删除成功", U('post/chaxun'));
        }else{
            $this->error("删除失败");
        }
    }
    */
    /**
     * 批量转移到待处理
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function plmove() {
        
        if (IS_POST) {
            $hobby = $_POST['hobby'];
            if (empty($hobby)) {
                $this->error("不能提交空值!");
            }
            $post = M('List');
            $dd_count = $post->where(array('status'=>'2'))->count();
            if (($dd_count + count($hobby)) > 100) {
                $this->error("待处理信息数量不能超过100条!");
            }
            
            foreach ($hobby as $k => $val) {
                $post->where(array('id'=>$val))->setField('status','2');
            }
            $this->success("转移成功", U('post/index',array('status'=>'2')));
        } else {
            $this->error("请不要恶意提交");
        }
    }

    /**
     * 批量更新
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function plupdate(){
        if (IS_POST) {
            $hobby = $_POST['hobby'];
            if (empty($hobby)) {
                $this->error("不能提交空值!");
            }
            $web = I('web'.$hobby[0]);
            $pid = I('pid'.$hobby[0]);
            if (empty($web) || empty($pid)) {
                $this->error("ID为".$hobby[0]."的批号与网址不能为空!");
            }
            $post = M('Post');
            foreach ($hobby as $k => $val) {
                $post->where(array('id'=>$val))->setField(array('web'=>$web,'pid'=>$pid));
            }
            $this->success("更新成功", U('post/index',array('status'=>$_POST['status'])));
        } else {
            $this->error("请不要恶意提交");
        }
    }

    /**
     * 批量移动到已完成
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function complate() {

        $post = M('Post');
        $where['status'] = 2;
        $where['pid'] = array('neq','');
        $where['web'] = array('neq','');
        $result = $post->where($where)->select();
        if (!empty($result)) {
            foreach ($result as $key => $value) {
                $post->where(array('id'=>$value['id']))->setField(array('status'=>3,'update_com'=>time()));
            }
        }
        $this->success("移动成功", U('post/index',array('status'=>'3')));
    }

    /**
     * 批量导出
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function export() {
        $post = M('Post');
        $result = $post->where(array('status'=>'2'))->select();
        if (empty($result)) {
            $this->error("暂无数据");
        }
        Header( "Content-type:application/octet-stream "); 
        Header( "Accept-Ranges:bytes "); 
        header( "Content-Disposition:attachment;filename=export.txt"); 
        header( "Expires:0"); 
        header( "Cache-Control:must-revalidate,post-check=0,pre-check=0"); 
        header( "Pragma:public"); 
        
        foreach ($result as $k => $val) {
            echo $val['udid'].'  '.$val['rid']."\n";
        }
    }

    /**
     * 删除已完成的超过365天的用户对应数据
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function delmem() {
        $this->error("禁止删除");
        $post = M('Post');
        $result = $post->where(array('status'=>3))->select();
        $num = 0;
        foreach ($result as $key => $value) {
            $gq_num = ($value['update_com'] -$value['create_new'])/86400;
            if ($gq_num > 365) {
                $result = $post->where("id=".$value['id'])->delete();
                $num = $num +1 ;
            }
        }

        if($result){
            $this->success("成功清除".$num."条数据", U('index/index'));
        }else{
            $this->error("清除失败");
        }
      
    }

    /**
     * 批量删除查询中的数据
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    /**
    public function pldel() {
        if (IS_POST) {
            //$this->error("禁止删除");
            $hobby = $_POST['hobby'];
            if (empty($hobby)) {
                $this->error("不能提交空值!");
            }
            dd($hobby);die;
            $model = M('post');
            foreach ($hobby as $key => $value) {
                $map['status']  = array('in','1,2,3');
                $map['id'] = $value;
                $result = $model->where($map)->setInc('status',3);
                //$result = $model->where("id=".$value)->delete();
            }
      
            if($result){
                $this->success("删除成功", U('post/chaxun'));
            }else{
                $this->error("删除失败");
            }
        } else {
            $this->error("请不要恶意提交");
        }
    }
    */
    public function huany($id,$status){
        $model = M('List');
        $map['status']  = array('in','1,2,3');
        $map['id'] = $id;
        $result = $model->where($map)->setDec('status',3);
        //$result = $model->where("id=".$id)->delete();
        if($result){
            $this->success("还原成功", U('list/index',array('status'=>'0')));
        }else{
            $this->error("还原失败");
        }
    }

    /**
     * 批量还原
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function alldel() {
        
        if (IS_POST) {
            $hobby = $_POST['hobby'];
            if (empty($hobby)) {
                $this->error("不能提交空值!");
            }


            $post = M('List'); 
            foreach ($hobby as $k => $val) {

                $map['id'] = $val;
                $res = $post->where($map)->delete();
                unlink('./Public/appipa/'.$val.'.plist');
                unlink('./Public/png/'.$val.'.png');
                /**
                if ($result['uptype'] == 1) {
                    // 初始化签权对象
                    $auth = new \Qiniu\Auth(trim($result['accesskey']), trim($result['secretkey']));

                    //初始化BucketManager
                    $bucketMgr = new \Qiniu\Storage\BucketManager($auth);
                    $err = $bucketMgr->delete(trim($result['bucket']), trim($result['qnname']));
                }
                **/

                if ($result['uptype'] == 2) {
                    $uptxt = $post->where(array('id'=>$val))->getFiled('uptxt');
                    unlink($uptxt);
                }
            }
            
            if($res){
                    //unlink('./Public/uploads/'.$info['uptxt']);    
                $this->success("批量删除成功", U('List/index'));
            }else{
                $this->error("删除失败");
            }

        } else {
            $this->error("请不要恶意提交");
        }
    }
}
