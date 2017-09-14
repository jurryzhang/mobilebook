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
	<style type="text/css">
        .page{
            margin-top: 20px;
            color: #838383;
            font-size: 12px;
            text-align: center;
        }
        .page a{
            border:1px solid #999;
            margin: 6px;
            padding: 2px 7px;
            border-radius: 3px;
            text-decoration: none;
            color: #838383;
        }
        .page .num{
            margin: 6px;
            padding: 2px 7px;
            border-radius: 3px;
        }
        .page li{
            display: inline-block;
        }
        .page li.active a{
            color: #ffffff;
            background:#f89345 ;
            border: none;
         }

    </style>
</head>

<body>
<div id="page" class="showpage">
    <div class="header">
        <div class="h-left">
            <a class="sb-back" href="javascript:history.go(-1);" title="返回" target="_self"></a>
        </div>

        <div class="h-mid">
            评论详情
        </div>

        <div style="float: right;margin-top: 5px;margin-right: 7%;">
            <a href="/" title="主页" target="_self" >
                <i class="fa fa-home" style="font-size: 30px;"></i>
            </a>
        </div>
    </div>

    <section class="index_floor_lou" >
        <div class="container" style="width: 100%;height:20%;overflow: hidden;">
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
                                
                                <ul class="textBox" style="width:81%;float: left;margin-left: -2%">
                                    <li style="line-height: 16px;vertical-align: middle;font-size: 14px;">
                                        <?php echo ($list['username']); ?>
                                    </li>
                                    <li style="line-height: 16px;vertical-align: middle;font-size: 12px;color: #8C8C8C;">
                                        <!--<?php echo mb_substr($list['content'],0,38,'utf-8');?>......--><?php echo ($list['content']); ?>
                                    </li>
                                    <li style="line-height: 16px;vertical-align: middle;font-size: 12px;color: #8C8C8C;">
                                        <?php echo date('Y-m-d H:i:s',$list['addtime']);?>
                                    </li>
									<?php if($list["reply"] == ''): else: ?>
                                        <ul style="font-size: 12px;">
                                            <li>作者回复：</li>
                                            <li style="line-height: 1px;color: #CCC;font-size: 11px;"><?php echo ($list["reply"]); ?></li>
                                        </ul><?php endif; ?>
                                </ul>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                </table>
				<div class="page"><?php echo ($page); ?></div><?php endif; ?>
            <!--</a>-->
        </div>
		
		<div class="want_comment" style="position: fixed;bottom: 60px;right: 20px;width: 42px;height: 42px;border-radius: 100%;background: red;text-align: center;line-height: 42px">
            <i class="fa fa-pencil-square-o" style="color: #ffffff"></i>
        </div>

    </section>
	
    

    <script>
        $(function()
        {
            width = document.documentElement.clientWidth - 160;

            width = 0.9 * width;

            $('.bookleft').css({width:width});
        });

        /*我要评论_文本弹出框*/
        /*$('.want_comment').click(function(){
            layer.open({
                type: 1
                ,content: $('.commentBox').html()
                ,anim: 'up'
                ,title:'发布评论'
                ,style: 'position:fixed; bottom:0; left:0; width: 100%; height: 100%;padding-left:-50px;border:none;background: rgba(255, 255, 255, 0.9);-webkit-animation-duration: .5s; animation-duration: .5s;'
            });
            /!*点击提交发布评论*!/
            $('.submitBtn').click(function(){
                console.log(111);
                if($('#textBox').text()=='' || $('#textBox').text()=='请输入评论内容'){
                    layer.open({
                        content: '请输入要发布的内容'
                        ,skin: 'msg'
                        ,time: 10 //2秒后自动关闭
                    });
                }
                var _text=$('#textBox').val();
                console.log($('#textBox'));
                console.log(_text);
            });
        });*/
        $('.want_comment').click(function(){
            $(location).attr('href', "/index.php/Gifts/comment/articleid/<?php echo ($articleid); ?>/isredBtn/1");
        });
    </script>
</div>
</body>
</html>