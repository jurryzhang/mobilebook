var isFirst = 0;//初始状态
var opts = {
    lines: 8, // 花瓣数目
    length: 10, // 花瓣长度
    width: 3, // 花瓣宽度
    radius: 10, // 花瓣距中心半径
    corners: 1, // 花瓣圆滑度 (0-1)
    rotate: 0, // 花瓣旋转角度
    direction: 1, // 花瓣旋转方向 1: 顺时针, -1: 逆时针
    color: '#fff', // 花瓣颜色
    speed: 1, // 花瓣旋转速度
    trail: 60, // 花瓣旋转时的拖影(百分比)
    shadow: false, // 花瓣是否显示阴影
    hwaccel: false, //spinner 是否启用硬件加速及高速旋转
    className: 'spinner', // spinner css 样式名称
    zIndex: 2e9, // spinner的z轴 (默认是2000000000)
    top: '50%', // spinner 相对父容器Top定位 单位 px
    left: '50%'// spinner 相对父容器Left定位 单位 px
};

var spinner = new Spinner(opts);
function showBuyInfo(articleid,chapterid)
{
    var url = "/index.php?m=Mobile&c=Article&a=userBuyChapter&book_id=" + articleid + "&chapter_id=" + chapterid;

    if(isFirst==0)
    {
        isFirst = 1;//加载状态
        $.ajax({
            type     : "GET",
            url      : url,
            dataType : "json",
            beforeSend: function () {
                //异步请求时spinner出现

                $(".ceng").show();
                var target = $("body").get(0);
                spinner.spin(target);
            },
            success  : function(data)
            {
                if(data.status == 1)
                {
                    //执行成功后插入html片段
                    $("#page").append(data.url);
                    isFirst = 2;
                    // $.getScript("/Template/mobile/default/Static/js/wapmfds/classie.js");
                    $.getScript("/Template/mobile/default/Static/js/wapmfds/modalEffects.js");
                    ///请求页面后执行
                    var sel =  $('.selected')[0];
                    $(sel).append("<img src='/Template/mobile/default/Static/images/selected.png'/>");
                    $('.ndmy').html($(sel).attr('need_money'))
                    $('.flex .disable').css('border-color','#eee');
                    // $('.flex .able').css('border-color','#333');
                    $('.flex .able').on("click", function() {
                        if ($(this).hasClass("selected")) {
                        } else {
                            $('.flex .item').removeClass("selected");
                            $('.flex .item img').remove();
                            $("#selItem").attr("value",$(this).attr("buy_index"));
                            $(this).addClass("selected");
                            $(this).add("selected");
                            $(this).append("<img src='/Template/mobile/default/Static/images/selected.png'/>");
                            $('.ndmy').html($(this).attr('need_money'))
                        }
                    });
                    $("form").append("<input type='hidden' name='book_id' value="+articleid+">");
                    $("form").append("<input type=\"hidden\" name=\"chapter_id\" value="+chapterid+">");
                    $('.md-close').on("click", function() {
                        $("form").submit();
                        // console.log("确认支付");
                        // console.log("需支付 ："+$('.selected').attr('need_money')+"软萌币");
                        // console.log("个人余额："+$('#yue').html())+"软萌币";
                        // console.log("是否订阅："+$('#subscibe').is(':checked'));
                    });
                }
                else if (data.status == -1)
                {
                    window.location.href = data.url;
                }

            },
            complete: function(XMLHttpRequest, textStatus) {
                //关闭spinner
                spinner.spin();
                $(".ceng").hide();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                isFirst = 0;
                if(XMLHttpRequest.status==404)
                {
                     // alert("404,加载失败！");    //http响应状态
                }
                // alert(XMLHttpRequest.status);    //http响应状态
                // alert(XMLHttpRequest.readyState);//5个状态
                // alert(textStatus);               //六个值
            },
        });
        console.log("加载js");
    }
    else if(isFirst==1) //防止网络不好的情况 一直在点击按钮
    {
        console.log("正在执行请求");
    }
    else
    {
        console.log("已经加载js");
    }
}
