<?php if (!defined('THINK_PATH')) exit(); if(is_array($topiclist)): foreach($topiclist as $k=>$topic): ?><div class="floor_body"  style="border-top: 0px;">
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
    </div>

    <script>
        $(function()
        {
            width = document.documentElement.clientWidth - 112;

            width = 0.9 * width;

            $('.bookleft').css({width:width});
        });
    </script><?php endforeach; endif; ?>