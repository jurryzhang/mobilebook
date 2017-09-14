<?php if (!defined('THINK_PATH')) exit(); if(is_array($chapterlist)): foreach($chapterlist as $key=>$value): ?><a  href="<?php echo U('Article/readChapter',array('chapter_id' => $value['chapterid'],'book_id' => $bookid));?>" >
        <div style="padding-left: 20px;font-size: 14px;padding-top: 10px;padding-bottom: 10px;line-height: 24px;border-top: 1px solid #efefef;border-bottom: 1px solid #efefef;text-align: left" >
        
            <?php if($_COOKIE['chapterMark']== $value['chapterid']): ?><span style="color:#46A3FF" ><?php echo ($value["chaptername"]); ?> </span> 
             <i class="fa fa-bookmark-o" style="color: #46A3FF;margin-left: 10px;font-size: 17px;"></i>
            <?php else: ?>

            <span><?php echo ($value["chaptername"]); ?></span><?php endif; ?> 

            <span style="padding-left: 20px">
                <?php if($value['isvip'] == 1): ?><!--<img width="15px"   src="/Template/mobile/default/Static/images/bottom_img/jiasuo.png">-->
					<!-- <i class="fa  fa-diamond"></i> -->
					<?php if($isFreeLimit == 1): ?><i class="fa fa-clock-o" style="color: #C00;"></i> <span style="color: #C00;">限免</span>
						<?php else: ?>
						<i class="fa fa-diamond" style="color:#CC00CC;">&nbsp;<?php echo ($value["saleprice"]); ?></i><?php endif; endif; ?>
            </span>
        </div>
    </a><?php endforeach; endif; ?>