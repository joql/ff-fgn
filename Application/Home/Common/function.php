<?php
/* 截取udid */
function str_to($str){
	if (!empty($str)) {
		return substr($str,0,4).'****'.substr($str,-4);
	} else {
		return '';
	}
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

/* 获取用户ID */
function getId($username){
	if (!empty($username)) {
		$member = M('Member');
		$id = $member->where(array('username'=>$username))->getField('id');
		return $id;
	} else {
		return '';
	}
}

//判断是否为字典数组（dict）
function isDict($array)
{
 return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));

}

//向xml节点中写入字典数组（dict）
function xmlWriteDict(XMLWriter $x, &$dict) 
{
 $x->startElement('dict');
 foreach($dict as $k => &$v) 
 {
 $x->writeElement('key', $k);
 xmlWriteValue($x, $v);
 }
 $x->endElement();
}
 
 //向xml节点中写入数组（array）
function xmlWriteArray(XMLWriter $x, &$arr) 
{
 $x->startElement('array');
 foreach($arr as &$v)
 xmlWriteValue($x, $v);
 $x->endElement();
}

//根据类型向xml节点中写入值
function xmlWriteValue(XMLWriter $x, &$v) 
{
 if (is_int($v) || is_long($v))
 $x->writeElement('integer', $v);
 elseif (is_float($v) || is_real($v) || is_double($v))
 $x->writeElement('real', $v);
 elseif (is_string($v))
 $x->writeElement('string', $v);
 elseif (is_bool($v))
 $x->writeElement($v?'true':'false');
 elseif (isDict($v))
 xmlWriteDict($x, $v);
 elseif (is_array($v))
 xmlWriteArray($x, $v);
 else 
 {
 trigger_error("Unsupported data type in plist ($v)", E_USER_WARNING);
 $x->writeElement('string', $v);
 }
}

//创建plist
function createplist($ctlist)
{
 $title = $ctlist['title'];
 $subtitle = $ctlist['subtitle'];
 $versionname = $ctlist['versionname'];
 $bundle_identifier = $ctlist['bundle_identifier']; 
 $ssl_server = $ctlist['ssl_server'];
 $channelid = $ctlist['cid'];  
 if (!$versionname) 
 {
 $versionname = '6.0.0';
 }
 $versioncode = str_replace('.', '', $versionname);

 if (!$channelid) 
 {
 $channelid = '0';
 }

 header('Content-Type: application/xml');
 $plist = new XmlWriter();
 $plist->openMemory();
 $plist->setIndent(TRUE);
 $plist->startDocument('1.0', 'UTF-8');
 $plist->writeDTD('plist', '-//Apple//DTD PLIST 1.0//EN', 'http://www.apple.com/DTDs/PropertyList-1.0.dtd');
 $plist->startElement('plist');
 $plist->writeAttribute('version', '1.0');

 $pkg = array();
 $pkg['kind'] = 'software-package';

 $member = M('Member');
 $uptype = $member->where(array('id'=>session('homeId')))->getField('uptype');
 if ($uptype == 1) {
   $pkg['url'] = $channelid;
 } elseif ($uptype == 2) {
   $pkg['url'] = $ssl_server . 'Public/uploads/' . $channelid;
 }
 
 
 $displayimage = array();
 $displayimage['kind'] = 'display-image';
 $displayimage['needs-shine'] = TRUE;
 $displayimage['url'] = $ssl_server . 'Icon.png';

 $fullsizeimage = array();
 $fullsizeimage['kind'] = 'full-size-image';
 $fullsizeimage['needs-shine'] = TRUE;
 $fullsizeimage['url'] = $ssl_server . 'Icon.png';

 $assets = array();
 $assets[] = $pkg;
 $assets[] = $displayimage;
 $assets[] = $fullsizeimage;

 $metadata = array();
 $metadata['bundle-identifier'] = $bundle_identifier;
 $metadata['bundle-version'] = $versionname;
 $metadata['kind'] = 'software';
 $metadata['subtitle'] = $subtitle;
 $metadata['title'] = $title;

 $items0 = array();
 $items0['assets'] = $assets;
 $items0['metadata'] = $metadata;

 $items = array();
 $items[] = $items0;

 $root = array();
 $root['items'] = $items;

 xmlWriteValue($plist, $root);

 $plist->endElement();
 $plist->endDocument();

 return $plist->outputMemory();
}

	/**
     * 解析XML格式的字符串
     *
     * @param string $str
     * @return 解析正确就返回解析结果,否则返回false,说明字符串不是XML格式
     */
    function xml_parser($str){
        $xml_parser = xml_parser_create();
        if(!xml_parse($xml_parser,$str,true)){
            xml_parser_free($xml_parser);
            return false;
        }else {
            return (json_decode(json_encode(simplexml_load_string($str)),true));
        }
    }

function deldir($dir) {
    //先删除目录下的文件：
    $dh=opendir($dir);
    while ($file=readdir($dh)) {
    	if($file!="." && $file!="..") {
        	$fullpath=$dir."/".$file;
      		if(!is_dir($fullpath)) {
          		unlink($fullpath);
      		} else {
          		deldir($fullpath);
      		}
    	}
  	}
 
  	closedir($dh);
  	//删除当前文件夹：
  	if(rmdir($dir)) {
    	return true;
  	} else {
    	return false;
  	}
}

//php文件大小单位转换GB MB KB 
function formatBytes($size) { 
  $units = array(' B', ' KB', ' MB', ' GB', ' TB'); 
  for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024; 
  return round($size, 2).$units[$i]; 
 }

 //获取用户过期时间
 function gqtime($id) {
  $member = M('Member');
  $list = $member->where(array('id'=>$id))->field('create_time,tianshu')->find();
  return ($list['create_time']  + ($list['tianshu'] * 24 * 60 * 60));
 }

