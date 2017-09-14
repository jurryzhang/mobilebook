<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html >
<html>
<head>
    <meta name="Generator"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">

    <meta http-equiv="Expires" CONTENT="0">
    <meta http-equiv="Cache-Control" CONTENT="no-cache">
    <meta http-equiv="Pragma" CONTENT="no-cache">

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
    <script type="text/javascript" src="/Template/mobile/default/Static/js/jquery.cookie.js"></script>
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
<div class="container">
    <div class="header" style="border-bottom: none">
        <!-- <div class="h-left">
            <a class="sb-back" href="/index.php/Index/index" title="返回" target="_self"></a>
        </div> -->

        <form id="searchForm" action="<?php echo U('Index/searchBook');?>" method="POST">
            <div class="h-mid" style="margin-left: 10%;">
                <input type="text" name="key_word" value="" onkeyup="if(event.keyCode==13){setcookie();this.form.submit();}" placeholder="书名/作者名" style="width: 90%;padding: 2%;background:rgba(253,152,69,0.03);border:none;border-radius:6px;">
            </div>

            <div style="float: right;margin-top: 8px;margin-right: 3%;">
                <img data-act="submit" style="width: 27px;background:transparent" src="/Template/mobile/default/Static/images/xin/icosousuo.png">
            </div>
        </form>
    </div>

    <div class="" style="width: 100%;background:#F6F6F6;padding-top: 4%;padding-bottom: 4%;">
        <div style="font-size: 16px;width: 90%;margin-left: 4%;vertical-align: middle">
            <span>热门搜索</span>
            <i class="fa fa-repeat" style="float: right;color: #CCC;font-size: 18px;margin-top: 2%"></i>
        </div>
        <div style="font-size: 14px;width: 90%;margin-left: 4%;">
            <?php if(is_array($hotbookList)): $i = 0; $__LIST__ = $hotbookList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$hotbook): $mod = ($i % 2 );++$i;?><a href="/Article/index/id/<?php echo ($hotbook['bookID']); ?>" class="hotbook" style="display: inline-block;background: rgba(<?php echo rand(125,255);?>,<?php echo rand(125,255);?>,<?php echo rand(125,255);?>,0.3);border-radius: 20px;padding: 0px 10px;margin-top: 3%;margin-left: 2%">
                    <?php echo ($hotbook["bookName"]); ?>
                </a><?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>

    <div class="" style="width: 100%;background:#F6F6F6;padding-top: 4%;padding-bottom: 4%;margin-top: 10px;">
        <div style="font-size: 16px;width: 90%;margin-left: 4%;vertical-align: middle">
            <span>搜索历史</span>
            <i class="fa fa-trash" style="float: right;color: #CCC;font-size: 18px;margin-top: 2%"></i>
        </div>
        <div style="font-size: 12px;width: 90%;margin-left: 4%;margin-top: 1%;" id="historyList">
            <!--<?php if(is_array($historyList)): $i = 0; $__LIST__ = $historyList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$history): $mod = ($i % 2 );++$i;?><p style="border-bottom: solid 1px #EEE;">
                    <a href="/index.php/Index/searchBook/key_word/<?php echo ($history['keyword']); ?>" style="color: #CCC;">
                        <?php echo ($history['keyword']); ?>
                    </a>
                </p><?php endforeach; endif; else: echo "" ;endif; ?>-->
        </div>
    </div>
</div>
<script>
	/*热门书籍换一批*/
    var num='';
    var bookarr=[];
    function numbers(){
        num=Math.floor(Math.random()*10);
        if(in_array(num,bookarr)){
            return numbers();
        }else{
            return num;
        }
    }
    function in_array(num,arr){
        for(var i in arr){
            if(num==arr[i]){
                return true;
            }
        }
        return false;
    }
	
    $(function(){
        /*搜索提交表单*/
        $("[data-act=submit]").click(function()
        {
            if($("input:text[name='key_word']").val()=="")
            {
                window.alert("请输入您要查询的内容！");
            }
            else
            {
                $('#searchForm').submit();
                setcookie();
            }
        });
        /*点击热门搜索刷新Btn*/
        /*$('.fa-repeat').click(function(){
			$.each($('.hotbook'),function(k,v){                
                $(v).css('background','rgba('+(Math.ceil(Math.random()*125+130))+','+(Math.ceil(Math.random()*125+130))+','+(Math.ceil(Math.random()*125+130))+',0.3)');
            });
        });*/
		/*点击热门搜索刷新Btn*/
        $('.fa-repeat').click(function(){
            /* location.reload();*/
            var _visitBookList=<?php echo ($visitBookList); ?>;
            var num1='';
			$.each($('.hotbook'),function(k,v){
                bookarr.push(num1);
                num1=numbers();
                num='';
                $(v).css('background','rgba('+(Math.ceil(Math.random()*125+130))+','+(Math.ceil(Math.random()*125+130))+','+(Math.ceil(Math.random()*125+130))+',0.3)');
                $(v).html(_visitBookList[num1].articlename);
                $(v).attr('href','/Article/index/id/'+_visitBookList[num1].articleid);

			});
            bookarr=[];
        });
        /*垃圾桶 清除cookie*/
        $('.fa-trash').click(function(){
            $.cookie("history",null,{expires:-1, path: '/'});
            $('#historyList').html('');
        });
    });
    function setcookie(){
        var history;
        var keyword=$("input:text[name='key_word']").val();
        var json="[";
        var json1;
        var canAdd= true;
        /****将搜索字符串存入cookie***/
        if(!$.cookie("history")){
            history = $.cookie("history",'[{"keyword":"'+keyword+'"}]',{expires:1, path: '/'});
        }else {
            history = $.cookie("history");
            json1 = eval("("+history+")");
            $(json1).each(function(){
                if(this.keyword==keyword){
                    canAdd=false;
                    return false;
                }
            })
            if(canAdd){
                $(json1).each(function(){
                    if(this.keyword!=keyword){
                        json = json + '{"keyword":"'+this.keyword+'"},';
                    }
                })
                json = json + '{"keyword":"'+keyword+'"}]';
                $.cookie("history",json,{expires:1, path: '/'});
            }
        }
    }

    function initHistory(){
        var _history=$.cookie('history');
        eval("_history="+_history);
        _history=_history.reverse();
        var historyStr='';
        $.each(_history,function(k,v){
            /*console.log(v.keyword);*/
            historyStr+='<p style="border-bottom: solid 1px #EEE;"><a href="/Index/searchBook/key_word/'+v.keyword+'.html" style="color: #CCC;">'+v.keyword+'</a></p>';
        });
        $('#historyList').append(historyStr);
    }
    initHistory();
</script>

</body>
</html>