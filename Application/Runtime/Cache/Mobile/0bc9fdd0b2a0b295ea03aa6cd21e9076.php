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

    <div id="tbh5v0" style="margin-top: 45px">
        <div class="Personal">
            <div id="tbh5v0">
                <div class="innercontent1" >
                    <form method="post" action="" id="edit_profile"  onSubmit="return checkinfo()">
                        <div class="name1">
                            <span>性　 别</span>

                            <ul>
                                <?php if($sex == 0): ?><li class='on'>
                                        <?php else: ?>
                                    <li><?php endif; ?>
                                <label for="sex0">
                                    <input type="radio" name="sex" value="0" tabindex="2" class="radio" id="sex0"/>
                                    保密
                                </label>
                                </li>


                                <?php if($sex == '1'): ?><li class='on'>
                                        <?php else: ?>
                                    <li><?php endif; ?>
                                <label for="sex1">
                                    <input type="radio" name="sex" value="1"  tabindex="3" class="radio" id="sex1"/>
                                    男
                                </label>
                                </li>

                                <?php if($sex == '2'): ?><li class='on'>
                                        <?php else: ?>
                                    <li><?php endif; ?>
                                <label for="sex2">
                                    <input type="radio" name="sex" value="2"  tabindex="4" class="radio" id="sex2" />
                                    女
                                </label>
                                </li>
                            </ul>
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
    </div>

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

        function checkinfo()
        {
            var nickname = $('#nickname').val();

            if(nickname=='')
            {
                alert("昵称不能为空");
                return false;
            }

            return true;
        }
    </script>
</body>
</html>