<?php
/**
 * Created by PhpStorm.
 * User: burn
 * Date: 2017/4/1
 * Time: 下午5:32
 */

$apiOrder = 2; //接口序号，请勿修改
$apiName = 'weixin'; //接口名，请勿修改
$apiTitle = '微信'; //接口标题，请勿修改

$apiConfigs['weixin']               = array(); //初始化参数数组，请勿修改

//$apiConfigs['weixin']['appid']      = 'wx21a88d472446942e';  //应用ID，根据实际申请的值修改
$apiConfigs['weixin']['appid']      = 'wx9424e12b84263c52';  //应用ID，根据实际申请的值修改

//$apiConfigs['weixin']['appkey']     = 'e83827e92a5eead09d426ab9bb80999d';  //接口密钥，根据实际申请的值修改
$apiConfigs['weixin']['appkey']     = '18008cf9ba12700dccc8d4f9a72fab71';  //接口密钥，根据实际申请的值修改

$apiConfigs['weixin']['return_url'] = "http://wap.kyread.com/LoginApi/callback/oauth/weixin.html";

$apiConfigs['weixin']['scope']      = 'snsapi_login'; //允许授权哪些api接口，用英文逗号分隔

return array('WEIXIN_LOGIN_CONF' => $apiConfigs['weixin']);