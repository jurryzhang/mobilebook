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
        <link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/read.css">
        <link rel="stylesheet" type="text/css" href="/Template/mobile/default/Static/css/yue.css">
		<link rel="stylesheet" type="text/css" href="/Public/bootstrap/css/font-awesome.min.css">
        <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/TouchSlide.1.1.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.json.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.cookie.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/touchslider.dev.js"></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/layer_mobile/layer.js" ></script>
        <script type="text/javascript" src="/Template/mobile/default/Static/js/common.js"></script>
       <!-- <link href="/Public/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/Public/bootstrap/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <script src="/Public/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>-->
    </head>
	
    <body class="chapter" data-con="read_cont">
	<script>
	 //界面初始化
                readInit();
                function readInit(){
                    if($.cookie("shuhai_read_fs")){
                        var _fsObj=$.cookie("shuhai_read_fs");
                        //			console.log(_fsObj);
                        _fsObj = eval("("+_fsObj+")");
                        //			console.log(_fsObj.lh);
                        $("[data-act=read_content]").children("p").css({"font-size":_fsObj.fs + "px","line-height":_fsObj.lh + "px","margin":_fsObj.pm + "px 0"});
                    }
                    if($.cookie("shuhai_read_mod")){
                        var _modObj=$.cookie("shuhai_read_mod");
                       // console.log(_modObj);
                        _modObj = eval("("+_modObj+")");
                        $("[data-con=read_cont]").toggleClass(_modObj.mode);
                        $("body").css("background",_modObj.bodybg);
                        $(".read_vf_nav").css("background",_modObj.bodybg);
                        $(".chapter_content p").css("color",_modObj.fontColor);
                        $(".read_vf_nav a").css("color",_modObj.fontColor);
                        //			$(".content").css("background",_modObj.con);
                        //			$(".content a").css("color",_modObj.cona);
                    }
                }
	</script>
        <div id="page">
           
           

            <div>
                <section>
                    <!--语音朗读-->
                        <embed id='audiofile' src="<?php echo ($audiodata["data"]); ?>" autostart="true" loop="true" hidden="true"></embed> 
                       <input type="hidden" id="sumf" name="countfile" value="<?php echo ($audiodata["countfile"]); ?>">
                    <div style="width: 100%;height: 100%;">
                        <ul style="margin-top: 10px;width: 100%;height: 100%;">
                            <li style="width: 15%;display: inline-block;margin-left: -8%;text-align: center;vertical-align: middle">
                                <a href="<?php echo U('Article/index',array('id' => $chapter['articleid']));?>" style="">
                                    <img src="<?php echo ($chapter["cover"]); ?>" style="width: 50px;height: 50px;border-radius: 50%;" >
                                </a>
                            </li>
                            <!--<li style="width: 60%;vertical-align: middle;text-align:center;display: inline-block;">-->
                            <li style="width: 57%;vertical-align: middle;display: inline-block;margin-left: 10%">
                                <span class="read_title"  style="margin-bottom: 15px;font-size: 20px;padding: 0px;" >
                                    <?php echo ($chapter["chaptername"]); ?>
                                </span>
                            </li>
                            <li style="width: 10%;display: inline-block;text-align: center;vertical-align: middle;">
                                <span class="config">
                                    <span class="night">
                                        <a href="javascript:;" class="szbut_a" data-act="read_mode">
                                            <i></i>
                                        </a>
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                            <div class="yue chapter_content " data-act="read_content" style="padding:0 0.2em;">
                                <p>
                                    <?php echo ($chapter["content"]); ?>
                                </p>
                            </div>
                        </div>

                    

                </section>
                <!-- <a href="javascript:goTop();" class="gotop" style="bottom:170px;">
                    <img src="/Template/mobile/default/Static/images/topup.png">
                </a> -->
            </div>
            <!--打赏、催更、评论-->
<div class="liwu" style="text-align: center;margin-top: -30px;">
    <!--<a href="javascript:void(0)" class="cuigeng" style="margin-right:12%">
        <div style="display: inline-block">
            <img style="width:40%;" src="/Template/mobile/default/Static/images/liwu/cuigeng.png">
            <p>催更</p>
        </div>
    </a>-->
    <div class="cuigeng" style="display: inline-block;width: 25%;font-size: 13px;vertical-align: middle">
        <img style="width:30%;" src="/Template/mobile/default/Static/images/liwu/cuigeng.png">
        <ul style="display: inline-block;line-height: 16px;vertical-align: middle">
            <li>催更</li>
            <li style="color: #909090"><?php echo ($bookinfo['sumhurry']); ?></li>
        </ul>
    </div>
    <div class="dashang" style="display: inline-block;width: 25%;font-size: 13px;vertical-align: middle;">
        <img style="width:30%;" src="/Template/mobile/default/Static/images/liwu/dashang.png">
        <ul style="display: inline-block;line-height: 16px;vertical-align: middle;">
            <li>打赏</li>
            <li style="color: #909090"><?php echo ($bookinfo['sumgift']); ?></li>
        </ul>
    </div>
    <div class="pinglun" style="display: inline-block;width: 25%;font-size: 13px;vertical-align: middle;">
        <img style="width:30%;" src="/Template/mobile/default/Static/images/liwu/pinglun.png">
        <ul style="display: inline-block;line-height: 16px;vertical-align: middle;">
            <li>评论</li>
            <li style="color: #909090"><?php echo ($bookinfo['sumcomment']); ?></li>
        </ul>
    </div>
