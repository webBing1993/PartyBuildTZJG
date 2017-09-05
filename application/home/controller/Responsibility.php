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
     */
    public function index(){
        $Model = new ResponsibilityModel();
        if(IS_POST) {
            $data = input('post.');
            $res = $Model->getListMore($data['length'],$data['type']);
            if($res) {
                return $this->success("加载成功","",$res);
            }else {
                return $this->error("加载失败");
            }
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
            $data = input('post.');
            $res = $Model->getListMore($data['length'],$data['type'],$data['class']);
            if($res) {
                return $this->success("加载成功","",$res);
            }else {
                return $this->error("加载失败");
            }
        }else {
            $type = input('type');
            $list = $Model->getBranchIndex($type);
            $this->assign('list',$list);
            $this->assign('type',$type);
            return $this->fetch();
        }
    }

    /**
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * 模版1详情
     */
    public function detail(){
        $this->anonymous();
        $this->jssdk();
        $Model = new ResponsibilityModel();
        $id = input('id');
        $userId = session('userId');
        //浏览加一
        $Model::where('id',$id)->setInc('views');

        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(3,$userId,$id);
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
        $like = $likeModel->getLike(3,$id,$userId);
        $info['is_like'] = $like;
        $this->assign('detail',$info);

        //获取 评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(3,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /**
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * 模版2详情
     */
    public function detail2(){
        $this->anonymous();
        $this->jssdk();
        $Model = new ResponsibilityModel();
        $id = input('id');
        $userId = session('userId');
        //浏览加一
        $Model::where('id',$id)->setInc('views');

        if($userId != "visitor"){
            //浏览不存在则存入pb_browse表
            $this->browser(3,$userId,$id);
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
        $like = $likeModel->getLike(3,$id,$userId);
        $info['is_like'] = $like;
        $this->assign('detail',$info);

        //获取 评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment(3,$id,$userId);
        $this->assign('comment',$comment);
        return $this->fetch();
    }

    /*党建责任发布*/
    public function publish(){
        if(IS_POST) {
            $data = input('post.');
            $Model = new ResponsibilityModel();
            $user = WechatUser::where('userid',$data['create_user'])->find();
            $data['publisher'] = $user['name'];
            $data['front_cover'] = $this->default_pic(); //生成随机封面
            $res = $Model->create($data);
            if($res) {
                return $this->success("添加成功");
            }else {
                return $this->error("添加失败");
            }
        }else {
            $userId = session('userId');
            $this->assign('userId',$userId);
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