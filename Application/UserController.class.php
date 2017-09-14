<?php
/**
 * 趣读商城
 * ============================================================================
 * * 版权所有 2015-2027 河南趣读信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.qudukeji.com
 * ----------------------------------------------------------------------------
 * ============================================================================
 */

namespace Mobile\Controller;
use Mobile\Logic\UsersLogic;

class UserController extends MobileBaseController
{
	var $weiXinBrower = 0;
	
	public function _initialize()
	{

		parent::_initialize();
		
		$nologin = array
					(
						'login','do_login','logout','reg','sendValidateCode','forgetPwd','check_captcha','check_username','sendValidateCode','zhifukaReturn','weixinAppPayReturn','getWeiXinInfo','tongleReturn'
					);
		
		$this->weiXinBrower = isWeiXin();

		if(((!isset($_COOKIE['uid'])) || empty($_COOKIE['uid'])) && !in_array(ACTION_NAME,$nologin))
		{
			if(ACTION_NAME != 'getWeiXinInfo' && ACTION_NAME != 'index')
			{
				header("Location:".U('User/login'));
				
				exit;
			}
		}
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
	
	public function getWeiXinUserInfo($code)
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
			
            $wxinfo=$channelWxModel->where(array('channelid'=>$fromid))->find();//获取渠道微信公众号信息
			
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
        //保存基础access_token到数据库中,10分钟刷新一次,
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
			
			
			
			if(isset($userInfo['subscribe']))
			{
				$_SESSION['subscribe'] = $userInfo['subscribe'];
			}
		}
		
		$userInfo['headimgurl'] = $userInfo['headimgurl'] ? $userInfo['headimgurl'] : DEFAULT_FACE_IMG;
		
		$userInfo['nickname']   = $userInfo['nickname'] ? $userInfo['nickname'] : DEFAULT_VISITOR_NICKNAME . date('YmdHis',time());
		
