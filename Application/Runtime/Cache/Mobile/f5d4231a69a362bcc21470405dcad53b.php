<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html >
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="applicable-device" content="mobile">
		<link rel="shortcut icon" href="/Public/images/favicon.ico" />
		<title><?php echo ($site_title); ?></title>
		<link rel="stylesheet" href="/Template/mobile/default/Static/css/public.css">
		<link rel="stylesheet" href="/Template/mobile/default/Static/css/index.css">
		<link rel="stylesheet" href="/Template/mobile/default/Static/css/user.css">
		<link rel="stylesheet" href="/Template/mobile/default/Static/css/category.css">
		<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/modernizr.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/layer.js" ></script>
		<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js" ></script>
		<script>
			function listenBack() {
				if (window.history.length <= 2) {
					window.history.replaceState("wx-back", null,"");
					window.history.pushState("", null,"");
				}
//				else{
//					window.history.replaceState("", null,"");
//				}
				window.onpopstate=function(){
//				alert("location: " + document.location + ", state: " + JSON.stringify(history.state));
					if (!!(<?php echo ($isweixin); ?>&&history.state=="wx-back")) {
						WeixinJSBridge.call('closeWindow');
					}
				}
			}
//			window.onload=function(){
//				if (window.history.length <= 2) {
//					window.history.replaceState("wx-back", null,"");
//					window.history.pushState("", null,"");
//				}
//				else{
//					window.history.replaceState("", null,"");
//				}
//				window.onpopstate=function(){
////				alert("location: " + document.location + ", state: " + JSON.stringify(history.state));
//					if (!!(<?php echo ($isweixin); ?>&&history.state=="wx-back")) {
//						WeixinJSBridge.call('closeWindow');
//					}
//				}
//				alert("location: " + document.location + ", state: " + JSON.stringify(history.state));
//			}
		</script>
		<style type="text/css">
	
		</style>
	</head>

    <body>
        
<div class="v_nav">
	<div class="vf_nav">
		<ul>
			<li style="border-color: #E0E0E0; width:24%;">
				<a href="<?php echo U('Index/index');?>" style="font: normal 18px/20px  serif; color:white;font-weight:lighter;">
					<!-- <?php if($type == 1): ?><img src="/Template/mobile/default/Static/images/bottom_img/jingxuan1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/jingxuan2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					<i class="fa fa-home"></i>首页
				</a>
			</li>

			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/getSortList');?>" style="font: normal 18px/20px serif; color:white;font-weight:lighter;">
					<!-- <?php if($type == 2): ?><img src="/Template/mobile/default/Static/images/bottom_img/fenlei1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/fenlei2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					书库
				</a>
			</li>

			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/search');?>" style="font: normal 18px/20px  serif; color:white;font-weight:lighter;">
					<!-- <?php if($type == 3): ?><img src="/Template/mobile/default/Static/images/bottom_img/zhuanti1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/zhuanti2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					搜索
				</a>
			</li>
			<li style="border-color: #E0E0E0;">
				<a href="<?php echo U('Index/freeLimitBookList');?>" style="font: normal 18px/20px  serif; color:white;font-weight:lighter;">
					<!-- <?php if($type == 3): ?><img src="/Template/mobile/default/Static/images/bottom_img/zhuanti1@3x.png" alt="" style="width: 25px">
					<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/zhuanti2@3x.png" alt="" style="width: 25px"><?php endif; ?> -->
					限免
				</a>
			</li>

			<li style="width:24%;">
				<?php if(($isweixin != 1)): ?><a href="<?php echo U('Mobile/User/buyEgold');?>" style="border-color:transparent;font: normal 18px/20px  serif; font-weight:lighter;color:white;">
						<?php else: ?>
						<a href="<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>" style="border:none;font: normal 18px/20px  serif; font-weight:lighter;color:white;"><?php endif; ?>
					<!-- <?php if($type == 4): ?><img src="/Template/mobile/default/Static/images/bottom_img/chongzhi1.png" alt="" style="width: 25px">
						<?php else: ?>
						<img src="/Template/mobile/default/Static/images/bottom_img/chongzhi2.png" alt="" style="width: 25px"><?php endif; ?> -->
					<i class="fa fa-diamond" style="font-size:13px;"></i>充值
				</a>
			</li>
		</ul>
	</div>
