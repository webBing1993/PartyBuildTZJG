<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
use app\home\model\Responsibility as ResponsibilityModel;
use app\home\model\WechatTest;
use app\home\model\WechatUser;

/**
 * Class Responsibility
 * @package app\home\controller
 * 党建责任
 */

class Responsibility extends Base{
    /* 党建责任首页 */
    public function home(){

        return $this->fetch();
    }

    /**
     * 党建责任列表、加载更多
     * $type 页面获取类型
     * $length 加载获取长度
     */
    public function index(){
        $Model = new ResponsibilityModel();
        if(IS_POST) {
            $data = input('post.');
            dump($data);

        }else {
            $type = input('type');
            $list = $Model->getIndex($type);
            $this->assign('list',$list);
            $this->assign('type',$type);
            return $this->fetch();
        }
    }

    /**
     * 述职报告列表页
     */
    public function branchindex() {
        $Model = new ResponsibilityModel();
        if(IS_POST) {

        }else {
            $type = input('type');
            $list = $Model->getBranchIndex($type);
            $this->assign('list',$list);
            $this->assign('type',$type);
            return $this->fetch();
        }
    }

    /*党建责任详情*/
    public function detail(){
        return $this->fetch();
    }

    /*党建责任发布*/
    public function publish(){
        if(IS_POST) {
            
        }else {
            
            return $this->fetch();
        }
    }

    /**
     * 搜索支部名称
     */
    public function search() {
        $name = input('name');
        $Model = new ResponsibilityModel();
        $map = array(
            'publisher' => array(['like',"%$name%"],['neq','']),
            'status' => 1
        );
        $res = $Model->where($map)->field('id,title,publisher,create_time')->select();
        foreach ($res as $val) {
            $val['time'] = date("Y-m-d",$val['create_time']);
        }
        if($res) {
            return $this->success("搜索成功","",$res);
        }else{
            return $this->error("搜索失败");
        }
    }
}