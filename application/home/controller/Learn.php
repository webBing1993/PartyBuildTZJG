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

class Learn extends Base{
    /* 方案部署 */
    public function program(){
        return $this->fetch();
    }

    /* 年度计划 */
    public function plan(){
        return $this->fetch();
    }

    /* 主题党日 */
    public function theme(){
        return $this->fetch();
    }
}