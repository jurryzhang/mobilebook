<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html >
<html>
	<head>
		<meta name="Generator" content="TPSHOP v1.1" />
	  	<meta charset="UTF-8">
	  	<meta name="viewport" content="width=device-width">
		<link rel="shortcut icon" href="/Public/images/favicon.ico" />
		<title><?php echo ($site_title); ?></title>
	  	<meta http-equiv="keywords" content="<?php echo ($tpshop_config['shop_info_store_keyword']); ?>" />	
	  	<meta name="description" content="<?php echo ($tpshop_config['shop_info_store_desc']); ?>" />
	  	<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
	  	<link rel="stylesheet" href="/Template/mobile/default/Static/css/loginxin.css">
	  	<link rel="stylesheet" href="/Template/mobile/default/Static/css/public.css" >
 	</head>

	<body>
		<header class="header_03">
  			<div class="nl-login-title">
    			<div class="h-left">
      				<a class="sb-back" href="javascript:history.back(-1)" title="返回"></a>
    			</div>
    			
    			<span style="text-align:center">系统提示</span>
  			</div>
		</header>
		
		<div class="ng-form-zhu" style="text-align: center;font-size:16px;">
  			<div class="tips_a">
      			<?php if(isset($message)){ ?>
      			<img src="/Template/mobile/default/Static/images/xin/icogantanhao.png">
      		</div>
     		
     		<?php }else{ ?>
     		<img src="/Template/mobile/default/Static/images/xin/icogantanhao-sb.png">
     	</div>
     
     	<?php }?>
		<div class="tips">
  			<?php if(isset($message)) {?>
			<?php echo($message); ?>
	  		<?php }else{?>
	  		<?php echo($error); }?>
  		</div>
  		<div class="tips"><span id="time"></span>秒后自动跳转！</div>
      	<div class="tips">
      		<a href="<?php echo($jumpUrl); ?>"  id="href" style="color: #666;">
				<span class="tishi">返回上一页</span>
      		</a>
      		
      		<a href="<?php echo U('Index/index');?>" style="color: #666;">
      			<span class="tishi">返回首页</span>
      		</a>
   		</div>
		
		<script type="text/javascript">
			(function()
			{
				var wait = 3,href = document.getElementById('href').href;
				var t = document.getElementById("time");
				t.innerHTML = wait;
				var interval = setInterval(function()
				{
					var time = --wait;
					t.innerHTML = time;
					if(time <= 0) 
					{
						location.href = href;
						clearInterval(interval);
					};
				}, 1000);
			})();
		</script>
	</body>
</html>