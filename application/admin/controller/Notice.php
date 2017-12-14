<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/8/21
 * Time: 9:35
 */
namespace app\admin\controller;
use app\admin\model\Notice as NoticeModel;
use app\admin\model\Picture;
use app\admin\model\WechatUserTag;
use app\admin\model\WechatUser;
use com\wechat\TPQYWechat;
use think\Config;
/**
 * Class Notice
 * @package 通知公告
 */
class Notice extends Admin
{
    /*
     * 列表 主页
     */
    public function index(){
        $map = array(
            'status' => array('egt',0),
        );
        $search = input('search');
        if ($search != '') {
            $map['title'] = ['like','%'.$search.'%'];
        }
        $list = $this->lists('Notice',$map);
        int_to_string($list,array(
            'recommend' => array(0=>"否",1=>"是"),
            'type' => [1 => "所有人" , 2 => "考核人员"]
        ));
        $this->assign('list',$list);
        return $this->fetch();
    }
    /**
     * 添加  修改
     */
    public function edit(){
        $noticeModel = new NoticeModel();
        if(IS_POST) {
            $data = input('post.');
            isset($data["file"]) ? $data["file"] = json_encode($data["file"]) : $data["file"] = "";
            if (empty($data['id'])){
                unset($data['id']);
                $res = $noticeModel->validate(true)->save($data);
            }else{
                $res = $noticeModel->validate(true)->save($data,['id' => $data['id']]);
            }
            if ($res){
                return $this->success("操作成功",Url('Notice/index'));
            }else{
                $this->get_update_error_msg($noticeModel->getError());
            }
        }else {
            $info = $noticeModel->get_content(input('id'));
            $this->assign('info',$info);
            return $this->fetch();
        }
    }
    /**
     * 删除
     */
    public function del(){
        $id = input('id');
        if (empty($id)){
            return $this->error('系统错误,参数不存在');
        }
        $res = NoticeModel::where('id',$id)->update(['status' => -1]);
        if ($res){
            return $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
    /**
     * 推送
     */
    public function push(){
        $id = input('id');
        if (empty($id)){
            return $this->error('系统错误,参数不存在');
        }
        $info = NoticeModel::where(['id' => $id])->find();
        $str = strip_tags($info['content']);
        $des = mb_substr($str,0,40);
        $content = str_replace("&nbsp;","",$des);  //空格符替换成空
        $pre = '【通知公告】';
        $url = "http://".$_SERVER['HTTP_HOST']."/home/Notice/detail/id/".$info['id'].".html";
        $image = Picture::get($info['front_cover']);
        $path = "http://".$_SERVER['HTTP_HOST'].$image['path'];
        $information = array(
            'title' => $pre.$info['title'],
            'description' => $content,
            'url'  => $url,
            'picurl' => $path
        );
        $send = array(
          "articles" => array(
              0 => $information
          )
        );
        if ($info['type'] == 1){
            // 所有人
            $message = array(
//                "touser" => "@all",
                "msgtype" => 'news',
                "agentid" =>1000009,
                "news" => $send,
            );
        }else{
            // 指定对象  标签
            $message = array(
//                "totag" => 1,
                "msgtype" => 'news',
                "agentid" =>1000009,
                "news" => $send,
                "safe" => "0"
            );
        }
        //发送给企业号
        $Wechat = new TPQYWechat(Config::get('notice'));
        $msg = $Wechat->sendMessage($message);
        
        if($msg['errcode'] === 0){
            //保存到推送列表
            $result = NoticeModel::where('id',$id)->update(['push' => 1]);
            if($result){
                return $this->success('发送成功');
            }else{
                $this->error('发送失败');
            }
        }else{
            $this->error($Wechat->errMsg);
        }
    }

}