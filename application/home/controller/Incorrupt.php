<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
use app\home\model\WechatTest;
use app\home\model\WechatUser;

class Incorrupt extends Base{

    /* 党风廉政首页 */
    public function home(){
        return $this->fetch();
    }

    /* 廉政责任 & 纪检报告*/
    public function index(){
        return $this->fetch();
    }

    /* 廉政教育 */
    public function education(){
        return $this->fetch();
    }

    /* 发布 */
    public function publish(){
        return $this->fetch();
    }
}