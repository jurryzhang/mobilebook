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
        <link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/read.css">

        <link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/category_list.css"/>
		<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/TouchSlide.1.1.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.json.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/touchslider.dev.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/layer.js" ></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>
        <style type="text/css">
            .dashang ul li{
                float: left;
                width: 13%;
                border-bottom: none;
                margin-left: 1%;
            }
            .dashang ul p{
                font-size: 10px;
                line-height: 10px;
            }
            .dashang_icon{
                width: 60%;
            }
            .liwu ul li{
                border-bottom: none;
                width: 100%;
            }
        </style>
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
             <!-- <div class="header">
                <div class="h-left">
                    <a class="sb-back" href="javascript:history.go(-1);" title="返回" target="_self"></a>
                </div>

                <div class="h-mid">
                    <?php echo ($book_info["articlename"]); ?>
                </div>
				
				<div style="float: right;margin-top: 5px;margin-right: 7%;">
                    <a href="/" title="主页" target="_self" >
                        <i class="fa fa-home" style="font-size: 30px;"></i>
                    </a>
                </div>
            </div>  -->
            

            <section class="index_floor_lou" >
                <div class="floor_body"  style="padding-top:8px;margin-top:-8px;">
                    <div id="scroll_promotion">
                        <ul class="flex">
                            <div class="cover">
                                <img  alt="<?php echo ($book_info["articlename"]); ?>" src="<?php echo ($book_info["cover"]); ?>">
                            </div>
                            <div class="bInfo" id="bookintro">
                                <h4 class="bookname" style="line-height:17px">
                                    <?php echo ($book_info["articlename"]); ?>
                                </h4>

                                <!-- <p class="artcile_info" >
                                    作者：<?php echo ($book_info["author"]); ?>
                                </p> -->

                                <p class="artcile_info" >
                                    字数：<?php echo ($book_info["size"]); ?>
                                </p>

                                 <!-- <p class="artcile_info" >
                                    价格：<?php echo ($book_info["price"]); ?>
                                </p>  -->
                                <p class="artcile_info" >
                                    状态：<?php echo ($book_info["flag"]); ?>
                                </p>
                                <!-- <p class="artcile_info" >
                                    来源：<?php echo ($book_info["sourcename"]); ?>
                                </p> -->
                            </div>
                        </ul>
                    </div>
                    <!--打赏个数-->
                    <!--<div style="width: 100%" class="dashang">
                        <ul style="width:100%">
                            <li><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv01.png"><p><?php echo ($giftSum[1]+0); ?></p></li>
                            <li><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv02.png"><p><?php echo ($giftSum[2]+0); ?></p></li>
                            <li><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv03.png"><p><?php echo ($giftSum[3]+0); ?></p></li>
                            <li><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv04.png"><p><?php echo ($giftSum[4]+0); ?></p></li>
                            <li><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv05.png"><p><?php echo ($giftSum[5]+0); ?></p></li>
                            <li><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv06.png"><p><?php echo ($giftSum[6]+0); ?></p></li>
                            <li><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv07.png"><p><?php echo ($giftSum[7]+0); ?></p></li>
                        </ul>
                    </div>-->
                </div>

                <!--打赏、催更、评论-->
                <div class="liwu" style="text-align: center;padding-bottom:2%;padding-top: 2%;border-top: 1px solid #F2F2F2">
                    <div class="cuigeng" style="display: inline-block;font-size: 13px;width: 27%;vertical-align: middle">
                        <img style="width:30%;" src="/Template/mobile/default/Static/images/liwu/cuigeng.png">
                        <ul style="display: inline-block;line-height: 16px;vertical-align: middle">
                            <li>催更</li>
                            <li style="color: #909090"><?php echo ($bookinfo['sumhurry']); ?></li>
                        </ul>
                    </div>
                    <div class="dashang" style="display: inline-block;font-size: 13px;width: 27%;vertical-align: middle;">
                        <img style="width:30%;" src="/Template/mobile/default/Static/images/liwu/dashang.png">
                        <ul style="display: inline-block;line-height: 16px;vertical-align: middle;margin-left: -7%">
                            <li>打赏</li>
                            <li style="color: #909090"><?php echo ($bookinfo['sumgift']); ?></li>
                        </ul>
                    </div>
                    <div class="pinglun" style="display: inline-block;width: 27%;font-size: 13px;vertical-align: middle;">
                        <img style="width:30%;" src="/Template/mobile/default/Static/images/liwu/pinglun.png">
                        <ul style="display: inline-block;line-height: 16px;vertical-align: middle;">
                            <li>评论</li>
                            <li style="color: #909090"><?php echo ($bookinfo['sumcomment']); ?></li>
                        </ul>
                    </div>
                </div>

                <div class="floor_body">
                    <h2 style="margin-top: 10px;border-bottom: 1px solid #efefef">
                        <em></em>
                        书籍简介
                    </h2>

                    <div style="margin: 10px 20px 10px 20px;font-size: 14px;line-height: 23px;color:#B0B0B0;">
                        <?php echo ($book_info["intro"]); ?>
                    </div>
                </div>

                <div class="floor_body">
                    <h2 style="margin-top: 10px;border-bottom: 1px solid #efefef">
                        <em></em>
                        章节目录
                    </h2>

                    <a href="<?php echo U('Article/readChapter',array('book_id' => $book_info['articleid'],'chapter_id' => $book_info['lastchapterid']));?>" >
                        <div style="margin: 10px 20px 10px 20px;font-size: 14px;line-height: 30px">
                            最新章节：<?php echo ($book_info["lastchapter"]); ?>
                        </div>

                        <div style="margin: 0;font-size: 10px;padding-top: 10px;padding-bottom: 10px;line-height: 18px;border-top: 1px solid #efefef;border-bottom: 1px solid #efefef;text-align: center" >
                            <a href="<?php echo U('Article/getDirectory',array('id' => $book_info['articleid']));?>" >
                                点击查看更多章节（共<?php echo ($book_info["chaptersum"]); ?>章）
                            </a>
                        </div>
                    </a>
                </div>
				
				<div class="container" style="width: 100%;height:20%;overflow: hidden;border-top: 10px solid #F2F2F2">
                    <h2 style="height: 35px;line-height: 35px;font-size: 16px;font-weight: normal;color: #7f7f7f;margin-bottom: 10px; margin-top: 10px;border-bottom: 1px solid #efefef">
                        <em style="width: 3px;height: 15px;background: #efa164;margin-right: 5px;margin-top: 10px;float: left;"></em>
                        书友评论
                        <span class="want_comment" style="float: right;margin-right:20px;font-size: 12px;">
                            <i class="fa fa-pencil-square-o"></i> 我要评论
                        </span>
                    </h2>

                    <!--<a href="<?php echo U('Article/readChapter',array('book_id' => $book_info['articleid'],'chapter_id' => $book_info['lastchapterid']));?>" >-->
                    <?php if(empty($commentList)): ?><div style="text-align:center;color:#AAA;margin-top: 10px;padding-left:20px;font-size: 14px;line-height: 30px;border-bottom: 1px solid #efefef;">
                            未发布任何评论
                        </div>
                    <?php else: ?>
                       <table style="width: 100%">
                            <?php if(is_array($commentList)): $i = 0; $__LIST__ = $commentList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
                                <td style="border-bottom: solid 1px #efefef;padding-bottom: 10px;padding-top: 10px">                                    
                                    <span style="width: 15%;float: left;margin-left: 2%;vertical-align: middle;">
                                        <img src="<?php echo ($list['faceImg']); ?>" style="width:36px;border-radius: 18px;">
                                    </span>                                    
                                    <ul class="textBox" style="width:81%;float: left;">
                                        <li style="line-height: 16px;vertical-align: middle;font-size: 14px;">
                                            <?php echo ($list['username']); ?>
                                        </li>
                                        <li style="line-height: 16px;vertical-align: middle;font-size: 11px;color: #8C8C8C;">
                                            <!--<?php echo mb_substr($list['content'],0,38,'utf-8');?>......--><?php echo ($list['content']); ?>
                                        </li>
                                        <li style="line-height: 16px;vertical-align: middle;font-size: 11px;color: #8C8C8C;">
                                            <?php echo date('Y-m-d H:i:s',$list['addtime']);?>
                                        </li>
                                        <?php if($list["reply"] == ''): else: ?>
                                            <ul style="font-size: 12px;">
                                                <li>作者回复：</li>
                                                <li style="line-height: 1px;color: #CCC;"><?php echo ($list["reply"]); ?></li>
                                            </ul><?php endif; ?>


                                    </ul>
                                </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                        </table>
						<div class="moreCom" style="text-align:center;color:#AAA;margin-top: 10px;padding-left:20px;padding-bottom:10px;font-size: 14px;line-height: 30px;border-bottom: 1px solid #efefef;">
                            点击加载更多评论
                        </div><?php endif; ?>
                    <!--</a>-->
                </div>
            </section>

            <!--start 打赏-->
