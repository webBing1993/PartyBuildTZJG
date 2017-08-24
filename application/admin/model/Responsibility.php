<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/22
 * Time: 11:25
 */

namespace app\admin\model;


class Responsibility extends Base {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 0
    ];
}