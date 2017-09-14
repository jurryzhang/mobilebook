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

<body style="background-color: rgb(246,246,246);">
	<div id="tbh5v0">
		<div class="user_com">
			<div style="background: url(/Template/mobile/default/Static/images/userbg.png);height: 120px">
				<div id="scroll_promotion">
					<ul>
						<li>
							<?php if($user["faceImg"] == ''): ?><a href="<?php echo U('User/login');?>">
							<?php else: ?>
								<a href="<?php echo U('User/setUserInfo');?>" style="margin-top: 0px;height: 0px"><?php endif; ?>

							<div class="left" style="padding: 20px;font-size: 14px">
								<img style="border-radius: 50%;" width="80px" height="80px"  src="<?php echo ((isset($user["faceImg"]) && ($user["faceImg"] !== ""))?($user["faceImg"]):'/Template/mobile/default/Static/images/user68.jpg'); ?>">
							</div>

							<div class="bookleft">
								<h4 class="bookname" style="margin-top: 20px">
									<?php if($user['name'] != ''): echo ($user['name']); ?>[<?php echo ($user['uid']); ?>]
									<?php else: ?>
										点击登录<?php endif; ?>
								</h4>

								<p class="bookinfo" style="height: 20px;color: black" >
									<?php echo ($user['egold']); ?>书币
								</p>

								<p class="bookinfo" style="height: 40px;color: black"  >
									<?php if($user['isvip'] == 1): ?>VIP年费会员<br/>
										到期时间:<?php echo (date('Y-m-d H:i',$user['viptime'])); ?>
									<?php else: ?>
										普通会员<?php endif; ?>
								</p>
							</div>

							<?php if($user["faceImg"] == ''): ?></a><?php endif; ?>
						</li>
					</ul>
				</div>
			</div>
			
			
			

			<div class="Wallet">
				<?php if(($isweixin != 1)): ?><a href="<?php echo U('Mobile/User/buyEgold');?>">
						<em class="Icon Icon1"></em>
						<dl class="b">
							<dt>
								快速充值
							</dt>
							<dd style="color:#aaaaaa;">
								&nbsp;
							</dd>
						</dl>
					</a>
				<?php else: ?>
					<a href="<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>">
						<em class="Icon Icon1"></em>
						<dl class="b">
							<dt>
								快速充值
							</dt>
							<dd style="color:#aaaaaa;">
								&nbsp;
							</dd>
						</dl>
					</a><?php endif; ?>

				<a href="<?php echo U('Mobile/User/pagLog');?>">
					<em class="Icon Icon6"></em>
					<dl class="b">
						<dt>
							充值记录
						</dt>
						<dd style="color:#aaaaaa;">
							&nbsp;
						</dd>
					</dl>
				</a>

				<a href="<?php echo U('Mobile/User/getBookCase');?>" >
					<em class="Icon Icon10"></em>
					<dl class="b">
						<dt>
							我的书架
						</dt>
						<dd>
							&nbsp;
						</dd>
					</dl>
				</a>

				<a href="<?php echo U('Mobile/User/setUserInfo');?>" >
					<img style="float: left;width:28px;height: 28px; margin-top: 5px;" src="/Template/mobile/default/Static/images/bottom_img/shezhi1.png"/>

					<span style="color:#666;float: left;font-size: 16px;line-height: 45px;margin-left: 10px">设置</span>
				</a>
			</div>

			<?php if(($isweixin != 1)): ?><div class="Wallet">
					<a href="<?php echo U('User/logout');?>">
						<em class="Icon Icon8"></em>
						<dl>
							<dt>
								注销登录
							</dt>
						</dl>
					</a>
				</div><?php endif; ?>
		</div>
	</div>

	<script type="text/javascript">
        $(function()
        {
            width = document.documentElement.clientWidth - 112;

            width = 0.9 * width;

            $('.bookleft').css({width:width});
        });
	</script>

	<div style="height:50px; line-height:50px; clear: both; background-color: rgb(246,246,246)"></div>
<div class="v_nav">
	<div class="vf_nav">
		<ul>
			<li style="margin-top: 3px">
				<a href="<?php echo U('Index/index');?>">
					<?php if($type == 1): ?><img src="/Template/mobile/default/Static/images/bottom_img/jingxuan1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/jingxuan2@3x.png" alt="" style="width: 25px"><?php endif; ?>
				</a>
			</li>

			<li style="margin-top: 3px">
				<a href="<?php echo U('Index/getSortList');?>">
					<?php if($type == 2): ?><img src="/Template/mobile/default/Static/images/bottom_img/fenlei1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/fenlei2@3x.png" alt="" style="width: 25px"><?php endif; ?>
				</a>
			</li>

			<li style="margin-top: 3px">
				<a href="<?php echo U('Index/getTopicList');?>">
					<?php if($type == 3): ?><img src="/Template/mobile/default/Static/images/bottom_img/zhuanti1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/zhuanti2@3x.png" alt="" style="width: 25px"><?php endif; ?>
				</a>
			</li>

			<li style="margin-top: 3px">
				<?php if(($isweixin != 1)): ?><a href="<?php echo U('Mobile/User/buyEgold');?>">
						<?php else: ?>
						<a href="<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>"><?php endif; ?>
					<?php if($type == 4): ?><img src="/Template/mobile/default/Static/images/bottom_img/chongzhi1.png" alt="" style="width: 25px">
						<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/chongzhi2.png" alt="" style="width: 25px"><?php endif; ?>
				</a>
			</li>
		</ul>
	</div>
</div> 
</body>
</html>