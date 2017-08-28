<?php
/**
 * Created by PhpStorm.
 * User: Lxx<779219930@qq.com>
 * Date: 2017/8/28
 * Time: 16:21
 */

namespace app\admin\model;


class Style extends Base {
    protected $insert = [
        'create_time' => NOW_TIME,
        'status' => 0
    ];
}