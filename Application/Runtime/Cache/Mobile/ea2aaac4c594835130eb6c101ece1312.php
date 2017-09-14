<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html >
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="applicable-device" content="mobile">
		<link rel="shortcut icon" href="/Public/images/favicon.ico" />
		<title><?php echo ($site_title); ?></title>
		<link rel="stylesheet" href="/Template/mobile/default/Static/css/public.css">
		<link rel="stylesheet" href="/Template/mobile/default/Static/css/index.css">
		<link rel="stylesheet" href="/Template/mobile/default/Static/css/user.css">
		<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/modernizr.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/layer.js" ></script>
		<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js" ></script>
		<script>
			function listenBack() {
				if (window.history.length <= 2) {
					window.history.replaceState("wx-back", null,"");
					window.history.pushState("", null,"");
				}
//				else{
//					window.history.replaceState("", null,"");
//				}
				window.onpopstate=function(){
//				alert("location: " + document.location + ", state: " + JSON.stringify(history.state));
					if (!!(<?php echo ($isweixin); ?>&&history.state=="wx-back")) {
						WeixinJSBridge.call('closeWindow');
					}
				}
			}
//			window.onload=function(){
//				if (window.history.length <= 2) {
//					window.history.replaceState("wx-back", null,"");
//					window.history.pushState("", null,"");
//				}
//				else{
//					window.history.replaceState("", null,"");
//				}
//				window.onpopstate=function(){
////				alert("location: " + document.location + ", state: " + JSON.stringify(history.state));
//					if (!!(<?php echo ($isweixin); ?>&&history.state=="wx-back")) {
//						WeixinJSBridge.call('closeWindow');
//					}
//				}
//				alert("location: " + document.location + ", state: " + JSON.stringify(history.state));
//			}
		</script>
	</head>

<body>
<div class="tab_nav">
    <div class="header">
        <div class="h-left">
            <a class="sb-back" href="<?php echo U('User/buyEgold');?>" title="返回" target="_self"></a>
        </div>

        <div class="h-mid" style="width: 80%;float: left">
                <span >
                    微信扫码支付
                </span>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/Template/mobile/default/Static/css/weixinpay.css">
<div class="weixinpayinfo" style="margin-top: 60px;">
    <h3 align="center">
        <strong  style="color:#00CC66 ">
            微信支付:<strong style="color:red"><?php echo ($total_fee); ?>元 </strong>(微信内长按二维码可支付)
        </strong>
    </h3>


    <input type="hidden" name="out_trade_no" id="out_trade_no" value="<?php echo ($out_trade_no); ?>" />

</div>
</div>
<div align="center" style="padding-left:25px;background: url('/Template/mobile/default/Static/images/zhiwen.gif') no-repeat center right;background-size: auto 100%;padding-bottom: 8%;margin: 0 20px;">
    <img alt="模式二扫码支付" src="<?php echo ($qrcimgurl); ?>" style="width: 45%;padding-right: 55%;display: block;box-sizing: content-box;"/>
</div>
<div style="width: auto;background: #fdf8e5;margin: 10px 20px;border-radius: 0 0 8px 8px;padding: 6px;">
    <strong style="color:#c00;font-size:16px;">提示</strong>
    <p style="color:#c90;font-size:13px;">1.如果您当前在微信中阅读，直接长按指纹即可付款；</p>
    <p style="color:#c90;font-size:13px;">2.如果您使用的是浏览器,先保存二维码图片（截图或直接保存），然后使用微信扫一扫功能-相册-扫描二维码付款。</p>
</div>
<script>

    //设置每隔1000毫秒执行一次load() 方法
    var myIntval = setInterval(function(){load()},3000);

    function load()
    {
        //document.getElementById("timer").innerHTML = parseInt(document.getElementById("timer").innerHTML) + 1;

        var xmlhttp;

        if (window.XMLHttpRequest)
        {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        }
        else
        {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function()
        {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
            {
                trade_state = xmlhttp.responseText;

                trade_state = trade_state.replace(/\ +/g,"");

                trade_state = trade_state.replace(/[\r\n]/g,"");

                trade_state = trade_state.replace(/[ ]/g,"");
                //console.log(trade_state);

                if(trade_state == 'SUCCESS')
                {
                    alert('支付成功，点击按钮跳转到首页');

                    //延迟3000毫秒执行tz() 方法
                    clearInterval(myIntval);

                    setTimeout("location.href='http://m.kyread.com/user/index.html'",0);
                }
            }
        }

        //orderquery.php 文件返回订单状态，通过订单状态确定支付状态
        xmlhttp.open("POST","http://m.kyread.com/User/tongleOrderQuery.html",false);

        //下面这句话必须有
        //把标签/值对添加到要发送的头文件。
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");

        xmlhttp.send("out_trade_no=" + $('#out_trade_no').val());
    }
</script>
</body>