</div>



<div class="read_vf_nav tborder" style=" margin-top:30px;margin-bottom:30px;">
    <!--
    <div>
        <?php if($chapter['prechapter'] != -1): ?><span url="<?php echo U('Article/readChapter',array('chapter_id' => $chapter['prechapter'],'book_id' => $chapter['articleid']));?>">
                上一章
            </span>
        <?php else: ?>
            <span >
                已经是第一章
            </span><?php endif; ?>
    </div>
    <div class="catalogue">
        <span url="<?php echo U('Article/getDirectory',array('id' => $chapter['articleid']));?>">目录</span>
    </div>-->
	<?php if($chapter['nextchapter'] != -1): ?><a style="text-align: center; border: solid #666 1px;padding-top: 5px;margin-left: 20px;border-radius: 5px;width: 70%;" href="<?php echo U('Article/readChapter',array('chapter_id' => $chapter['nextchapter'],'book_id' => $chapter['articleid']));?>">
    <?php else: ?>
	 <a style="text-align: center; border: solid #666 1px;padding-top: 5px;margin-left: 20px;border-radius: 5px;width: 70%;" href="javascript:void(0)" onclick="alert('当前章节已经是最后一章! ')"><?php endif; ?>
        <?php if($chapter['nextchapter'] != -1): ?><span url="<?php echo U('Article/readChapter',array('chapter_id' => $chapter['nextchapter'],'book_id' => $chapter['articleid']));?>">
                下一章
            </span>
        <?php else: ?>
            <span >
                已经是最后一章
            </span><?php endif; ?>
    </a>

    <a id="menu" href="javascript:void(0)" onclick="menudisplay()" style="text-align: center;border: solid #666 1px;padding-top: 5px;margin:0 20px;border-radius: 5px;width: 15%;">
            <span >
               菜单
            </span>
    </a>
</div>
<!--菜单项-->
<div  id='menu_nav' style="display: none">

    <div class="read_vf_nav tborder" style="margin-top:2% ;margin-left:5%;margin-bottom:4% ;width:94%;">

        <?php if($chapter['prechapter'] != -1): ?><a  href="<?php echo U('Article/readChapter',array('chapter_id' => $chapter['prechapter'],'book_id' => $chapter['articleid']));?>" class="read_foot_a"  >
                上一章  </a>
            <?php else: ?>
            <a  href="javascript:void(0)"  class="read_foot_a" onclick="alert('当前章节已经是第一章! ')" style="color:#999">
                上一章
            </a><?php endif; ?>

        <a  href="<?php echo U('Article/index',array('id' => $chapter['articleid']));?>" class="read_foot_a"  >
            返回封面
            </a>
            <a href="/Article/getDirectory/id/<?php echo ($chapter['articleid']); ?>/chapterorder/<?php echo ($chapter['chapterorder']); ?>/.html#<?php echo ($chapter['chapterid']); ?>" class="read_foot_a"  >
                           返回目录
            </a>
            <a href="Index/index" class="read_foot_a"  >
                           返回首页
            </a>

    </div>
    <!--
    <div class="read_vf_nav tborder" style="margin: 10px;">

        <a  class="read_foot_a"  style="">
            签到送礼
        </a>
        <a  class="read_foot_a"  style="">
            评论
        </a>
        <a  class="read_foot_a"  style="">
           打赏
        </a>
        <a  class="read_foot_a"  style="">
            书架
        </a>

    </div>
    -->
</div>


<!--广告位-->


<?php if($ad["adtype"] > 0): ?><div style="width: 90%;margin: 0px 5%; border-bottom: 1px dashed #999;padding: 0px;">
        <p style="text-align: center;font-size: 12px;color: #999 ;margin-bottom: -8px;">广告
        </p>
    </div>
