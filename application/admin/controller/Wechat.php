<?php
/**
 * Created by PhpStorm.
 * User: 虚空之翼 <183700295@qq.com>
 * Date: 16/5/20
 * Time: 09:14
 */

namespace app\admin\controller;


use app\admin\model\AuthGroupAccess;
use app\admin\model\Member;
use app\admin\model\WechatDepartment;
use app\admin\model\WechatDepartmentUser;
use app\admin\model\WechatTag;
use app\admin\model\WechatUser;
use app\admin\model\WechatUserTag;
use app\user\model\UcenterMember;
use com\wechat\QYWechat;
use com\wechat\TPWechat;
use think\Config;
use think\Input;

class Wechat extends Admin
{
    public function index() {
        $xx = substr('1993.10',0,4);
        $ss = date("Y",time());
    }


    public function user() {
        $name = input('name');
        $map = ['name' => ['like', "%$name%"]];
        $list = $this->lists("WechatUser", $map);
        $department = WechatDepartment::column('id, name');

        foreach ($list as $key=>$value) {
            $departmentId = $value['department'];
            if($departmentId){
                $name = $department[$departmentId];
                $list[$key]['department'] = $name;
            }else{
                $list[$key]['department'] = "暂无";
            }
        }
        // 状态转化
        wechat_status_to_string($list);
        $this->assign('list', $list);
        $this->assign('department', $department);

        return $this->fetch();
    }


    /**
     * 同步通讯录用户
     */
    public function synchronizeUser() {
        $Wechat = new QYWechat(Config::get('mail'));
        if($Wechat->errCode != 40001) {
            return $this->error("同步出错");
        }

        /* 同步部门 */
        $list = $Wechat->getDepartment();

        /* 同步最顶级部门下面的用户 */
        foreach ($list['department'] as $key=>$value) {
            $users = $Wechat->getUserListInfo($list['department'][$key]['id']);
            foreach ($users['userlist'] as $user) {
                $user['department'] = $user['department'][0];
                $user['order'] = $user['order'][0];
                $user['extattr'] = json_encode($user['extattr']);
                if(WechatUser::get(['userid'=>$user['userid']])) {
                    WechatUser::where(['userid'=>$user['userid']])->update($user);
                } else {
                    WechatUser::create($user);
                }
            }
        }
        $data = "用户数:".count($users['userlist'])."!";

        return $this->success("同步成功", '', $data);
    }

    /**
     * 同步部门
     */
    public function synchronizeDp(){
        $Wechat = new QYWechat(Config::get('mail'));
        if($Wechat->errCode != 40001) {
            return $this->error("同步出错");
        }

        /* 同步部门 */
        $list = $Wechat->getDepartment();
        foreach ($list['department'] as $key=>$value) {
            if(WechatDepartment::get($value['id'])){
                WechatDepartment::update($value);
            } else {
                WechatDepartment::create($value);
            }
        }

        /* 同步部门-用户关系表 */
        WechatDepartmentUser::where('1=1')->delete();
        foreach ($list['department'] as $key=>$value) {
            $users = $Wechat->getUserList($value['id']);
            foreach ($users['userlist'] as $user) {
                $data = array(
                    'departmentid'=>$value['id'],
                    'userid'=>$user['userid'],
                    'order' => 0,
                );
                if(empty(WechatDepartmentUser::where($data)->find())){
                    WechatDepartmentUser::create($data);
                }
            }
        }
        $data = "同步部门数:".count($list['department'])."!";
        return $this->success("同步成功", '', $data);
    }

    /**
     * 同步标签
     */
    public function synchronizeTag(){
        ini_set('max_execution_time', '60');
        $Wechat = new QYWechat(Config::get('mail'));
        if($Wechat->errCode != 40001) {
            return $this->error("同步出错");
        }

        $TagModel = new WechatTag();
        $TagUserModel = new WechatUserTag();
        $UcenterMemberModel = new UcenterMember();
        $MemberModel = new Member();
        /* 同步标签 */
        $tags = $Wechat->getTagList();
        foreach ($tags['taglist'] as $tag) {
            if($TagModel->get(['tagid'=>$tag['tagid']])) {
                $TagModel->where(['tagid'=>$tag['tagid']])->update($tag);
            } else {
                $TagModel->create($tag);
            }
        }

        /* 同步标签-用户关系表 */
        foreach ($tags['taglist'] as $value) {
            $users = $Wechat->getTag($value['tagid']);
            foreach ($users['userlist'] as $key => $user) {
                $map1 = ['tagid' => $value['tagid'],'name'=>$user['name']];
                $map2 = ['tagid' => $value['tagid'],'userid' => $user['userid']];
                $data = ['tagid'=>$value['tagid'],'name'=>$user['name'], 'userid'=>$user['userid']];
                $res1 = $TagUserModel->where($map1)->find();
                $res2 = $TagUserModel->where($map2)->find();
                if($res1) {
                    //存在name相同，userid不同 则修改wechat_tag 和 ucenter_member
                    if($res1['userid'] != $user['userid']) {
                        $TagUserModel->save($data,['id'=>$res1['id']]);
                        $UcenterMemberModel->where('username',$res1['userid'])->update(['username'=>$user['userid']]);
                    }
                }elseif ($res2){
                    //存在userid相同，name不同 则修改wechat_tag 和 member
                    if($res2['name'] != $user['name']) {
                        $TagUserModel->save($data,['id'=>$res2['id']]);
                        $res = $UcenterMemberModel->where('username',$res2['userid'])->find();
                        $MemberModel->where('id',$res['id'])->update(['nickname' => $user['name']]);
                    }
                }else {
                    //否则新增
                    $res = WechatUserTag::create($data);
                    if($res) {
                        $map = array(
                            'username' => $user['userid'],
                            'password' => '123456',
                            'email' => '',
                            'group_id' => $value['tagid'],
                            'nickname' => $user['name']
                        );
                        $Model =  new WechatTag();
                        $Model->setUser($map);  //创建后台管理员账号及权限
                    }
                }
            }
        }
        $data = "同步标签数:".count($tags['taglist'])."!";
        return $this->success("同步成功", '', $data);
    }
    
    public function department(){
        $list = $this->lists("WechatDepartment");
        $this->assign('list', $list);

        return $this->fetch();
    }

    public function tag(){
        $list = $this->lists("WechatTag");
        $this->assign('list', $list);

        return $this->fetch();
    }


}