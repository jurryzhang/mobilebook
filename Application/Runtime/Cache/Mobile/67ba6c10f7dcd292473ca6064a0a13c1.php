<?php if (!defined('THINK_PATH')) exit(); if(is_array($resultbooklist)): foreach($resultbooklist as $k=>$book): ?><div class="floor_body"  style="border-top: 0px;">
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
    </div>

    <script>
        $(function()
        {
            width = document.documentElement.clientWidth - 112;

            width = 0.9 * width;

            $('.bookleft').css({width:width});
        });
    </script><?php endforeach; endif; ?>