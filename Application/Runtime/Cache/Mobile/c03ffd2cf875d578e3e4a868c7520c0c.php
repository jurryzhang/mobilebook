<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html >
<html xmlns:wb="http://open.weibo.com/wb">
	<head>
		<meta name="Generator" content="TPshop1.2" />
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width">
		<link rel="shortcut icon" href="/Public/images/favicon.ico" />
		<title><?php echo ($site_title); ?></title>
		<meta http-equiv="keywords" content="<?php echo ($tpshop_config['shop_info_store_keyword']); ?>" />
		<meta name="description" content="<?php echo ($tpshop_config['shop_info_store_desc']); ?>" />

		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
		<link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/public.css"/>
		<link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/login.css"/>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/layer.js" ></script>
		<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2673436165" type="text/javascript" charset="utf-8"></script>
	</head>
	
	<body>
		<div class='header'>
	    	<div class="h-left">
				<a class="sb-back" href="javascript:history.back(-1);" title="返回" target="_self"></a>
	    	</div>
			
			<div class="h-mid" style="width: 80%;float: left">
				用户登录
			</div>
	 	</div>
	  	
	  	<div class="denglu">
			<form action="" method="post">        	
	            <div class="Login">
					<dl>
						<dt>用户名：</dt>
						<dd>
							<input type="text" name="username" id="username" placeholder="请输入用户名" value="" />
						</dd>
					</dl>

					<dl style=" margin-top:15px;">
						<dt>密码：</dt>
						<dd>
							<input type="password" name="password" id="password" placeholder="密码"/>
						</dd>
					</dl>

					<div class="field submit-btn">
						<input type="button" class="btn_big1" onClick="checkSubmit()" value="登 录" />
						<input type="hidden" name="referurl" id="referurl" value="<?php echo ($referurl); ?>">
					</div>

	            	<div class="ng-foot">
						<div class="ng-link-area" >
							<span style=" margin-right:5px; border-right:1px solid #eeeeee">
								<a href="<?php echo U('User/reg');?>" >免费注册</a>
							</span>

							<span class="user_line"></span>

							<span >
								<a href="<?php echo U('User/forgetPwd');?>" >忘记密码？</a>
							</span>
						</div>

	              		<div class="third-area ">
							<!-- <?php if($isweixin != 1): ?><div class="third-area-a">第三方登录</div> -->
									<!--<a class="ta-qq"  href="<?php echo U('LoginApi/login',array('oauth'=>'qq'));?>" target="_self" title="qq">-->
									<!--</a>-->
									<!-- <div>
										<a href="<?php echo U('LoginApi/login',array('oauth'=>'qq'));?>" target="_self" title="qq">
											<img src="/Template/mobile/default/Static/images/bottom_img/qqlogin/Connect_logo_5.png" alt="">
										</a>
									</div><?php endif; ?> -->

								<div>
									<!--<a class="ta-weixin"  href="<?php echo U('LoginApi/login',array('oauth'=>'weixin'));?>" target="_self" title="weixin">-->
									<!--</a>-->

									<a  href="<?php echo U('LoginApi/login',array('oauth'=>'weixin'));?>" target="_self" title="weixin">
										<img src="/Template/mobile/default/Static/images/bottom_img/wx_btn.png" alt="">
									</a>

								</div>
								<!-- <div>

                                <?php if($isweixin != 1): ?><a href="<?php echo U('LoginApi/login',array('oauth'=>'weibo'));?>" target="_self" title="weibo">
										<img src="/Template/mobile/default/Static/images/bottom_img/weibo_btn.png" alt="">
									</a> -->
									<!--<div>-->
										<!--<wb:login-button type="1,2" onlogin="login" onlogout="logout">登录按钮</wb:login-button>-->
									<!--</div>-->
							<!--<?php endif; ?> -->
	              		</div>
	            	</div>
	          	</div>
			</form>
		</div>
		
		<script type="text/javascript">
			function checkSubmit()
			{
				var username = $.trim($('#username').val());
				var password = $.trim($('#password').val());
				var referurl = $('#referurl').val();
				
				if(username == '')
				{
					showErrorMsg('用户名不能为空!');
					return false;
				}
				
				if(!checkMobile(username))
				{
					showErrorMsg('账号格式不匹配!');
					return false;
				}
				
				if(password == '')
				{
					showErrorMsg('密码不能为空!');
					return false;
				}
				
				//$('#login-form').submit();
				$.ajax(
				{
					type     : 'post',
					url 	 : '/index.php?m=Mobile&c=User&a=do_login&t='+Math.random(),
					data 	 : {
									username:username,
									password:password,
									referurl:referurl
								},		
					dataType : 'json',
					success  : function(res)
					{
						if(res.status == 1)
						{
							top.location.href = res.url;
						}
						else
						{
							showErrorMsg(res.msg);
						}
					},
					error : function()
					{
						showErrorMsg('网络失败，请刷新页面后重试');
					}
				})
			}
		
		    function checkMobile(tel)
		    {
		        var reg = /(^1[3|4|5|7|8][0-9]{9}$)/;
		        
		        if (reg.test(tel))
		        {
		            return true;
		        }
		        else
		        {
		            return false;
		        };
		    }
		    
		    function showErrorMsg(msg)
		    {
		    	//$('.msg-err').show();
		    	//$('.J-errorMsg').html(msg);
		    	layer.open({content:msg,time:2});
		    }

            function toQQLogin()
            {
                //以下为按钮点击事件的逻辑。注意这里要重新打开窗口
                //否则后面跳转到QQ登录，授权页面时会直接缩小当前浏览器的窗口，而不是打开新窗口
                var A=window.open("<?php echo U('LoginApi/login',array('oauth'=>'weixin'));?>","TencentLogin",
                    "width=450,height=320,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1");
            }
		</script>
	</body>
</html>