<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/6/14
 * Time: 16:48
 */

namespace app\home\model;


use think\Model;

class PushReview extends Model { 
    protected $insert = [
        'review_time' => NOW_TIME,
    ];
}