<?php
/**
 * Created by PhpStorm.
 * User: laowang <958364865@qq.com>
 * Date: 2017/8/22
 * Time: 15:52
 */

namespace app\admin\validate;
use think\Validate;

class News extends Validate
{
    protected $rule = [
        'front_cover' => 'require',
        'title' => 'require',
        'content' => 'require',
        'publisher' => 'require'
    ];
    protected $message = [
        'title.require' => '标题不能为空',
        'front_cover.require' => '图片不能为空',
        'content.require' => '内容不能为空',
        'publisher' => '发布人不能为空'
    ];
}