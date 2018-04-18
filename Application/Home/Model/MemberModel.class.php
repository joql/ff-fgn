<?php
namespace Home\Model;
use Think\Model;
class MemberModel extends Model{
    protected $_validate = array(
        array('username','require','请填写邮箱！'), //默认情况下用正则进行验证
        //array('username','/^[A-Za-z0-9]+$/', '用户名只能为英文字母或数字！',2),
        array('username','email','邮箱格式不正确！',self::EXISTS_VALIDATE),
        array('password','require','请填写密码！','','',self::MODEL_INSERT), //默认情况下用正则进行验证
     	array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
        array('username','','邮箱已存在！',0,'unique',self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('staus',array(0,1),'请勿恶意修改字段',3,'in'), // 当值不为空的时候判断是否在一个范围内
        array('type',array(1,2),'请勿恶意修改字段',3,'in'), // 当值不为空的时候判断是否在一个范围内
        array('accesskey','require','accesskey不能为空！'), //默认情况下用正则进行验证
        array('secretkey','require','secretkey不能为空！'), //默认情况下用正则进行验证
        array('bucket','require','bucket不能为空！'), //默认情况下用正则进行验证 
        array('domain','require','domain不能为空！'), //默认情况下用正则进行验证
    );

    protected $_auto = array(
        array('status','1'),  // 新增的时候把status字段设置为1
        array('type','1'),  // 新增的时候把type字段设置为1
        array('username','trim',1,'function') , //添加时用md5函数处理
    	array('password','md5',1,'function') , //添加时用md5函数处理 
        array('update_time','time',2,'function'), //更新时
        array('create_time','time',1,'function'), //新增时
        array('login_ip','get_client_ip',3,'function'), //新增时
      //  array('password','',2,'ignore')   //怎么不能用？
    );


}