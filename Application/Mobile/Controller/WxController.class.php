<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/24 0024
 * Time: 下午 4:42
 */
namespace Mobile\Controller;
use Think\Controller;

class WxController extends Controller
{
    public $wxModel='';

    public function _initialize()
    {
        $weixinPay = C('WEIXIN_APP_PAY_CONF');
        $appid    = $weixinPay['appid'];
        $key= $weixinPay['opendkey'];
        $token= $weixinPay['token'];
		$id=$_GET['id']+0;
        $channelid=0;
        if($id>0) {
            $channelWxModel = M('distribution_channel_wx');
            $wxinfo = $channelWxModel->field('channelid,appid,appsecret,token')->where(array('channelid' => $id))->find();
            if ($wxinfo) {
                $appid = $wxinfo['appid'];
                $key = $wxinfo['appsecret'];
                $token = $wxinfo['token'];
                $channelid = $wxinfo['channelid'];
            }
        }
        $wxModel=D('Wechat');
	
        $wxModel->initset($appid,$key,$token,$channelid);
		
        $this->wxModel=$wxModel;
		//var_dump($this->wxModel) ;
		//file_put_contents('./chnnel.txt',var_export($_SERVER,true));
		//file_put_contents('./post.txt',var_export($id,true));
		
    }

    public function index()
    {

        $str=var_export($_SERVER['REQUEST_URI'],true);
        if(strpos($str,'echostr')>0){
            $num=strpos($str,'?')+1;
            $str=substr($str,$num,-1);
            $get=explode('&',$str);
            foreach($get as $k=>$v){
                $v=explode('=',$v);
                $_GET[$v[0]]=$v[1];
            }
            file_put_contents('./12.txt',$str);
            $this->wxModel->valid();

        }else{
            $this->wxModel->responseMsg();//被动响应
        }
    }


    /**
     * 创建菜单
     */
	
    public function createMenu()
    {
		 

        $menu='{
                 "button":[
                 {
                       "type":"view",
                       "name":"阅读记录",
                       "url":"http://wap.kyread.com/User/getBookCase/cid/'.$this->wxModel->_channelid.'.html"
                       
                   },
                  {
                       "name":"访问书城",
                       "sub_button":[                       
                        {
                           "type":"view",
                           "name":"书城首页",
                           "url": "http://wap.kyread.com/Index/index/cid/'.$this->wxModel->_channelid.'.html"
                        },
						{	
                           "type":"view",
                           "name":"主编强推",
                           "url":"http://wap.kyread.com/Index/getHotCommendBookList/show_id/4/cid/'.$this->wxModel->_channelid.'.html"
                        }
						,
						{	
                           "type":"view",
                           "name":"免费专区",
                           "url":"http://wap.kyread.com/Index/freeLimitBookList/cid/'.$this->wxModel->_channelid.'.html"
                        }
						]
                   },
                   {
                       "name":"用户中心",
                       "sub_button":[
					   {
                           "type":"view",
                           "name":"我要充值",
                           "url": "http://wap.kyread.com/User/buyEgold/cid/'.$this->wxModel->_channelid.'.html"
                        },                      
                       {	
                           "type":"view",
                           "name":"免费书币",
                           "url":"http://wap.kyread.com/user/sign/cid/'.$this->wxModel->_channelid.'.html"
                        },
                         {	
                           "type":"click",
                           "name":"联系客服",
                           "key":"kefu"
                        }]
                   }]
             }';
			 //echo '<pre>',$this->wxModel->_channelid;
			// var_dump($this->wxModel);exit;
			
        $this->wxModel->_createMenu($menu);
		
    }
	
	/**
     * @return token
     * 获取token接口
     */

    public function getToken()
    {
        $res= $this->wxModel->_getAccesstoken();
		echo $res;
		return $res;

    }
	public function addmedia(){
		$res=$this->wxModel->_addMedia('image','./kefu.jpg');
		dump($res);
	}
	
	 public function delmenu()
    {
        $this->wxModel->_deleteMenu();
	}
   
}
