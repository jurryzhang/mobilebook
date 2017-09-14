<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <title>支持正版，关注我们</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="https://ommdq027l.qnssl.com/static/release/1497063132918/vendor.css" />
    <link rel="stylesheet" href="https://ommdq027l.qnssl.com/static/release/1497063132918/front.css" />

</head>
<body class="page-follow-hint ">

<div class="weui_msg">
    <div class="weui_text_area">
        <p style="font-size:22px;color:darkred;margin-top:30px;font-weight: bold;margin-bottom:10px;">关注<?php echo ($wxname); ?>,获取更好的服务</p>
        <p>有料有趣有内涵的公众号,你要不要关注一下</p>

        <div style="text-align:center;margin:10px 0;">
            <img src="http://open.weixin.qq.com/qr/code/?username=<?php echo ($wxnum); ?>" style="width:80%"/>
        </div>
        <div style="color:darkred;font-size:20px;font-weight: bold;">
            长按上图识别二维码关注
        </div>
        <div style="margin-top:10px;font-size:16px;">
            或搜索 <?php echo ($wxnum); ?> 关注 "<?php echo ($wxname); ?>"
        </div>
    </div>
</div>
</body>
</html>