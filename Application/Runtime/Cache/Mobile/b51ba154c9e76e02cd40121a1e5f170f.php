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

 
    </head>


<body>

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
<div id="page" class="showpage">

    <div id="tbh5v0">
        <div class="order ajax_return">
            <?php if(empty($freeBookList)): ?><div id="list_0_0" class="font12">暂未有免费书籍，敬请期待！</div>
                <?php else: ?>
                <section class="index_floor_lou">
                    <?php if(is_array($freeBookList)): $i = 0; $__LIST__ = $freeBookList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$book): $mod = ($i % 2 );++$i;?><div class="floor_body"  style="border-top: 10px;">
                            <div id="scroll_promotion">
                                <ul>
                                    <li >
                                        <a href="<?php echo ($book["url"]); ?>" title="1" class="flex">
                                            <div class="cover">
                                                <img  alt="<?php echo ($book["articlename"]); ?>" src="<?php echo ($book["cover"]); ?>">
                                            </div>

                                            <div class="bInfo" id="bookintro">
                                                <h4 class="bookname" onclick="showInfo(<?php echo ($book['caseid']); ?>)">
                                                    <?php echo ($book["articlename"]); ?>
                                                </h4>

                                                <p style="font-size: 12px;color: #C00;margin-top: -15px;margin-left:-33px;">
                                                    截止日期至：<?php echo date('Y-m-d H:i:s',$book['freetime']);?>
                                                </p>

                                                <p class="bookinfo" onclick="showInfo(<?php echo ($book['caseid']); ?>)">
                                                    <?php echo ($book["intro"]); ?>
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
    <!--  -->
</div>
</body>
</html>