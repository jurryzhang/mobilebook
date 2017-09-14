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
            <a class="sb-back" href="/" title="返回" target="_self"></a>
        </div>
		<div class="h-mid" style="width: 70%;float: left">
			特惠充值(VIP专享)
		</div>
        <div style="float: right;margin-top: 5px;margin-right: 7%;">
            <a href="<?php echo U('User/Index');?>" title="用户" target="_self" >
                  <img src="<?php echo ($faceImg); ?>" style="width: 36px;border-radius: 18px;">
            </a>
        </div>
    </div>
</div>


<div id="tbh5v0" style="margin-top: 45px">
    <div class="Personal">
        <div id="tbh5v0">
            <div class="innercontent1" >
                <div style="margin:0 2.5%;color:#FF6600; border-bottom: 1px solid #ddd;font-size: 18px;"><?php echo ($promotion['desc']); ?>
                    <p style="font-size: 13px;">活动名额仅剩237名,请抓紧时间充值!</p>
                </div>
                <div style="font-size: 16px;margin-left: 2.5%" >
                    <span>您的余额:<span style="color: #FF6600;" ><?php echo ($egold); ?></span>书币</span>
                </div>
               
                    <form method="post" action="<?php echo U('User/pay');?>" id="pay_info" >
                     <div  <?php if($isweixin != 1) echo 'style="border-bottom:0"'; ?> >
                        <div class="payetype1 curpayetype1" onclick="javascript:void(0);" id="weixinpay">
                            <!--<input type="radio" name="paytype" value ="weixinpay" checked="true">-->
                            <img style="height:20px;width: 20px;" src="/Template/mobile/default/Static/images/bottom_img/pay_weixin@3x.png"/>
                            <span>
                                    &nbsp;微信支付
                            </span>
                            <!--</input>-->

                            <?php if($isweixin == 1): ?><input type="hidden" name="code" value ="<?php echo ($code); ?>" ><?php endif; ?>
                        </div>

                        <?php if($isweixin != 1): ?><div class="payetype1" onclick="javascript:void(0);" id="alipay">
                                <!--<input type="radio" name="paytype" value ="alipay">-->
                                <img style="height:20px;width: 20px;"  src="/Template/mobile/default/Static/images/bottom_img/pay_zhifubao@3x.png"/>
                                <span>
                                    &nbsp;支付宝</span>
                                <!--</input>-->
                            </div><?php endif; ?>

                            <div class="payetype1" onclick="javascript:void(0);" id="weixintonglepay">
                                <!--<input type="radio" name="paytype" value ="weixinapppay">-->
                                <img style="height:20px;width: 20px;"  src="/Template/mobile/default/Static/images/bottom_img/pay_weixin@3x.png"/>
                                <span>
                                &nbsp;微信扫码</span>
                                <!--</input>-->
                            </div>

                    </div>

                    <input type="hidden" name="paytype" value ="weixinpay" id="paytype">

                    <div style="width:100%;border-bottom:1px solid #dddddd;float: left"></div>


                    <ul class="mainmenu">
                        <div style="display: inline-block;font-size: 16px;margin-top: 10px; width:70%">
                            <p style="margin-left: 2.5%">选择充值金额:<span style="color: red">(1元=100书币)</span></p>
                        </div>

                        <li class="current" onclick="javascript:void(0);" id="<?php echo ($promotion['paymoney']*100+$promotion['givemoney']*100); ?>" style="width:95%" >
                            <a style="position: relative;">
                                <p style="font-size: 20px;color:#000000;"><?php echo ($promotion['paymoney']); ?>元</p>
                                <p style="font-size: 12px;color:#000000;margin-top:-8px;margin-bottom:-8px;">
                                    <?php echo ($promotion['paymoney']*100); ?>+<?php echo ($promotion['givemoney']*100); ?>币
                                </p>
                                <span style="">多送<?php echo ($promotion['givemoney']); ?>元</span>
                            </a>
                        </li>

                    </ul>

                    <input type="hidden" name="egold" value="<?php echo ($promotion['paymoney']*100+$promotion['givemoney']*100); ?>" id="egold">

                    <?php if($isweixin != 1): ?><div class="field submit-btn">
                            <input type="submit" value="确认支付" class="btn_big1" />
                        </div>
                    <?php else: ?>
                        <div class="field submit-btn">
                            <input type="button" value="确认支付" class="btn_big1" onclick="weixinAppPay()"/>
                        </div><?php endif; ?>
                </form>

            <span id="test"></span>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var appId     = '';
    var timeStamp = '';
    var nonceStr  = '';
    var package   = '';
    var signType  = '';
    var paySign   = '';
    var orderID   = '';

    weixin = <?php echo ($isweixin); ?>;



    function onBridgeReady()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            {
                "appId"     : appId,     //公众号名称，由商户传入
                "timeStamp" : timeStamp,         //时间戳，自1970年以来的秒数
                "nonceStr"  : nonceStr, //随机串
                "package"   : package,
                "signType"  : "MD5",         //微信签名方式：
                "paySign"   : paySign //微信签名
            },
            function(res)
            {			
                if(res.err_msg == "get_brand_wcpay_request:ok" )
                {
                    window.location.href = 'http://m.kyread.com/User/index.html';
                }
                // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
            }
        );
    }


