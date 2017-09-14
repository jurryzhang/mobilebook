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

<script type="text/javascript" src="/Template/mobile/default/Static/js/layer_mobile/layer.js" ></script>
<body>
    <div class="tab_nav">
        <div class="header">
            <div class="h-left">
                <!--<a class="sb-back"  href="javascript:void(0);" onclick="goBack()" title="返回" target="_self"></a>-->

                <a class="sb-back" href="/" title="返回" target="_self"></a>
            </div>

            <div class="h-mid" style="width: 70%;float: left">
                签到送币
            </div>
            <!--<div style="float: right;margin-top: 5px;margin-right: 7%;">
                <a href="<?php echo U('User/index');?>" title="用户" target="_self" >
                    <i class="fa fa-user" style="font-size: 30px;"></i>
                </a>
            </div>-->
        </div>
    </div>

    <div id="tbh5v0" style="margin-top:40px;background: #ffffff;height: 522px;text-align: center">
        <i class="fa  fa-gift " style="color:#CC0000;font-size: 150px;margin-top: 90px;"></i>
        <p><strong style="font-size: 18px;">签到送礼</strong></p>
        <p style="font-size: 14px;">每日签到可获得<strong style="color: red">50</strong>书币奖励</p><br/>
        <?php if($sign == 1): ?><div class="field submit-btn">
                <input type="button" id="sign" value="您今天已经签到过,明天再来吧!" onclick="signed()" class="btn_big1" />
            </div>
            <?php else: ?>
            <div class="field submit-btn">
                <input type="button"  id="sign" value="点击签到" class="btn_big1" onclick="sign(<?php echo ($uid); ?>)"/>
            </div><?php endif; ?>
    </div>

    <script type="text/javascript">

        function sign(uid)
        {
		
            $.ajax(
            {
                type     : 'get',
                url 	 : "/User/sign/id/" + uid+".html",
                dataType : 'json',
                success  : function(res)
                {
                    if(res.status == 1)
                    {
                        var content='<srtong><b style="font-size:16px;">签到成功</b></srtong><br/><div style="font-size:13px; "><p>每日首次阅读,赠送<span style="color:red">50</span>书币</p><br/><p>请明日继续签到哦~</p><br/><p>分享朋友将有更多惊喜,一般人我不告诉他呦~</p></div>'
                        $('#sign').val('您今天已经签到过,明天再来吧!');
                        open(content);
                    }
                    else
                    {
                        open('您今天已经签到过,明天再来吧!');
                    }
                },
                error : function()
                {
                    open('网络失败，请刷新页面后重试');
                }
            })
        }

        function signed() {
            open('您今天已经签到过,明天再来吧!');
        }


    </script>
    <script>
        //页面打开如果没签到打开签到弹框
        function open(content) {
                layer.open({
                    content:content,
                    btn: '<span style="color: #51C332">知道了</span>',
                    shadeClose: false,
                    style:'width:74%;padding:0px;'
                });
        }

    </script>
    <style>
        .layui-m-layercont {
            padding: 20px 30px;
            line-height: 22px;
            text-align: center;
        }
    </style>
</body>
</html>