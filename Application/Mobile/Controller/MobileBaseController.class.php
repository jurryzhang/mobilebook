<?php
/**
 * 免费读书手机网站
 * ============================================================================
 * * 版权所有 2015-2027 河南趣读信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.qudukeji.com
 * ----------------------------------------------------------------------------
 * ============================================================================
 *
 */

namespace Mobile\Controller;
use Think\Controller;
use Mobile\Logic\UsersLogic;

class MobileBaseController extends Controller
{
	public $session_id;
	public $cateTrre = array();
	
	public $channelID   = '';
	public $channelType = '';
	public $channelName = '';
	public $secretKey   = '';
	
	/*
	 * 初始化操作
	 */
	public function _initialize()
	{


		$this->session_id = session_id(); // 当前的 session_id
		
		$isweiXinBrower = isWeiXin();
		$this->setChannelinfo();


		if($isweiXinBrower)
		{
			$pos = strpos($_SERVER['REQUEST_URI'],'code=');
			
			if(ACTION_NAME == 'readChapter' && CONTROLLER_NAME == 'Article' && $pos)
			{
				$_SESSION['readchaptercount'] = 1;
			}

			if($pos===false && isset($_GET['cid'])){
                $backUrl       = $_SERVER['REQUEST_URI'];
                $weixinPayData = new UsersLogic();
                $weixinPayData->getWXUserOpenID1($backUrl);
            }
		}else{
           /*  $wxAction=array('zhifukaReturn','weixinAppPayReturn','aliPayReturn','tongleReturn');
            if(!in_array(ACTION_NAME,$wxAction)){
                header("Content-Type:text/html;charset=utf-8");
                echo '<h3>-------请在微信中打开链接!--------<h3>';exit;
            }*/
        }
		
		if(CONTROLLER_NAME == 'LoginApi')
		{

		}
		elseif(ACTION_NAME == 'pay')
		{

		}
		elseif(ACTION_NAME == 'weixinOrderQuery')
		{

		}
		else
		{
			//获取secretkey值
			//$this->getChannelFromSecretkey();

			$this->getWXUserInfo();
		}
		
		$this->public_assign();
	}
	
	
	/**
	 * 保存公告变量到 smarty中 比如 导航
	 */
	public function public_assign()
	{
		$siteTitle = C('WEB_SITE_TITILE');
		
		$isWeiXinBrower = isWeiXin();
		
		$this->assign('isweixin', $isWeiXinBrower);
		
		if($_SESSION['usercode'])
		{
			$isShow = 1;
		}
		else
		{
			$isShow = 0;
		}
		
		$this->assign('isshow',$isShow);
		
		$isweiXinBrower = isWeiXin();
		
//		if($isweiXinBrower)
//		{
//			if(cookie('channel_name'))
//			{
//				$this->assign('site_title', cookie('channel_name'));
//			}
//			else
//			{
//				$this->assign('site_title', $siteTitle);
//			}
//		}
//		else
		{
			//edit by muyi 2017/05/31
        //添加不同服务站显示不同的站名
        $fromid=cookie('fromid');
        if($fromid){
            $channelWxModel=M('distribution_channel_wx');
            $wxinfo=$channelWxModel->where(array('channelid'=>cookie('fromid')))->find();
            if($wxinfo){
                $siteTitle=$wxinfo['sitename'];
                $wxnum=$wxinfo['wxnum'];

            }else{
                $wxnum='vipxs9';
                $siteTitle='手掌阅读';
            }
        }else{
            $wxnum='vipxs9';
            $siteTitle='手掌阅读';
        }

			$this->assign('site_title', $siteTitle);
		}
	}
	
	/**
	 * 获取相应频道的 热推书
	 * @param $channelID
	 * @return array
	 */
	public function getHotCommendBook($channelID)
	{
		$orderStr = '`showID` ASC';
		
		$tmpBookList = M('app_hotcommend')->where(1)->order($orderStr)->select();
		
		foreach($tmpBookList as $key => $value)
		{
			$tmpHotBook['title']   = $value['title'];
			
			$tmpHotBook['showid']  = $value['id'];
			
			$tmpHotCommendBookList = explode('|',$value['booksID']);
			
			$tmpHotCommendBookList = array_slice($tmpHotCommendBookList,0,6);
			
			$tmpHotBookList        = getBookInfoFromBookList($tmpHotCommendBookList);
			
			$tmpHotBook['booklist'] = $tmpHotBookList;
			
			unset($tmpHotBookList);
			
			$hotBookList[] = $tmpHotBook;
		}
		
		return $hotBookList;
	}
	
