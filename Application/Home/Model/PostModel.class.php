<?php
namespace Home\Model;
use Think\Model;
class PostModel extends Model{
    protected $_validate = array(
        array('udid','checkUdid','udid不符合要求。',1,'callback'),
        array('udid',"40,40",'udid必须40个字符',0,'length'), // 当值不为空的时候判断是否在一个范围内
        array('rid','/^[0-9]+$/', '编号只能为数字！',2),
        array('rid', '0,9999999999' , '编号必须是十位以内的数字', 0, 'between', self::MODEL_BOTH),
    );
    function checkUdid($udid) {
        $ttd_num = strlen(trim($udid));

        if ($ttd_num !== 40) {
            return false;
        } else {
            return true;
        }
    }
    protected $_auto = array ( 
        array('status','1'),
        array('create_new','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
        array('uid','getUid',1,'callback'), // 对update_time字段在更新的时候写入当前时间戳
    );
    protected function getUid(){
    	return session('homeId');
    }
} 