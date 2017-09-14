<?php
/**
 * Created by PhpStorm.
 * User: muyi
 * Date: 2017/5/19
 * Time: 下午3:38
 *同乐支付配置文件
 */


$tonglepay['merchantID']     = '1093'; //商户号

$tonglepay['key']    = 'ce7295edbed524313f0f26dc3dd22dc5';

$tonglepay['cardType']    = '010001';// 010000:QQ钱包扫码 010001:微信扫码

$tonglepay['remarks']      = '快阅书城小说币'; //商品描述
$tonglepay['type']      = 2; //通道类型 1.跳转到同乐页面进行支付，2.同步返回xml数据(含二维码链接)，需自定义页面。默认1
$tonglepay['merURL'] = 'http://m.kyread.com/User/tongleReturn.html'; //通知地址
$tonglepay['backURL'] = 'http://m.kyread.com/User/index.html'; //支付成功跳转地址

//这个参数不设置的话，用户可以购买任意值的虚拟货币，按照一元钱100币折算。如果设置了这个参数，则购买金额只能按照里面的设置，对应的也金钱按对应关系折算，如 '1000'=>'10' 是指 1000虚拟币需要10元
$tonglepay['paylimit']  = array('50000' => '300', '15000' => '100', '7000' => '50', '4000' => '30', '2000'=>'20');

$tonglepay['moneytype'] = 0;

$tonglepay['paysilver'] = 0;

return array('TONGLE_PAY_CONF' => $tonglepay);

?>