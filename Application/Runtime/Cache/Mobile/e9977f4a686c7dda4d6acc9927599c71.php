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
                <a class="sb-back" href="<?php echo U('User/index');?>" title="返回" target="_self"></a>
            </div>

            <div class="h-mid" style="width: 80%;float: left">
                充值记录
            </div>
        </div>
    </div>

    <div id="tbh5v0">
        <!--------筛选 form 表单 开始-------------->
        <div class="order ajax_return" style="padding-top: 45px">
            <?php if(empty($list)): ?><div id="list_0_0" class="font12">暂无充值记录！</div>
            <?php else: ?>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><div class="order_list">
                        <dl>
                            <dd class="name" style="font-size: 14px;width: 70%">
                                流水账号：<?php echo ($list['payid']); ?>
                            </dd>

                            <dd class="pice" style="overflow:visible;color: #efa164;line-height: inherit">
                                <?php if($list['egold'] == 0): ?>VIP年费会员
                                    <?php else: ?>
                                    <?php echo ($list['egold']); ?>书币<?php endif; ?>
                            </dd>

                            <dd class="name" style="font-size: 14px;width: 70%">
                                <?php echo ($list['buytime']); ?>
                            </dd>

                            <dd class="pice" style="overflow:visible;line-height: inherit">
                                <?php if($list['payflag'] == 1): ?>已支付
                                <?php else: ?>
                                    未支付<?php endif; ?>
                            </dd>
                        </dl>
                    </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </div>
    </div>
</body>
</html>