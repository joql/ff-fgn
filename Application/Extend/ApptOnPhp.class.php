<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/4/16
 * Time: 16:57
 */

namespace Extend;

//test
//$test = new apptOnPhp('test.apk', 'D:\tool\apk-tools\aapt');
//if($test->parse()){
//     echo $test->getPackageName();
//     echo $test->getAppName();
//     echo $test->getIcon();
//}

//
class ApptOnPhp{

    //windows http://oykeubbl7.bkt.clouddn.com/apk-tools.7z
    private $aapt_path;
    private $apk_path;
    private $apk_info;
    public function __construct($apk_path, $aapt_path = 'aapt')
    {
        $this->aapt_path = $aapt_path;
        $this->apk_path = $apk_path;
    }

    public function parse(){
        exec($this->aapt_path.' d badging '.$this->apk_path.' 2>&1', $this->apk_info);
        if(strpos($this->apk_info[0],'package') !== false){
            return true;
        }
        return $this->apk_info;
    }

    public function getPackageName(){
        preg_match('/name=\'(.*?)\'/', $this->apk_info[0], $match);
        return $match[1];
    }

    public function getAppName(){
        foreach ($this->apk_info as $v){
            if(preg_match('/application-label:\'(.*?)\'/', $v, $match)){
                return $match[1];
            }
        }
        return false;
    }

    public function getIcon($type='base64', $dst=''){
        foreach ($this->apk_info as $v){
            if(preg_match('/icon=\'(res.*?)\'/', $v, $match)){
                $zip = new \ZipArchive();
                $zip->open($this->apk_path);
                if($type == 'base64'){
                    return 'data:image/jpg/png/gif;base64,'.base64_encode($zip->getFromName($match[1]));
                }elseif ($type == 'file'){
                    $filename='icon-'.time().'.png';
                    $file = fopen($dst.'/'.$filename, 'w');
                    fwrite($file,$zip->getFromName($match[1]));
                    fclose($file);
                    return $dst.'/'.$filename;
                }

            }
        }
    }


}