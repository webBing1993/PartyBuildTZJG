<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/28
 * Time: 15:29
 */

namespace app\admin\model;


class Special extends Base {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 0
    ];
}