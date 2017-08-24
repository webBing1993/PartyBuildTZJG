<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
use app\home\model\WechatUser;
use  app\home\model\Push;
use app\home\model\PushReview;
use com\wechat\TPQYWechat;
use app\home\model\Picture;
use think\Config;
/**
 * Class Review
 * @package 消息审核
 */
class Review extends Base{
    /* 发布审核 列表 */
    public function index(){
        $Push = new  Push();
        // 获取 发布审核 列表
        $list1 = $Push->get_list();
        // 获取 推送审核 列表
        $list2 = $Push->get_list(1);
        // 获取 已审核 列表
        $list3 = $Push->get_list(-1,true);
        $this->assign('list1',$list1);
        $this->assign('list2',$list2);
        $this->assign('list3',$list3);
        return $this->fetch();
    }
    /* 首页  加载更多*/
    public function listmore(){
        $Push = new  Push();
        $type = input('type');
        $len = input('length');
        switch ($type){
            case 0: // 发布审核
                $list = $Push->get_list(0,$len);
                break;
            case 1:  // 推送审核
                $list = $Push->get_list(1,$len);
                break;
            default : // 已审核
                $list = $Push->get_list(-1,$len,true);
                break;
        }
        if ($list){
            return $this->success('加载成功','',$list);
        }else{
            $this->error('加载失败');
        }
    }
    /*
     * 审核 详细页
     */
    public function detail(){
        $id = input('param.id');
        $list = Push::where(['id' => $id])->find();
        // 根据不同的值   获取相应的数据
        switch ($list['class']){
            case 1:
                // 根据 主图文id  获取详情
                break;
            case 2:
                // 根据 主图文id  获取详情
                break;
            case 3:
                // 根据 主图文id  获取详情
                break;
            default:
        }
        $this->assign('list',$list);
        return $this->fetch();
    }
    /*发布审核 */
    public function review(){
        
    }
    /*推送 审核 */
    public function push(){
        $userId = session('userId');
        $user = WechatUser::where('userid', $userId)->find();
        $username = $user['name'];
        $msg = input('post.');
        $push = Push::where('id',$msg['id'])->find();
        //新建pushreview数据
        $data1 = array(
            'push_id' => $msg['id'],
            'user_id' => $userId,
            'username' => $username,
            'status' => $msg['status'],
        );
        $this_info['status'] = $msg['status'];   //更新push表状态
        if($msg['status'] == 1 ){  // 审核通过
            $new = PushReview::create($data1);
            if($new){
                // 发送消息
                $arr1 = $push['focus_main'];    //主图文id
                !empty($push['focus_vice']) ? $arr2 = json_decode($push['focus_vice']) : $arr2 = "";    //副图文id
                //主图文信息  根据 class 的值 获取不同的数据
                switch ($push['class']){
                    case 1:
                        $res = $this->push_detail(1,$arr1,$arr2);
                        break;
                    case 2:
                        $res = $this->push_detail(2,$arr1,$arr2);
                        break;
                    case  3:
                        $res = $this->push_detail(3,$arr1,$arr2);
                        break;
                    default:
                }
                if ($res['errcode'] == 0) {
                    Push::where('id',$msg['id'])->update($this_info);    //改变推送状态
                    return $this->success("审核通过，已推送消息");
                };
            }
        }else{  //不通过
            $new = PushReview::create($data1);   //记录
            if ($new) {
                Push::where('id',$msg['id'])->update($this_info);
                return $this->error("审核不通过");
            }
        }
    }
}