<div class="want_dashang" style="display: none;width: 100%;height: 100%;position: relative;z-index: 100;">
    <div class="want_dashang_top"></div>
    <div class="want_dashang_bottom">
        <div style="width:100%;height:20%;margin-top:3%;">
            <div style="width: 10%;position: relative;left: 3%;top: 8px;">
                <a href="/User/index.html">
                    <span style="width: 20%">
                        <img src="<?php echo ($userinfo["faceImg"]); ?>" style="width:36px;border-radius: 18px;">
                    </span>
                </a>
            </div>
            <div style="position: relative;left:13%;top: -33px;width: 30%;">
                <a href="/User/index.html">
                    <p class="bookname" style="font-size: 13px;">
                        <?php echo ($userinfo["name"]); ?>
                    </p>
                    <p class="bookinfo" style="font-size: 12px;margin-top: -10px;height: 20px;color:#f89345">
                        <?php echo ($userinfo["egold"]); ?> 书币
                    </p>
                </a>
            </div>
            <div style="position: relative;right:-75%;top: -72px;width: 30%;text-align: center;line-height: 20px;">
                <?php if(($isweixin != 1)): ?><a href="<?php echo U('Mobile/User/buyEgold');?>"  style="font-size: 15px;color:#f89345;">
                        充值
                    </a>
                    <?php else: ?>
                    <a href="<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>"  style="font-size: 15px;color:#f89345;">
                        充值
                    </a><?php endif; ?>
                <!-- <a href="/User/buyEgold.html" style="font-size: 15px;color:#f89345;">充值</a> -->
            </div>
        </div>
        <div class="dashang_icon_all">
            <ul style="width:100%">
                <li data-val="10" data-type="1"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv01.png"></li>
                <li class="active" data-val="100" data-type="2"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv02.png"></li>
                <li data-val="500" data-type="3"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv03.png"></li>
                <li data-val="1000" data-type="4"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv04.png"></li>
                <li data-val="5000" data-type="5"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv05.png"></li>
                <li data-val="10000" data-type="6"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv06.png"></li>
                <li data-val="100000" data-type="7"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv07.png"></li>
            </ul>
        </div>
        <div class="want_egold">
            <span style="font-size: 13px;">数量</span>
            <i class="fa fa-minus yunsuan jian"></i>
            <input type="text" value="1" name="count" readonly="readonly" style="border: none;width: 32px;text-align: center;">
            <!--<span class="count" style="text-align: center;font-size: 12px;margin:0px 3px;">1</span>-->
            <i class="fa fa-plus yunsuan jia"></i>
            <span style="font-size: 13px;margin-left: 15px">需：</span>
            <span class="egold" style="font-size: 12px;color:#f89345;">100</span>
            <span style="font-size: 12px;color: #BBB">书币</span>
            <a href="javascript:void(0);" class="zengsong"
               data-uid="<?php echo ($userinfo["uid"]); ?>" data-articleid="<?php echo ($book_info["articleid"]); ?>" data-chapterid="<?php echo ($book_info["lastchapterid"]); ?>" date-egold="<?php echo ($userinfo["egold"]); ?>">
                赠送
            </a>
        </div>
    </div>