	public function getChannelFromSecretkey()
	{
//		if(empty($_COOKIE['channel_id']) || empty($_COOKIE['channel_name']) || empty($_COOKIE['secretkey']) || empty($_COOKIE['channel_type']))
		{
		   // var_dump($_SERVER['QUERY_STRING']);
			$secretkeyStr = $_SERVER['QUERY_STRING'];
			
			if($secretkeyStr)
			{
				$serverList = explode('&', $secretkeyStr);
				
				foreach($serverList as $item)
				{
					$itemList = explode('=', $item);
					
					$str      = str_replace('%25','%',$itemList[1]);
					
					$str      = rawurldecode($str);
					
					$result[$itemList[0]] = trim($str);
				}
				
				if($result['channel'] && $result['type'] && $result['channelid'])
				{
					$channelID = M('distribution_channels')->where(array('channelname' => $result['channel'],'secretkey' => $result['secretkey'],'channeltype' => $result['type']))->field('channelid,pid')->find();
					
					cookie('channel_id', $channelID['channelid']);

                    //edit by muyi 2017/056/31
                    //cookie中添加上级渠道id,用于后期根据服务号建立微信分站
                    if($channelID['pid']==0){
                        cookie('fromid', $channelID['channelid']);
                    }else{
                        cookie('fromid',$channelID['pid']);
                    }
					
					cookie('channel_name', $result['channel']);
					
					cookie('secretkey', $result['secretkey']);
					
					cookie('channel_type', $result['type']);

                    //edit by muyi 2017/056/31
                    //cookie中添加上级渠道id,用于后期根据服务号建立微信分站
                    if($channelID['pid']==0){
                        session('fromid', $channelID['channelid']);
                    }else{
                        session('fromid',$channelID['pid']);
                    }
					
					$_SESSION['channel_id']   = $channelID['channelid'];
					
					$_SESSION['channel_name'] = $result['channel'];
					
					$_SESSION['secretkey']    = $result['secretkey'];
					
					$_SESSION['channel_type'] = $result['type'];
				}
			}
		}
	}
	
	public function getChannelBySecretkey($channelKey,$channelName,$channelType)
	{
//		if(empty($_COOKIE['channel_id']) || empty($_COOKIE['channel_name']) || empty($_COOKIE['secretkey']) || empty($_COOKIE['channel_type']))
		{
			$channelID = M('distribution_channels')->where(array('channelname' => $channelName,'secretkey' => $channelKey,'channeltype' => $channelType))->field('channelid,pid')->find();
			
			cookie('channel_id', $channelID['channelid']);

			//edit by muyi 2017/056/31
			//cookie中添加上级渠道id,用于后期根据服务号建立微信分站
			if($channelID['pid']==0){
                cookie('fromid', $channelID['channelid']);
            }else{
                cookie('fromid',$channelID['pid']);
            }

			
			cookie('channel_name', $channelName);
			
			cookie('secretkey', $channelKey);
			
			cookie('channel_type', $channelType);
			
			$_SESSION['channel_id']   = $channelID['channelid'];

			$_SESSION['fromid']   = $channelID['pid'];

			$_SESSION['channel_name']   =  $channelName;

			$_SESSION['secretkey']    = $channelKey;
			
			$_SESSION['channel_type'] = $channelType;
		}
	}
	
