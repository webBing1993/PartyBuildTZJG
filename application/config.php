<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

return [
    'url_route_on' => true,
//    'log'          => [
//        'type' => 'trace', // 支持 socket trace file
//    ],

    /* 默认模块和控制器 */
    'default_module' => 'home',

    /* URL配置 */
    'base_url'=>'',
    'parse_str'=>[
        '__ROOT__' => '/',
        '__STATIC__' => '/static',
        '__ADMIN__' => '/admin',
        '__HOME__' => '/home',
    ],

    /* UC用户中心配置 */
    'uc_auth_key' => '(.t!)=JTb_OPCkrD:-i"QEz6KLGq5glnf^[{p;je',

    /* 分页自定义 */
    'paginate' => [
        'type'     => '\org\Pager',
        'var_page' => 'page',
        'list_rows'=> 12
    ],
    
    //最新动态
    'work' => array(
        'login' => 'http://djz.0571ztnet.com/home/index/login',
        'token' => '',
        'encodingaeskey' => '',
        'appid' => '',
        'appsecret' => '',
        'agentid' => 1000002
    ),
    //中心工作
    'central' => [
        'appid' => '',
        'appsecret' => '',
        'agentid' => 1000003
    ],
    //最多跑一次
    'policy' => [
        'appid' => '',
        'appsecret' => '',
        'agentid' => 1000005
    ],
    //廉政新市
    'learn' => [
        'appid' => '',
        'appsecret' => '',
        'agentid' => 1000006
    ],
    //通讯名录
    'mail' => [
        'appid' => '',
        'appsecret' => '',
        'agentid' => 1000007
    ],
    //消息审核
    'review' => array(
        'appid' => '',
        'appsecret' => '',
        'agentid' => 1000008
    ),
    'host_url' => "http://".$_SERVER['HTTP_HOST']
];