</div>
<!--end 打赏-->

<!--start 催更-->
<div class="want_cuigeng" style="display: none;width: 100%;height: 100%;position: relative;z-index: 100;">
    <div class="want_cuigeng_top"></div>
    <div class="want_cuigeng_bottom">
        <div style="width:100%;height:20%;margin-top:3%;">
            <div style="width: 10%;position: relative;left: 3%;top: 8px;">
                <a href="/User/index.html">
                    <span style="width: 20%">
                        <img src="<?php echo ($userinfo["faceImg"]); ?>" style="width:36px;border-radius: 18px;">
                    </span>
                </a>
            </div>
            <div style="position: relative;left:13%;top: -33px;width: 30%;">
                <a href="/User/index.html">
                    <p class="bookname" style="font-size: 13px;">
                        <?php echo ($userinfo["name"]); ?>
                    </p>
                    <p class="bookinfo" style="font-size: 12px;margin-top: -10px;height: 20px;color:#f89345">
                        <?php echo ($userinfo["egold"]); ?> 书币
                    </p>
                </a>
            </div>
            <div style="position: relative;right:-75%;top: -72px;width: 30%;text-align: center;line-height: 20px;">
                <?php if(($isweixin != 1)): ?><a href="<?php echo U('Mobile/User/buyEgold');?>"  style="font-size: 15px;color:#f89345;">
                        充值
                    </a>
                    <?php else: ?>
                    <a href="<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>"  style="font-size: 15px;color:#f89345;">
                        充值
                    </a><?php endif; ?>
                <!-- <a href="/User/buyEgold.html" style="font-size: 15px;color:#f89345;">充值</a> -->
            </div>
        </div>
        <div class="cuigeng_icon_all">
            <ul class="cuiUl">
                <li class="activeC" data-val="300" data-size="3000"><ul><li>3千字</li><li>更新</li></ul></li>
                <li data-val="600" data-size="6000"><ul><li>6千字</li><li>更新</li></ul></li>
                <li data-val="900" data-size="9000"><ul><li>9千字</li><li>更新</li></ul></li>
                <li data-val="1200" data-size="12000"><ul><li>1.2万字</li><li>更新</li></ul></li>
                <li data-val="1500" data-size="15000"><ul><li>1.5万字</li><li>更新</li></ul></li>
                <li data-val="1800" data-size="18000"><ul><li>1.8万字</li><li>更新</li></ul></li>
            </ul>
        </div>
        <div class="want_cui_egold">
            <span style="font-size: 13px;">请输入催更金额：
                <input type="text" value="300" name="cui_egold" style="border: none;width: 140px;color: #f89345;">
                <p style="font-size: 12px;color: #CCC;margin-top:-10px">(书币;不小于100的整数)</p>
            </span>
            <a href="javascript:void(0);" class="cuigeng-btn"
               data-uid="<?php echo ($userinfo["uid"]); ?>" data-articleid="<?php echo ($book_info["articleid"]); ?>" data-chapterid="<?php echo ($book_info["lastchapterid"]); ?>" date-egold="<?php echo ($userinfo["egold"]); ?>">
                催更
            </a>
        </div>
    </div>
