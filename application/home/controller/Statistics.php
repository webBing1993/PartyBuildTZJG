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

class Statistics extends Base{
    /*
     * 数据统计
     */
    public function index(){
        return $this->fetch();

    }
}
