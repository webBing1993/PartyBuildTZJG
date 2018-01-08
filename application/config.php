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
    'default_module' => 'admin',
    'app_debug' => true,

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
    
    //党建责任
    'responsibility' => array(
        'login' => 'http://www.0576ztnet.com/home/index/login',
        'token' => 'bRmYcj9jXnOTVY5t3X6gzpz3IilCo65',
        'encodingaeskey' => 'bjsJ0SS3krTw2ds3CQ4RxQ2nrHVR79sn9DuS91DuEfw',
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => 'Og0bm6Hvbhbq5-fLzVnRub1RuPsUvG5VcI6Rxxq1aqk',
        'agentid' => 1000002
    ),
    //两学一做
    'learn' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => '8mrwMFv25fHH2D8mAuwDDD62VQ5cRcY6bGR92DJSUYI',
        'agentid' => 1000003
    ],
    //组织建设
    'organization' => [
        'appid' => '79YAiGi82hqNL4W7faazTZ7vX9AmI5OWvKLEz8Elk18',
        'appsecret' => '8mrwMFv25fHH2D8mAuwDDD62VQ5cRcY6bGR92DJSUYI',
        'agentid' => 1000004
    ],
    //特色创新
    'special' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => '2sqAJY1if94DLMQJEq5hn4j0Ewaf8WcGdvfOtSQPybg',
        'agentid' => 1000005
    ],
    //志愿服务
    'volunteer' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => 'H5398NbZ7OfJNTR7X3qbslBj4DSDyRSugGOPYELzQ5c',
        'agentid' => 1000006
    ],
    //党风廉政
    'incorrupt' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => 'AseyEaENEPDzzz33YEj3SxhZvsOuGaUyKD3jkVSHqEk',
        'agentid' => 1000007
    ],
    //作风建设
    'style' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => 'K2N7ey3R2-ohVx9ooAWWE80eIJhMXS1QhTJZjJdZDYw',
        'agentid' => 1000013
    ],
    //通讯名录
    'mail' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => 'xfICmSbBsMcXBipeQaYueTPq2vNLwqqjcdxwepmkzIA',
        'agentid' => 1000008
    ],
    //通知公告
    'notice' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => 'lUNZErK1Xm0hhE7T_PZx3xLH3EBTxLn89J4kLSNqyRE',
        'agentid' => 1000009
    ],
    //数据统计
    'data' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => 'il4l6l_9QkD553x8lGW6DGXNrD6sa1Y0F7S3p0AEvOY',
        'agentid' => 1000010
    ],
    //个人中心
    'user' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => 'K6O1F5f2tGNnM19zy0fESYGqdwUZN-pico8rhS4JWJE',
        'agentid' => 1000011
    ],
    //消息审核
    'audit' => [
        'appid' => 'wwbab5777aedaa7c19',
        'appsecret' => '_zbJURRstsnvrTvIkWYGBzHsDyI2q7Wa0TOq8I_K3nE',
        'agentid' => 1000012
    ],

    'host_url' => "http://".$_SERVER['HTTP_HOST']
];
