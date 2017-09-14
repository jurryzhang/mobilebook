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

<!--<body onpageshow="listenBack()">-->
<body>
    <div class="tab_nav">
        <div class="header">
            <div class="h-left">
                <!--<a class="sb-back"  href="javascript:void(0);" onclick="goBack()" title="返回" target="_self"></a>-->

                <a class="sb-back" href="/" title="返回" target="_self"></a>
            </div>

            <div class="h-mid" style="width: 70%;float: left">
                我的书架
            </div>
			
			<div style="float: right;margin-top: 5px;margin-right: 7%;">
                <a href="<?php echo U('User/index');?>" title="用户" target="_self" >
                   <img src="<?php echo ($faceImg); ?>" style="width: 36px;border-radius: 18px;">
                </a>
            </div>
        </div>
    </div>

    <div id="tbh5v0">
        <!--------筛选 form 表单 开始-------------->
        <div class="order ajax_return" style="padding-top: 45px">
            <?php if(empty($list)): ?><div id="list_0_0" class="font12">您的书架还是空！</div>
            <?php else: ?>
                <section class="index_floor_lou">
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$book): $mod = ($i % 2 );++$i;?><div class="floor_body"  style="border-top: 10px;">
                            <div id="scroll_promotion">
                                <ul>
                                    <li >
                                        <a href="javascript:return false;" title="1" class="flex">
                                            <div class="cover" onclick="showInfo(<?php echo ($book['caseid']); ?>)">
                                                <img  alt="<?php echo ($book["info"]["articlename"]); ?>" src="<?php echo ($book["cover"]); ?>">
                                            </div>

                                            <div class="bInfo" id="bookintro">
                                                <h4 class="bookname" onclick="showInfo(<?php echo ($book['caseid']); ?>)">
                                                    <?php echo ($book["info"]["articlename"]); ?>
                                                </h4>

                                                <p class="bookinfo" onclick="showInfo(<?php echo ($book['caseid']); ?>)">
                                                    <?php echo ($book["info"]["intro"]); ?>
                                                </p>

                                                <p class="bookauthor" >
                                                    <!-- <span style="font-size: 12px" onclick="showInfo(<?php echo ($book['caseid']); ?>)">
                                                       作者：<?php echo ($book["info"]["author"]); ?>
                                                    </span> -->

                                                    <span class="booksize1" id="delbookcase" onclick="delBookCase(<?php echo ($book['caseid']); ?>)">
                                                        取消收藏
                                                    </span>

                                                    <span class="booksort1" id="showinfo" onclick="showInfo(<?php echo ($book['caseid']); ?>)">
                                                        继续阅读
                                                    </span>
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </section><?php endif; ?>
        </div>
    </div>

    <!-- <a href="javascript:goTop();" class="gotop">
        <img src="/Template/mobile/default/Static/images/topup.png">
    </a> -->

    <script type="text/javascript">
        function delBookCase(caseID)
        {
            $.ajax(
            {
                type     : 'get',
                url 	 : "/index.php?m=Mobile&c=User&a=delBookFromBookCase&id=" + caseID,
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
                error : function(jqXHR)
                {
                    showErrorMsg('网络失败，请刷新页面后重试');
                }
            })
        }

        function showInfo(bookcaseID)
        {
            window.location.href = "/Article/readChapterFromBookCase/bookcase_id/" + bookcaseID + ".html";
        }

        $(function()
        {
            width = document.documentElement.clientWidth - 112;

            width = 0.9 * width;

            $('.bookleft').css({width:width});
        });

        function goTop()
        {
            $('html,body').animate({'scrollTop':0},600);
        }
    </script>
</body>
</html>