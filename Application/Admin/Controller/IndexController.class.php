<?php
namespace Admin\Controller;
use Admin\Controller;

class IndexController extends BaseController{

    public function index(){
    	$model = M('Member');
    	$this->member_num = $model->count();
    	$this->xin_num = $model->where(array('uptype'=>1))->count();
    	$this->dai_num = $model->where(array('uptype'=>3))->count();
    	$post = M('List');
    	$this->com_num = $post->count();
        $this->display();
    }
}