</div>
<!--end 催更-->





<script>

    /*************************daidai**************************************/
    var isweixin=<?php echo ($isweixin); ?>;
    var useregold=<?php echo ($userinfo['egold']+0); ?>;

    var needegoldS=$('.egold').html();
    var count=$('input[name=count]').val();
    var countSum=$('.dashang ul li:last').html();

    var needegoldC=$('input[name=cui_egold]').val();
    var wordsize=$('li[class=activeC]').attr('data-size');
    var cuiSumegold=$('.cuigeng ul li:last').html();

    var commentCount=$('.pinglun ul li:last').html();

    /*****设置打赏弹出框***********************************************/
    $('.dashang').click(function(){
        $('.want_dashang').fadeIn('fast');
    });
    $('.want_dashang_top').click(function(){
        $('.want_dashang').fadeOut('fast');
    });

    /*点击打赏图标*/
    /*$('.dashang_icon_all td').click(function(){
     $(this).parents('table').find('td').removeClass('active');
     $(this).addClass('active');*/
    function getyuansuan(_this){
        var _tdVal=parseInt(_this.attr('data-val'));

        /*点击加减控制书币值*/
        var countBoj=$('input[name=count]');
        var _count=parseInt(countBoj.val());
        var _egold=_tdVal*_count;
        $('.egold').html(_egold);

        $('.want_egold .jian').click(function(e){
            e.preventDefault();
            _count=_count-1;
            _egold=_tdVal*_count;
            if(_count<=0){
                _count=1;
                _egold=_tdVal;
            }
            countBoj.val(_count);
            $('.egold').html(_egold);
        });

        $('.want_egold .jia').click(function(e){
            e.preventDefault();
            _count=_count+1;
            _egold=_tdVal*_count;
            countBoj.val(_count);
            $('.egold').html(_egold);
        });
    }
    getyuansuan($('.dashang_icon_all li:eq(1)'));
    $('.dashang_icon_all li').click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        getyuansuan($(this));
    });

    /*打赏_赠送btn*/
    $('.zengsong').click(function(){
        var uid=$(this).attr('data-uid');
        var articleid=$(this).attr('data-articleid');
        var chapterid=$(this).attr('data-chapterid');
        var giftstype=$('li[class=active]').attr('data-type');
        count=$('input[name=count]').val();
        needegoldS=$('.egold').html();
        $.ajax(
            {
                type    : "POST",
                url     : "/index.php?m=Mobile&c=Gifts&a=index",
                data    : {uid:uid,articleid:articleid,chapterid:chapterid,giftstype:giftstype,count:count,needegold:needegoldS} ,
                success : function(data)
                {
                    $('.want_dashang').fadeOut('fast');
                    successLater(data);

                    $('.dashang_icon_all ul li').removeClass('active');
                    $('.dashang_icon_all ul li:eq(1)').addClass('active');
                    $('input[name=count]').val(1);
                    $('.egold').html(100);
                    countSum=parseInt(countSum)+parseInt(count);
                    $('.dashang ul li:last').html(countSum);
                    commentCount=parseInt(commentCount)+1;
                    $('.pinglun ul li:last').html(commentCount);
                }
            });
    });


    /*****设置催更弹出框*************************************************/
    $('.cuigeng').click(function(){
        $('.want_cuigeng').fadeIn('fast');
    });
    $('.want_cuigeng_top').click(function(){
        $('.want_cuigeng').fadeOut('fast');
    });
    /*$('.cuigeng_icon_all table tr td').click(function(){
     $(this).parents('table').find('td').removeClass('activeC');
     $(this).addClass('activeC');*/
    $('.cuigeng_icon_all .cuiUl>li').click(function(){
        $(this).addClass('activeC').siblings().removeClass('activeC');

        var _tdVal=parseInt($(this).attr('data-val'));

        $('input[name=cui_egold]').val(_tdVal);
    });
    /*催更*/
    $('.cuigeng-btn').click(function(){
        var uid=$(this).attr('data-uid');
        var articleid=$(this).attr('data-articleid');
        var chapterid=$(this).attr('data-chapterid');
        wordsize=$('li[class=activeC]').attr('data-size');
        needegoldC=$('input[name=cui_egold]').val();

        if(needegoldC<100){
            layer.open({
                content: '催更金额不得少于100'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
                ,end:function(){
                    location.reload();
                }
            });
            return false;
        }

        $.ajax(
            {
                type    : "POST",
                url     : "/index.php?m=Mobile&c=Gifts&a=urgeUpdate",
                data    : {uid:uid,articleid:articleid,chapterid:chapterid,wordsize:wordsize,needegold:needegoldC} ,
                success : function(data)
                {
                    $('.want_cuigeng').fadeOut('fast');
                    successLater(data);

                    $('.cuigeng_icon_all .cuiUl li').removeClass('activeC');
                    $('.cuigeng_icon_all .cuiUl li:eq(0)').addClass('activeC');
                    $('input[name=cui_egold]').val(300);
                    cuiSumegold=parseInt(cuiSumegold)+parseInt(needegoldC);
                    $('.cuigeng ul li:last').html(cuiSumegold);
                    commentCount=parseInt(commentCount)+1;
                    $('.pinglun ul li:last').html(commentCount);
                }
            });
    });


    function successLater(data){
        layer.open({
            content: data.message
            ,skin: 'msg'
            ,time: 1 //2秒后自动关闭
            ,end:function(){
                if(data.status==-1){
                    if(isweixin){
                        $(location).attr('href', "<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>");
                        /*location.href("<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>");*/
                    }else{
                        $(location).attr('href', "<?php echo U('Mobile/User/buyEgold');?>");
                        /*location.href("<?php echo U('Mobile/User/buyEgold');?>");*/
                    }
                }else if(data.status==1){
                    /*location.reload();*/
                    if(data.action=='dashang'){
                        useregold=useregold-needegoldS;
                        $('.bookinfo').html(useregold);
                    }else{
                        useregold=useregold-needegoldC;
                        $('.bookinfo').html(useregold);
                    }
                }else{
                    location.reload();
                }
            }
        });
    }
    /****评论****/
    $('.pinglun').click(function(){
        /*$(location).attr('href', "/index.php/Article/index/id/<?php echo ($chapter['articleid']); ?>");*/
        $(location).attr('href', "/Gifts/comment/articleid/<?php echo ($book_info['articleid']); ?>.html");
    });
