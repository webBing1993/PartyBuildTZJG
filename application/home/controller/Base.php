<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/4/21
 * Time: 15:58
 */

namespace app\home\controller;

use app\home\model\Browse;
use app\home\model\Comment;
use app\home\model\Like;
use app\home\model\WechatUserTag;
use app\user\model\WechatUser;
use think\Config;
use think\Controller;
use com\wechat\TPQYWechat;
use app\home\model\Picture;
use think\Db;

class Base extends Controller {
    public function _initialize(){
//        session('userId','ZhangNeng');
//        session('userId','13968699567');
        session('userId','13586095588');

//        session('header','/home/images/vistor.jpg');
//        session('nickname','游客');
//        session('requestUri', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
        $userId = session('userId');

        if(Config::get('WEB_SITE_CLOSE')){
            return $this->error('站点维护中，请稍后访问~');
        }

        //如果是游客的话默认userid为visitors
        if($userId == 'visitor'){
            session('nickname','游客');
            session('header','/home/images/vistor.jpg');
        }else{
            //微信认证
            $Wechat = new TPQYWechat(Config::get('responsibility'));
            // 1用户认证是否登陆
            if(empty($userId)) {
                $redirect_uri = Config::get("responsibility.login");
                $url = $Wechat->getOauthRedirect($redirect_uri);
                $this->redirect($url);
            }

            // 2获取jsapi_ticket
            $jsApiTicket = session('jsapiticket');
            if(empty($jsApiTicket) || $jsApiTicket=='') {
                session('jsapiticket', $Wechat->getJsTicket()); // 官方7200,设置7000防止误差
            }
        }
    }
    
    /**
     * 微信官方认证URL
     */
    public function oauth(){
        $Wechat = new TPQYWechat(Config::get('responsibility'));
        $Wechat->valid();
    }

    /**
     * 判断是否是游客登录
     */
    public function anonymous() {
        $userId = session('userId');
        //如果userId等于visitor  则为游客登录，否则则正常显示
        if($userId == 'visitor'){
            $this->assign('visit', 1);
        }else{
            $this->assign('visit', 0);
        }
    }

    /**
     * 获取企业号签名
     */
    public function jssdk(){
        $Wechat = new TPQYWechat(Config::get('responsibility'));
        $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $jsSign = $Wechat->getJsSign($url);
        $this->assign("jsSign", $jsSign);
    }

    /**
     * 默认图片
     * $front_pic 封面图片id：1-10
     * $list_pic 列表图片id：35-44
     * $carousel_pic 轮播图片id: 45-54
     */
    public function default_pic(){
        //随机封面图
        $a = array('1'=>'a','2'=>'b','3'=>'c','4'=>'d','5'=>'e','6'=>'f','7'=>'g','8'=>'h','9'=>'i','10'=>'j','11'=>'k','12'=>'l','13'=>'m','14'=>'n','15'=>'o');
        $front_pic = array_rand($a,1);
        return $front_pic;
    }