<?php if($ad["adtype"] == 1): ?><div class="ad-controller" onclick="adclick()" id="ad-controller" style="position: relative ;margin: 10px 2.5%; width: 95%;border: 1px solid #e7e7eb;height: 100px;background-color: #FFFFff;border-radius: 2px;">
    <div id="ad-content" style="vertical-align: middle;height: 100%;width: 100%">
        <img id='pic' src="<?php echo ($ad["picurl"]); ?>" style="margin:5px 8px 5px 5px;height:90px;width:90px;display: inline-block"/>
        <div style="width: 40% ;display: inline-block;font-size: 15px;vertical-align: middle;line-height: 18px;color: #8d8d8d">
            <h3 style="margin-bottom: 10px;color: #222 ;font-size: 15px; " id="title" ><?php echo ($ad["title"]); ?></h3>
            <span id="content"><?php echo ($ad["content"]); ?></span>
        </div>
        <div style="text-align:center;vertical-align:middle;position: absolute;width:20%;height:100%;display: inline-block;font-size: 15px;margin-right: 3%;">
             <span style=" position:absolute;text-align: center;padding:2px 5%;width: 80%;color: #40b950 ;border: 1px solid #40b950;border-radius: 5px;left:22%;top: 30%;right: -5px;">
                关 注
             </span>
        </div>
        <input type="hidden" value="<?php echo ($ad["url"]); ?>" name="url" id="adurl">
        <input type="hidden" value="<?php echo ($ad["id"]); ?>" name="id" id="adid">
    </div>
</div>

<?php elseif($ad["adtype"] == 2): ?>

    <div class="ad-controller" onclick="adclick()" id="ad-controller" style="position: relative ;margin: 10px 2.5%; width: 95%;border: 1px solid #e7e7eb;height: 100px;background-color: #FFFFff;border-radius: 2px;">
        <div id="ad-content" style="overflow: hidden;vertical-align: middle;height: 100%;width: 100%">
            <img src="http://admin.kyread.com<?php echo ($ad["picurl"]); ?>"style="margin:5px 8px 5px 5px;height:90px;width:90px;display: inline-block"/>
            <div style="width: 60% ;height:90%; margin:5px;display: inline-block;font-size: 15px;vertical-align: middle;line-height: 18px;color: #8d8d8d">
                <h3 style="margin: 5px 0;color: #222;font-size: 15px;" id="title"><?php echo ($ad["title"]); ?></h3>
                <span id="content"><?php echo ($ad["content"]); ?></span>
            </div>
            <div style="position: absolute;text-align: center; font-size:14px;width:25%;border:0px;border-radius:2px;background: rgba(40,40,40,.6);bottom: 0;right: 0; color: #FFFFff">活动推广</div>
            <input type="hidden" value="<?php echo ($ad["url"]); ?>" name="url" id="adurl">
            <input type="hidden" value="<?php echo ($ad["id"]); ?>" name="url" id="adid">
        </div>
    </div>
    <?php elseif($ad["adtype"] == 3): ?>
    <div class="ad-controller" onclick="adclick()" id="ad-controller" style="position: relative ;margin: 10px 2%;width: 96%;height: 100px;background-color: #FFFFff;border-radius: 2px;">
        <div id="ad-content" style="overflow: hidden;vertical-align: middle;height: 100%;width: 100%">
            <img id='pic' src="http://admin.kyread.com<?php echo ($ad["picurl"]); ?>" style="width: 100%;height:100%;display: inline-block"/>
            <div style="position: absolute; font-size:14px;text-align: center; width:25%;border:0px;border-radius:2px;background: rgba(40,40,40,.6);bottom: 0;right: 0; color: #FFFFff">活动推广</div>
            <input type="hidden" value="<?php echo ($ad["url"]); ?>" name="url" id="adurl">
            <input type="hidden" value="<?php echo ($ad["id"]); ?>" name="id" id="adid">
        </div>

    </div><?php endif; endif; ?>




<!--点击内容—字体背景设置-->
<div class="read_set" style="display: none" >
    <li style="">
        <label >字体</label>
        <label class="set_size" onclick="fontAdd();">A+</label>
        <label class="set_size"  onclick="fontrev();">默认</label>
        <label class="set_size"  onclick="fontdef();">A—</label>
    </li>
    <li>
        <label style="color: #FFFFff">背景</label>
        <label style="background-color:#B0B0B0" class="set_color" onclick="setcolor(1);"></label>
        <label style="background-color:#AEA99F" class="set_color" onclick="setcolor(2);"></label>
        <label style="background-color:#988E85" class="set_color" onclick="setcolor(3);"></label>
        <label style="background-color:#c7edcc"  class="set_color"  onclick="setcolor(4);"></label>
        <label style="background-color:#FCEDEA" class="set_color" onclick="setcolor(5);"></label>
        <label style="background-color:#E7F4FE"  class="set_color"  onclick="setcolor(6);"></label>
        <!-- <label style="background-color:#363637"  class="set_color"  onclick="setcolor(7);"></label> -->
    </li>
</div>

