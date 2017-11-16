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
use think\Db;
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
            $userid = session('userId');
            $this->checkUserPower($userid);
            return $this->fetch();
        }
    }

    /**
     * 发布
     */
    public function publish(){
        $Model = new VolunteerModel();
        if(IS_POST) {
            $data = input('post.');
            $user = WechatUser::where('userid',$data['userid'])->find();
            $data['publisher'] = $user['name'];
            isset($data["list_images"]) ? $data["list_images"] = json_encode($data["list_images"]) : $data["list_images"] = "";
            if (!empty($data['id'])){
                // 修改
                $res = $Model->save($data,['id' => $data['id']]);
                if ($data['status'] == 0){
                    get_score(6,$data['id'],session('userId'));
                }
            }else{
                // 添加
                unset($data['id']);
                $data['front_cover'] = $this->default_pic(); //生成随机封面
                $res = $Model->create($data);
                if ($data['status'] == 0){  // 去审核   统计积分
                    get_score(6,$res->id,session('userId'));
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