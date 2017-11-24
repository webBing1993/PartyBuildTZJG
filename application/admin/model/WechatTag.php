<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2016/5/20
 * Time: 16:03
 */

namespace app\admin\model;


use think\Model;

class WechatTag extends Base {
    public function Wechat_user() {
        return $this->belongsToMany('WechatUser','WechatTag');
    }

    /**
     * 标签同步后生成UCenter用户
     */
    public function setUser($data) {
        $UcenterMemberModel = new UcenterMember();
        $ucenter = array(
            'username' => $data['username'],
            'password' => think_ucenter_md5($data['password'], config('uc_auth_key')),
            'email' => $data['email'],
            'reg_ip' => get_client_ip(1)
        );
        if(empty($ucenter['email'])) { unset($ucenter['email']);}
        $res = $UcenterMemberModel->where('username',$data['username'])->find();
        if(empty($res)) {
            $uid = $UcenterMemberModel->save($ucenter);
            if($uid > 0) {
                $member = array(
                    'id' => $uid,
                    'nickname' => $data['nickname']
                );
                Member::create($member);
                AuthGroupAccess::create(['uid'=>$uid, 'group_id'=>$data['group_id']]);
            }else {
                return $this->error("创建用户失败");
            }
        }
    }
}