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

        <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/TouchSlide.1.1.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.json.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/touchslider.dev.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/layer.js" ></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>
    </head>

    <body>
        <div id="page" class="showpage">
            <div class="header">
                <div class="h-mid" style="width: 100%">
                    <?php echo ($site_title); ?>
                </div>
            </div>

            <div>
                <section style="background-color: white">
                    <div class="touchweb-com_searchListBox openList" id="topic_list">
                        <?php if(empty($topiclist)): ?><p class="goods_title"  style="margin-bottom: 0px;">
                                抱歉暂时没有相关结果！
                            </p>
                        <?php else: ?>
                            <?php if(is_array($topiclist)): foreach($topiclist as $k=>$topic): ?><div class="floor_body"  style="border-bottom: 0px;">
                                    <div id="scroll_promotion">
                                        <ul>
                                            <li style="margin-top: 10px;width: 100%;margin-left: 0px">
                                                <a href="<?php echo U('Index/getBookListFromTopicId',array('topic_id' => $topic['id']));?>" title="1">
                                                    <img width="95%" alt="<?php echo ($topic["title"]); ?>" src="<?php echo ($topic["cover"]); ?>">

                                                    <p><?php echo ($topic["summary"]); ?></p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div><?php endforeach; endif; endif; ?>
                    </div>

                    <?php if(!empty($topiclist)): ?><div id="getmore" style="font-size:.24rem;text-align: center;color:#888;padding:.25rem .24rem .4rem; clear:both">
                            <a href="javascript:void(0)" onClick="ajax_sourch_submit()">
                                点击加载更多
                            </a>
                        </div><?php endif; ?>
                </section>

                <script>
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
                                url  :  "/Index/ajaxTopicList/is_ajax/1/p/" + pageIndex + ".html",//+tab,

                                success: function(data)
                                {
                                    if($.trim(data) == '')
                                    {
                                        $('#getmore').hide();
                                    }
                                    else
                                    {
                                        $("#topic_list").append(data);
                                    }
                                }
                            });
                    }
                </script>

                <!-- <a href="javascript:goTop();" class="gotop">
                    <img src="/Template/mobile/default/Static/images/topup.png">
                </a> -->
            </div>

            
        </div>
    </body>
</html>