		$resulf =  array
		(
			'uname'    => $userInfo['openid'],//微信名
			'oauth'    => 'weixinlogin',
			'nickname' => $userInfo['nickname'],
			'head_pic' => $userInfo['headimgurl']
		);
		//var_dump($resulf);exit;
		return $resulf;
	}
	
	/*
	 * 用户中心首页
	 */
    public function index()
    {
        if(!$_COOKIE['uid'])
	    {
	        $this->display('login');
	    }
	    else
	    {
		    $logic = new UsersLogic();
		
		    $user = $logic->get_info($_COOKIE['uid']);
		    $user = $user['result'];
			//如果VIP没有到期显示是VIP会员
			if($user['viptime']>time()){
		        $user['isvip']=1;
            }
		
		    $this->assign('isweixin',$this->weiXinBrower);
		
		    $this->assign('type',4);
		
		    if($_SESSION['code'])
		    {
			    $this->assign('authorized_login',0);
		    }
		    else
	        {
		        $this->assign('authorized_login',1);
		    }
		    
		    $this->assign('user',$user);
		    
		    $this->display();
	    }
    }

	/**
	 * 注销用户
	 */
    public function logout()
    {
    	$secretkey = cookie('secretkey');

	    $channel   = cookie('channel_name');

	    $type      = cookie('channel_type');

	    setcookie("uid","",time() - 3600 * 24 * 365,'/');

	    setcookie("uname","",time() - 3600 * 24 * 365,'/');
	
	    setcookie("code","",time() - 3600 * 24 * 365,'/');
	
	    setcookie("usercode","",time() - 3600 * 24 * 365,'/');
	
	    session('code',null);
	
	    session('usercode',null);
	
	    unset($_SESSION);
	    
	    session_destroy();
	    
        if($secretkey && $channel && $type)
        {
	        $url = 'secretkey=' . $secretkey . '&channel=' . $channel . '&type=' . $type;

	        header("Location:/?" . $url);
        }
        else
        {
	        header("Location:" . U('Index/index'));
        }
    }

	/**
	 * 获取用户充值记录
	 */
    public function pagLog()
    {
	    $payList = M('distribution_pay_paylog')->where(array('buyid' => $_COOKIE['uid']))->field('buytime,payid,payflag,egold')->order('`buytime` DESC ')->select();

	    foreach($payList as $key => $value)
	    {
		    $payList[$key]['payid']   = $value['buytime'] . $value['payid'];

		    $payList[$key]['buytime'] = date('Y-m-d H:i:s',$value['buytime']);
	    }

	    $this->assign('list',$payList);

    	$this->display();
    }
	
	/**
	 * 获取用户的书架
	 */
	public function getBookCase()
	{
		$bookCaseList = M('distribution_article_bookcase')->where(array('userid' => $_COOKIE['uid']))->order('`joindate` DESC')->select();
		
		foreach($bookCaseList as $key => $value)
		{
			$bookCaseList[$key]['cover'] = getBookCoverUrl($value['articleid']);
			
			$bookCaseList[$key]['info']  = M('article_article')->where(array('articleid' => $value['articleid']))->find();
		}
		
        $userModel=M('distribution_system_users');
        $userinfo=$userModel->field('faceImg')->where(array('uid'=>cookie('uid')))->find();
        $this->assign('faceImg',$userinfo['faceImg']);
		$this->assign('list',$bookCaseList);
  
		$this->display();
	}
	
	/**
	 * 从用户书架删除书籍
	 */
	public function delBookFromBookCase()
	{
		$caseID = I('id');

		$userInfo = M('distribution_article_bookcase')->where(array('caseid' => $caseID))->find();

		if($userInfo['userid'] == $_COOKIE['uid'])
		{
			$flag = M('distribution_article_bookcase')->where(array('caseid' => $caseID))->delete();

			if($flag)
			{
				echo json_encode_ex(array('status' => 1,'message' => '删除成功！' , 'url' => U('User/getBookCase')));
			}
			else
			{
				echo json_encode_ex(array('status' => 0,'message' => '删除失败！','url' => U('User/getBookCase')));
			}
		}
		else
		{
			echo json_encode_ex(array('status' => 1,'message' => '请登录！','url' => U('User/login')));
		}

		return;
	}

	/**
	 * 用户充值
	 */
	public function buyEgold()
	{
		if($_COOKIE['uid'])
		{
			$egoldName  = C('DEFAULT_EGOLD_NAME');

			$weixinFlag = $this->weiXinBrower;
            //edit by muyi 如果是电脑微信客户端扫码支付
            $agent= strtolower($_SERVER['HTTP_USER_AGENT']);
           if(strpos($agent,'window')||strpos($agent,'macintosh')){
               $weixinFlag=0;
           }

			if($weixinFlag)
			{
				if(!$_SESSION['paycode'])
				{
					$info = getResonseInfoFromRequesURI();

					$code          = $info['code'];

					$openIDInfo    = $this->getWeiXinOpenID($code);

					cookie('paycode',$openIDInfo['openid']);

					$_COOKIE['paycode'] = $openIDInfo['openid'];
					
					$_SESSION['paycode'] = $openIDInfo['openid'];
				}
			}
			
			$userModel=M('distribution_system_users');
		$userinfo=$userModel->field('egold,faceImg,name,uid')->where(array('uid'=>cookie('uid')))->find();

            $this->assign('userinfo',$userinfo);
            $this->assign('egold',$userinfo['egold']);
			$this->assign('faceImg',$userinfo['faceImg']);
			
			$this->assign('isweixin',$weixinFlag);

			$this->assign('egoldname',$egoldName);

			$this->assign('egoldnum',1000);
			
			
			//如果有促销活动ID显示促销活动页面
			//file_put_contents('./buy.txt',var_export($_SERVER,true));
			$info     = getResonseInfoFromRequesURI();
            $saleid=$info['saleid'];
            if($saleid){
               // var_dump(cookie('paycode'));exit;
                $promotionModel=M('distribution_promotion');
                $promotion=$promotionModel->find($saleid);
                if($promotion['endtime']>time()){
                    $this->assign('promotion',$promotion);
                    $this->assign('site_title', '特惠充值(VIP专享)');
                    $this->display('promotion');
                    exit;
                }else{
                    header("Content-Type:text/html;charset=utf-8");
                    echo "<script>alert('本次活动已结束！');
                                 window.location.href='/user/buyEgold/cid/".cookie('channel_id').".html';
                                </script>";
                }
            }

			$this->display();
		}
		else
		{
			$this->display('login');
		}
	}

	public function pay()
	{
		if(IS_POST)
		{
			
            $agent= strtolower($_SERVER['HTTP_USER_AGENT']);
            if(strpos($agent,'window')||strpos($agent,'macintosh')){
                $this->weiXinBrower=0;
            }

			if($this->weiXinBrower)
			{
				$this->weixinAppPay();
			}
			else
			{
				$payType = I('paytype');
				
				if($payType == 'weixinpay')
				{
					$this->weixinPay();
				}
				else if($payType == 'alipay')
				{
					$this->aLiPay();
				}
				else if($payType == 'weixinapppay')
				{
					$this->zhifukaPay();
				}else if($payType == 'weixintonglepay'){
                    $this->tonglePay();
                }
			}
		}
	}

	/**
	 * 微信支付
	 */
	public function weixinPay()
	{
		if($_COOKIE['uid'])
		{
			$payNum  = I('egold');

			if(!empty($payNum) && is_numeric($payNum) && $payNum > 0)
			{
				$egold = intval($payNum);

				$weixinCodePayConf = C('WEIXIN_CODE_PAY_CONF');

				//计算实际需要支付的金额
				if(!empty($weixinCodePayConf['paylimit']))
				{
					if(!empty($weixinCodePayConf['paylimit'][$egold]))
					{
						$money = intval($weixinCodePayConf['paylimit'][$egold]) * 100;
						
//						$money = 1;
						
						//查询5分钟内是否有相同订单
						

						$flag  = $this->addPayLog($money,$egold,'weixinPay');
						
						//分为单位
						if(!$flag)
						{
							$this->error('支付失败，请重试！',U('buyEgold'));
						}
						else
						{

						    $weixinPayData = new UsersLogic();

							$payID         = $flag;

							$payInfo       = $weixinPayData->generateWeiXinPayInfo($payID,$money);

							if($payInfo)
							{
								$this->assign('imgUrl',$payInfo['code_url']);

								$this->assign('total_fee',$payInfo['total_fee']);

								$this->assign('out_trade_no',$payInfo['out_trade_no']);

								$this->display('weixinPay');
							}
						}
					}
					else
					{
						$this->error('对不起，您选择的购买金额类型不存在！' , U('buyEgold'));
					}
				}
			}
		}
		else
		{
			$this->display('login');
		}
	}

	/**
	 * 微信扫码支付订单查询
	 */
	public function weixinOrderQuery()
	{
		$weixinPay = new UsersLogic();

		$flag = $weixinPay->weixinOrderQuery();
		if($flag){
		    echo 'SUCCESS';
        }
	}

	/**
	 * 支付宝支付
	 */
	public function aLiPay()
	{
		if($_COOKIE['uid'])
		{
			$payNum  = I('egold');

			if(!empty($payNum) && is_numeric($payNum) && $payNum > 0)
			{
				$payNum = intval($payNum);

				$aliPayConf = C('ALI_PAY_CONF');

				if(!empty($aliPayConf['paylimit']))
				{
					if(!empty($aliPayConf['paylimit'][$payNum]))
					{
						$egold = $payNum;

						$money = $aliPayConf['paylimit'][$payNum];
						
//						$money = 0.01;
						
						//元为单位
						$flag  = $this->addPayLog($money,$egold,'aliPay');

						if(!$flag)
						{
							$this->error('支付失败，请重试！',U('buyEgold'));
						}
						else
						{
							$aliPayData = new UsersLogic();

							$payID      = $flag;

							$aliPayData->generateAliPayInfo($payID,$money);
						}
					}
					else
					{
						$this->error('对不起，您选择的购买金额类型不存在！' , U('buyEgold'));
					}
				}
			}
			else
			{
				$this->error('对不起，请先选择您要购买的金额！',U('buyEgold'));
			}
		}
		else
		{
			$this->display('login');
		}
	}



	/**
	 * 添加payLog日志
	 *
	 * @param $money
	 */
	private function addPayLog($money,$egold,$payType)
	{
		if($payType == 'aliPay')
		{
			$aliPayConf = C('ALI_PAY_CONF');

			$payLogData['moneytype'] = $aliPayConf['moneytype'];

			$payLogData['egoldtype'] = $aliPayConf['paysilver'];

			$payLogData['paytype']   = 'alipay';
			
			$payLogData['money']     = $money;
		}
		else if($payType == 'weixinPay')
		{
			$weixinCodePayConf = C('WEIXIN_CODE_PAY_CONF');

			$payLogData['moneytype'] = $weixinCodePayConf['moneytype'];

			$payLogData['egoldtype'] = $weixinCodePayConf['paysilver'];

			$payLogData['paytype']   = 'weixinPay';
			
			$payLogData['money']     = $money / 100;
		}
		else if($payType == 'weixinapppay')
		{
			$zhifukaPayConf = C('ZHIFUKA_PAY_CONF');
			
			$payLogData['moneytype'] = $zhifukaPayConf['moneytype'];
			
			$payLogData['egoldtype'] = $zhifukaPayConf['paysilver'];
			
			$payLogData['paytype']   = 'zhifukaPay';
			
			$payLogData['money']     = $money / 100;
		}elseif($payType == 'weixintonglepay'){
		    //edit by miyi 2017/05/19
            //增加同乐第三方扫码支付
            $tonglePayConf = C('TONGLE_PAY_CONF');

            $payLogData['moneytype'] = $tonglePayConf['moneytype'];

            $payLogData['egoldtype'] = $tonglePayConf['paysilver'];

            $payLogData['paytype']   = 'tonglepay';

            $payLogData['money']     = $money / 100;
        }

		$payLogData['siteid']      = 0;
		$payLogData['buytime']     = time();
		$payLogData['rettime']     = 0;
		$payLogData['buyid']       = $_COOKIE['uid'];
		$payLogData['buyname']     = $_COOKIE['uname'];
		$payLogData['buyinfo']     = '';
		$payLogData['egold']       = $egold;
		//如果充值金额为365,书币清零充值年费会员
		if($payLogData['money']==365){
			//$payLogData['money']=365;
            $payLogData['egold']       = 0;
        }
		
		$payLogData['retserialno'] = '';
		$payLogData['retaccount']  = '';
		$payLogData['retinfo']     = '';
		$payLogData['masterid']    =  0;
		$payLogData['mastername']  = '';
		$payLogData['masterinfo']  = '';
		$payLogData['note']        = '';
		$payLogData['payflag']     = 0;
		
		//获取当前用户引导渠道信息
        $channel = M('distribution_system_users')->where(array('uid' => $_COOKIE['uid']))->find();
        $pid=M('distribution_channels')->where(array('channelid'=>$channel['channelid']))->find();
        $pid=$pid['pid'];

        if(cookie('channel_id') == 12 || cookie('channel_id') == 0){
            //如果渠道ID为12或者0就使用用户引导的渠道信息生成订单
            if($channel['channelid']){
                $payLogData['channelid']   = $channel['channelid'];
                $payLogData['channeltype'] ='weixin';
            }else{
                //如果用户信息里的渠道id没有就是官方渠道
                $payLogData['channelid']   = 0;
                $payLogData['channeltype'] = '官方渠道';
            }
            //如果cookie里的渠道id等于用户自己渠道的上一级渠道执行判断
        }elseif(cookie('channel_id')==$pid){
				file_put_contents('channelid.txt',cookie('channel_id'). $channel['channelid']);
            //如果渠道是上一级渠道并且cookie里ptid>0,就算做上一级渠道自己推广渠道
            if(cookie('ptid')>0){
                $payLogData['channelid']   = cookie('channel_id');
                $payLogData['channeltype'] ='weixin';
            }else{
                $payLogData['channelid']   = $channel['channelid'];
                $payLogData['channeltype'] ='weixin';
            }
        }else{
            //其他情况直接用cookie里的渠道id
            $payLogData['channelid']   = cookie('channel_id');
            $payLogData['channeltype'] ='weixin';
        }

        //获取cookie里的ptid
        $ptModel=M('distribution_channel_pt');
        $count =  $ptModel->where(array('channelid'=>$payLogData['channelid'],'ptid'=>cookie('ptid')))->count();
        //如果根据之前的确认了渠道ID,且找到了有记录直接用cookie里的ptid
        if($count>0){
            $payLogData['ptid']   = cookie('ptid');
        }elseif(cookie('ptid')==0){
            //如果ptid=0,判断cookie里的渠道ID是自己渠道或者是渠道的上一级就用自己引导的渠道信息
            if($channel['channelid']==cookie('channel_id')||$pid==cookie('channel_id')){
                $payLogData['channelid']   = $channel['channelid'];
                $payLogData['ptid']=$channel['ptid'];
            }else{
                $payLogData['ptid']   = 0;
            }
        }else{
            $payLogData['ptid']=0  ;
        }
		$payModel=M('distribution_pay_paylog');
		if($payLogData['paytype']=='weixinPay'){
			$where = array(
				'buyid' => $payLogData['buyid'],
				'money' => $payLogData['money'],
				'paytype'  => $payLogData['paytype'],
				'payflag'=>0,
                'buytime'=>array('gt',time()-60*5)
				);
			$res=$payModel->where($where)->find();
		    if($res){
		        $payModel->where(array('payid'=>$res['payid']))->save($payLogData);
		        $flag=$res['payid'];
            }else{
            	$flag = $payModel->add($payLogData);
            }
		}else{
            $flag = $payModel->add($payLogData);
        }
		
		
		
		return $flag;
	}
	
	
	 public function testVIP()
    {
        $ulog = new UsersLogic();
        $ulog->testVIP();

	}

	/**
	 * 阿里支付回调函数
	 */
	public function aliPayReturn()
	{
		$aliPayData = new UsersLogic();

		$flag = $aliPayData->aliPayReturn();

		if($flag)
		{
			header("Location:" . U('Index/index'));
		}
		else
		{
			header("Location:" . U('User/buyEgold'));
		}
	}
	/*daisy测试*/
    public function test(){
        $aliPayData = new UsersLogic();
        $aliPayData->paylogpaylog();
    }

    /**
     *  登录
     */
    public function login()
    {
	    if($_COOKIE['uid'] > 0)
	    {
		    header("Location: ".U('Mobile/User/Index'));
	    }
	
	    $weixinFlag = $this->weiXinBrower;
	
	    $this->assign('isweixin',$weixinFlag);
	
	    $referurl = U("Mobile/User/index");
	
	    $this->assign('referurl',$referurl);
	
	    $this->display();
    }
	
	/**
	 * 执行登录操作
	 */
    public function do_login()
    {
    	$username = I('post.username');
    	$password = I('post.password');

    	$username = trim($username);
    	$password = trim($password);
    	$logic    = new UsersLogic();
    	$res      = $logic->login($username,$password);

    	if($res['status'] == 1)
    	{
    		$this->updateUserLoginInfo();

    		$res['url'] =  urldecode(I('post.referurl'));

    		$nickname = empty($res['result']['uname']) ? $username : $res['result']['uname'];

		    setcookie('uid',$res['result']['uid']);
			cookie('uid',$res['result']['uid'],3600*24*30);

		    setcookie('uname',$nickname);
			cookie('uname',$nickname,3600*24*30);
    	}

    	exit(json_encode($res));
    }

	/**
	 * 更新用户登录信息
	 */
    private function updateUserLoginInfo()
    {
    	if($_COOKIE['uid'])
	    {
		    $data['lastip']    = getIP();

		    $data['lastlogin'] = time();

		    $flag = M('distribution_system_users')->where(array('uid' => $_COOKIE['uid']))->save($data);
	    }
	    else
	    {
		    $flag = 0;
	    }

	    return $flag;
    }

    /**
     *  注册
     */
    public function reg()
    {
        if(IS_POST)
        {
            //验证码检验
            $username  = I('post.username','');
            $password  = I('post.password','');
            $password2 = I('post.password2','');
	        $code      = I('post.mobile_code','');

	        $flag = $this->checkValidateCode($username,$code);

	        if($flag == 1)
	        {
		        $logic     = new UsersLogic();

		        $data = $logic->reg($username,$password,$password2);

		        if($data['status'] != 1)
		        {
			        $this->error($data['msg']);
		        }

		        $nickname = empty($data['result']['uname']) ? $username : $data['result']['uname'];

		        setcookie('uid',$data['result']['uid']);

		        setcookie('uname',$nickname);

		        $this->success($data['msg'],U('Mobile/Index/index'));

		        exit;
	        }
	        else if($flag == -1)
	        {
		        $this->error('验证码已过期，请重新注册！',U('Mobile/User/reg'));

		        exit;
	        }
	        else
	        {
		        $this->error('验证码不存在，请重试！',U('Mobile/User/reg'));

		        exit;
	        }
        }

        $this->display();
    }

	/**
	 * 设置个人信息
	 */
	public function setUserInfo()
	{
		$this->display();
	}

	/**
	 * 修改用户昵称
	 */
	public function setNickName()
	{
		$userLogic = new UsersLogic();

		$nickName = M('distribution_system_users')->where(array('uid' => $_COOKIE['uid']))->field('name')->find();

		if(IS_POST)
		{
			I('post.nickname') ? $post['name'] = I('post.nickname') : false; //昵称

			if(!$userLogic->update_info($_COOKIE['uid'],$post))
			{
				$this->error("保存失败",U('User/setNickName'));
			}

			$this->success("操作成功",U('User/index'));

			exit;
		}

		$this->assign('name',$nickName['name']);

		$this->display();
	}

	/**
	 * 修改用户性别
	 */
	public function setSex()
	{
		$userLogic = new UsersLogic();

		$nickName = M('distribution_system_users')->where(array('uid' => $_COOKIE['uid']))->field('sex')->find();

		if(IS_POST)
		{
			I('post.sex') ? $post['sex'] = I('post.sex') : false; //昵称

			if(!$userLogic->update_info($_COOKIE['uid'],$post))
			{
				$this->error("保存失败",U('User/setSex'));
			}

			$this->success("操作成功",U('User/index'));

			exit;
		}

		$this->assign('sex',$nickName['sex']);

		$this->display();
	}

	/**
	 * 修改用户性别
	 */
	public function setPwd()
	{
		if(IS_POST)
		{
			$oldPass = M('distribution_system_users')->where(array('uid' => $_COOKIE['uid']))->field('pass')->find();

			if($oldPass['pass'] == encrypt(I('post.old_password')))
			{
				if(I('post.new_password') == I('post.comfirm_password'))
				{
					$userLogic = new UsersLogic();

					I('post.new_password') ? $post['pass'] = I('post.new_password') : false; //昵称

					$post['pass'] = encrypt($post['pass']);

					if(!$userLogic->update_info($_COOKIE['uid'],$post))
					{
						$this->error("保存失败",U('User/setPwd'));
					}

					$this->success("操作成功",U('User/index'));

					exit;
				}
				else
				{
					$this->error("两次密码输入不一致，请重试！",U('User/setPwd'));
				}
			}
			else
			{
				$this->error("请输入正确的原始密码！",U('User/setPwd'));
			}
		}

		$this->display();
	}

	/**
	 * 发送验证码
	 */
    public function sendValidateCode()
    {
	    $phoneNum = I('send');

	    $type     = I('get.type');

	    $isExist  = $this->checkPhoneIsExist($phoneNum);

	    if($isExist && $type == 'reg')
	    {
		    echo json_encode_ex(array('status' => 2,'message' => '该手机号码已经存在，请登录！','url' => U('User/login')));
	    }
	    else
	    {
		    $aLiDaYuFile = C('A_LI_DA_YU_CONF_PATH');

		    include $aLiDaYuFile;

		    date_default_timezone_set('Asia/Shanghai');

		    vendor('ALiDaYu.TopSdk');

		    $c            = new  \TopClient();

		    $c->appkey    = $aLiDaYu['key'];
		    $c->secretKey = $aLiDaYu['Secret'];

		    $req          = new \AlibabaAliqinFcSmsNumSendRequest;

		    $req->setSmsType($aLiDaYu['sms_type']);
		    $req->setSmsFreeSignName($aLiDaYu['sms_free_sign_name']);

		    $code = rand(1000,9999);

		    $smsParam = array('code' => $code,'product' => $aLiDaYu['sms_free_sign_name']);

		    $smsParam = json_encode_ex($smsParam);

		    $req->setSmsParam($smsParam);
		    $req->setRecNum($phoneNum);
		    $req->setSmsTemplateCode($aLiDaYu['sms_template_code']);
		    $resp = $c->execute($req);

		    if(isset($resp->result))
		    {
			    if(current($resp->result->success) == 'true')
			    {
				    $time = time();

				    M('app_verifycode')->where(array('phone' => $phoneNum))->delete();

				    $data['phone']      = $phoneNum;

				    $data['verifyCode'] = $code;

				    $data['sendTime']   = $time;

				    M('app_verifycode')->add($data);

				    echo json_encode_ex(array('status' => 1,'message' => '验证码发送成功！'));
			    }
			    else
			    {
				    echo json_encode_ex(array('status' => 0,'message' => '验证码发送失败！'));
			    }
		    }
		    else if(isset($resp->sub_msg))
		    {
			    echo json_encode_ex(array('status' => 0,'message' => '达到一小时的注册上限，请过段时间再试！'));
		    }
	    }

	    return;
    }

	/**
	 * 检测手机号码是否存在，存在返回1，不存在返回0
	 */
    private function checkPhoneIsExist($phone)
    {
    	$count = M('distribution_system_users')->where(array('uname' => $phone))->count();

    	if($count)
	    {
	    	return 1;
	    }
	    else
	    {
	    	return 0;
	    }
    }

	/**
	 * 检测验证码是否过期
	 */
	private function checkValidateCode($phone,$code)
	{
		$codeInfo = M('app_verifycode')->where(array('phone' => $phone,'verifyCode' => $code))->find();

		if($codeInfo)
		{
			$sendTime = $codeInfo['sendTime'];

			$time     = time();

			if($time - $sendTime > 120)
			{
				$status =  -1;
			}
			else//验证成功
			{
				$status = 1;
			}

			$id = $codeInfo['id'];

			M('app_verifycode')->where(array('id' => $id))->delete();

			return $status;
		}
		else
		{
			return 0;//不存在验证码，请重试！
		}
	}

	/**
	 * 忘记密码
	 */
	public function forgetPwd()
	{
		if(IS_POST)
		{
			$userName = I('post.username');

			$count    = M('distribution_system_users')->where(array('uname' => $userName))->count();

			if(!$count)
			{
				$this->error("该用户不存在，请注册！",U('User/forgetPwd'));
			}
			else
			{
				$mobileCode = I('post.mobile_code');

				$flag = $this->checkValidateCode($userName,$mobileCode);

				if($flag == 1)
				{
					$passWord1 = I('post.password');

					$passWord2 = I('post.password2');

					if($passWord2 == $passWord1)
					{
						$userLogic = new UsersLogic();

						$userInfo = M('distribution_system_users')->where(array('uname' => $userName))->find();

						$post['pass'] = encrypt($passWord1);

						if(!$userLogic->update_info($userInfo['uid'],$post))
						{
							$this->error("找回密码失败",U('User/forgetPwd'));
						}

						$this->error("找回密码成功，即将跳转到登录页面！",U('User/login'));
					}
					else
					{
						$this->error("两次输入的密码不一致，请重试！",U('User/forgetPwd'));
					}
				}
				else if($flag == -1)
				{
					$this->error('验证码已过期，请重新注册！',U('Mobile/User/forgetPwd'));

					exit;
				}
				else
				{
					$this->error('验证码不存在，请重试！',U('Mobile/User/forgetPwd'));

					exit;
				}
			}

			exit;
		}

		$this->display();
	}
	
	public function addBookCase()
	{
		$bookID   = I('id');
		
		$bookName = I('bookname');
		
		$count  = M('distribution_article_bookcase')->where(array('articleid' => $bookID,'userid' => cookie('uid')))->count();
		
		if($count)
		{
			$returnArr = array('status' => 3,'msg' => '该书已存在在书架中！');
		}
		else
		{
			$bookCaseData['userid']      = $_COOKIE['uid'];
			
			$bookCaseData['username']    = $_COOKIE['uname'];
			
			$bookCaseData['articleid']   = $bookID;
			
			$bookCaseData['articlename'] = $bookName;
			
			$bookCaseData['joindate']    = time();
			
			$chapterList = M('article_chapter')->where(array('articleid' => $bookCaseData['articleid']))->order('`chapterorder` DESC')->select();
			
			$chapterInfo  = $chapterList[0];
			
			$bookCaseData['chapterid']    = $chapterInfo['chapterid'];
			
			$bookCaseData['chaptername']  = $chapterInfo['chaptername'];;
			
			$bookCaseData['chapterorder'] = $chapterInfo['chapterorder'];;
			
			$flag = M('distribution_article_bookcase')->add($bookCaseData);
			
			if($flag)
			{
				$returnArr = array('status' => 1,'msg' => '加入书架成功！');
			}
			else
			{
				$returnArr = array('status' => 2,'msg' => '加入书架失败！');
			}
		}
		
		$this->ajaxReturn($returnArr);
	}


    /**
     * edit by miyi 2017/05/19
     *增加同乐第三方扫码支付
     * 第三方tongle支付
     *
     */
    public function tonglepay()
    {

        if($_COOKIE['uid'])
        {
            $payNum  = I('egold');

            if(!empty($payNum) && is_numeric($payNum) && $payNum > 0)
            {
                $egold = intval($payNum);

                $tonglePayConf = C('TONGLE_PAY_CONF');

                //计算实际需要支付的金额
                if(!empty($tonglePayConf['paylimit']))
                {
                    if(!empty($tonglePayConf['paylimit'][$egold]))
                    {
                        $money = intval($tonglePayConf['paylimit'][$egold]) ;//充值金额单位元
						//$money=0.01;

                        $flag  = $this->addPayLog($money*100,$egold,'weixintonglepay');

                        if(!$flag)
                        {
                            $this->error('支付失败，请重试！',U('buyEgold'));
                        }
                        else
                        {
                            //配置请求参数
                            $tonglePayConf = C('TONGLE_PAY_CONF');
                            $params = array();
                            $params['merchantID']   = $tonglePayConf['merchantID'];
                            $params['orderNumber']  = $flag;//订单在商户系统中的流水号
                            $params['cardType']     = $tonglePayConf['cardType'];//微信扫码支付
                            $params['userID']       = cookie('uid');//用户id
                            $params['orderAmount']  =$money;//订单支付金额；单位:元(人民币)
                            $params['merURL']       = $tonglePayConf['merURL'];
                            $params['mark']         =$flag;
                            $params['remarks']      =$tonglePayConf['remarks'];
                            $params['key']          = $tonglePayConf['key'];

                            //计算签名
                            $sign = '';
                            foreach($params as $k => $v)
                            {
                                if(!empty($sign))
                                {
                                    $sign .= '&';
                                }

                                $v=iconv('UTF-8','GB2312',$v);
                                $k=iconv('UTF-8','GB2312',$k);

                                $sign .= $k . '=' . $v;
                            }

                            $sign   = strtoupper(md5($sign));

                            $params['sign']      = $sign;
                            $params['type']         =$tonglePayConf['type'];
                            $params['backURL']      = 'http://wap.kyread.com' . U('User/index');
                            unset( $params['key']);

                            //构造请求参数
                            $str = '';
                            foreach($params as $k => $v)
                            {
                                if(!empty($str))
                                {
                                    $str .= '&';
                                }

                                $str .= $k . '=' . $v;
                            }
                            //访问同乐下单接口
                            $gourl  = 'https://api.tongle.net/gateway/Weixinpay/Weixinpay.aspx?'.$str;
                           //解析返回数据
						  //var_dump($gourl);exit;
                            $result=simplexml_load_string(request($gourl), 'SimpleXMLElement', LIBXML_NOCDATA);
                            $result=$result->items;
                            $result=json_encode($result);
                            $result=json_decode($result,true);
							 
                            $result=$result['item'];
                            $state=$result[0]['@attributes']['value'];//获取下单结果
                            $qcimgurl=$result[6]['@attributes']['value'];//获取二维码图片地址
                            if($state=='1'){
                                //如果下单成功分配变量
                                $this->assign('qrcimgurl',$qcimgurl);
                                $this->assign('total_fee',$money);
                                $this->assign('out_trade_no',$flag);
                            }else{
                                $this->error('订单生成失败!请重试',U('buyEgold'));
                            }

                            $this->display('tonglepay');
                        }
                    }
                    else
                    {
                        $this->error('对不起，您选择的购买金额类型不存在！' , U('buyEgold'));
                    }
                }
            }
        }
        else
        {
            $this->display('login');
        }
        
	}


    
    /**
     * edit by miyi 2017/05/19
     *增加同乐第三方扫码支付回调
     * 第三方tongle支付回调
     *
     */    

    public function tongleReturn()
    {
        $tonglePayData = new UsersLogic();
        $flag = $tonglePayData->tongleReturn();
        if($flag)
        {
            echo 'ok';
        }else{
            echo 'no';
        }

    }


    /**
     * edit by muyi 2017/05/19
     * 增加第三方同乐查询接口
     */

    public function tongleOrderQuery()
    {
        $tonglePay = new UsersLogic();

        $flag = $tonglePay->tongleOrderQuery();

        if($flag){
            echo 'SUCCESS';
        }

    }
    
	
	
	public function zhifukaPay()
	{
		if($_COOKIE['uid'])
		{
			$payNum  = I('egold');
			
			if(!empty($payNum) && is_numeric($payNum) && $payNum > 0)
			{
				$egold = intval($payNum);
				
				$zhifukaPayConf = C('ZHIFUKA_PAY_CONF');
				
				//计算实际需要支付的金额
				if(!empty($zhifukaPayConf['paylimit']))
				{
					if(!empty($zhifukaPayConf['paylimit'][$egold]))
					{
						$money = intval($zhifukaPayConf['paylimit'][$egold]) * 100;
						
//						$money = 1;
						
						//分为单位
						$flag  = $this->addPayLog($money,$egold,'weixinapppay');
						
						if(!$flag)
						{
							$this->error('支付失败，请重试！',U('buyEgold'));
						}
						else
						{
							$zhifukaPayConf = C('ZHIFUKA_PAY_CONF');
							
							$params = array();
							$params['customerid']  = $zhifukaPayConf['appid'];
							$params['sdcustomno']  = $flag;//订单在商户系统中的流水号
							$params['orderAmount'] = $money;//订单支付金额；单位:分(人民币)
							$params['cardno']      = $zhifukaPayConf['cardno'];
							$params['noticeurl']   = "" . $zhifukaPayConf['noticeurl'];
							$params['backurl']     = 'http://wap.kyread.com' . U('User/index');
							
//							$params['backurl']     = 'http://wap.kyread.com';
							
							$sign = '';
							
							foreach($params as $k => $v)
							{
								if(!empty($sign))
								{
									$sign .= '&';
								}
								
								$sign .= $k . '=' . $v;
							}
							
							$Md5str = $sign . $zhifukaPayConf['paykey'];
							
							$sign   = strtoupper(md5($Md5str));
							
							$gourl  = 'http://www.zhifuka.net/gateway/weixin/wap-weixinpay.asp?customerid=' . $params['customerid'] . '&sdcustomno=' . $params['sdcustomno'] . '&orderAmount=' . $params['orderAmount'] . '&cardno=' . $params['cardno'].'&noticeurl=' . $params['noticeurl'] . '&backurl=' . $params['backurl'] . '&sign=' . $sign . '&mark=' . time();
							
							echo "<script language=\"javascript\">";
							echo "document.location=\"" . $gourl."\"";
							echo "</script>";
						}
					}
					else
					{
						$this->error('对不起，您选择的购买金额类型不存在！' , U('buyEgold'));
					}
				}
			}
		}
		else
		{
			$this->display('login');
		}
	}
	
	public function zhifukaReturn()
	{
		$zhifukaPayData = new UsersLogic();
		
		$flag = $zhifukaPayData->zhifukaReturn();
		
		if($flag)
		{
			header("Location:" . U('Index/index'));
		}
		else
		{
			header("Location:" . U('User/buyEgold'));
		}
	}
	
	public function weixinAppPay()
	{
		if($_COOKIE['uid'])
		{
			$payNum  = I('egold');
			
			if(!empty($payNum) && is_numeric($payNum) && $payNum > 0)
			{
				$egold = intval($payNum);
				
				$weixinAppPayConf = C('WEIXIN_APP_PAY_CONF');
				
				//计算实际需要支付的金额
				if(!empty($weixinAppPayConf['paylimit']))
				{
					if(!empty($weixinAppPayConf['paylimit'][$egold]))
					{
						$money = intval($weixinAppPayConf['paylimit'][$egold]) * 100;
						
						//$money = 1;
						
						//分为单位
						$flag  = $this->addPayLog($money,$egold,'weixinPay');
						
						if(!$flag)
						{
							$returnArr = json_encode_ex(array('status' => 0,'msg' => '支付失败，请重试'));
							
							echo $returnArr;
							
							return;
						}
						else
						{
							$weixinPayData = new UsersLogic();
							
							$payID         = $flag;
							
							$code          = $_SESSION['paycode'];
							
							$openIDInfo['openid'] = $code;
							
							if($openIDInfo && $openIDInfo['openid'])
							{
								$payInfo       = $weixinPayData->generateWeiXinAppPayInfo($payID,$money,$openIDInfo['openid']);
														
								//file_put_contents('33.txt',var_export($payInfo,true));
								if($payInfo)
								{							
									
									$returnArr = array('status' => 1, 'data' => $payInfo);
									
									$returnArr = json_encode_ex($returnArr);
									
									echo $returnArr;
									
									return;
								}
								else
								{
									$returnArr = array('status' => 1, 'data' => $payInfo);
									
									$returnArr = json_encode_ex($returnArr);
									
									echo $returnArr;
									
									return;
								}
							}
							else
							{
								$returnArr = array('status' => -1,'msg' => '对不起，获取用户支付信息失败！code ' . $code . ' ---  $openIDInfo[\'openid\']' . $openIDInfo['openid']);
								
								$returnArr = json_encode_ex($returnArr);
								
								echo $returnArr;
								
								return;
							}
						}
					}
					else
					{
						$returnArr = array('status' => -1,'msg' => '对不起，您选择的购买金额类型不存在！');
						
						$this->ajaxReturn($returnArr);
					}
				}
			}
		}
		else
		{
			$this->display('login');
		}
	}
	
	public function weixinAppPayReturn()
	{
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		
		$reponse = weixinPayFromXml($xml);//将$xml转换为array
		
		$weixinPay = C('WEIXIN_APP_PAY_CONF');
		file_put_contents('./payreturnres.txt',var_export($reponse,true),FILE_APPEND);
		//支付成功
		if(isset($reponse["return_code"]) &&
			$reponse["return_code"] == "SUCCESS" &&
			isset($reponse["result_code"]) &&
			$reponse["result_code"] == "SUCCESS")
		{
			//参数数组
			$varArray = array();
			
			$outTradeNo = $reponse["out_trade_no"];
			
			$mchIDLength    = strlen($weixinPay['mch_id']);
			
			$dateLength     = 14;
			
			$orderid        = intval(substr(strval($outTradeNo),$mchIDLength + $dateLength));
			
			$paylog   = M('distribution_pay_paylog')->where(array('payid' => $orderid))->find();
			
			if(is_array($paylog))
			{
				$buyname = $paylog['buyname'];
				$buyid   = $paylog['buyid'];
				$payflag = $paylog['payflag'];
				$egold   = $paylog['egold'];
				$money   = $paylog['money'];
				
				if($payflag == 0)
				{
					$weixinPayData = new UsersLogic();	
					$userInfo = M('distribution_system_users')->where(array('uid' => $buyid))->find();					
					if($money==365){
							if($userInfo['viptime']>time()){
								$userData['viptime']=strtotime('+1 year',$userInfo['viptime']);
							}else{
								$userData['viptime']=strtotime('+1 year',time());
							}
						}

						$userData['egold'] = $userInfo['egold'] + $egold;

						M('distribution_system_users')->where(array('uid' => $buyid))->save($userData);

						$paylogData['payflag'] = 1;

						$note = sprintf('给 %s 增加%s %s', $buyname, C('DEFAULT_EGOLD_NAME'), $egold);
						if($money==365){
							$note= $buyname.'购买了年费会员!';
						}
						$paylogData['note'] = $note;

						M('distribution_pay_paylog')->where(array('payid' => $orderid))->save($paylogData);
					
					$weixinPayData->addEgoldTOChannelByWeiXin($paylog,$money);
				}
				
				$urlvars['return_code'] = 'SUCCESS';
				
				$urlvars['return_msg'] = 'OK';
			}
			else
			{
				$urlvars['return_code'] = 'FAIL';
				
				$urlvars['return_msg']  = '操作失败';
			}
		}
		else
		{
			$urlvars['return_code'] = 'FAIL';
			
			$urlvars['return_msg']  = '参数格式校验错误';
		}
		//告诉微信已接收到支付结果通知
		header("Content-type: text/html; charset=utf-8");
		$tpl='<xml>
                  <return_code><![CDATA[%s]]></return_code>
                  <return_msg><![CDATA[%s]]></return_msg>
               </xml>';
			   
		 echo sprintf($tpl,$urlvars['return_code'],$urlvars['return_msg']);
		
		/*$dataXML = weixinPayToXml($urlvars);
		
		$apiUrl  = $weixinPay['orderquery_url'];
		
		weixinPayPostXmlCurl($dataXML, $apiUrl);//商户处理后同步返回给微信*/
	}
	
	public function getWeiXinInfo()
	{
		if($this->weiXinBrower)
		{
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

			$weixinPayData->getWXUserOpenID($backUrl);
		}
	}
	
	public function getWeiXinOpenID($code)
	{
		//edit by muyi 2017/05/31
        //添加不同服务号用户分离
        $params = array();
        $weixinPay = C('WEIXIN_APP_PAY_CONF');       
        $params['appid']      = $weixinPay['appid'];
        $params['secret']     = $weixinPay['opendkey'];
		$params['code']       = $code;
		$params['grant_type'] = 'authorization_code';
		
		$accessTokenURL       = "https://api.weixin.qq.com/sns/oauth2/access_token";//通过code获取access_token
		
		$accessTokenUrl = $accessTokenURL . '?' . http_build_query($params);
		
		$result = getResponseFromUrl($accessTokenUrl);
		
		$result = json_decode($result,true);
		
		return $result;
	}
	
	
	
    /**
     * edit by muyi 2017/06/19
     * 签到送书币
     */
    public function sign()
    { //edit by muyi 2017/06/19签到增书币
        if(cookie('uid')>0){
            $uid=cookie('uid');
        }

        $getid=I('get.id')+0;
        if($getid>0){
            $uid=$getid;
        }

        //获取用户信息
        $userModel=D('User');
        $userinfo=$userModel->field('uname,egold,viptime,signtime')->where(array('uid'=>$uid))->find();
        $today=strtotime(date('Y-m-d',time()));
        //判断有没有签到过
        if($userinfo['signtime']<$today&&$uid>0){
            $this->assign('sign',0);
        }else{
            if($getid>0){
                $return= array("status"=>0);
                echo json_encode($return);
                exit;
            }
            $this->assign('sign',1);
        }

        //如果是ajax签到增加书币
        if($getid>0){
            $userinfo['signtime']=time();
            $userinfo['egold']= $userinfo['egold']+50;
            $userModel->where(array('uid'=>$uid))->save($userinfo);
			$userModel->sendSigninfo($userinfo['uname']);
            $return= array("status"=>1);
            echo json_encode($return);
            exit;
        }
        $this->assign('uid',$uid);

        $this->display();

	}
	
	
	private function getUrlParameter($parameterList)
	{
		$returnArr = array();
		
		for($i = 2; $i < count($parameterList);)
		{
			$returnArr[$parameterList[$i]] = $parameterList[$i + 1];
			
			$i += 2;
		}
		
		return $returnArr;
	}
}
