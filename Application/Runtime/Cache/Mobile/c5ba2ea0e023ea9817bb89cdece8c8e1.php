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
		<style type="text/css">
	
		</style>
	</head>


    <body>
        <div id="page" class="showpage">
            <!-- <div class="header">
                <div class="h-left">
                    <a class="sb-back" href="<?php echo U('Mobile/Index/getSortList');?>" title="返回">
                    </a>
                </div>

                <div class="h-mid">
                    <?php echo ($site_title); ?>
                </div>
				
				<div style="float: right;margin-top: 5px;margin-right: 7%;">
                    <a href="/" title="主页" target="_self" >
                        <i class="fa fa-home" style="font-size: 30px;"></i>
                    </a>
                </div>
				
            </div> -->
            
<div class="v_nav">
	<div class="vf_nav">
		<ul>
			<li style="border-color: #E0E0E0; width:24%;">
				<a href="<?php echo U('Index/index');?>" style="font: normal 18px/20px  serif; color:white;font-weight:lighter;">
					<!-- <?php if($type == 1): ?><img src="/Template/mobile/default/Static/images/bottom_img/jingxuan1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/jingxuan2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					<i class="fa fa-home"></i>首页
				</a>
			</li>

			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/getSortList');?>" style="font: normal 18px/20px serif; color:white;font-weight:lighter;">
					<!-- <?php if($type == 2): ?><img src="/Template/mobile/default/Static/images/bottom_img/fenlei1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/fenlei2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					书库
				</a>
			</li>

			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/search');?>" style="font: normal 18px/20px  serif; color:white;font-weight:lighter;">
					<!-- <?php if($type == 3): ?><img src="/Template/mobile/default/Static/images/bottom_img/zhuanti1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/zhuanti2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					搜索
				</a>
			</li>
			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/freeLimitBookList');?>" style="font: normal 18px/20px  serif; color:white;font-weight:lighter;">
					<!-- <?php if($type == 3): ?><img src="/Template/mobile/default/Static/images/bottom_img/zhuanti1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/zhuanti2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					限免
				</a>
			</li>

			<li style="width:24%;">
				<?php if(($isweixin != 1)): ?><a href="<?php echo U('Mobile/User/buyEgold');?>" style="border-color:transparent;font: normal 18px/20px  serif; font-weight:lighter;color:white;">
						<?php else: ?>
						<a href="<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>" style="border:none;font: normal 18px/20px  serif; font-weight:lighter;color:white;"><?php endif; ?>
					<!-- <?php if($type == 4): ?><img src="/Template/mobile/default/Static/images/bottom_img/chongzhi1.png" alt="" style="width: 25px">
						<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/chongzhi2.png" alt="" style="width: 25px"><?php endif; ?> -->
					<i class="fa fa-diamond" style="font-size:13px;"></i>充值
				</a>
			</li>
		</ul>
	</div>
</div> 
            <div>
                <!-- <section class="filtrate_term" id="product_sort" style="width: 100%; height: 40px">
                    <ul>
                        <li style="width: 50%" class="<?php if($show_type == 1): ?>on<?php endif; ?>">
                        <a href="<?php echo urldecode(U('Mobile/Index/getBookListFromSortID',array('order_by' => 'lastupdate','sort_id' => $sort_id))) ?>">
                                最新
                            </a>
                        </li>
                        <li style="width: 50%" class="<?php if($show_type == 2): ?>on<?php endif; ?>">
                            <a href="<?php echo urldecode(U('Mobile/Index/getBookListFromSortID',array('order_by'=>'allvote','sort_id' => $sort_id))) ?>">
                                最热
                            </a>
                        </li>
                    </ul>
                </section> -->

                <section style="background-color: white">
                    <div class="touchweb-com_searchListBox openList" id="book_list">
                        <?php if(empty($resultbooklist)): ?><p class="goods_title">抱歉暂时没有相关结果！</p>
                        <?php else: ?>
                            <?php if(is_array($resultbooklist)): foreach($resultbooklist as $k=>$book): ?><div class="floor_body"  style="border-top: 0px;">
                                    <div id="scroll_promotion">
                                        <ul>
                                            <li style="margin-top: 10px;width: 100%;margin-left: 0px">
                                                <a href="<?php echo ($book["url"]); ?>" title="1" class="flex">
                                                    <div class="cover">
                                                        <img  alt="<?php echo ($book["info"]["articlename"]); ?>" src="<?php echo ($book["cover"]); ?>">

                                                    </div>

                                                    <div class="bInfo" id="bookintro">
                                                        <h4 class="bookname">
                                                            <?php echo ($book["articlename"]); ?>
                                                        </h4>

                                                        <p class="bookinfo" >
                                                            <?php echo ($book["intro"]); ?>
                                                        </p>

                                                        <!-- <p class="bookauthor" >
															<span>
																<?php echo ($book["author"]); ?>
															</span>

                                                            <span class="booksize">
																<?php echo ($book["size"]); ?>
															</span>
                                                        </p> -->
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><?php endforeach; endif; endif; ?>
                    </div>

                    <?php if(!empty($resultbooklist)): ?><div id="getmore" style="font-size:.24rem;text-align: center;color:#888;padding:.25rem .24rem .4rem; clear:both">
                            <a href="javascript:void(0)" onClick="ajax_sourch_submit()">
                                点击加载更多
                            </a>
                        </div><?php endif; ?>
                </section>

                <script>
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

                    var  page = 1;

                    /*** ajax 提交表单 查询订单列表结果*/
                    function ajax_sourch_submit()
                    {
                        page += 1;

                        pageIndex = page;

                        $.ajax(
                        {
                            type : "GET",
                            url  :  "/Index/ajaxBookListFromSortID/order_by/<?php echo ($order_by); ?>/sort_id/1/is_ajax/1/p/" + pageIndex + ".html",//+tab,

                            success: function(data)
                            {
                                if($.trim(data) == '')
                                {
                                    $('#getmore').hide();
                                }
                                else
                                {
                                    $("#book_list").append(data);
                                }
                            }
                        });
                    }
                </script>

                <!-- <a href="<?php echo U('Mobile/User/getBookCase');?>" class="gotoBookshelf">
                    <img src="/Template/mobile/default/Static/images/bottom_img/bookshelf.png">
                </a> -->

                <!-- <a href="javascript:goTop();" class="gotop">
                    <img src="/Template/mobile/default/Static/images/topup.png">
                </a> -->
            </div>
        </div>
    </body>
</html>