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
                <a class="sb-back" href="javascript:history.back(-1);" title="返回" target="_self"></a>
            </div>

            <div class="h-mid" style="width: 80%;float: left">
                个人信息修改
            </div>
        </div>
    </div>

    <div class="Personal">
        <div id="tbh5v0" style="margin-top: 45px">
            <div class="innercontent11" style="border-bottom: 0px" >
                <!--<form name="formPassword" action="<?php echo U('User/password');?>" method="post" onSubmit="return editPassword()">-->
                <form name="formPassword" action="" method="post" onSubmit="return editPassword()">
                    <div class="field_pwd">
                        <label for="password">
                            <input type="password" name="old_password" id="password" class="c-f-text" placeholder="原密码"/>
                        </label>
                    </div>

                    <div class="field_pwd">
                        <label for="new_password">
                            <input type="password" name="new_password" id="new_password" class="c-f-text" placeholder="新密码"/>
                        </label>
                    </div>

                    <div class="field_pwd">
                        <label for="comfirm_password">
                            <input type="password" name="comfirm_password" id="comfirm_password" class="c-f-text" placeholder="确认密码"/>
                        </label>
                    </div>

                    <div class="field submit-btn">
                        <input type="submit" value="确认修改" class="btn_big1" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('.name1 ul li').click(function()
        {
            $(this).find("input").attr("checked","checked");
            $('.name1 ul li').removeClass("on");
            $(this).addClass("on");
        })
    </script>

    <script language="javascript">
        $(function()
        {
            $('input[type=text],input[type=password]').bind(
                {
                    focus:function()
                    {
                        $(".global-nav").css("display",'none');
                    },
                    blur:function()
                    {
                        $(".global-nav").css("display",'flex');
                    }
                });
        })

        var old_password_empty     = "请输入您的原密码！";
        var new_password_empty     = "请输入您的新密码！";
        var confirm_password_empty = "请输入您的确认密码！";
        var both_password_error    = "您现两次输入的密码不一致！";

        /* 会员修改密码 */
        function editPassword()
        {
            var frm = document.forms['formPassword'];
            var old_password = frm.elements['old_password'].value;
            var new_password = frm.elements['new_password'].value;
            var confirm_password = frm.elements['comfirm_password'].value;

            var msg = '';

            if (old_password.length == 0)
            {
                msg += old_password_empty + '\n';
            }

            if (new_password.length == 0)
            {
                msg += new_password_empty + '\n';
            }

            if (confirm_password.length == 0)
            {
                msg += confirm_password_empty + '\n';
            }

            if (new_password.length > 0 && confirm_password.length > 0)
            {
                if (new_password != confirm_password)
                {
                    msg += both_password_error + '\n';
                }
            }

            if(msg.length > 0)
            {
                alert(msg);

                return false;
            }
            else
            {
                return true;
            }
        }
    </script>
</body>
</html>