<!--start 打赏-->
<div class="want_dashang" style="display: none;width: 100%;height: 100%;position: relative;">
    <div class="want_dashang_top"></div>
    <div class="want_dashang_bottom">
        <div style="width:100%;height:20%;margin-top:3%;">
            <div style="width: 10%;position: relative;left: 3%;top: 8px;">
                <a href="/User/index.html">
                    <span style="width: 20%">
                        <img src="<?php echo ($userinfo["faceImg"]); ?>" style="width:36px;border-radius: 18px;">
                    </span>
                </a>
            </div>
            <div style="position: relative;left:13%;top: -33px;width: 30%;">
                <a href="/User/index.html">
                    <p class="bookname" style="font-size: 13px;">
                        <?php echo ($userinfo["name"]); ?>
                    </p>
                    <p class="bookinfo" style="font-size: 12px;margin-top: -10px;height: 20px;color:#f89345">
                        <?php echo ($userinfo["egold"]); ?> 书币
                    </p>
                </a>
            </div>
            <div style="position: relative;right:-75%;top: -72px;width: 30%;text-align: center;line-height: 20px;">
				<?php if(($isweixin != 1)): ?><a href="<?php echo U('Mobile/User/buyEgold');?>"  style="font-size: 15px;color:#f89345;">
                        充值
                    </a>
                    <?php else: ?>
                    <a href="<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>"  style="font-size: 15px;color:#f89345;">
                        充值
                    </a><?php endif; ?>
                <!-- <a href="/User/buyEgold.html" style="font-size: 15px;color:#f89345;">充值</a> -->
            </div>
        </div>
        <div class="dashang_icon_all">
            <!-- <table>
                <tr>
                    <td class="active" data-val="10" data-type="1"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv01.png"></td>
                    <td data-val="100" data-type="2"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv02.png"></td>
                    <td data-val="500" data-type="3"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv03.png"></td>
                    <td data-val="1000" data-type="4"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv04.png"></td>
                </tr>
                <tr>
                    <td data-val="5000" data-type="5"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv05.png"></td>
                    <td data-val="10000" data-type="6"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv06.png"></td>
                    <td data-val="100000" data-type="7"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv07.png"></td>
                </tr>
            </table> -->
			<ul style="width:100%">
                <li data-val="10" data-type="1"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv01.png"></li>
                <li  class="active" data-val="100" data-type="2"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv02.png"></li>
                <li data-val="500" data-type="3"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv03.png"></li>
                <li data-val="1000" data-type="4"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv04.png"></li>
                <li data-val="5000" data-type="5"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv05.png"></li>
                <li data-val="10000" data-type="6"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv06.png"></li>
                <li data-val="100000" data-type="7"><img class="dashang_icon" src="/Template/mobile/default/Static/images/liwu/nan-giv07.png"></li>
            </ul>
        </div>
        <div class="want_egold">
            <span style="font-size: 13px;">数量</span>
            <i class="fa fa-minus yunsuan jian"></i>
            <input type="text" value="1" name="count" readonly="readonly" style="border: none;width: 32px;text-align: center;">
            <!--<span class="count" style="text-align: center;font-size: 12px;margin:0px 3px;">1</span>-->
            <i class="fa fa-plus yunsuan jia"></i>
            <span style="font-size: 13px;margin-left: 15px">需：</span>
            <span class="egold" style="font-size: 12px;color:#f89345;">100</span>
            <span style="font-size: 12px;color: #BBB">书币</span>
            <a href="javascript:void(0);" class="zengsong"
               data-uid="<?php echo ($userinfo["uid"]); ?>" data-username="<?php echo ($userinfo["name"]); ?>" data-articlename="<?php echo ($chapter["articlename"]); ?>" data-articleid="<?php echo ($chapter["articleid"]); ?>" data-chapterid="<?php echo ($chapter["chapterid"]); ?>" date-egold="<?php echo ($userinfo["egold"]); ?>">
                赠送
            </a>
        </div>
    </div>
</div>
<!--end 打赏-->

