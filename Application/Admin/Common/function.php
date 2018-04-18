<?php

/* 清除缓存 */
function deldir($dir){
	$dh = opendir($dir);
	while ($file = readdir($dh)) {
		if ($file != "." && $file != "..") {
			$fullpath = $dir . "/" . $file;
			if (!is_dir($fullpath)) {
				unlink($fullpath);
			} else {
				deldir($fullpath);
			}
		}
	}
}

/* 获取用户未审核数 */
function wshenhe($uid){
	$where['uid'] = $uid;
	$where['status'] = array('eq',1);
	$wnum = M('Post')->where($where)->count();
	return $wnum;
}

/* 获取用户提交总数 */
function shenhe($uid){
	$where['uid'] = $uid;
	$ynum = M('Post')->where($where)->count();
	return $ynum;
}

/* 获取用户名称 */
function getName($uid){
	if (!empty($uid)) {
		$member = M('Member');
		$username = $member->where(array('id'=>$uid))->getField('username');
		return $username;
	} else {
		return '';
	}
}

 //获取用户过期时间
 function gqtime($id) {
  $member = M('Member');
  $list = $member->where(array('id'=>$id))->field('create_time,tianshu')->find();
  return ($list['create_time']  + ($list['tianshu'] * 24 * 60 * 60));
 }

/*
 //该用户上传的应用个数
 function getappnum($id) {
 	$list = M("List");
 	$where['fid'] = $id;
 	$appcount = $list->where($where)->order('id desc')->count();
 	return $appcount;
 }
 */
