<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
use app\home\model\Browse;
use app\home\model\News;
use app\home\model\Notice as NoticeModel;
use app\home\model\WechatUser;
use app\home\model\WechatUserTag;
use com\wechat\TPQYWechat;
use app\home\model\Picture;
use think\Config;
use think\Db;
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
        $this->anonymous();
        //获取jssdk
        $this->jssdk();
        $userId = session('userId');
        $id = input('id');
        $info = $this->content(1,$id);
        // 是否 超级管理员
        $limit = WechatUserTag::where(['tagid' => 3, 'userid' => $userId])->find();
        if ($limit){// 超级管理员
             // 判断 是否  到  截止时间
            if ($info['end_time'] > time()){
                // 未截止
                $info['limit'] = 2;
            }else{
                // 已经截止
                $info['limit'] = 1;
            }
        }else{
            $info['limit'] = 0; // 普通
        }
        // 获取  需要考核的人员
        $People = WechatUserTag::where(['tagid' => 1])->select();
        $people = array();
        // 未读人员  名单
        foreach($People as $value){
            $read = Browse::where(['type' =>1,'aid' => $id,'uid' => $value['userid']])->find();
            if (!$read){
                // 未读
                $name = WechatUser::where(['userid' => $value['userid']])->value('name');
                if (empty($name)){
                    $name = "暂无";
                }
                array_push($people,$name);
            }
        }
        $info['people'] = implode('，',$people);
        $this->assign('info',$info);
        return $this->fetch();
    }
    /**
     * 一键提醒  功能
     */
    public function remind(){
        $id = input('id');
        // 获取  需要考核的人员
        $People = WechatUserTag::where(['tagid' => 1])->select();
        $people = array();
        // 未读人员  名单
        foreach($People as $value){
            $read = Browse::where(['type' =>1,'aid' => $id,'uid' => $value['userid']])->find();
            if (!$read){
                // 未读
                array_push($people,$value['userid']);
            }
        }
        $Notice = NoticeModel::where(['id' => $id])->field('title,end_time')->find();
        $content = '标题为【'.$Notice['title'].'】的通知公告您还未阅读，请在'.date('m-d H:i',$Notice['create_time']).'前查看哦';
        $Wechat = new TPQYWechat(Config::get('user'));
        $message = array(
            'touser' => implode('|',$people),
            "msgtype" => 'text',
            "agentid" => 1000011, // 个人中心
            "text" => array(
                "content" => $content
            ),
            "safe" => "0"
        );
        $res = $Wechat->sendMessage($message);  //审核通过，向用户推送提示
        if ($res['errcode'] == 0){
            return $this->success('提醒成功');
        }else{
            return $this->error($Wechat->errMsg);
        }
    }
    /**
     * 党建动态 详情页
     */
    public function dynamicdetail(){
        //判断是否是游客
        $this->anonymous();
        //获取jssdk
        $this->jssdk();
        $id = input('id');
        $this->assign('new',$this->content(2,$id));
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