	/**
	 * 如果是微信用户的话，获取用户的信息
	 */
	public function getWXUserInfo()
	{
		$isWeiXinBrower = isWeiXin();
		
		if($isWeiXinBrower)
		{
			$logic = new UsersLogic();
			
			$pos   = strpos($_SERVER['REQUEST_URI'],'code=');
            $pos1 = stripos($_SERVER['REQUEST_URI'],'buyEgold');
            $pos2 = stripos($_SERVER['REQUEST_URI'],'cid');
			
            $payflag=false;
            if($pos1){
				if($pos2){
					$pos1=false;
					$payflag=true;				
				}	
			}
			
			if($pos&&$pos1==false )
			{  
				
				$info     = getResonseInfoFromRequesURI();
				
				$code     = $info['code'];
				
				session('usercode',$code);
				
				$_SESSION['usercode'] = $code;
				
				$userInfo = $this->getWXUserInfoByCode($_SESSION['usercode']);	
										
					$data     = $logic->thirdLogin1($userInfo);	
					
						if($data['status'] == 1)
						{
							$user = $data['result'];
							
							
							
							$_COOKIE['uid']   = $user['uid'];
							cookie('uid',$user['uid'],3600*24*30);
							
							
							
							$_COOKIE['uname'] = $user['uname'];
							
							cookie('uname',$user['uname'],3600*24*30);
							
							$logic->addUserAccessLog();
						}
						header("Content-type: text/html; charset=utf-8"); 
						//var_dump($userInfo);exit;
				if($payflag){
                    $logic->getWXUserOpenID('User/buyEgold');
                }
				
				
			}
		}
	}

