<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html >
<html>
<head>
    <meta name="Generator"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" href="/Public/images/favicon.ico" />
    <title><?php echo ($site_title); ?></title>
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
    <link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/public.css"/>
    <link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/index.css"/>
	<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/category_list.css"/>

    <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.js"></script>
    <script type="text/javascript" src="/Template/mobile/default/Static/js/TouchSlide.1.1.js"></script>
    <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.json.js"></script>
    <script type="text/javascript" src="/Template/mobile/default/Static/js/touchslider.dev.js"></script>
    <script type="text/javascript" src="/Template/mobile/default/Static/js/layer.js" ></script>
    <script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>
</head>

    <body>
        <div id="page" class="showpage">
          <!--   <div class="header">
                <div class="h-left">
                    <?php if($action == 'searchBook'): ?><a class="sb-back" href="/index.php/Index/search/keyWord/<?php echo ($keyWord); ?>" title="返回"></a>
                        <?php else: ?>
                        <a class="sb-back" href="javascript:history.back(-1);" title="返回"></a><?php endif; ?>
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
			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/index');?>">
					<!-- <?php if($type == 1): ?><img src="/Template/mobile/default/Static/images/bottom_img/jingxuan1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/jingxuan2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					<span style="font: normal 15px/30px Times,'New Century Schoolbook', serif; color:white;">首页</span>
				</a>
			</li>

			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/getSortList');?>">
					<!-- <?php if($type == 2): ?><img src="/Template/mobile/default/Static/images/bottom_img/fenlei1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/fenlei2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					<span style="font: normal 15px/30px Times,'New Century Schoolbook', serif; color:white;">书库</span>
				</a>
			</li>

			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/search');?>">
					<!-- <?php if($type == 3): ?><img src="/Template/mobile/default/Static/images/bottom_img/zhuanti1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/zhuanti2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					<span style="font: normal 15px/30px Times,'New Century Schoolbook', serif; color:white;">搜索</span>
				</a>
			</li>
			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/freeLimitBookList');?>">
					<!-- <?php if($type == 3): ?><img src="/Template/mobile/default/Static/images/bottom_img/zhuanti1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/zhuanti2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					<span style="font: normal 15px/30px Times,'New Century Schoolbook', serif; color:white;">限免</span>
				</a>
			</li>

			<li style="border-color:transparent;">
				<?php if(($isweixin != 1)): ?><a href="<?php echo U('Mobile/User/buyEgold');?>">
						<?php else: ?>
						<a href="<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>"><?php endif; ?>
					<!-- <?php if($type == 4): ?><img src="/Template/mobile/default/Static/images/bottom_img/chongzhi1.png" alt="" style="width: 25px">
						<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/chongzhi2.png" alt="" style="width: 25px"><?php endif; ?> -->
					<span style="font: normal 15px/30px Times,'New Century Schoolbook', serif; color:white;">充值</span>
				</a>
			</li>
		</ul>
	</div>
</div> 
            <section class="index_floor_lou">
				<?php if(empty($resultbooklist)): ?><div style="text-align: center;padding-top: 2%;padding-bottom: 2%">
                        <span style="background: transparent;font-size:14px;text-align: center;">
                        抱歉,未找到相关书籍
                    </span>
                    </div>
                <?php else: ?>
					<?php if(is_array($resultbooklist)): $i = 0; $__LIST__ = $resultbooklist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$book): $mod = ($i % 2 );++$i;?><div class="floor_body"  style="border-top: 0px;">
							<div id="scroll_promotion">
								<ul>
									<li>
										<a href="<?php echo ($book["url"]); ?>" title="1" class="flex">
											<div class="cover">
												<img  alt="<?php echo ($book["info"]["articlename"]); ?>" src="<?php echo ($book["cover"]); ?>">
											</div>

											<div class="bInfo" id="bookintro">
												<h4 class="bookname">
													<?php echo ($book["info"]["articlename"]); ?>
													<span class="booksort">
														<?php echo ($book["info"]["sort"]); ?>
													</span>
												</h4>
												<p class="bookinfo" >
													<?php echo ($book["info"]["intro"]); ?>
												</p>
												<!-- <p class="bookauthor" >
													<span>
														<?php echo ($book["info"]["author"]); ?>
													</span>

													<span class="booksize">
														<?php echo ($book["info"]["size"]); ?>
													</span>

													<span class="booksort">
														<?php echo ($book["info"]["sort"]); ?>
													</span>
												</p> -->
											</div>
										</a>
									</li>
								</ul>
							</div>
						</div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
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
            </script>

            <!-- <a href="<?php echo U('Mobile/User/getBookCase');?>" class="gotoBookshelf">
                <img src="/Template/mobile/default/Static/images/bottom_img/bookshelf.png">
            </a>

            <a href="javascript:goTop();" class="gotop">
                <img src="/Template/mobile/default/Static/images/topup.png">
            </a> -->

        </div>
    </body>
</html>