function weixinAppPay() {
    if( $('#paytype').val()=='weixintonglepay'){	
        window.location.href="/User/tonglepay/egold/"+ $('#egold').val()+'.html';
    }else{
        weixinJsPay()
    }
}
 

 function weixinJsPay()
    {
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

                eval("var res =" + trade_state);

//                alert('res.status = --' + res.status + '-');

                if(res.status == 1)
                {
				
                    appId     = res.data.appId;
                    timeStamp = res.data.timeStamp;
                    nonceStr  = res.data.nonceStr;
                    package   = res.data.package;
                    paySign   = res.data.paySign;

                    orderID   = res.data.orderID;

                    document.getElementById('test').innerHTML = '';

//                    onBridgeReady();

//                    document.addEventListener('WeixinJSBridgeReady', function onpay() {
//                        onBridgeReady();
//                        // 通过下面这个API隐藏右上角按钮
//                        //WeixinJSBridge.call('hideOptionMenu');
//                        // 发送给好友
//
//                    }, false);

                    if (typeof WeixinJSBridge == "undefined") {
                        if (document.addEventListener) {
                            document.addEventListener('WeixinJSBridgeReady', onBridgeReady,
                                false);
                        } else if (document.attachEvent) {
                            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
                            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
                        }
                    } else {
                        onBridgeReady();
                    }
                }
                else if(res.status == -1)
                {
				 
				//微信支付失败调用同乐扫码支付
                    document.getElementById('test').innerHTML = 'false pay';
                    //location.href = "<?php echo U('User/Index');?>" + Math.random();
					window.location.href="/User/tonglepay/egold/"+ $('#egold').val()+'.html';

                }
            }
        }

        //文件返回订单状态，通过订单状态确定支付状态
//        setTimeout(xmlhttp.open("POST","http://m.kyread.com/User/pay.html",false),2000);

        xmlhttp.open("POST","http://m.kyread.com/User/pay.html",false);

        //下面这句话必须有
        //把标签/值对添加到要发送的头文件。
        xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");

        xmlhttp.send($('#pay_info').serialize());
    }


//    王亚飞添加，2017-04-25
    $("li").click(function ()
    {

        value = this.id;

        if(value)
        {
            $('#egold').val(value);
        }

        $(this).addClass('current').siblings().removeClass('current');

//        $(this).find("input").checked();
    });

    $("div").click(function ()
    {
        value = this.id;

        if(value == 'alipay' || value == 'weixinpay' || value == 'weixintonglepay')
        {
            $('#paytype').val(value);

//            $(this).removeClass('payetype1');

            $(this).addClass('curpayetype1').siblings().removeClass('curpayetype1');
        }
    });

    //2017-04-26，burn添加

</script>

</body>
</html>