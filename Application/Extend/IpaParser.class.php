<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/4/12
 * Time: 16:44
 */

namespace Extend;

require __DIR__.'/IosParse/zip.php';
require __DIR__.'/IosParse/CFPropertyList/CFPropertyList.php';
require __DIR__.'/IosParse/pngCompote.php';

class IpaParser
{
    private $newDir;//保存文件目录
    private $oldDir;//原目录
    private $filename;//文件名
    private $appDir;//app 目录名
    private $plist;
    private $d;

    public function __construct($oldDir, $filename, $newDir)
    {
        $this->oldDir = $oldDir;
        $this->filename = $filename;
        $this->newDir = $newDir;
    }

    private function ipa2Dir(){
        $this->appDir = time().rand('111','999');
        @copy($this->oldDir.$this->filename, str_replace('ipa', 'zip', $this->newDir.$this->filename));
        $zip = new \PclZip(str_replace('ipa', 'zip', $this->newDir.$this->filename));
        $dir = $this->newDir.$this->appDir.'/';
        if(!is_dir($dir)){
            @mkdir($dir, 0777, true);
        }
        $zip->extract(77001, $dir, 77016);
        return true;
    }

    public function parse(){
        $this->ipa2Dir();

        $dir = $this->newDir.$this->appDir.'/Payload';
        if(!is_dir($dir)){
            return false;
        }

        $this->d= NULL;
        $h = opendir($dir);
        while ($f = readdir($h)) {
            if ($f != '.' && $f != '..' && is_dir($dir . '/' . $f)) {
                $this->d= $dir . '/' . $f;
            }
        }
        closedir($h);
        $info = file_get_contents($this->d. '/Info.plist');
        $plist = new \CFPropertyList\CFPropertyList();
        $plist->parse($info);
        $this->plist = $plist->toArray();
        return true;
    }

    public function  getAppName(){
        return $this->plist['CFBundleDisplayName'];
    }

    public function getBid(){
        return $this->plist['CFBundleIdentifier'];
    }
    public function getVersion(){
        return $this->plist['CFBundleShortVersionString'];
    }

    public function  getIcon($type='base64'){
        //icon
        $icon_path = $this->newDir.$this->appDir.'.png';
        $icon = $this->plist['CFBundleIcons']['CFBundlePrimaryIcon']['CFBundleIconFiles'];
        if (preg_match('/\./', $icon[0])) {
            for ($i = 0; $i < count($icon); $i++) {
                $array[] = filesize($this->d. '/' . $icon[$i]);
            }
            sort($array);
            for ($p = 0; $p < count($icon); $p++) {
                if ($array[0] == filesize($this->d. '/' . $icon[$p])) {
                    $oldfile = $this->d. '/' . $icon[$p];
                }
            }
        } else {
            for ($i = 0; $i < count($icon); $i++) {
                $array[] = filesize($this->d. '/' . $icon[$i] . '@2x.png');
            }
            sort($array);
            for ($p = 0; $p < count($icon); $p++) {
                if ($array[0] == filesize($this->d. '/' . $icon[$p] . '@2x.png')) {
                    $ext = preg_match('/20x20/', $icon[$p]) ? '@3x.png' : '@2x.png';
                    $oldfile = $this->d. '/' . $icon[$p] . $ext;
                }
            }
        }
        $png = new \PngFile\PngFile($oldfile);
        if (!$png->revertIphone($icon_path)) {
            //copy('../../../static/app/icon.png', $icon_path);
        }
        if($type == 'base64'){
            $handle  =  fopen ( $icon_path ,  "r" );
            $contents  =  fread ( $handle ,  filesize ( $icon_path ));
            fclose ( $handle );
            return 'data:image/jpg/png/gif;base64,'.base64_encode($contents);
        }elseif ($type == 'file'){
            return $icon_path;
        }

    }
    /**
     * use for:编码转换
     * auth: Joql
     * @param $str
     * @return string
     * date:2018-04-12 15:45
     */
    private function detect_encoding($str){
        $chars = NULL;
        $list = array('GBK', 'UTF-8');
        foreach($list as $item){
            $tmp = mb_convert_encoding($str, $item, $item);
            if(md5($tmp) == md5($str)){
                $chars = $item;
            }
        }
        return strtolower($chars) !== 'gbk' ? iconv($chars, strtoupper('gbk').'//IGNORE', $str) : $str;
    }

}