    /**
     * 点赞，$type,$aid
     * type值：
     * 0 评论点赞
     * 1 notice
     * 2 news
     * 3 responsibility
     * 4 learn   
     * 5
     * 6
     * 7
     */
    public function like(){
        $uid = session('userId'); //点赞人
        $type = input('type'); //获取点赞类型
        $aid = input('aid');
        switch ($type) {    //根据类别获取表明
            case 0:
                $table = "comment";
                break;
            case 1:
                $table = "notice";
                break;
            case 2:
                $table = "news";
                break;
            case 3:
                $table = "responsibility";
                break;
            case 4:
                $table = "learn";
                break;
            case 5:
                $table = "organization";
                break;
            case 6:
                $table = "special";
                break;
            case 7:
                $table = "style";
                break;
            case 8:
                $table = "volunteer";
                break;
            case 9:
                $table = "incorrupt";
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
        $data = array(
            'type' => $type,
            'table' => $table,
            'aid' => $aid,
            'uid' => $uid,
        );
        $likeModel = new Like();
        $like = $likeModel->where($data)->find();
        if(empty($like)) {  //点赞
            $res = $likeModel->create($data);
            if($res) {
                //点赞成功积分+1
                WechatUser::where('userid',$uid)->setInc('score',1);
                //更新数据
                Db::name($table)->where('id',$aid)->setInc('likes');
                return $this->success("点赞成功");
            }else {
                return $this->error("点赞失败");
            }
        }else { //取消
            $result = $likeModel::where($data)->delete();
            if($result) {
                //取消成功积分-1
                WechatUser::where('userid',$uid)->setDec('score',1);
                Db::name($table)->where('id',$aid)->setDec('likes');
                return $this->success("取消成功");
            }else {
                return $this->error("取消失败");
            }
        }
    }

    /**
     * 评论，$type,$aid,$content
     * type值：
     * 1 notice
     * 2 news
     * 3 responsibility
     * 4 learn
     * 5
     * 6
     */
    public function comment(){
        if(IS_POST){
            $uid = session('userId');
            $type = input('type');
            $aid = input('aid');
            switch ($type) {    //根据类别获取表明
                case 1:
                    $table = "notice";
                    break;
                case 2:
                    $table = "news";
                    break;
                case 3:
                    $table = "responsibility";
                    break;
                case 4:
                    $table = "learn";
                    break;
                case 5:
                    $table = "organization";
                    break;
                case 6:
                    $table = "special";
                    break;
                case 7:
                    $table = "style";
                    break;
                case 8:
                    $table = "volunteer";
                    break;
                case 9:
                    $table = "incorrupt";
                    break;
                default:
                    return $this->error("无该数据表");
                    break;
            }
            $commentModel = new Comment();
            $data = array(
                'content' => input('content'),
                'type' => $type,
                'aid' => $aid,
                'uid' => $uid,
                'table' => $table,
            );
            $res = $commentModel->create($data);
            if($res) {  //返回comment数组
                //评论成功增加1分
                WechatUser::where('userid',$uid)->setInc('score',1);
                //更新主表数据
                $map['id'] = $res['aid'];   //文章id
                $model = Db::name($table)->where($map)->setInc('comments');
                if($model) {
                    $user = WechatUser::where('userid',$uid)->find();    //获取用户头像和昵称
                    $nickname = ($user['nickname']) ? $user['nickname'] : $user['name'];
                    $header =  ($user['header']) ? $user['header'] : $user['avatar'];
                    //返回用户数据
                    $jsonData = array(
                        'id' => $res['id'],
                        'time' => date("Y-m-d",time()),
                        'content' => input('content'),
                        'nickname' => $nickname,
                        'header' => $header,
                        'type' => $type,
                        'create_time' => time(),
                        'likes' => 0,
                        'status' => 1,
                    );
                    return $this->success("评论成功","",$jsonData);
                }else {
                    return $this->error("评论失败");
                }
            }else {
                return $this->error($commentModel->getError());
            }
        }
    }

    /**
     * 加载更多评论
     */
    public function morecomment(){
        $uid = session('userId');
        $len = input('length');
        $map = array(
            'type' => input('type'),
            'aid' => input('aid'),
        );
        //敏感词屏蔽
        $badword = array(
            '法轮功','法轮','FLG','六四','6.4','flg'
        );
        $badword1 = array_combine($badword,array_fill(0,count($badword),'***'));
        $comment = Comment::where($map)->order('likes desc,create_time desc')->limit($len,7)->select();
        if($comment) {
            foreach ($comment as $value) {
                $user = WechatUser::where('userid',$value['uid'])->find();
                $value['nickname'] = ($user['nickname']) ? $user['nickname'] : $user['name'];
                $value['header'] =  ($user['header']) ? $user['header'] : $user['avatar'];
                $value['time'] = date('Y-m-d',$value['create_time']);
                $value['content'] = strtr($value['content'], $badword1);
                $map1 = array(
                    'type' => 0,
                    'aid' => $value['id'],
                    'uid' => $uid,
                    'status' => 0,
                );
                $like = Like::where($map1)->find();
                ($like) ? $value['is_like'] = 1 : $value['is_like'] = 0;
            }
            return $this->success("加载成功","",$comment);
        }else{
            return $this->error("没有更多");
        }

    }

    /**
     * 浏览记录
     */
    public function browser($type,$uid,$aid) {
        switch ($type) {    //根据类别获取表明
            case 1:
                $table = "notice";
                break;
            case 2:
                $table = "news";
                break;
            case 3:
                $table = "responsibility";
                break;
            case 4:
                $table = "learn";
                break;
            case 5:
                $table = "organization";
                break;
            case 6:
                $table = "special";
                break;
            case 7:
                $table = "style";
                break;
            case 8:
                $table = "volunteer";
                break;
            case 9:
                $table = "incorrupt";
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
        $data = array(
            'type' => $type,
            'uid' => $uid,
            'aid' => $aid,
            'table' => $table
        );
        $browserModel = new Browse();
        $history = $browserModel->where($data)->find();

        if(!$history && $aid != 0){
            $s['score'] = array('exp','`score`+1');
            $browserModel->create($data);
            WechatUser::where('userid',$uid)->update($s);
        }
    }
    /**
     * 获取数据详情 ，$type,$id
     * type值：
     * 1 notice 通知公告
     * 2 news   党建动态
     * 3
     * 4
     */
    public function content($type,$id){
        $userId = session('userId');
        switch ($type) {    //根据类别获取表明
            case 1:
                $table = "notice";
                break;
            case 2:
                $table = "news";
                break;
            case 3:
                $table = "";
                break;
            case 4:
                $table = "";
                break;
            case 5:
                $table = "";
                break;
            case 6:
                $table = "";
                break;
            default:
                return $this->error("无该数据表");
                break;
        }
        //活动基本信息
        $list = Db::name($table)->find(['id' => $id]);
        if (empty($list)){
            $this ->error('该内容不存在或已删除!');
        }
        //浏览加一
        Db::name($table)->where('id',$id)->setInc('views');
        if($userId != "visitor"){
            $this->browser($type,$userId,$id);
        }
        $list['user'] = $userId;
        //分享图片及链接及描述
        if (isset($list['front_cover'])){ // 封面图
            if (empty($list['front_cover'])){
                $list['share_image'] = '/home/images/common/3.png';  // 默认
            }else{
                $image = Picture::where('id',$list['front_cover'])->find();
                $list['share_image'] = "http://".$_SERVER['SERVER_NAME'].$image['path'];
            }
        }else{
            $list['share_image'] = '/home/images/common/3.png';  // 默认
        }
        if (isset($list['description'])){
            if (empty($list['description'])){
                $list['desc'] = str_replace('&nbsp;','',strip_tags($list['content']));
            }else{
                $list['desc'] = $list['description'];
            }
        }else{
            $list['desc'] = str_replace('&nbsp;','',strip_tags($list['content']));
        }
        $list['link'] = Config::get('host_url').$_SERVER['REDIRECT_URL'];
        // 获取文件
        if($list['file']) {
            $temp = json_decode($list['file']);
            $arr[] = [];
            foreach($temp as $key => $value){
                $savepath = Db::name('file')->where('id',$value)->value('savepath');
                $savename = Db::name('file')->where('id',$value)->value('savename');
                $arr[$key]['url'] = "http://".$_SERVER["SERVER_NAME"]."/uploads/download/".$savepath.$savename;
                $arr[$key]['name'] = Db::name('file')->where('id',$value)->value('name');
            }
            $list['files'] = $arr;
        }else{
            $list['files'] = '';
        }
        //获取 文章点赞
        $likeModel = new Like;
        $like = $likeModel->getLike($type,$id,$userId);
        $list['is_like'] = $like;
        //获取 评论
        $commentModel = new Comment();
        $comment = $commentModel->getComment($type,$id,$userId);
        $list['comment'] = $comment;
        return $list;
    }

    /**
     * 检查用户是否具备发布权限
     * 1具备发布，0隐藏菜单
     */
    public function checkUserPower($userid) {
        $map = array(
            'userid' => $userid
        );
        $res = WechatUserTag::where($map)->find();
        if($res) {
            return $this->assign('pub',1);
        }else {
            return $this->assign('pub',0);
        }
    }
}