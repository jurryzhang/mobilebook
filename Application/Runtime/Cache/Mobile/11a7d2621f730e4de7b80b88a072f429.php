<?php if (!defined('THINK_PATH')) exit();?><div class="touchweb-com_searchListBox openList" id="book_list">
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

<script>
        $(function()
        {
            width = document.documentElement.clientWidth - 112;

            width = 0.9 * width;

            $('.bookleft').css({width:width});
        });
                var  page = 1;

                    /*** ajax 提交表单 查询订单列表结果*/
                    function ajax_sourch_submit()
                    {
                        page += 1;

                        pageIndex = page;

                        $.ajax(
                        {
                            type : "GET",
                            url  :  "/Index/ajaxBookListFromSortID/order_by/<?php echo ($order_by); ?>/sort_id/<?php echo ($sort_id); ?>/groupid/<?php echo ($groupid); ?>/is_ajax/1/p/" + pageIndex + ".html",//+tab,

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