<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html >
<html xmlns:wb="http://open.weibo.com/wb">
	<head>
		<meta name="Generator"/>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width">
		<link rel="shortcut icon" href="/Public/images/favicon.ico" />
		<title><?php echo ($site_title); ?></title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=0"/>
		<link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/public.css"/>
		<link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/index.css"/>
		<link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

		<script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/TouchSlide.1.1.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.json.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/touchslider.dev.js"></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/layer.js" ></script>
		<script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>
		<script src="http://tjs.sjs.sinajs.cn/open/api/js/wb.js?appkey=2673436165" type="text/javascript" charset="utf-8"></script>
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
		<div id="page" class="showpage">
		
			<div>
				 <!-- <div id="fake-search" class="index_search" style="margin-bottom:0px;padding-bottom:0px;padding-top:5px;">
					<form class="index_search_mid" id ="searchForm" action="<?php echo U('Index/searchBook');?>" method="POST">
						<span data-act="submit">
							<i></i>
	  					</span>
						<input  type="text" placeholder="书名/作者名" name="key_word" />
					</form>
				</div> -->
				
				<div style="width:100%;height: 40px;padding-top: 10px;background: #fff;margin-bottom: 10px;">
					<div style="margin:0px 10px; font-size:14px; ">
						<div style="display:inline-block;">
							<a style="width: 20%;color:#6699CC;font-size: 16px;" href="<?php echo U('Mobile/User/index');?>">
								<!-- <i class="fa   fa-leanpub " style="color:#efa164;font-size:20px;" ></i><span style="" >书架</span> -->
								<img src="<?php echo ($faceImg); ?>" style="width:32px;border-radius: 18px;"/>&nbsp;&nbsp;<?php echo ($username); ?>
							</a>
						</div>
						<!--语音朗读-->
						<!-- <embed src="/audio.mp3" autostart="true" loop="true" hidden="true"></embed> -->
						<!-- <span style="width: 40%;margin-left:-13%;">
							<form style="display: inline-block"  id="searchForm" action="<?php echo U('Index/searchBook');?>" method="POST">
								<input type="text" name="key_word" placeholder="书名/作者名" style="width:80%;margin-left: 18%;border: 0px;border-bottom: 1px solid #ccc">
								<img  data-act="submit" style="width: 20px;margin-top: -5px;margin-left: -30px;" src="/Template/mobile/default/Static/images/xin/icosousuo.png">
							</form>
						</span> -->
						<!-- <span style="width: 40%;margin-left: 5%;" id="search">
							<img data-act="submit" style="width: 25px;margin-top: -5px;" src="/Template/mobile/default/Static/images/xin/icosousuo.png">
						</span> -->

						<a href="<?php echo U('Mobile/User/index');?>" style="float: right;">
						<span style="width: 20%;color:#6699CC;font-size: 16px;">
								 <!-- <img src="<?php echo ($faceImg); ?>" style="width:36px;border-radius: 18px;">
								 <span style="font-size:15px;">个人中心</span> -->
								 <!-- <ul style="line-height:0px;text-align:center;">
									<li style="margin-top:-6px;"><img src="<?php echo ($faceImg); ?>" style="width:32px;border-radius: 18px;"></li>
									<li style="font-size:12px;margin-top:7px;font-weight:bold;">个人中心</li>
								 </ul> -->
								 个人中心
						</span>
						</a>

					</div>
				</div>

				<div class="index_search" style=" font-size:14px;height: 30px;padding: 5px; ">
				<?php if(empty($lastbook["articlename"])): ?><a  style="color: #0099FF;font-size: 16px;" href="#">
					<i class="fa   fa-clock-o " ></i>
					上次阅读:<?php echo ($lastbook["lastread"]); ?></a>
				<?php else: ?>
				<a  style="color: #0099FF;font-size: 16px;" href="/Article/readChapterFromBookCase/bookcase_id/<?php echo ($lastbook["caseid"]); ?>.html">
					<i class="fa   fa-clock-o " ></i>
					上次阅读:<?php echo ($lastbook["lastread"]); ?></a><?php endif; ?>	
				</div>
				

				<div id="scrollimg" class="scrollimg">
					<div class="bd">
						<ul>
							<?php if(is_array($slidebannerlist)): $i = 0; $__LIST__ = $slidebannerlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$book): $mod = ($i % 2 );++$i;?><li>
									<a href="<?php echo ($book["url"]); ?>" >
									<img src="<?php echo ($book["bookcover"]); ?>" title="<?php echo ($book["bookid"]); ?>" width="100%"/>
									</a>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					</div>

	  				<div class="hd">
						<ul></ul>
	  				</div>
				</div>
				<!--<div class="goBookshelf">-->
					<!--<a href="<?php echo U('Mobile/User/getBookCase');?>">-->
						<!--<dt>书架</dt>-->
						<!--<dd style="color:#aaaaaa;">&nbsp;</dd>-->
					<!--</a>-->
				<!--</div>-->
				<!--<div class="home_menu">-->
					<!--<a href="<?php echo U('Mobile/User/getBookCase');?>">-->
						<!--<em></em>-->
						<!--<span>书架</span>-->
					<!--</a>-->
				<!--</div>-->
				<script type="text/javascript">
					TouchSlide(
					{
						slideCell : "#scrollimg",
						titCell   : ".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
						mainCell  : ".bd ul",
						effect    : "leftLoop",
						autoPage  : true,//自动分页
						autoPlay  : true //自动播放
					});
				</script>


				<section class="index_floor_lou">
					<?php if(is_array($hotcommondbooklist)): $i = 0; $__LIST__ = array_slice($hotcommondbooklist,0,1,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$booklist): $mod = ($i % 2 );++$i;?><div class="floor_body">
							<h2 style="margin-top: 10px;font-size: 18px;border-bottom: 1px solid #efefef">
								<em></em>
								<?php echo ($booklist["title"]); ?>

								<div class="geng">
									<a href="<?php echo U('Index/getHotCommendBookList',array('show_id'=> $booklist['showid']));?>">
										更多
									</a>
									<span></span>
								</div>
							</h2>

							<div>
								<ul style="width: 100%">
									<?php if(is_array($booklist["booklist"])): $i = 0; $__LIST__ = $booklist["booklist"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$book): $mod = ($i % 2 );++$i;?><li style="float: left;width: 28%;border-bottom: none;padding: 2% 2%;margin-left: 1%;">
											<a href="<?php echo ($book["url"]); ?>" title="1">
												<img class="cover" alt="<?php echo ($book["info"]["articlename"]); ?>" src="<?php echo ($book["cover"]); ?>" style="height:130px;">
												<h4 style="font-size: 13px;font-weight:normal;line-height: 20px;text-align: center">
													<?php echo mb_strlen($book['info']['articlename'],'utf-8')<=7?$book['info']['articlename']:(msubstr($book['info']['articlename'],0,6,'utf-8',true));?>
												</h4>
											</a>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</ul>
							</div>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>

					<?php if(is_array($hotcommondbooklist)): $i = 0; $__LIST__ = array_slice($hotcommondbooklist,1,null,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$booklist): $mod = ($i % 2 );++$i;?><div class="floor_body">
							<h2 style="margin-top: 10px;font-size: 18px;border-bottom: 1px solid #efefef">
								<em></em>
								<?php echo ($booklist["title"]); ?>

								<div class="geng">
									<a href="<?php echo U('Index/getHotCommendBookList',array('show_id'=> $booklist['showid']));?>">
									 	更多
									</a>
									<span></span>
								</div>
							</h2>

							<div id="scroll_promotion">
								<ul>
									<?php if(is_array($booklist["booklist"])): $i = 0; $__LIST__ = array_slice($booklist["booklist"],0,4,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$book): $mod = ($i % 2 );++$i;?><li>
											<a href="<?php echo ($book["url"]); ?>" title="1" class="flex">
												<div class="cover">
													<img  alt="<?php echo ($book["info"]["articlename"]); ?>" src="<?php echo ($book["cover"]); ?>">

												</div>

												<div class="bInfo" id="bookintro">
													<h4 class="bookname" style="font-size: 16px;">
														<?php echo ($book["info"]["articlename"]); ?>
														<!-- <span class="booksort">
															<?php echo ($book["info"]["sort"]); ?>
														</span> -->
													</h4>

													<p class="bookinfo" style="color:#B0B0B0;font-size: 14px;">
														<?php echo ($book["info"]["intro"]); ?>
													</p>
													<!-- <p class="bookauthor" >
														<span>
															<?php echo ($book["info"]["author"]); ?>
														</span>

														<span class="booksize">
															<?php echo ($book["info"]["size"]); ?>
														</span>

														<span class="booksort">
															<?php echo ($book["info"]["sort"]); ?>
														</span>
													</p> -->
												</div>
											</a>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</ul>
							</div>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
					<!--免费专区-->					
					<?php if(!empty($freeBookList)): ?><div class="floor_body">
							<h2 style="margin-top: 10px;border-bottom: 1px solid #efefef">
								<i class="fa fa-clock-o" style="color: #efa164;margin-left:10px;"></i>
								限时免费
								<div class="countdown" data-time="<?php echo date('m/d/Y H:i:s',$freeBookList[0]['freetime']);?>" style="font-size: 14px;float: right;margin-right: 10px;">
									<input style="display: none" type="text" readonly="readonly" value="<?php echo date('m/d/Y H:i:s',$freeBookList[0]['freetime']);?>" id="edtime"/>
									还剩
									<span id="d" class="days" style="color: #C00">00</span> 天
									<span id="h" class="hours" style="color: #C00">00</span> 时
									<span id="i" class="minutes" style="color: #C00">00</span> 分
									<span id="s" class="seconds" style="color: #C00">00</span> 秒
								</div>
							</h2>

							<div>
								<ul style="width: 100%;">
									<?php if(is_array($freeBookList)): $i = 0; $__LIST__ = $freeBookList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$book): $mod = ($i % 2 );++$i;?><li style="float: left;width: 28%;border-bottom: none;padding: 2% 2%;margin-left: 1%;">
											<a href="<?php echo ($book['url']); ?>" title="1">
												<img class="cover" alt="<?php echo ($book['articlename']); ?>" src="<?php echo ($book['cover']); ?>" style="height:130px;">
												<h4 style="font-size: 14px;font-weight:normal;line-height: 20px;text-align: center">
													<?php echo mb_strlen($book['articlename'],'utf-8')<=7?$book['articlename']:(msubstr($book['articlename'],0,6,'utf-8',true));?>
												</h4>
											</a>
										</li><?php endforeach; endif; else: echo "" ;endif; ?>
								</ul>
							</div>
						</div><?php endif; ?>
				</section>
				<!--关注微信二维码-->
				<div align="center">
					<p style="font-size:14px;color:#787878;">关注微信公众号: <?php echo ($wxnum); ?> ，方便下次阅读</p>
					<p style="font-size:14px;color:#787878;">微信内可长按扫码识别</p>
					<img style="margin:20px;" src=" http://open.weixin.qq.com/qr/code/?username=<?php echo ($wxnum); ?>" width="200" height="200" title="" />
					<p style="font-size:15px;color: #9d9d9d;">郑州心动文化传媒有限公司</p>
				</div>
				
				<!-- <a href="<?php echo U('Mobile/User/getBookCase');?>" class="gotoBookshelf">
					<img src="/Template/mobile/default/Static/images/bottom_img/bookshelf.png">
				</a>
				<a href="javascript:goTop();" class="gotop">
					<img src="/Template/mobile/default/Static/images/topup.png">
				</a> -->
			</div>
			
			<script>
				function goTop()
				{
					$('html,body').animate({'scrollTop':0},600);
				}
			</script>
			<script type="text/javascript">
				$(function()
				{
					width = document.documentElement.clientWidth - 112;

					width = 0.9 * width;

					$('.bookleft').css({width:width});

					$('#search_text').click(function()
					{
						$(".showpage").children('div').hide();
						$("#search_hide").css('position','fixed').css('top','0px').css('width','100%').css('z-index','999').show();
					})

					$('#get_search_box').click(function()
					{
						$(".showpage").children('div').hide();
						$("#search_hide").css('position','fixed').css('top','0px').css('width','100%').css('z-index','999').show();
					})

					$("#search_hide .close").click(function()
					{
						$(".showpage").children('div').show();
						$("#search_hide").hide();
					})

					/*$("[data-act=submit]").click(function()
					{
						if($("input:text[name='key_word']").val()=="")
						{
							window.alert("请输入您要查询的内容！");
						}
						else
						{
							$('#searchForm').submit();
						}
					})*/
					/*搜索 Daisy*/
					$('#search').click(function(){
                        $(location).attr('href', "/Index/search");
					});
				});

			</script>
			<!-- 限免书籍倒计时 -->
			<!-- <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.min.js"></script>
			<script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.downCount.js"></script>
			<script type="text/javascript">
				var _time="<?php echo date('m/d/Y H:i:s',$freeBookList[0]['freetime']);?>";
                $('.countdown').downCount({
                    date: _time,
                    offset: +10
                }, function () {
                    alert('限时免费书籍时间已到，敬请期待下次活动!');
                });
			</script> -->
			<!-- <script type="text/javascript" src="/Template/mobile/default/Static/js/time/laydate.js"></script> -->
			<script type="text/javascript">
                window.onload=function (){
                    function clock(){
                        // 当前时间
                        var nowTime =new Date().getTime();
                        // 2016/12/22 hh:mm:ee
                        // 结束时间
                        var endTime = new Date(document.getElementById('edtime').value);
                        // 相差的时间
                        var t = Number( endTime.getTime() - nowTime);
                        if(t<=0){
                            $('.countdown').html('限时免费书籍时间已到，敬请期待下次活动!');
                            return false;
                        }

                        var d = Math.floor(t/1000/60/60/24);
                        var h = Math.floor(t/1000/60/60%24);
                        var i = Math.floor(t/1000/60%60);
                        var s = Math.floor(t/1000%60);

                        document.getElementById('d').innerHTML = d;
                        document.getElementById('h').innerHTML = h;
                        document.getElementById('i').innerHTML = i;
                        document.getElementById('s').innerHTML = s;
					}
					if(document.getElementById('edtime').value !=''){
						setInterval(clock, 1000);
                    	clock();
					}
                    
				}
			</script>
		</div>
	</body>
</html>