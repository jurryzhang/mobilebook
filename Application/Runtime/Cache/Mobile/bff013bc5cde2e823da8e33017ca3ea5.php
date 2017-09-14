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
    <!--<script type="text/javascript" src="/Template/mobile/default/Static/js/layer.js" ></script>-->
    <script type="text/javascript" src="/Template/mobile/default/Static/js/layer_mobile/layer.js" ></script>
    <script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>
</head>

<body>
<div class="container">
    <div class="header">
        <div class="h-left">
            <a class="sb-back" href="javascript:history.go(-1);" title="返回" target="_self"></a>
        </div>

        <div class="h-mid">
            发布评论
        </div>

        <div style="float: right;margin-top: 5px;margin-right: 7%;">
            <a href="/" title="主页" target="_self" >
                <i class="fa fa-home" style="font-size: 30px;"></i>
            </a>
        </div>
    </div>

    <div class="commentBox" style="width: 100%;">
        <textarea id="textBox" placeholder="请输入发布内容" style="resize : none;text-indent: 0px;padding-top:3%;margin-left: 3%;margin-top: 2%;font-size:15px;color:#909090;width: 94%;height: 150px;border: none;"></textarea>
        <a href="javascript:void(0);" data-articleid="<?php echo ($_GET['articleid']); ?>" id="submit" style="display: inline-block;margin-left: 3%;margin-top: 2%;text-align:center;width: 89%;background-color:#f89345;color: #FFFFff;font-size: 15px;padding: 7px 10px;border-radius: 4px;">提交</a>
    </div>
</div>

<script>
	String.prototype.trim = function() {
        return this.replace(/(^\s*)|(\s*$)/g, '');
    }; 
    $('#submit').click(function(){
        if($('#textBox').val().trim()=='' || $('#textBox').val().trim()=='请输入评论内容'){
            layer.open({
                content: '请输入要发布的内容'
                ,skin: 'msg'
                ,time: 2 //2秒后自动关闭
            });
			return false;
        }
		
        var content=$('#textBox').val();
        var articleid=$('#submit').attr('data-articleid');
		var chapterid=<?php echo ($chapterid); ?>+0;
		var isredBtn=<?php echo ($isredBtn); ?>+0;
		
        if(content.length>200){
            layer.open({
                content: '不得超过200字'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
            });
            return false;
        }
        $.ajax(
            {
                type    : "POST",
                url     : "/index.php?m=Mobile&c=Gifts&a=comment",
                data    : {content:content,articleid:articleid} ,
                success : function(data)
                {
                    layer.open({
                        content: data.msg
                        ,skin: 'msg'
                        ,time: 2 //2秒后自动关闭
                    });
                    /*$(location).attr('href', "/index.php/Article/index/id/<?php echo ($articleid); ?>");*/
					/*$(location).attr('href', "/index.php/Article/readChapter/book_id/<?php echo ($articleid); ?>/chapter_id/<?php echo ($chapterid); ?>");*/
					if(isredBtn==1){
                        $(location).attr('href', "/Gifts/commentList/articleid/<?php echo ($articleid); ?>");
                    }else{
                        if(chapterid){
                            $(location).attr('href', "/Article/readChapter/book_id/<?php echo ($articleid); ?>/chapter_id/<?php echo ($chapterid); ?>");
                        }else{
                            $(location).attr('href', "/Article/index/id/<?php echo ($articleid); ?>");
                        }
                    }
                }
            });
    });
</script>
</body>
</html>