</script>

            <div style="height:50px; line-height:0px; clear: both; background-color: rgb(246,246,246)"></div>
<div class="artcile_nav"  style="background: #efa164; ">
    <ul>
        <li style="width: 50%">
            <!--<a style="color: white;font-size: 20px" href="<?php echo U('User/addBookCase',array('book_id' => $book_info['articleid']));?>" onClick="addBookCase(<?php echo ($book_info['articleid']); ?>)">-->
            <a style="color: white;font-size: 20px" onClick="addBookCase(<?php echo ($book_info['articleid']); ?>,'<?php echo ($book_info['articlename']); ?>')">
                <!--加入收藏-->
                <img width=100% src="/Template/mobile/default/Static/images/bottom_img/jiarushujia1@3x.png">
            </a>
        </li>

        <?php if($bookcase == ''): ?><li style="width: 50%">
                <a style="color: white;font-size: 20px" href="<?php echo U('Article/readChapter',array('chapter_id' => $book_info['firstchapter'],'book_id' => $book_info['articleid']));?>">
                    <!--开始阅读-->
                    <img width=100% src="/Template/mobile/default/Static/images/bottom_img/kaishiyuedu1@3x.png">
                </a>
            </li>
            <?php else: ?>
            <li style="width: 50%">
                <a style="color: white;font-size: 20px" href="<?php echo U('Article/readChapterFromBookCase',array('bookcase_id' => $bookcase['caseid']));?>">
                    <!--继续阅读-->
                    <img width=100% src="/Template/mobile/default/Static/images/bottom_img/kaishiyuedu2@3x.png">
                </a>
            </li><?php endif; ?>
    </ul>

    <script>
        function addBookCase(bookID,bookName)
        {
            $.ajax(
            {
                type : "GET",
                url  :  "/User/addBookCase/id/" + bookID + "/bookname/" + bookName + ".html",//+tab,
                success: function(data)
                {
                    alert(data.msg);
                }
            });
        }
    </script>
</div>

            <script>
                $(function()
                {
                    width = document.documentElement.clientWidth - 160;

                    width = 0.9 * width;

                    $('.bookleft').css({width:width});
                });
				/*我要评论*/
				$('.want_comment').click(function(){
                    $(location).attr('href', "/Gifts/comment/articleid/<?php echo ($book_info['articleid']); ?>");
                });
				/*更多评论*/
				$('.moreCom').click(function(){
                    $(location).attr('href', "/Gifts/commentList/articleid/<?php echo ($book_info['articleid']); ?>");
                });
            </script>
        </div>

    </body>
</html>