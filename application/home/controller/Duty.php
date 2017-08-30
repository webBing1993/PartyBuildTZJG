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

class Duty extends Base{
    /* 党建责任首页 */
    public function home(){
        return $this->fetch();
    }

    /*专题研究、责任清单、工作计划列表*/
    public function index(){
        return $this->fetch();
    }

    /*述职报告列表*/
    public function branchindex(){
        return $this->fetch();
    }

    /*党建责任详情*/
    public function details(){
        return $this->fetch();
    }

    /*党建责任发布*/
    public function publish(){
        return $this->fetch();
    }
}