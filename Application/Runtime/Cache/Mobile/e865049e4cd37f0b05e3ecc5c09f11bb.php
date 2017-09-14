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

        <link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/category_list.css"/>
        <link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

        <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/TouchSlide.1.1.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.json.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/touchslider.dev.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/layer.js" ></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>

        <style type="text/css">
            /*滑动设置*/
            /*.read_set{width: 100%;height:50px;background: rgba(0,0,0,0.75);position: fixed;bottom: 0}
            .read_set li{text-align: center;line-height:40px;}
            .set_size{
                margin-left: 10px;height: 50%;
                vertical-align:middle;color: #ffffff;padding: 7px 10px;font-size: 14px;
            }*/
            .sortBtn{
                background:#FFFFff;
                text-align:center;
                font-size: 16px;
                padding-top: 10px;
                padding-bottom: 10px;
                line-height: 24px;
                border-top: 1px solid #efefef;
                border-bottom: 1px solid #efefef;
            }
            .sortBtn li.active{
                color: #F89345;
                border-bottom: solid 2px #F89345
            }
        </style>
    </head>

    <body>
        <div id="page" class="showpage">
             <!-- <div class="header">
                <div class="h-left">
                    <a class="sb-back" href="javascript:history.back(-1);" title="返回" target="_self">
                    </a>
                </div>

                <div class="h-mid">
                    目录
                </div>
				
				<div style="float: right;margin-top: 5px;margin-right: 7%;">
                    <a href="/" title="主页" target="_self" >
                        <i class="fa fa-home" style="font-size: 30px;"></i>
                    </a>
                </div>
            </div>  -->
            
            <div class="sortBtn" style="" >
                <!--<li>
                    <label class="set_size" style="margin-right: 8%"  onclick="chapterSort('ASC');">正序</label>
                    <label class="set_size">|</label>
                    <label class="set_size"  style="margin-right: 8%;margin-left: 8%" onclick="goTop();">顶部</label>
                    <label class="set_size">|</label>
                    <label class="set_size"  style="margin-left: 8%" onclick="chapterSort('DESC');">倒序</label>
                </li>-->
                <ul>
                    <li onclick="chapterSort('ASC');" id="ASC" style="float: left;width: 50%;background: #FFFFff;padding-bottom: 10px;">
                        正序
                        <i class="fa fa-sort-amount-asc"></i>
                    </li>
                    <li onclick="chapterSort('DESC');" id="DESC" style="float: left;width: 50%;background: #FFFFff;padding-bottom: 10px;">
                        倒序
                        <i class="fa fa-sort-amount-desc"></i>
                    </li>
                </ul>
            </div>

            <section class="index_floor_lou" >
                <div class="touchweb-com_searchListBox openList" id="chapter_list" style="margin-bottom: 30px">
                    <div id="chapterS">
                    <?php if(empty($chapterlist)): ?><p class="goods_title">抱歉暂时没有相关结果！</p>
                    <?php else: ?>
                        <?php if(is_array($chapterlist)): foreach($chapterlist as $key=>$value): if($value['size'] != 0): ?><a id="<?php echo ($value['chapterid']); ?>" name="<?php echo ($value['chapterid']); ?>" href="<?php echo U('Article/readChapter',array('chapter_id' => $value['chapterid'],'book_id' => $bookid));?>" >
                                    <div style="padding-left: 20px;font-size: 14px;padding-top: 10px;padding-bottom: 10px;line-height: 24px;border-top: 1px solid #efefef;border-bottom: 1px solid #efefef;text-align: left" >
                                        
                                         <?php if($_COOKIE['chapterMark']== $value['chapterid']): ?><span style="color:#46A3FF" ><?php echo ($value["chaptername"]); ?> </span> 
                                         <i class="fa fa-bookmark-o" style="color: #46A3FF;margin-left: 10px;font-size: 17px;"></i>
                                        <?php else: ?>

                                        <span><?php echo ($value["chaptername"]); ?></span><?php endif; ?> 

                                        <span style="padding-left: 20px">
                                            <!-- <?php if($value['isvip'] == 1): ?><i class="fa  fa-diamond"></i><?php endif; ?> -->
											<?php if($value['isvip'] == 1): if($isFreeLimit == 1): ?><i class="fa fa-clock-o" style="color: #C00;"></i> <span style="color: #C00;">限免</span>
                                                    <?php else: ?>
                                                    <i class="fa fa-diamond" style="color:#CC00CC;">&nbsp;<?php echo ($value["saleprice"]); ?></i><?php endif; endif; ?>
                                        </span>
                                    </div>
                                </a>
                            <?php else: ?>
                                <div style="padding-left: 20px;font-size: 14px;padding-top: 10px;padding-bottom: 10px;line-height: 24px;border-top: 1px solid #efefef;border-bottom: 1px solid #efefef;text-align: left;color: #960" >
                                    <?php echo ($value["chaptername"]); ?>
                                    <span style="float: right;padding-right: 20px">
                                        <?php if($value['isvip'] == 1): ?><i class="fa  fa-diamond"></i><?php endif; ?>
                                    </span>
                                </div><?php endif; endforeach; endif; endif; ?>
                    </div>
					<div style="font-size: 13px;display: none;text-align: center;margin-top: 10px;" id="image">
                        <img src="/Template/mobile/default/Static/images/loading.gif"> 加载中...
                    </div>
                </div>

                <!--<?php if(!empty($chapterlist)): ?><div id="getmore" style="font-size:.24rem;text-align: center;color:#888;padding:.25rem .24rem .4rem; clear:both;margin-bottom: 40px">
                        <a href="javascript:void(0)" onClick="ajax_sourch_submit()">
                            点击加载更多
                        </a>
                    </div><?php endif; ?>-->
            </section>

			  <!--<a href="javascript:goTop();" class="gotop" id="gotop" style="right:40px;bottom:80px;display:none;">
                    <img src="/Template/mobile/default/Static/images/topup.png">-->
                </a> 
             <!--<div class="read_set" style="display: none;">
                <li>
                    <label class="set_size"  onclick="chapterSort('ASC');">正序</label>
                    <label class="set_size">|</label>
                    <label class="set_size"  onclick="goTop();">顶部</label>
                    <label class="set_size">|</label>
                    <label class="set_size"  onclick="chapterSort('DESC');">倒序</label>
                </li>
            </div>-->

            <script>
                $(function()
                {
                    width = document.documentElement.clientWidth - 160;

                    width = 0.9 * width;

                    $('.bookleft').css({width:width});
                });

                function goTop()
                {
                    $('html,body').animate({'scrollTop':0},600);
                }


                /*** ajax 提交表单 查询订单列表结果*/
                /*function ajax_sourch_submit()
                {
                    page += 1;

                    pageIndex = page;

                    $.ajax(
                    {
                        type : "GET",
                        url  :  "/Article/ajaxDirectoryList/is_ajax/1/articleid/<?php echo ($value["articleid"]); ?>/p/" + pageIndex + ".html",//+tab,

                        success: function(data)
                        {
                            if($.trim(data) == '')
                            {
                                $('#getmore').hide();
                            }
                            else
                            {
                                $("#chapter_list").append(data);
                            }
                        }
                        });
                }*/

                var page = <?php echo ($chapterPage); ?>+0;
                var times=0;
                var sorttype="<?php echo ($sorttype); ?>";

                $(window).scroll(function () {
                    //正序倒序
                    /*$(".read_set").fadeIn();*/

                    //阅读章节自动加载
                    var scrollTop = $(this).scrollTop();
                    var scrollHeight = $(document).height();
                    var windowHeight = $(this).height();
                    var positionValue = (scrollTop + windowHeight) - scrollHeight;
                    if (positionValue == 0 && times<1) {					
                        scroolC();						
                    }
                });

                function scroolC(){
                    $("#image").css("display","block");
                        page += 1;
                        pageIndex = page;
                        $.ajax({
                            type : "GET",
                            url  :  "/Article/ajaxDirectoryList/is_ajax/1/articleid/<?php echo ($value["articleid"]); ?>/p/" + pageIndex + "/sorttype/"+sorttype+".html",//+tab,

                            success: function(data) {
                                $("#image").css("display","none");
                                if($.trim(data) != '')
                                {
                                    /*$('#image').before(data);*/
                                    $("#chapterS").append(data);
                                }else{
                                    if(times<1){
                                        times++;
                                        $('#image').before('<div id="dixian" style="text-align: center;font-size: 13px;color:#CCC;margin-top:20px;">---------------^o^我是有底线的---------------</div>');
                                    }
                                }
                            }
                        });
                }
                function chapterSort(sorttype1){
                    /*window.location="/index.php/Article/getDirectory/id/<?php echo ($bookid); ?>/sorttype/"+sorttype+".html";*/
                    $('#chapterS').html('');
                    page=0;
					times=0;
					$('#dixian').remove();
                    sorttype=sorttype1;
                    scroolC();
                }

                function initSort(){
					if(sorttype==''){
                        sorttype="ASC";
                    }
                    $('#'+sorttype).addClass('active');
                }
                initSort();

                $('.sortBtn li').click(function(){
                    $(this).parents('ul').find('li').removeClass('active');
                    $(this).addClass('active');
                });
                /*$('.read_set').click(function(){
                    $(this).hide();
                });*/


            </script>

            <!--<a href="javascript:goTop();" class="gotop">
                <img src="/Template/mobile/default/Static/images/topup.png">
            </a>-->
        </div>
    </body>
</html>