<!--start 催更-->
<div class="want_cuigeng" style="display: none;width: 100%;height: 100%;position: relative;">
    <div class="want_cuigeng_top"></div>
    <div class="want_cuigeng_bottom">
        <div style="width:100%;height:20%;margin-top:3%;">
            <div style="width: 10%;position: relative;left: 3%;top: 8px;">
                <a href="/User/index.html">
                    <span style="width: 20%">
                        <img src="<?php echo ($userinfo["faceImg"]); ?>" style="width:36px;border-radius: 18px;">
                    </span>
                </a>
            </div>
            <div style="position: relative;left:13%;top: -33px;width: 30%;">
                <a href="/User/index.html">
                    <p class="bookname" style="font-size: 13px;">
                        <?php echo ($userinfo["name"]); ?>
                    </p>
                    <p class="bookinfo" style="font-size: 12px;margin-top: -10px;height: 20px;color:#f89345">
                        <?php echo ($userinfo["egold"]); ?> 书币
                    </p>
                </a>
            </div>
            <div style="position: relative;right:-75%;top: -72px;width: 30%;text-align: center;line-height: 20px;">
				<?php if(($isweixin != 1)): ?><a href="<?php echo U('Mobile/User/buyEgold');?>"  style="font-size: 15px;color:#f89345;">
                        充值
                    </a>
                    <?php else: ?>
                    <a href="<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>"  style="font-size: 15px;color:#f89345;">
                        充值
                    </a><?php endif; ?>
                <!-- <a href="/User/buyEgold.html" style="font-size: 15px;color:#f89345;">充值</a> -->
            </div>
        </div>
        <div class="cuigeng_icon_all">
            <!-- <table>
                <tr>
                    <td class="activeC" data-val="300" data-size="3000"><ul><li>3千字</li><li>更新</li></ul></td>
                    <td data-val="600" data-size="6000"><ul><li>6千字</li><li>更新</li></ul></td>
                    <td data-val="900" data-size="9000"><ul><li>9千字</li><li>更新</li></ul></td>
                </tr>
                <tr>
                    <td data-val="1200" data-size="12000"><ul><li>1.2万字</li><li>更新</li></ul></td>
                    <td data-val="1500" data-size="15000"><ul><li>1.5万字</li><li>更新</li></ul></td>
                    <td data-val="1800" data-size="18000"><ul><li>1.8万字</li><li>更新</li></ul></td>
                </tr>
            </table> -->
			<ul class="cuiUl">
                <li class="activeC" data-val="300" data-size="3000"><ul><li>3千字</li><li>更新</li></ul></li>
                <li data-val="600" data-size="6000"><ul><li>6千字</li><li>更新</li></ul></li>
                <li data-val="900" data-size="9000"><ul><li>9千字</li><li>更新</li></ul></li>
                <li data-val="1200" data-size="12000"><ul><li>1.2万字</li><li>更新</li></ul></li>
                <li data-val="1500" data-size="15000"><ul><li>1.5万字</li><li>更新</li></ul></li>
                <li data-val="1800" data-size="18000"><ul><li>1.8万字</li><li>更新</li></ul></li>
            </ul>
        </div>
        <div class="want_cui_egold">
            <span style="font-size: 13px;">请输入催更金额：
                <input type="text" value="300" name="cui_egold" style="border: none;width: 140px;color: #f89345;">
                <p style="font-size: 12px;color: #CCC;margin-top:-10px">(书币;不小于100的整数)</p>
            </span>
            <a href="javascript:void(0);" class="cuigeng-btn"
               data-uid="<?php echo ($userinfo["uid"]); ?>" data-articleid="<?php echo ($chapter["articleid"]); ?>" data-username="<?php echo ($userinfo["name"]); ?>" data-articlename="<?php echo ($chapter["articlename"]); ?>" data-chapterid="<?php echo ($chapter["chapterid"]); ?>" date-egold="<?php echo ($userinfo["egold"]); ?>">
                催更
            </a>
        </div>
    </div>
</div>
<!--end 催更-->





