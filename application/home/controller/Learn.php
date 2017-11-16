<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;

use app\home\model\Comment;
use app\home\model\Learn as LearnModel;
use app\home\model\Like;
use app\home\model\Picture;
use app\home\model\WechatUser;
use think\Db;
/**
 * Class Learn
 * @package app\home\controller
 * 两学一做
 */
class Learn extends Base{
    /* 方案部署 */
    public function program(){
        $Model = new LearnModel;
        $list = $Model->getProgram();
        $this->assign('list',$list);
        $userid = session('userId');
        $this->checkUserPower($userid);
        return $this->fetch();
    }

    /* 三会一课 */
    public function lesson(){
        $Model = new LearnModel;
        $list = $Model->getLesson();
        $this->assign('list',$list);
        $userid = session('userId');
        $this->checkUserPower($userid);
        return $this->fetch();
    }

    /* 年度计划 */
    public function plan(){
        $Model = new LearnModel;
        $list = $Model->getPlan();
        $this->assign('list',$list);
        $userid = session('userId');
        $this->checkUserPower($userid);
        return $this->fetch();
    }

    /* 主题党日 */
    public function theme(){
        $Model = new LearnModel;
        $list = $Model->getTheme();
        $this->assign('list',$list);
        $userid = session('userId');
        $this->checkUserPower($userid);
        return $this->fetch();
    }

    /**
     * @return mixed
     * 加载更多
     */
    public function listmore() {
        $Model = new LearnModel;
        $data = input('post.');
        $res = $Model->getMoreList($data['length'],$data['type'],$data['class']);
        if($res) {
            return $this->success("加载成功","",$res);
        }else {
            return $this->error("加载失败");
        }

    }
    
    /* 发布 */
    public function publish(){
        $Model = new LearnModel();
        if(IS_POST) {
            $data = input('post.');
            $user = WechatUser::where('userid',$data['userid'])->find();
            $data['publisher'] = $user['name'];
            !empty($data['time']) ? $data['time'] = strtotime($data['time']) : $data['time'] = 0;
            isset($data["list_images"]) ? $data["list_images"] = json_encode($data["list_images"]) : $data["list_images"] = "";
            if (!empty($data['id'])){
                // 修改
                $res = $Model->save($data,['id' => $data['id']]);
                if ($data['status'] == 0){
                    get_score(2,$data['id'],session('userId'));
                }
            }else{
                // 添加
                unset($data['id']);
                $data['front_cover'] = $this->default_pic(); //生成随机封面
                $res = $Model->create($data);
                if ($data['status'] == 0){  // 去审核   统计积分
                    get_score(2,$res->id,session('userId'));
                }
            }
            if($res) {
                return $this->success("操作成功");
            }else {
                return $this->error("未做修改，操作失败");
            }
        }else {
            $userId = session("userId");
            empty(input('get.id')) ? $id = 0 : $id = input('get.id');
            $msg = $Model->where('id',$id)->find();
            if(!empty($msg)){
                $msg['list_images'] = json_decode($msg['list_images']);
            }
            $this->assign('msg',$msg);
            $this->assign('userId',$userId);
            return $this->fetch();
        }
    }

    /**
     * @return mixed
     * 模版1
     */
    public function detail() {
        $this->anonymous();
        $this->jssdk();
        $Model = new LearnModel();
        $id = input('id');
        $userId = session('userId');
        //浏览加一
        $Model::where('id',$id)->setInc('views');

        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(4,$userId,$id);
        }
        //详细信息
        $info = $Model::get($id);
        if(!empty($info['list_images'])){
            $info['images'] = json_decode($info['list_images']);
        }
        //分享图片及链接及描述
        $image = Picture::where('id',$info['front_cover'])->find();
        $info['share_image'] = "http://".$_SERVER['SERVER_NAME'].$image['path'];
        $info['link'] = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'];
        $info['desc'] = str_replace('&nbsp;','',strip_tags($info['content']));

        // 获取文件
        if($info['file']) {
            $temp = json_decode($info['file']);
            $arr[] = [];
            foreach($temp as $key => $value){
                $savepath = Db::name('file')->where('id',$value)->value('savepath');
                $savename = Db::name('file')->where('id',$value)->value('savename');
                $arr[$key]['url'] = "http://".$_SERVER["SERVER_NAME"]."/uploads/download/".$savepath.$savename;
                $arr[$key]['name'] = Db::name('file')->where('id',$value)->value('name');
            }
            $info['files'] = $arr;
        }else{
            $info['files'] = '';
        }

        //获取 文章点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(4,$id,$userId);
        $info['is_like'] = $like;
        $this->assign('detail',$info);

        //获取 评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(4,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 模版2
     */
    public function detail2() {
        $this->anonymous();
        $this->jssdk();
        $Model = new LearnModel();
        $id = input('id');
        $userId = session('userId');
        //浏览加一
        $Model::where('id',$id)->setInc('views');

        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(4,$userId,$id);
        }
        //详细信息
        $info = $Model::get($id);
        $info['images'] = json_decode($info['list_images']);

        //分享图片及链接及描述
        $image = Picture::where('id',$info['front_cover'])->find();
        $info['share_image'] = "http://".$_SERVER['SERVER_NAME'].$image['path'];
        $info['link'] = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'];
        $info['desc'] = str_replace('&nbsp;','',strip_tags($info['content']));

        // 获取文件
        if($info['file']) {
            $temp = json_decode($info['file']);
            $arr[] = [];
            foreach($temp as $key => $value){
                $savepath = Db::name('file')->where('id',$value)->value('savepath');
                $savename = Db::name('file')->where('id',$value)->value('savename');
                $arr[$key]['url'] = "http://".$_SERVER["SERVER_NAME"]."/uploads/download/".$savepath.$savename;
                $arr[$key]['name'] = Db::name('file')->where('id',$value)->value('name');
            }
            $info['files'] = $arr;
        }else{
            $info['files'] = '';
        }

        //获取 文章点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(4,$id,$userId);
        $info['is_like'] = $like;
        $this->assign('detail',$info);

        //获取 评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(4,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }
    /**
     * 无 党小组 操作
     */
    public function play(){
        $userid = session('userId');
        $user = \app\home\model\WechatUserTag::where(['tagid' => 1 ,'userid' => $userid])->find();
        if ($user){
            // 是否考核人员
            $map = ['class' => 2,'type' => 9,'userid' => $userid]; // 三会一课  党小组会
            $count = \think\Db::name('score')->where($map)->whereTime('create_time','y')->count();
            if ($count < 1){
                // 可以积分
                \think\Db::name('score')->insert(['class' => 2,'type' => 9,'aid' => 0,'userid' => $userid,'score_up' => 1,'score_down' => 1,'create_time' => time()]);
                return $this->success('积分成功');
            }else{
                return $this->error('您已积分~');
            }
        }
    }
}