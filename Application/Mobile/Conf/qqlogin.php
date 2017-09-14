<?php
/**
 * Created by PhpStorm.
 * User: burn
 * Date: 2017/4/1
 * Time: 下午5:32
 */
//QQ登录接口参数设置
//未申请QQ登录接口账号的，请到 http://connect.opensns.qq.com/ 提交申请

$apiConfigs['qq']['appid']      = '101393792';  //应用ID，根据实际申请的值修改

$apiConfigs['qq']['appkey']     = 'ec829b5a25742de7b7f097993fb90c96';  //接口密钥，根据实际申请的值修改

$apiConfigs['qq']['return_url'] = "http://m.kyread.com/LoginApi/callback/oauth/qq.html";

//$apiConfigs['qq']['return_url'] = "http://m.kyread.com?c=LoginApi&a=callback&oauth=qq";

//$apiConfigs['qq']['return_url'] = "http://m.kyread.com";

//$apiConfigs['qq']['scope']      = 'get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo'; //允许授权哪些api接口，用英文逗号分隔

$apiConfigs['qq']['scope']      = 'get_user_info'; //允许授权哪些api接口，用英文逗号分隔

return array('QQ_LOGIN_CONF' => $apiConfigs['qq']);