<script>
    $(".read_vf_nav div span").click(function () {
        if(typeof($(this).attr("url"))=="undefined")
        {
        }
        else
        {
            window.location.replace($(this).attr("url"));
        }
    })

    var flag=false;
    function menudisplay(){
        flag=!flag;
        console.log(flag);
        if(flag){
            $('#menu_nav').css("display","block");
            var h = $(document).height()-$(window).height();
            $(document).scrollTop(h);
        }else{
            $('#menu_nav').css("display","none");
        }
    }

    //弹出设置菜单
    $(".chapter_content").click(
        function () {
            $(".read_set").slideToggle('fast');
        }
    );

    var _content = $("[data-act=read_content]");
    var _change=4;

    //设置字体加
    function fontAdd() {
        var _cont_size = $("[data-act=read_content]").children("p:first").css("font-size");
        var _cont_marg = $("[data-act=read_content]").children("p:first").css("margin");
        var _last_size = parseInt(_cont_size)+_change;
        var _chan_marg = parseInt(_cont_marg)+_change;
        console.log(_cont_size,_cont_marg);
        /*if (_last_size>=36) {
            _last_size = 36;
            _chan_marg = 20;
        }*/
         _last_size = 24;
         _chan_marg = 13;
        setFontsize(_last_size,_chan_marg);
    }

    //设置字体减
    function fontdef() {
        var _cont_size = $("[data-act=read_content]").children("p:first").css("font-size");
        var _cont_marg = $("[data-act=read_content]").children("p:first").css("margin");
        var _last_size = parseInt(_cont_size)-_change;
        var _chan_marg = parseInt(_cont_marg)-_change;
        /*if (_last_size<=12){
            _last_size=12;
            _chan_marg=6;
        }*/
         _last_size = 16;
         _chan_marg = 8;
        setFontsize(_last_size,_chan_marg);

    }


    //设置默认字体
    function fontrev () {
        _last_size=20;
        _chan_marg=11;
        setFontsize(_last_size,_chan_marg);

    }


    //设置字体
    function setFontsize(_last_size,_chan_marg) {
        console.log(_last_size,_chan_marg);
        var _last_lh = _last_size *1.6;
        _content.children("p").css({"font-size":_last_size + "px","line-height":_last_lh + "px","margin":_chan_marg + "px 0"});
        var _read_str = '{"fs":"'+_last_size + '","lh":"'+_last_lh + '","pm":"' +_chan_marg + '"}';
        $.cookie('shuhai_read_fs',_read_str,{expires:30,path:"/"});

    }

    //设置背景色
    function setcolor(num){
       // var _color={"bodybg":"url(/Template/mobile/default/Static/images/readbg_0"+num+".jpg)","nightcolor":"#333","conn":"#353434","conan":"#ffffff"};
       var bgcolor=["#ffffff","#B0B0B0", "#AEA99F", "#988E85", "#c7edcc", "#FCEDEA","#E7F4FE","#363637"];
       var _color={"bodybg":bgcolor[num],"nightcolor":"#333","conn":"#353434","conan":"#ffffff"};

        $("body").css("background",_color.bodybg);
        $(".read_vf_nav").css("background",_color.bodybg);
        $(".chapter_content p").css("color",_color.nightcolor);
        $(".read_vf_nav a").css("color",_color.nightcolor);
        _read_str = '{"bodybg":"'+_color.bodybg + '","con":"'+_color.con + '","conan":"'+_color.cona + '","mode":"day","fontColor":"'+_color.nightcolor + '"}';
        $.cookie('shuhai_read_mod',_read_str,{expires:30,path:"/"});
    }

    /*************************daidai**************************************/
	var isweixin=<?php echo ($isweixin); ?>;
	var useregold=<?php echo ($userinfo['egold']+0); ?>;

	var needegoldS=$('.egold').html();
    var count=$('input[name=count]').val();
    var countSum=$('.dashang ul li:last').html();

    var needegoldC=$('input[name=cui_egold]').val();
    var wordsize=$('li[class=activeC]').attr('data-size');
    var cuiSumegold=$('.cuigeng ul li:last').html();

	var commentCount=$('.pinglun ul li:last').html();
	
    /*****设置打赏弹出框***********************************************/
    $('.dashang').click(function(){
        $('.want_dashang').fadeIn('fast');
    });
    $('.want_dashang_top').click(function(){
        $('.want_dashang').fadeOut('fast');
    });

    /*点击打赏图标*/
    /*$('.dashang_icon_all td').click(function(){
        $(this).parents('table').find('td').removeClass('active');
        $(this).addClass('active');*/
    function getyuansuan(_this){
        var _tdVal=parseInt(_this.attr('data-val'));

        /*点击加减控制书币值*/
        var countBoj=$('input[name=count]');
        var _count=parseInt(countBoj.val());
        var _egold=_tdVal*_count;
        $('.egold').html(_egold);

        $('.want_egold .jian').click(function(e){
            e.preventDefault();
            _count=_count-1;
            _egold=_tdVal*_count;
            if(_count<=0){
                _count=1;
                _egold=_tdVal;
            }
            countBoj.val(_count);
            $('.egold').html(_egold);
        });

        $('.want_egold .jia').click(function(e){
            e.preventDefault();
            _count=_count+1;
            _egold=_tdVal*_count;
            countBoj.val(_count);
            $('.egold').html(_egold);
        });
    }
    getyuansuan($('.dashang_icon_all li:eq(1)'));
	$('.dashang_icon_all li').click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        getyuansuan($(this));
    });

        /*打赏_赠送btn*/
    $('.zengsong').click(function(){
        var uid=$(this).attr('data-uid');
        var username=$(this).attr('data-username');
        var articleid=$(this).attr('data-articleid');
        var articlename=$(this).attr('data-articlename');
        var chapterid=$(this).attr('data-chapterid');
        var giftstype=$('li[class=active]').attr('data-type');
        count=$('input[name=count]').val();
        needegoldS=$('.egold').html();
        $.ajax(
            {
                type    : "POST",
                url     : "/index.php?m=Mobile&c=Gifts&a=index",
                data    : {uid:uid,articleid:articleid,username:username,articlename:articlename,chapterid:chapterid,giftstype:giftstype,count:count,needegold:needegoldS} ,
                success : function(data)
                {
                    $('.want_dashang').fadeOut('fast');
                    successLater(data);

                    $('.dashang_icon_all ul li').removeClass('active');
                    $('.dashang_icon_all ul li:eq(1)').addClass('active');
                    $('input[name=count]').val(1);
                    $('.egold').html(100);
                    countSum=parseInt(countSum)+parseInt(count);
                    $('.dashang ul li:last').html(countSum);
                    commentCount=parseInt(commentCount)+1;
                    $('.pinglun ul li:last').html(commentCount);
                }
            });
    });


    /*****设置催更弹出框*************************************************/
    $('.cuigeng').click(function(){
        $('.want_cuigeng').fadeIn('fast');
    });
    $('.want_cuigeng_top').click(function(){
        $('.want_cuigeng').fadeOut('fast');
    });
    /*$('.cuigeng_icon_all table tr td').click(function(){
        $(this).parents('table').find('td').removeClass('activeC');
        $(this).addClass('activeC');*/
    $('.cuigeng_icon_all .cuiUl>li').click(function(){        
        $(this).addClass('activeC').siblings().removeClass('activeC');
        
        var _tdVal=parseInt($(this).attr('data-val'));

        $('input[name=cui_egold]').val(_tdVal);
    });
    /*催更*/
    $('.cuigeng-btn').click(function(){
        var uid=$(this).attr('data-uid');
        var username=$(this).attr('data-username');
        var articleid=$(this).attr('data-articleid');
        var articlename=$(this).attr('data-articlename');
        var chapterid=$(this).attr('data-chapterid');
        wordsize=$('li[class=activeC]').attr('data-size');
        needegoldC=$('input[name=cui_egold]').val();

        if(needegoldC<100){
            layer.open({
                content: '催更金额不得少于100'
                ,skin: 'msg'
                ,time: 1 //2秒后自动关闭
                ,end:function(){
                    location.reload();
                }
            });
            return false;
        }

        $.ajax(
            {
                type    : "POST",
                url     : "/index.php?m=Mobile&c=Gifts&a=urgeUpdate",
                data    : {uid:uid,articleid:articleid,username:username,articlename:articlename,chapterid:chapterid,wordsize:wordsize,needegold:needegoldC} ,
                success : function(data)
                {
                    $('.want_cuigeng').fadeOut('fast');
                    successLater(data);

                    $('.cuigeng_icon_all .cuiUl li').removeClass('activeC');
                    $('.cuigeng_icon_all .cuiUl li:eq(0)').addClass('activeC');
                    $('input[name=cui_egold]').val(300);
                    cuiSumegold=parseInt(cuiSumegold)+parseInt(needegoldC);
                    $('.cuigeng ul li:last').html(cuiSumegold);
                    commentCount=parseInt(commentCount)+1;
                    $('.pinglun ul li:last').html(commentCount);
                }
            });
    });


	function successLater(data){
        layer.open({
            content: data.message
            ,skin: 'msg'
            ,time: 1 //2秒后自动关闭
            ,end:function(){
                if(data.status==-1){
                    if(isweixin){
                        $(location).attr('href', "<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>");
                        /*location.href("<?php echo U('Mobile/User/getWeiXinInfo' ,array('backurl' => 'User-buyEgold'));?>");*/
                    }else{
                        $(location).attr('href', "<?php echo U('Mobile/User/buyEgold');?>");
                        /*location.href("<?php echo U('Mobile/User/buyEgold');?>");*/
                    }
                }else if(data.status==1){
                    /*location.reload();*/
                    if(data.action=='dashang'){
                        useregold=useregold-needegoldS;
                        $('.bookinfo').html(useregold);
                    }else{
                        useregold=useregold-needegoldC;
                        $('.bookinfo').html(useregold);
                    }
                }else{
                    location.reload();
                }
            }
        });
    }
    /****评论****/
    $('.pinglun').click(function(){
        /*$(location).attr('href', "/index.php/Article/index/id/<?php echo ($chapter['articleid']); ?>");*/
        $(location).attr('href', "/Gifts/comment/articleid/<?php echo ($chapter['articleid']); ?>/chapterid/<?php echo ($chapter['chapterid']); ?>");
    });
	
	function adclick() {
        var url=$('#adurl').val();
        var adid=$('#adid').val();
        $.get("/Article/adClick/id/"+adid+'.html',function (v) {
           window.location.href=url;
        });
    }
	
	
	
