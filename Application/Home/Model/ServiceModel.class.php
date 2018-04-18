<?php
namespace Home\Model;
use Think\Model;
class ServiceModel extends Model{
    protected $_validate = array(
        array('udid',"40,40",'udid必须40个字符',0,'length'), // 当值不为空的时候判断是否在一个范围内
        array('udid','require','请填写udid！'), //默认情况下用正则进行验证
        array('content','require','请填写内容！'), //默认情况下用正则进行验证
    );
    protected $_auto = array ( 
        array('status','1'),
        array('create_new','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
    );

}