<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/29
 * Time: 10:32
 */

namespace app\admin\model;


class Volunteer extends Base {
    protected  $insert = [
        'create_time' => NOW_TIME,
        'status' => 0,
    ];
}