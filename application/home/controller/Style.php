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

class Style extends Base{

    /* 作风建设 */
    public function home(){
        return $this->fetch();
    }

    /* 作风建设列表页*/
    public function index(){
        return $this->fetch();
    }

    /* 作风建设 详情页*/
    public function detail(){
        return $this->fetch();
    }
}