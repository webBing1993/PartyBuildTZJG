<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
use app\home\model\News;
use app\home\model\Notice as NoticeModel;

/**
 * Class Notice
 * @package 通知公告
 */
class Notice extends Base{
    /* 通知公告  党建动态 */
    public function index(){
        $News = new News();
        $Notice = new NoticeModel();
        // 通知公告 轮播
        $map = [
            'status' => ['egt',0],
            'recommend' => 1
        ];
        $banner1 = $Notice->get_list($map,0,3);
        $this->assign('banner1',$banner1);
        // 党建动态  轮播
        $maps = [
            'status' => ['egt',0],
            'recommend' => 1
        ];
        $banner2 = $News->get_list($maps,0,3);
        $this->assign('banner2',$banner2);
        // 通知公告  列表
        $list1 = $Notice->get_list(['status' => ['egt',0]]);
        $this->assign('list1',$list1);
        // 党建动态  列表
        $list2 = $News->get_list(['status' => ['egt',0]]);
        $this->assign('list2',$list2);
        return $this->fetch();
    }
    /**
     * 通知公告 详情
     */
    public function detail(){
        //判断是否是游客
        $this ->anonymous();
        //获取jssdk
        $this ->jssdk();
        $id = input('id');
        $this->assign('info',$this->content(1,$id));
        return $this->fetch();
    }
    /**
     * 党建动态 详情页
     */
    public function dynamicdetail(){
        //判断是否是游客
        $this ->anonymous();
        //获取jssdk
        $this ->jssdk();
        $id = input('id');
        $this->assign('info',$this->content(2,$id));
        return $this->fetch();
    }
    /**
     * 加载更多
     */
    public function more(){
        $News = new News();
        $Notice = new NoticeModel();
        $len = input('length');
        $type = input('type'); // 0 通知公告  1 党建动态
        if ($type == 0){
            return $this->success('加载成功','',$Notice->get_list(['status' => ['egt',0]],$len));
        }else{
            return $this->success('加载成功','',$News->get_list(['status' => ['egt',0]],$len));
        }
    }
}