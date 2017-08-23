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

class Organization extends Base{
    /* 规范化建设 */
    public function standard(){
        return $this->fetch();
    }

    /* 信息录用 */
    public function information(){
        return $this->fetch();
    }
}