</script>
        </div>
        <script type="text/javascript">
            $(function()
            {
                /****监听播放结束事件,自动播放下一段****/
                var sum = $('#sumf').val();
                var p=1;
                var b = Math.floor(<?php echo ($chapter['articleid']+0); ?>);
                var cid = <?php echo ($chapter['chapterid']+0); ?>;
                $('#audiofile').bind('ended',function(){
                    while(p<=sum){
                        $('#audiofile').attr('src','/audio/'+b+'/'+cid+'/'+cid+'-'+p+'.mp3');
                        p++;
                    }
                });
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
            });
            function goTop()
            {
                $('html,body').animate({'scrollTop':0},600);
            }
        </script>
        <script type="text/javascript">
            $(function() {
                // 阅读页面设置
                var _content = $("[data-act=read_content]");
                // 文字样式
                $("[data-act=read_act]").live('click', function(event) {
                    var _mod = $(this).attr("data-name");
                    var _cont_size = $("[data-act=read_content]").children("p:first").css("font-size");
                    var _cont_marg = $("[data-act=read_content]").children("p:first").css("margin");
                    var _change = 4;
                    if ('plus' === _mod) {
                        var _last_size = parseInt(_cont_size)+_change;
                        var _chan_marg = parseInt(_cont_marg)+_change;
                        if (_last_size>=24){
                            _last_size=24;
                            _chan_marg=29.76;
                        }
                        /*var _last_size = 24;
                        var _chan_marg = 29.76;*/
                    } else if('less' === _mod) {
                        var _last_size = parseInt(_cont_size)-_change;
                        var _chan_marg = parseInt(_cont_marg)-_change;
                        if (_last_size<=16){
                            _last_size=16;
                            _chan_marg=19.84;
                        }
                        /*var _last_size = 16;
                        var _chan_marg = 19.84;*/
                    }else{
                        var _last_size = _cont_size;
                        var _chan_marg = _cont_marg;
                    }
                    // 设置行高和段间距
                    var _last_lh = _last_size *2.2;
                    //var _last_lh = _last_size + _change*2;
                    //		alert(_last_pm);
                    _content.children("p").css({"font-size":_last_size + "px","line-height":_last_lh + "px","margin":_chan_marg + "px 0"});
                    var _read_str = '{"fs":"'+_last_size + '","lh":"'+_last_lh + '","pm":"' +_chan_marg + '"}';
                    //		_read_str = eval("("+_read_str+")");
                    //		console.log(_read_str);
                    $.cookie('shuhai_read_fs',_read_str,{expires:30,path:"/"});
                });
                // 夜间模式
                $("[data-act=read_mode]").live('click', function(event) {
                    var _this_cont = $("[data-con=read_cont]");
                    _this_cont.toggleClass("night");
                    var _color={"bodybg":"#AEA99F","bodybgn":"#1f1c1d","conn":"#353434","conan":"#ffffff","con":"#ffffff","cona":"#0068b7","daycolor":"#999","nightcolor":"#333"};
                    var _read_str="";
                    if(_this_cont.hasClass("night")){
                        $("body").css("background",_color.bodybgn);
                        $(".read_vf_nav").css("background",_color.bodybgn);
                        $(".chapter_content p").css("color",_color.daycolor);
                        $(".read_vf_nav a").css("color",_color.daycolor);
						$(".read_info_right a").css("color",_color.daycolor);
                        //			$(".container").css("background",_color.conn);
                        //			$(".container a").css("color",_color.conan);
                        _read_str = '{"bodybg":"'+_color.bodybgn + '","con":"'+_color.conn + '","cona":"'+_color.conan + '","mode":"night","fontColor":"'+_color.daycolor + '"}';
                    }else{
                        $("body").css("background",_color.bodybg);
                        $(".read_vf_nav").css("background",_color.bodybg);
                        $(".chapter_content p").css("color",_color.nightcolor);
                        $(".read_vf_nav a").css("color",_color.nightcolor);
						$(".read_info_right a").css("color",_color.nightcolor);
                        //			$(".container").css("background",_color.con);
                        //			$(".container a").css("color",_color.cona);
                        _read_str = '{"bodybg":"'+_color.bodybg + '","con":"'+_color.con + '","conan":"'+_color.cona + '","mode":"day","fontColor":"'+_color.nightcolor + '"}';
                    }
                    //		_read_str = eval("("+_read_str+")");
                    //		console.log(_read_str.bodybg);
                    $.cookie('shuhai_read_mod',_read_str,{expires:30,path:"/"});
                })

                //界面初始化
                readInit();
                function readInit(){
                    if($.cookie("shuhai_read_fs")){
                        var _fsObj=$.cookie("shuhai_read_fs");
                        //			console.log(_fsObj);
                        _fsObj = eval("("+_fsObj+")");
                        //			console.log(_fsObj.lh);
                        $("[data-act=read_content]").children("p").css({"font-size":_fsObj.fs + "px","line-height":_fsObj.lh + "px","margin":_fsObj.pm + "px 0"});
                    }
                    if($.cookie("shuhai_read_mod")){
                        var _modObj=$.cookie("shuhai_read_mod");
                       // console.log(_modObj);
                        _modObj = eval("("+_modObj+")");
                        $("[data-con=read_cont]").toggleClass(_modObj.mode);
                        $("body").css("background",_modObj.bodybg);
                        $(".read_vf_nav").css("background",_modObj.bodybg);
                        $(".chapter_content p").css("color",_modObj.fontColor);
                        $(".read_vf_nav a").css("color",_modObj.fontColor);
                        //			$(".content").css("background",_modObj.con);
                        //			$(".content a").css("color",_modObj.cona);
                    }
                }
            })

            function goHtml()
            {
                var backUrl = document.referrer;

                if(backUrl)
                {
                    //返回目录
                    $('#go_Html').attr('href',"javascript:history.go(-1)");
                }
                else
                {
                    $('#go_Html').attr('href',"http://wap.kyread.com");
                }

//                if(!backUrl)
//                {
//                    $('#go_Html').attr('href',"http://m.kyread.com");
//                }

            }
			
        </script>
		<script>
		 $(function () {
                var sign=<?php echo ($sign); ?>;
                if(sign){
                    layer.open({
                        content:'<srtong><b style="font-size:16px;">签到成功</b></srtong><br/><div style="font-size:13px; "><p>每日首次阅读,赠送<span style="color:red">50</span>书币</p><br/><p>请明日继续签到哦~</p><br/><p>分享本章将有更多惊喜,一般人我不告诉他呦~</p></div>',
                        btn: '<span style="color: #51C332">知道了</span>',
                        shadeClose: false,
                        style:'width:74%;padding:0px;'
                    });
                }
            });
		</script>
		
		
        <style>
            .layui-m-layercont {
                padding: 20px 30px;
                line-height: 22px;
                text-align: center;
            }
        </style>
    </body>
</html>