<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
use app\home\model\WechatDepartment;
use app\home\model\WechatUser;

class Statistics extends Base{
    /*
     * 数据统计
     */
    public function index(){
        //获取关注比例
        $sum = WechatDepartment::count();     //总部门
        $User = WechatUser::where('status',1)->select();
        $arr = array();
        foreach($User as $value){
            $Depart = json_decode($value['department']);
            if (is_array($Depart)){
                $depart = $Depart[0];  // 两个部门 只获取第一个
            }else{
                $depart = $Depart;
            }
            $res = WechatDepartment::where('id',$depart)->find();
            if (!empty($res) && !in_array($depart,$arr)){
                array_push($arr,$depart);
            }
        }
        $num1 = count($arr);
        $num2 = $sum - $num1;
        $msg = array(
            'sum' => $sum,  // 总
            'num_is' => $num1,  // 关注
            'num_not' => $num2   // 未关注
        );
        $this->assign('msg',$msg);
        return $this->fetch();
    }
}
