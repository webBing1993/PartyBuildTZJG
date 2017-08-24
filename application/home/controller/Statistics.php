<?php
/**
 * Created by PhpStorm.
 * User: 老王
 * Date: 2016/11/2
 * Time: 13:21
 */
namespace app\home\controller;
use app\home\model\WechatTest;
use app\home\model\WechatUser;

class Statistics extends Base{
    /*
     * 数据统计
     */
    public function index(){
        return $this->fetch();
        return false;
        //获取男女比例
        $map['department'] = array('neq','[493]');
        $userNum = WechatUser::where($map) ->count();
        $m = WechatUser::where($map) ->where('gender','eq',1) ->count(); //男性人数
        $male = round(($m/$userNum)*100);
        $w = WechatUser::where($map) ->where('gender','eq',2) ->count(); //女性人数
        $female = round(($w/$userNum)*100);

        //获取关注人数
        $userNum = WechatUser::where($map)->count();     //总人数
        $concernNum = WechatUser::where('status',1)->where($map)->count(); //关注人数
        $nonNum = $userNum - $concernNum; //未关注人数

        //统计学历
        $edu4 = WechatUser::where('education','eq',"中专")->where($map)->count();
        $edu5 = WechatUser::where('education','eq',"大专")->where($map)->count();
        $edu6 = WechatUser::where('education','eq',"本科")->where($map)->count();
        $edu7 = WechatUser::where('education','eq',"硕士")->where($map)->count();

        //获取年龄
        $year = date('Y',time());//取得今年年份
        $age1 = WechatUser::where(['left(partytime,4)' =>['gt',$year-10]]) ->where($map)->count();//10年以下
        $age2 = WechatUser::where("left(partytime,4) > $year-20 and left(partytime,4) <= $year-10") ->where($map)->count();//10年 - 20年
        $age3 = WechatUser::where("left(partytime,4) > $year-30 and left(partytime,4) <= $year-20") ->where($map)->count();//20年 - 30年
        $age4 = WechatUser::where("left(partytime,4) > $year-40 and left(partytime,4) <= $year-30") ->where($map)->count();//30年 - 40年
        $age5 = WechatUser::where("left(partytime,4) <= $year-40") ->where($map)->count();//40年以上
        $msg = array(
            'male' => $male, //男性比例
            'female' => $female, //女性比例
            'usernum' => $userNum, //总人数
            'concernnum' => $concernNum, //关注人数
            'nonnum' => $nonNum, //未关注人数
            'edu4' => $edu4,
            'edu5' => $edu5,
            'edu6' => $edu6,
            'edu7' => $edu7,
            'agepercent1' => $age1,
            'agepercent2' => $age2,
            'agepercent3' => $age3,
            'agepercent4' => $age4,
            'agepercent5' => $age5,
        );
        return $msg;
    }
}
