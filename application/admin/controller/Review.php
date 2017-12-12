<?php
/**
 * Created by PhpStorm.
 * User: laowang
 * Date: 2017/12/12
 * Time: 下午3:26
 */

namespace app\admin\controller;
use app\admin\model\Responsibility;
use app\admin\model\Learn;
use app\admin\model\Special;
use app\admin\model\Style;
use app\admin\model\Volunteer;
use app\admin\model\Incorrupt;
use app\admin\model\Organization;
/**
 * Class Review
 * @package app\admin\controller 后台 消息审核
 */

class Review extends Admin
{
    /**
     * 待审核列表
     */
    public function index(){
        $list = array();
        //
        $this->assign('list','');
        return $this->fetch();
    }
    /**
     * 去 审核
     */
    /**
     * 已审核列表
     */
    public function pass(){
        $this->assign('list','');
        return $this->fetch();
    }
}