</div> 
      <form action="" id="search-form2" class="navbar-form form-inline" method="post" onsubmit="return false">
      <input type="hidden" id="groupid" name="groupid" value="">
      <input type="hidden" id="sortid" name="sortid" value="">
            <ul class="category">
                <li class="fgroup"><label><b>类型:</b></label></li>
     
                <li class="group active" data-id="2">  男生 </li>
                <li class="group <?php if($groupid == $v1.groupid): ?>active<?php endif; ?>" data-id="1">  女生 </li>       
            </ul>
            <div style="clear:both;"></div>
            <ul class="category">
                 <li id="category" class="ffcate"><label><b>分类:</b></label></li>
                 
                 <li class="cate selactive" data-id="0" >全部</li>
                <?php if(is_array($sortList)): foreach($sortList as $k1=>$v1): ?><li class="cate" style="float:left;margin-left: 10px; " data-id="<?php echo ($v1["sortid"]); ?>">
                        
                        <span class="catename" style="padding-top: 10%;text-align:center">
                            <?php echo ($v1["caption"]); ?>
                        </span>
                        
                    </li><?php endforeach; endif; ?>
                
            <!--  -->
            </ul>
            </form>
            <div style="clear:both;"></div>
           
            
        <section id="ajax_return_channel">
               
        </section>
        <script>
            $(function()
            {
                
                page =1
                ajax_get_table('search-form2',page);

            });

            $(".group").live("click",function(){
                groupid = $(this).attr('data-id');
                cateid = 0;
                $('.selactive').removeClass('selactive');
                $('#fcate').addClass('selactive');
                html=''
                html += "<li id='fcate' class='cate selactive' data-id='0'>全部</li>";
                $.ajax({
                    type:'POST',
                    url:"/index.php?m=Mobile&c=Index&a=getSortList",
                    data:{groupid:groupid,cateid:cateid},
                    success : function(data){
                        $('.cate').remove();
                        $.each(data,function(i,n){
                            

                            html+="<li class='cate' data-id='"+n.sortid+"'>";
                            html+= "<span class='catename' style='padding-top: 10%;text-align:center'>"+n.caption+"</span>";
                            html+="</li>";
                                                                                       
                        });
                        //$("#fcate").remove();
                        //console.log(html);
                        $("#category").after(html);
                        

                    }

                });
                    
                  $('.active').removeClass('active');
                         $(this).addClass('active');
                        ajax_get_table("search-form2",1); 
            });



            function ajax_get_table(form,page)
            {
                $("#groupid").val($('.active').attr('data-id')) ;
                $("#sortid").val($('.selactive').attr('data-id')) ;
                //alert(111);
                $.ajax(
                {
                    type    : "POST",
                    url     : "/index.php?m=Mobile&c=Index&a=getCateBook&p="+page,
                    data    : $('#' + form).serialize(),// 你的formid

                    success : function(datas)
                    {
                        //$("#orderBtnGroup").children().removeClass("btn-primary active");
                        //console.log(datas);
                        $("#ajax_return_channel").html('');

                        $("#ajax_return_channel").append(datas);

                        //ajax_get_table2('search-form2');


                    },
                    error   : function(datas)
                    {
                        alert('error -- data = ' + datas.responseText);
                    }
                });
            }
            //点击分类切换
            $('.cate').live("click",function(){
                
                $('.selactive').removeClass('selactive');
                $(this).addClass('selactive');
                ajax_get_table('search-form2',1);
            });
            function goTop()
            {
                $('html,body').animate({'scrollTop':0},600);
            }

             
        </script>

            

        
    </body>
</html>