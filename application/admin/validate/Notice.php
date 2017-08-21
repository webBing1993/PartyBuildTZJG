<?php

/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/8/21
 * Time: 13:37
 */
namespace app\admin\validate;
use think\Validate;
class Notice extends Validate
{
    protected $rule = [
        'front_cover' => 'require',
        'title' => 'require',
        'content' => 'require',
        'publisher' => 'require',
        'end_time' => "require"
    ];

    protected $message = [
        'title.require' => '标题不能为空',
        'front_cover.require' => '图片不能为空',
        'content.require' => '内容不能为空',
        'publisher.require' => '发布人不能为空',
        'end_time' => "截止时间不能为空"
    ];
}