	public function getWXUserInfoByCode($code)
	{
		$accessTokenURL = "https://api.weixin.qq.com/sns/oauth2/access_token";//通过code获取access_token
		
		$accessTokenURL2 = "https://api.weixin.qq.com/cgi-bin/token";//通过code获取access_token
		
		//		$openidURL = "https://api.weixin.qq.com/sns/userinfo";
		
		$openidURL = "https://api.weixin.qq.com/cgi-bin/user/info";

		//edit by muyi 2017/05/31
        //增加分销不同服务号同一用户分离
        $channelWxModel=M('distribution_channel_wx');
        $tokentime='';
        $atoken='';
        $fromid=cookie('fromid');
		
        $weixinPay = C('WEIXIN_APP_PAY_CONF');
        $accessTokenParams['appid']      = $weixinPay['appid'];
        $accessTokenParams['secret']     = $weixinPay['opendkey'];
        $accessToken2Params['appid']      = $weixinPay['appid'];
        $accessToken2Params['secret']     = $weixinPay['opendkey'];
		
		$pos1 = stripos($_SERVER['REQUEST_URI'],'buyEgold');
        $pos2 = stripos($_SERVER['REQUEST_URI'],'cid');
        if($pos1){
            if($pos2){
                $pos1=false;
            }
        }
		if(!empty($fromid)&&$pos1==false ){
			
            $wxinfo=$channelWxModel->field('channelid,appid,appsecret,atoken,tokentime')->where(array('channelid'=>$fromid))->find();//获取渠道微信公众号信息
			
            if($wxinfo){
                $accessTokenParams['appid']=$wxinfo['appid'];
                $accessTokenParams['secret']=$wxinfo['appsecret'];
                $accessToken2Params['appid']=$wxinfo['appid'];
                $accessToken2Params['secret']=$wxinfo['appsecret'];
                $atoken=$wxinfo['atoken'];
                $tokentime=$wxinfo['tokentime'];
            }else{				
				$file = './accesstoken'; //用于保存access token
				if(file_exists($file)){ //判断文件是否存在
					$content = file_get_contents($file); //获取文件内容
					$content = json_decode($content);//json解码
					$tokentime=filemtime($file);
					//判断文件是否过期
					if(time()-$tokentime<60){
						$atoken=$content->access_token;
					}else{
						$accessToken2Params['code']       = $code;
						$accessToken2Params['grant_type'] = 'client_credential';
						$accessToken2Url  = $accessTokenURL2 . '?' . http_build_query($accessToken2Params);
						$content = $this->getWxContents($accessToken2Url);
						file_put_contents($file, $content);
						$content = json_decode($content,true);
						$atoken=$content['access_token'];
						$tokentime=time();
					}
				}else{					
					$accessToken2Params['code']       = $code;
					$accessToken2Params['grant_type'] = 'client_credential';
					$accessToken2Url  = $accessTokenURL2 . '?' . http_build_query($accessToken2Params);
					$content = $this->getWxContents($accessToken2Url);
					file_put_contents($file, $content);
					$content = json_decode($content,true);
					$atoken=$content['access_token'];
					$tokentime=time();
				}

        }
			
        }
		

		$accessTokenParams['code']       = $code;
		$accessTokenParams['grant_type'] = 'authorization_code';
		
		$accessTokenUrl  = $accessTokenURL . '?' . http_build_query($accessTokenParams);
			//var_dump($accessTokenParams);exit;
		$accessTokenInfo = $this->getWxContents($accessTokenUrl);
		
		$accessTokenInfo = json_decode($accessTokenInfo,true);

        //edit by muyi 2015/05/31
        //保存基础access_token到数据库中,两个小时刷新一次,
        //如果没有过期直接用数据库的access_token
        if (!empty($atoken)&&!empty($tokentime)&&(time()-$tokentime<60)){
			
		   $accessToken2Info =$atoken;

        }else{
            $accessToken2Params['code']       = $code;
            $accessToken2Params['grant_type'] = 'client_credential';

            $accessToken2Url  = $accessTokenURL2 . '?' . http_build_query($accessToken2Params);

            $accessToken2Info = $this->getWxContents($accessToken2Url);
            $accessToken2Info = json_decode($accessToken2Info,true);
            $token['atoken']=$accessToken2Info['access_token'];
            $token['tokentime']=time();
            $channelWxModel->where(array('channelid'=>cookie('fromid')))->save($token);
            $accessToken2Info=$accessToken2Info['access_token'];

        }
		//获取用户个人信息（UnionID机制）
		//var_dump($accessTokenInfo);exit;
		$apiParams['access_token'] = $accessToken2Info;
		
		if($accessTokenInfo['openid'] &&$pos1==false)
		{
			$apiParams['openid'] = $accessTokenInfo['openid'];
			
			$_SESSION['code']    = $apiParams['openid'];
			
			$_COOKIE['code']     = $apiParams['openid'];
			
			$apiParams['lang']   = 'zh_CN';
			
			$open_id_url = $openidURL . '?' . http_build_query($apiParams);
			
			$userJson = file_get_contents($open_id_url);
			
			$userInfo = json_decode($userJson,true);
			
			
			$userInfo['isfollow']=0;
			
			if(isset($userInfo['subscribe']))
			{
				if($userInfo['subscribe']==1){
					 $userInfo['isfollow']=1;
				}				
				$_SESSION['subscribe'] = $userInfo['subscribe'];
			}
			
		}
		
		$userInfo['nickname']=filterWx($userInfo['nickname']);
		
		
		$userInfo['headimgurl'] = $userInfo['headimgurl'] ? $userInfo['headimgurl'] : DEFAULT_FACE_IMG;
		
		$userInfo['nickname']   = $userInfo['nickname'] ? $userInfo['nickname'] : DEFAULT_VISITOR_NICKNAME . date('YmdHis',time());
		
		$resulf =  array
		(
			'uname'    => $userInfo['openid'],//微信名
			'oauth'    => 'weixinlogin',
			'nickname' => $userInfo['nickname'],
			'head_pic' => $userInfo['headimgurl'],
			'isfollow' => $userInfo['isfollow']
		);
		//var_dump($resulf);exit;
		return $resulf;
	}
	
