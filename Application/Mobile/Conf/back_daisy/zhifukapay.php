<?php
/**
 * Created by PhpStorm.
 * User: burn
 * Date: 2017/4/1
 * Time: 下午3:38
 */


$zhifukapay['appid']     = '155022'; //公众账号

$zhifukapay['paykey']    = '2364400b4f34feef9cb9b11b955e4d71';

$zhifukapay['cardno']    = '32';//固定值32为（微信）  36为（手机QQ）

$zhifukapay['body']      = '郑州心动文化传媒有限公司'; //商品描述

$zhifukapay['noticeurl'] = 'http://m.kyread.com/User/zhifukaReturn.html'; //通知地址

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$zhifukapay['paylimit']  = array('50000' => '300', '15000' => '100', '7000' => '50', '4000' => '30', '2000'=>'20');

$zhifukapay['moneytype'] = 0;

$zhifukapay['paysilver'] = 0;

return array('ZHIFUKA_PAY_CONF' => $zhifukapay);

?>