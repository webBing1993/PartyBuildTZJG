<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
use app\home\model\Comment;
use app\home\model\Like;
use app\home\model\Picture;
use app\home\model\WechatUser;
use app\home\model\Volunteer as VolunteerModel;

class Volunteer extends Base{
    /* 志愿服务 */
    public function index(){
        $Model = new VolunteerModel;
        if(IS_POST) {
            $data = input('post.');
            $res = $Model->getListMore($data['length'],$data['type']);
            if($res) {
                return $this->success("加载成功","",$res);
            }else {
                return $this->error("加载失败");
            }
        }else {
            $list = $Model->getIndex();
            $this->assign('list',$list);
            return $this->fetch();
        }
    }

    /**
     * 发布
     */
    public function publish(){
        if(IS_POST) {
            $data = input('post.');
            $Model = new VolunteerModel();
            $user = WechatUser::where('userid',$data['userid'])->find();
            $data['publisher'] = $user['name'];
            $data['front_cover'] = $this->default_pic(); //生成随机封面
            isset($data["list_images"]) ? $data["list_images"] = json_encode($data["list_images"]) : $data["list_images"] = "";
            $res = $Model->create($data);
            if($res) {
                return $this->success("添加成功");
            }else {
                return $this->error("添加失败");
            }
        }else {
            $userId = session("userId");
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
        $Model = new VolunteerModel();
        $id = input('id');
        $userId = session('userId');
        //浏览加一
        $Model::where('id',$id)->setInc('views');

        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(8,$userId,$id);
        }
        //详细信息
        $info = $Model::get($id);
        //分享图片及链接及描述
        $image = Picture::where('id',$info['front_cover'])->find();
        $info['share_image'] = "http://".$_SERVER['SERVER_NAME'].$image['path'];
        $info['link'] = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'];
        $info['desc'] = str_replace('&nbsp;','',strip_tags($info['content']));

        //获取 文章点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(8,$id,$userId);
        $info['is_like'] = $like;
        $this->assign('detail',$info);

        //获取 评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(8,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * 模版2
     */
    public function detail2() {
        $this->anonymous();
        $this->jssdk();
        $Model = new VolunteerModel();
        $id = input('id');
        $userId = session('userId');
        //浏览加一
        $Model::where('id',$id)->setInc('views');

        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(8,$userId,$id);
        }
        //详细信息
        $info = $Model::get($id);
        $info['images'] = json_decode($info['list_images']);

        //分享图片及链接及描述
        $image = Picture::where('id',$info['front_cover'])->find();
        $info['share_image'] = "http://".$_SERVER['SERVER_NAME'].$image['path'];
        $info['link'] = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'];
        $info['desc'] = str_replace('&nbsp;','',strip_tags($info['content']));

        //获取 文章点赞
        $likeModel = new Like();
        $like = $likeModel->getLike(8,$id,$userId);
        $info['is_like'] = $like;
        $this->assign('detail',$info);

        //获取 评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(8,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

}