	private function getWxContents($url)
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
		return $file_contents;
	}
	
	private function testGetWeiXinUserInfo($isweiXinBrower)
	{
//		http://wap.kyread.com/User/getWeiXinInfo/backurl/User-index/secretkey/28511ec7dad79ab8/channel/%E5%BE%AE%E4%BF%A1%E6%87%92%E4%BA%BA%E9%98%85%E8%AF%BB/type/%E5%BE%AE%E4%BF%A1.html
		
		if($isweiXinBrower)
		{
			file_put_contents('$_REQUEST.txt',print_r($_REQUEST,true));
			
			$channelKey  = urldecode(I('secretkey'));
			
			$channelName = urldecode(I('channel'));
			
			$channelType = urldecode(I('type'));
			
			if($channelKey && $channelName && $channelType)
			{
				$this->getChannelBySecretkey($channelKey,$channelName,$channelType);
			}
			
			$backUrl       = I('backurl');
			
			if($channelKey || $channelName || $channelType)
			{
				$_SESSION['authorized_login'] = 0;
			}
			
			$backUrl       = str_replace('-','/',$backUrl);
			
			$weixinPayData = new UsersLogic();
			
			$weixinPayData->testGetWXUserOpenID($backUrl);
		}
	}
	
	
	
	
	
    /**
     * 设置渠道信息用于后期充值引流统计
     */
    public function setChannelinfo()
    {
    	if(!cookie('uid')&&!isset($_GET['cid'])){
    		$_GET['cid'] = 119;
    	}

        $channelid=I('cid')+0;
        $ptid=I('ptid')+0;
        $fromid=cookie('fromid')+0;
        $channel = M('distribution_channels')->where(array('channelid' => $channelid))->find();
        if($channel) {
            //获取fromid
            if ($channel['pid'] == 0) {
                $pid = $channel['channelid'];
            } else {
                $pid = $channel['pid'];
            }

            //判断是否被覆盖如果是父级渠道连接并且ptid=0不覆盖,其他否覆盖
            if ($channelid == $fromid) {
                if ($ptid > 0) {
                    $setflag = true;
                } else {
                    $setflag = false;
                }
            } else {
                //如果不是父级渠道直接覆盖cookie
                $setflag = true;
            }


            $oldChannelid = cookie('channel_id');

            if ($setflag) {

                cookie('channel_id', $channel['channelid'], 3600 * 24 * 30);

                cookie('channel_name', $channel['channelname'], 3600 * 24 * 30);

                cookie('secretkey', $channel['secretkey'], 3600 * 24 * 30);

                cookie('channel_type', $channel['channeltype'], 3600 * 24 * 30);

                cookie('fromid', $pid, 3600 * 24 * 30);

                $_SESSION['channel_id'] = $channel['channelid'];

                $_SESSION['fromid'] = $pid;

                $_SESSION['channel_name'] = $channel['channelname'];

                $_SESSION['secretkey'] = $channel['secretkey'];

                $_SESSION['channel_type'] = $channel['channeltype'];


                if ($ptid > 0) {
                    $ptmodel = M('distribution_channel_pt');
                    $pt = $ptmodel->field('channelid,ptid')->where(array('channelid' => $channelid, 'ptid' => $ptid))->find();
                    if ($pt){
                        cookie('ptid', $ptid, 3600 * 24 * 30);
                        session('ptid', $ptid);
                        $pos = strpos($_SERVER['REQUEST_URI'], 'code=');
                        if ($pos) {
                            $ptmodel->where(array('ptid' => $ptid))->setInc('click', 1);
                        }
                    }else{
                        cookie('ptid', 0);
                        session('ptid', 0);
                    }
                } else {
                    if ($oldChannelid != $channelid) {
                        cookie('ptid', 0);
                        session('ptid', 0);
                    }

                }

            }
			$_SESSION['authorized_login'] = 0;

        }
    }



    /**
     * 根据渠道ID获取渠道信息
     */
    public function getChannelByID($channelid)
    {

        $channel = M('distribution_channels')->field('channelid,pid,channelname,secretkey,channeltype')->where(array('channelid' => $channelid))->find();

        //edit by muyi 2017/05/31
        //cookie中添加上级渠道id,用于后期根据服务号建立微信分站
        if($channel['pid']==0){
            $pid=$channel['channelid'];
        }else{
            $pid=$channel['pid'];
        }

        $fromid=cookie('fromid');

        if(empty($fromid)||$channelid!=$fromid){

            cookie('channel_id', $channel['channelid'],3600*24*30);

            cookie('channel_name', $channel['channelname'],3600*24*30);

            cookie('secretkey', $channel['secretkey'],3600*24*30);

            cookie('channel_type', $channel['channeltype'],3600*24*30);

            cookie('fromid', $pid,3600*24*30);

            $_SESSION['channel_id']   = $channel['channelid'];

            $_SESSION['fromid']   = $pid;

            $_SESSION['channel_name']   =  $channel['channelname'];

            $_SESSION['secretkey']    =$channel['secretkey'];

            $_SESSION['channel_type'] = $channel['channeltype'];

        }

    }
}
