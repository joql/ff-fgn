<?php
namespace Admin\Model;
use Think\Model;
class ListModel extends Model{
    protected $_validate = array(
        //array('plist','require','请填写.plist地址！'), //默认情况下用正则进行验证
        //array('type',array(1,2,3,4),'请勿恶意修改字段',3,'in'), // 当值不为空的时候判断是否在一个范围内
    );
    protected $_auto = array ( 
        array('create_time','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
    );

}