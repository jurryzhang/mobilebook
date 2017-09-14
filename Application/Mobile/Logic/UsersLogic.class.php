<?php
/**
 * 趣读商城
 * ============================================================================
 * * 版权所有 2015-2027 河南趣读信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.qudukeji.com
 * ----------------------------------------------------------------------------
 * ============================================================================
 */

namespace Mobile\Logic;

use Think\Model\RelationModel;
use Think\Page;

/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class UsersLogic extends RelationModel
{
	/**
	 * 验证用户名和密码
	 * @param unknown $username 用户名
	 * @param unknown $password 密码
	 */
    public function login($username,$password)
    {
    	$result = array();
        
        if(!$username || !$password)
        {
        	$result = array('status' => 0,'msg' => '请填写账号或密码');
        }
        
        $user = M('distribution_system_users')->where("uname='{$username}'")->find();
	
        if(!$user)
        {
           $result = array('status' => -1,'msg' => '账号不存在!');
        }
        elseif(encrypt($password) != $user['pass'])
        {
           $result = array('status' => -2,'msg' => '密码错误!');
        }
        else
        {
           $result = array('status' => 1,'msg' => '登陆成功','result' => $user);
        }
        
        return $result;
    }

    /*
     * 第三方登录
     */
    public function thirdLogin1($data = array())
    {
        $openid = $data['uname']; //第三方返回唯一标识
        $oauth  = $data['oauth']; //来源
        
        if(!$openid || !$oauth)
        {
        	return array('status' => -1,'msg' => '参数有误','result' => '');
        }
        
        //获取用户信息
        $user = get_user_info($openid,$oauth);
	
	    //账户不存在 注册一个
	    $map['pass']      = encrypt(C('DEFAULT_PASSWORD'));
	    $map['uname']     = $openid;
	    $map['name']      = $data['nickname'];
	    $map['regdate']   = time();
	    $map['logintype'] = $oauth;
	    $map['faceImg']   = $data['head_pic'];
	    $map['lastlogin'] = time();
		$map['isfollow'] = $data['isfollow'];
	
        if(!$user)
        {
	        if(cookie('channel_id'))
	        {
		        $map['channelid'] = cookie('channel_id');
	        }else{
                $map['channelid'] =0;
            }
	
	        if(cookie('channel_name'))
	        {
		        $map['channelname'] = cookie('channel_name');
	        }else{
                $map['channelname'] ='官方渠道';
            }


            if(cookie('ptid'))
            {
                $map['ptid'] = cookie('ptid');
            }

            $row = M('distribution_system_users')->add($map);
            
            if(!$row)
            {
	            return array('status' => -1,'msg' => '参数有误1','result' => '');
            }
            
            $user = get_user_info($openid,$oauth);
        }
        else
        {
			//edit by muyi 2017/06/09
            //如果用户已存在用户名不是游客就不修改
            $pos   = strpos($data['nickname'],DEFAULT_VISITOR_NICKNAME);
            if($pos===false){
                $map['name']      = $data['nickname'];
                $map['faceImg']   = $data['head_pic'];
            }else{
                unset( $map['name'] );
                unset( $map['faceImg']);
            }

	        $map['logintype'] = $oauth;
			if($map['isfollow']==0){
				unset($map['isfollow']);
			}

	        
	        //edit by muyi 2017/05/15
            //如果用户存在不更新用户注册时间
            unset( $map['regdate']);
			//var_dump($pos );exit;
	
	        $row = M('distribution_system_users')->where(array('uid' => $user['uid']))->save($map);
	        
	        if(!$row)
	        {
		        return array('status' => -1,'msg' => '参数有误1','result' => '');
	        }
	        
	        $user = get_user_info($openid,$oauth);
        }
        
        return array('status' => 1,'msg' => '登陆成功','result' => $user);
    }

	
	 /*
     * 第三方登录
     */
    public function thirdLogin($data = array())
    {
        $openid = $data['uname']; //第三方返回唯一标识
        $oauth  = $data['oauth']; //来源
        
        if(!$openid || !$oauth)
        {
        	return array('status' => -1,'msg' => '参数有误','result' => '');
        }
        
        //获取用户信息
        $user = get_user_info($openid,$oauth);
	
	    //账户不存在 注册一个
	    $map['pass']      = encrypt(C('DEFAULT_PASSWORD'));
	    $map['uname']     = $openid;
	    $map['name']      = $data['nickname'];
	    $map['regdate']   = time();
	    $map['logintype'] = $oauth;
	    $map['faceImg']   = $data['head_pic'];
	    $map['lastlogin'] = time();
	
        if(!$user)
        {
	        if(cookie('channel_id'))
	        {
		        $map['channelid'] = cookie('channel_id');
	        }
	
	        if(cookie('channel_name'))
	        {
		        $map['channelname'] = cookie('channel_name');
	        }
        	
            $row = M('distribution_system_users')->add($map);
            
            if(!$row)
            {
	            return array('status' => -1,'msg' => '参数有误1','result' => '');
            }
            
            $user = get_user_info($openid,$oauth);
        }
        else
        {
	        $map['name']      = $data['nickname'];
	        $map['logintype'] = $oauth;
	        $map['faceImg']   = $data['head_pic'];
	
	        $row = M('distribution_system_users')->where(array('uid' => $user['uid']))->save($map);
	        
	        if(!$row)
	        {
		        return array('status' => -1,'msg' => '参数有误1','result' => '');
	        }
	        
	        $user = get_user_info($openid,$oauth);
        }
        
        return array('status' => 1,'msg' => '登陆成功','result' => $user);
    }

	
	
	
    /**
     * 注册
     * @param $username  邮箱或手机
     * @param $password  密码
     * @param $password2 确认密码
     * @return array
     */
    public function reg($username,$password,$password2)
    {
    	$is_validated = 0 ;
    	
        if(check_email($username))
        {
            $is_validated = 1;
        }

        if(check_mobile($username))
        {
            $is_validated = 1;
        }

        if($is_validated != 1)
        {
        	return array('status' => -1,'msg' => '请用手机号或邮箱注册','result' => '');
        }

        if(!$username || !$password)
        {
        	return array('status' => -1,'msg' => '请输入用户名或密码','result' => '');
        }

        //验证两次密码是否匹配
        if($password2 != $password)
        {
        	return array('status' => -1,'msg' => '两次输入密码不一致','result' => '');
        }
        
        //验证是否存在用户名
        if(get_user_info($username,1) || get_user_info($username,2))
        {
        	return array('status' => -1,'msg' => '账号已存在','result' => '');
        }
	
	    $time                 = time();
        $map['uname']         = $username;
	    $map['name']          = DEFAULT_NICKNAME . getRandChar(10);
        $map['pass']          = encrypt($password);
	    $map['regdate']       = $time;
        $map['lastlogin']     = $time;
	    $map['sex']           = 0;
	    $map['faceImg']       = DEFAULT_FACE_IMG;
        $map['mobile']        = $username;
	    $map['channelid']     = cookie('channel_id');
	    $map['channelname']   = cookie('channel_name');
        
        $row = M('distribution_system_users')->add($map);
        
        if(!$row)
        {
        	return array('status' => -1,'msg' => '注册失败','result' => '');
        }
        else
        {
	        return array('status' => 1,'msg' => '注册成功','result' => $user);
        }
    }

     /*
      * 获取当前登录用户信息
      */
     public function get_info($user_id)
     {
		if(!$user_id > 0)
		{
			return array('status' => -1,'msg' => '缺少参数','result' => '');
		}
		
        $user_info = M('distribution_system_users')->where("uid = {$user_id}")->find();
        
        if(!$user_info)
        {
        	return false;
        }
         
		return array('status' => 1,'msg' => '获取成功','result' => $user_info);
     }
     
    /**
     * 更新用户信息
     * @param $user_id
     * @param $post  要更新的信息
     * @return bool
     */
    public function update_info($user_id,$post=array())
    {
    	$key = array_keys($post);
	
	    $key = $key[0];
	
	    $result = M('distribution_system_users')->where("uid = $user_id")->field($key)->find();
	    
	    if($result[$key] == $post[$key])
	    {
		    return true;
	    }
	    else
	    {
		    $row = M('distribution_system_users')->where("uid = $user_id")->save($post);
		
		    if($row == false)
		    {
			    return false;
		    }
		
		    return true;
	    }
    }
	
	/**
	 * 生成阿里支付信息
	 *
	 * @param $money
	 */
    public function generateAliPayInfo($payID,$money)
    {
    	if($_COOKIE['uid'])
	    {
		    $aliPayConf = C('ALI_PAY_CONF');
		
		    $urlvars = array();
		    $urlvars['service']        = $aliPayConf['service']; //交易类型
		    $urlvars['partner']        = $aliPayConf['payid']; //合作商户号

		    $urlvars['return_url']     = $aliPayConf['payreturn'];  //同步返回

            $urlvars['notify_url']     = $aliPayConf['notify_url'];  //异步返回
		    
		    $urlvars['_input_charset'] = 'utf-8';  //字符集，默认为GBK
		
		    $urlvars['subject']        = '小说币';  //商品名称，必填
		    $urlvars['out_trade_no']   = $payID; //商品外部交易号，必填,每次测试都须修改
		    $urlvars['total_fee']      = $money; //商品总价
		
		    $urlvars['payment_type']   = $aliPayConf['payment_type']; // 商品支付类型 1 ＝商品购买 2＝服务购买 3＝网络拍卖 4＝捐赠 5＝邮费补偿 6＝奖金
		    $urlvars['show_url']       = $aliPayConf['show_url'];  //商品相关网站公司
		
		    $urlvars['seller_email']   = $aliPayConf['seller_email'];   //卖家邮箱，必填
		
		    ksort($urlvars);
		    reset($urlvars);
		    $sign  = '';
		    $query = '';
		
		    foreach($urlvars as $k => $v)
		    {
			    if(!empty($sign))
			    {
				    $sign.='&';
			    }
			
			    $sign .= $k . '=' . $v;
			
			    if(!empty($query))
			    {
				    $query.='&';
			    }
			
			    $query .= $k . '=' . urlencode($v);
		    }
		
		    $sign   = md5($sign . $aliPayConf['paykey']);
		
		    $query .= '&sign_type=' . $aliPayConf['sign_type'] . '&sign=' . $sign;
		    $query  = $aliPayConf['payurl'] . '?' . $query;
		
		    header('Location: ' . $query);
	    }
	    else
	    {
		    header('Location: ' . U('Mobile/User/login'));
	    }
    }
	
    public function aliPayReturn()
    {
	    $aliPayConf = C('ALI_PAY_CONF');
	
	    $info = getAliPayReturnInfo();
	    
	    if(!empty($info['notify_id']) && !empty($info['buyer_email']) && !empty($info['out_trade_no']))
	    {
	    	if($info['seller_email'] == $aliPayConf['seller_email'] && $info['seller_id'] == $aliPayConf['payid'])
		    {
			    //直接返回模式
			    $getvars  = $info;
			
			    $showmode = 1;
		    }
		    else
		    {
		    	return;
		    }
	    }
	    elseif(!empty($_POST['notify_id']) && !empty($_POST['buyer_email']) && !empty($_POST['out_trade_no']))
	    {
		    //异步返回模式
		    echo 'success';
		
		    if(I('post.seller_email') == $aliPayConf['seller_email'] && I('post.seller_id') == $aliPayConf['payid'])
		    {
			    //直接返回模式
			    $getvars  = I('post.');
			
			    $showmode = 0;
		    }
		    else
		    {
			    return;
		    }
	    }
	    else
	    {
		    echo 'fail';

		    exit;
	    }

	    //检查交易状态(是不是付款成功)
	    if(strtoupper($getvars['trade_status']) != 'TRADE_SUCCESS')
	    {
		    if(!$showmode)
		    {
			    exit;
		    }
	    }

	    //md5校验
	    ksort($getvars);
	    reset($getvars);
	    $signtext   = '';
	    $signdecode = '';

	    foreach($getvars as $k => $v)
	    {
		    if($k != 'sign' && $k != 'sign_type')
		    {
			    if(!empty($signtext))
			    {
				    $signtext   .= '&';
				    $signdecode .= '&';
			    }

			    $signtext   .= $k . '=' . $v;
			    $signdecode .= $k . '=' . urldecode($v);
		    }
	    }
	
	    $tmpSignKey     = $signtext . $aliPayConf['paykey'];
	
	    $tmpSign        = strtolower(md5($tmpSignKey));
	    
	    $tmpSignCodeKey = $signdecode . $aliPayConf['paykey'];
	
	    $tmpSignCode    = strtolower(md5($tmpSignCodeKey));
	
	    if(strtolower($getvars['sign']) == $tmpSign || strtolower($getvars['sign']) ==  $tmpSignCode)
	    {
		    $orderid  = intval($getvars['out_trade_no']);
		
		    $paylog   = M('distribution_pay_paylog')->where(array('payid' => $orderid))->find();
		
		    if(is_array($paylog))
		    {
			    $buyid   = $paylog['buyid'];
			    $buyname = $paylog['buyname'];
			    $payflag = $paylog['payflag'];
			    $egold   = $paylog['egold'];
			    $money   = $paylog['money'];
			
			    if($payflag == 0)
			    {
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

                    $flag=M('distribution_pay_paylog')->where(array('payid' => $orderid))->save($paylogData);

                    $this->addEgoldTOChannelByWeiXin($paylog,$money);
				
				    return $flag;
			    }
		    }
		    else
		    {
			    return;
		    }
	    }
	}
	
	public function generateWeiXinPayInfo($payID,$money)
	{
		if($_COOKIE['uid'])
		{
			$weixinCodePayConf = C('WEIXIN_CODE_PAY_CONF');
			
			$urlvars = array();
			$urlvars['appid']    = $weixinCodePayConf['appid']; //公众账号ID
			
			$urlvars['body'] = $weixinCodePayConf['body'];
			
			$urlvars['device_info']      = $weixinCodePayConf['device_info']; //设备号
			
			$urlvars['mch_id']           = $weixinCodePayConf['mch_id']; //合作商户号
			
			$urlvars['nonce_str']        = getRandChar(16);//随机数生成
			
			$urlvars['out_trade_no']     = $urlvars['mch_id'] . date("YmdHis") . $payID; //商品外部交易号，必填,每次测试都须修改
			 
			$urlvars['total_fee']        = $money;//标价金额，单位为分
			
			$urlvars['spbill_create_ip'] = gethostbyname($_ENV['COMPUTERNAME']);//终端IP，获取服务器的IP
			
			$urlvars['notify_url']       = $weixinCodePayConf['notify_url']; //通知地址
			
			$urlvars['trade_type']       = $weixinCodePayConf['trade_type']; //交易类型
			
			ksort($urlvars);
			
			$sign = toUrlParams($urlvars);
			
			$keyStr = "&key=" . $weixinCodePayConf['paykey']; //key
			
			$sign .= $keyStr;
			
			$sign  = strtoupper(md5($sign));
			
			$urlvars['sign'] = $sign;
			
			$dataXML = toXml($urlvars);
			
			$apiUrl = $weixinCodePayConf['api_url'];
			
			$startTimeStamp = getMillisecond();//请求开始时间
			
			$response = postXmlCurl($dataXML, $apiUrl);
			
			$data = fromXml($response);
			
			$flag = checkSign($data,$weixinCodePayConf['paykey']);
			
			if($flag)
			{
				reportCostTime($apiUrl, $startTimeStamp, $data);//上报请求花费时间
				
				$result['code_url']     = $data['code_url'];
				
				$result['total_fee']    = $urlvars['total_fee'] / 100;
				
				$result['out_trade_no'] = $urlvars['out_trade_no'];
				
				return $result;
			}
			else
			{
				return;
			}
		}
		else
		{
			header('Location: ' . U('Mobile/User/login'));
		}
	}
	
	public function weixinOrderQuery()
	{
		if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != "")
		{
			$outTradeNo = $_REQUEST['out_trade_no'];
			
			$weixinCodePayConf = C('WEIXIN_CODE_PAY_CONF');
			
			//参数数组
			$varArray = array();
			
			$varArray['appid']        = $weixinCodePayConf['appid'];
			$varArray['mch_id']       = $weixinCodePayConf['mch_id'];
			$varArray['out_trade_no'] = $outTradeNo;
			$varArray['nonce_str']    = getRandChar(16);
			
			//按字典序排序参数
			ksort($varArray);
			
			//生成签名
			$sign   = toUrlParams($varArray);
			
			$keyStr = "&key=" . $weixinCodePayConf['paykey']; //key
			
			$sign  .= $keyStr;
			
			$sign   = strtoupper(md5($sign));
			
			$varArray['sign'] = $sign;
			
			$dataXML  = toXml($varArray);
			
			$orderURL = $weixinCodePayConf['orderquery_url'];
			
			$startTimeStamp = getMillisecond();//请求开始时间
			$response = postXmlCurl($dataXML, $orderURL);
			
			$result = fromXml($response);
			
			checkSign($result,$weixinCodePayConf['paykey']);
			
			reportCostTime($orderURL,$startTimeStamp,$result);
			
			echo $result['trade_state'];
			
			if($result['trade_state'] == 'SUCCESS')
			{
				//获取payID
				$mchIDLength = strlen($weixinCodePayConf['mch_id']);
				
				$dateLength  = 14;
				
				$orderid     = intval(substr(strval($outTradeNo),$mchIDLength + $dateLength));
				
				$paylog      = M('distribution_pay_paylog')->where(array('payid' => $orderid))->find();
				
				if(is_array($paylog))
				{
					$buyid   = $paylog['buyid'];
					$buyname = $paylog['buyname'];
					$payflag = $paylog['payflag'];
					$egold   = $paylog['egold'];
					$money   = $paylog['money'];
					
					if($payflag == 0)
					{
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

                    $this->addEgoldTOChannelByWeiXin($paylog,$money);
					}
				}
			}
		}
	}


    /**
     *同乐第三方支付查询接口
     */
    public function tongleOrderQuery()
    {
        if(isset($_REQUEST["out_trade_no"]) && $_REQUEST["out_trade_no"] != "")
        {
            $outTradeNo = $_REQUEST['out_trade_no'];

            $tonglePayConf = C('TONGLE_PAY_CONF');

            //参数数组
            $varArray = array();

            $varArray['merchantID']= $tonglePayConf['merchantID'];
            $varArray['orderNumber'] =$outTradeNo;
            $varArray['mark'] = $outTradeNo;
            $varArray['key'] =  $tonglePayConf['key'];

            //计算签名
            $sign = '';
            foreach($varArray as $k => $v)
            {
                if(!empty($sign))
                {
                    $sign .= '&';
                }

                $sign .= $k . '=' . $v;
            }

            $sign   = strtoupper(md5($sign));
            $varArray['sign'] =  $sign;
            unset( $varArray['key']);

            //构造请求参数
            $str = '';
            foreach($varArray as $k => $v)
            {
                if(!empty($str))
                {
                    $str .= '&';
                }

                $str .= $k . '=' . $v;
            }
            //访问同乐查询交易接口
            $gourl  = 'https://api.tongle.net/gateway/Weixinpay/orderquery.aspx?'.$str;
            //解析返回数据

            $result=simplexml_load_string(request($gourl), 'SimpleXMLElement', LIBXML_NOCDATA);
            $result=$result->items;
            $result=json_encode($result);
            $result=json_decode($result,true);
            $result=$result['item'];
            $state=$result[0]['@attributes']['value'];
            //如果成功更新用户书币
            if($state == '1')
            {
                $orderid     = $outTradeNo;
                $paylog      = M('distribution_pay_paylog')->where(array('payid' => $orderid))->find();

                if(is_array($paylog))
                {
                    $buyid   = $paylog['buyid'];
                    $buyname = $paylog['buyname'];
                    $payflag = $paylog['payflag'];
                    $egold   = $paylog['egold'];
                    $money   = $paylog['money'];

                    if($payflag == 0)
                    {
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

						$this->addEgoldTOChannelByWeiXin($paylog,$money);
                    }

                    return true;
                }
            }
        }
	}

	/*daisy测试*/
	public function paylogpaylog(){
        $paylog      = M('distribution_pay_paylog')->where(array('payid' => 3937))->find();
        $money   = $paylog['money'];
        $this->addEgoldTOChannelByWeiXin($paylog,$money);
    }
    /*daisy修改*/
	public function addEgoldTOChannelByWeiXin($paylog,$money)
	{
        
         $channel=M('distribution_channels')->field('pid,proportion')->where(array('channelid' => $paylog['channelid']))->find();
       // dump($pid);exit;
         //var_dump($channel);
        $channelid=$paylog['channelid'];
        $pid=intval($channel['pid']);
        $proportion=$channel['proportion'];

        //渠道充值表里添加数据
        $paylogModel=M('distribution_pay_log');
        $oldData = $paylogModel->where(array('channelid' => $channelid))->find();
		$oldDataP = $paylogModel->where(array('channelid' => $pid))->find();

        $payData['remainmoney'] = $oldData['remainmoney'] + $money;        
        $payDataP['remainmoney'] = $oldDataP['remainmoney'] + $money;        

        $res1=$paylogModel->where(array('channelid' => $channelid))->save($payData);
        $res2=$paylogModel->where(array('channelid' => $pid))->save($payDataP);

        //渠道结算单里添加数据
        //判断这个渠道今天添加过结算单没有
        $today=strtotime(date('Y-m-d',time()));
        $now = time();
        //$ftime = $today+60*60*24;
        if($now>=1504195200){//第一次使用,判断在今天当前时间以前才可执行
        $paymentModel=M('distribution_payment');
        $paymentOldData=$paymentModel->where(array('channelid' => $channelid,'paydate'=>$today,'isall'=>0))->find();
        //var_dump($paymentOldData);
        if($paymentOldData){
            //如果已经添加过该渠道今天的结算单更新充值金额
            $paymentOldData['askmoney']= $paymentOldData['askmoney']+$money;
            $paymentOldData['paycount']= $paymentOldData['paycount']+1;
            $paymentOldData['paymoney']= $paymentOldData['paymoney']+$money*$paymentOldData['proportion'];
            $paymentRes=$paymentModel->save($paymentOldData);
            if($paymentRes){
                //如果添加成功,添加上级结算单金额
                if($pid==0){
                    $pid=$channelid;

                }

                //添加成功之后在上级渠道的结算单中增加充值金额
                //判断上级结算单有没有添加过
                $paymentOldDataP=$paymentModel->where(array('channelid' => $pid,'paydate'=>$today,'isall'=>1))->find();
                //var_dump($paymentOldDataP);exit;
                if($paymentOldDataP){
                	//var_dump($paymentOldDataP);
                    //如果上级已经被添加更新上级的充值的值
                    $paymentOldDataP['askmoney']=$paymentOldDataP['askmoney']+$money;
                    $paymentOldDataP['paycount']=$paymentOldDataP['paycount']+1;
                    $paymentOldDataP['paymoney']=$paymentOldDataP['paymoney']+$money * $paymentOldDataP['proportion'];
                    $rr = $paymentModel->save($paymentOldDataP);
                    //var_dump($rr);exit;


                }else{
                    //如果上级结算单没有被添加过第一次添加
                    //获取上级渠道的分成比例
                    $pchannel=M('distribution_channels')->field('proportion')->where(array('channelid' => $pid))->find();
                    
                    $paymentDataP['asktime']=date('Y-m-d',time());
                    $paymentDataP['askmoney']=$money;
                    $paymentDataP['paymoney']=$money * $pchannel['proportion'];
                    $paymentDataP['proportion']=$pchannel['proportion'];
                    $paymentDataP['addtime']=time();
                    $paymentDataP['paydate']=$today;
                    $paymentDataP['paycount']=1;
                    $paymentDataP['isall']=1;
                    $paymentDataP['channelid']=$pid;                    
                    $paymentModel->add($paymentDataP);
                    
                }
            }

        }else{
            //如果没有就添加
            //如果是一级代理自推广渠道就直接显示已结算状态防止扣错
            if($pid==0){
                $paymentData['status']=2;
                //如果pid等于0,就是一级代理让pid=channelid
                $pid=$channelid;
            }else{
                $paymentData['status']=0;
            }
            $paymentData['asktime']=date('Y-m-d',time());
            $paymentData['askmoney']=$money;
            $paymentData['paycount']=1;
            $paymentData['paymoney']=$money * $proportion;
            $paymentData['proportion']=$proportion;
            $paymentData['addtime']=time();
            $paymentData['paydate']=$today;
            $paymentData['isall']=0;
            $paymentData['channelid']=$channelid;

            //添加充值渠道的结算单
            $res=$paymentModel->add($paymentData);
            //echo M()->getLastSql();
            //var_dump($res);
            if($res){
                //添加成功之后在上级渠道的结算单中增加充值金额
                //判断上级结算单有没有添加过
                $paymentOldDataP=$paymentModel->where(array('channelid' => $pid,'paydate'=>$today,'isall'=>1))->find();
                
                if($paymentOldDataP){
                    //如果上级已经被添加更新上级的充值的值

                    $paymentOldDataP['askmoney']=$paymentOldDataP['askmoney']+$money;
                    $paymentOldDataP['paycount']=$paymentOldDataP['paycount']+1;
                    $paymentOldDataP['paymoney']=$paymentOldDataP['paymoney']+$money * $paymentOldDataP['proportion'];
                    $paymentModel->save($paymentOldDataP);

                }else{
                    //如果上级结算单没有被添加过第一次添加
                    //获取上级渠道的分成比例
                    $pchannel=M('distribution_channels')->field('proportion')->where(array('channelid' => $pid))->find();
                    $paymentDataP['asktime']=date('Y-m-d',time());
                    $paymentDataP['askmoney']=$money;
                    $paymentDataP['paymoney']=$money * $pchannel['proportion'];
                    $paymentDataP['proportion']=$pchannel['proportion'];
                    $paymentDataP['addtime']=time();
                    $paymentDataP['paydate']=$today;
                    $paymentDataP['paycount']=1;
                    $paymentDataP['isall']=1;
                    $paymentDataP['channelid']=$pid;
                    $paymentModel->add($paymentDataP);
                }

        
            }
        }
        }
    
	}
	/* public function addEgoldTOChannelByWeiXin($paylog,$money)
	{
		$oldData = M('distribution_pay_log')->where(array('channelid' => $paylog['channelid']))->find();
		
		$payData['remainmoney'] = $oldData['remainmoney'] + $money;
		
		M('distribution_pay_log')->where(array('id' => $paylog['channelid']))->save($payData);
	} */
	
	
	
	/**
	 * 增加渠道的可兑换金额，小说币
	 *
	 * @param $egold 要增加的小说
	 */
	public function addEgoldTOChannel($egold)
	{
		if(cookie('channel_id') && cookie('channel_name'))
		{
			$oldData = M('distribution_pay_log')->where(array('channelid' => cookie('channel_id'),'channelname' => cookie('channel_name')))->find();
			
			if($oldData)
			{
				$payData['remainmoney'] = $oldData['remainmoney'] + $egold;
				
				M('distribution_pay_log')->where(array('id' => $oldData['id']))->save($payData);
			}
			else
			{
				$payData['channelid']   = cookie('channel_id');
				
				$payData['channelname'] = cookie('channel_name');
				
				$payData['remainmoney'] = $egold;
				
				M('distribution_pay_log')->add($payData);
			}
		}
		else
		{
			$channelID   = $_REQUEST['channelid'];
			
			$channelKey  = $_REQUEST['secretkey'];
			
			$channelName = $_REQUEST['channel'];
			
			$channelType = $_REQUEST['type'];
			
			if($channelID)
			{
				cookie('channel_id', $channelID);
			}
			
			if($channelKey)
			{
				cookie('secretkey', $channelKey);
			}
			
			if($channelName)
			{
				cookie('channel_name', $channelName);
			}
			
			if($channelType)
			{
				cookie('channel_type', $channelType);
			}
			
			$oldData = M('distribution_pay_log')->where(array('channelid' => $_COOKIE['channel_id'],'channelname' => $_COOKIE['channel_name']))->find();
			
			if($oldData)
			{
				$payData['remainmoney'] = $oldData['remainmoney'] + $egold;
				
				M('distribution_pay_log')->where(array('id' => $oldData['id']))->save($payData);
			}
			else
			{
				$payData['channelid']   = cookie('channel_id');
				
				$payData['channelname'] = cookie('channel_name');
				
				$payData['remainmoney'] = $egold;
				
				M('distribution_pay_log')->add($payData);
			}
		}
	}


    /**
     * edit by muyi 2017/05/19
     * @return int
     * 同乐支付成功或失败回调
     */
    public function tongleReturn()
    {

        $tonglePayConf = C('TONGLE_PAY_CONF');
        $signParams = array();

        $signParams['merchantID'] = $tonglePayConf['merchantID'];
        $signParams['orderNumber'] = I('orderNumber');
        $signParams['customerNumber'] = I('customerNumber');
        $signParams['orderAmount'] = I('orderAmount');
        $signParams['status'] = I('status');
        $signParams['result'] = I('result');
        $signParams['key']        = $tonglePayConf['key'];

        $sign = '';

        foreach($signParams as $k => $v)
        {
            if(!empty($sign))
            {
                $sign .= '&';
            }

            $sign .= $k . '=' . $v;
        }

        $sign = strtoupper(md5($sign));

        if(I('sign') != $sign)
        {

            return 0;
            exit();
        }

        if(I('status') == 1)
        {
            $orderid = I('orderNumber');

            $paylog  = M('distribution_pay_paylog')->where(array('payid' => $orderid))->find();

            if(is_array($paylog))
            {
                $buyid   = $paylog['buyid'];
                $buyname = $paylog['buyname'];
                $payflag = $paylog['payflag'];
                $egold   = $paylog['egold'];
                $money   = $paylog['money'];

                if($payflag == 0)
                {
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

                    $this->addEgoldTOChannelByWeiXin($paylog,$money);



                    return 1;
                }
                else
                {


                    return 1;
                }
            }
            else
            {

                return 0;
            }
        }
        else if(I('status') == 2)
        {
            //当充值失败后同步商户系统订单状态
            //此处编写商户系统处理订单失败流程
            //............
            //............
            //商户在接受到网关通知时，应该打印出<result>1</result>标签，以供接口程序抓取信息，以便于我们获取是否通知成功的信息，否则订单会显示没有通知商户

            //记录订单处理日志

            exit();
        }
	}



	
	public function zhifukaReturn()
	{
		$zhifukaPayConf = C('ZHIFUKA_PAY_CONF');
		
		$info = getAliPayReturnInfo();
		
		if($info)
		{
			echo "<result>1</result>";
		}
		
		$signParams = array();
		
		$signParams['customerid'] = $info['customerid'];
		
		$signParams['sd51no']     = $info['sd51no'];
		
		$signParams['sdcustomno'] = $info['sdcustomno'];
		
		$signParams['mark']       = $info['mark'];
		
		$signParams['key']        = $zhifukaPayConf['paykey'];
		
		$sign = '';
		
		foreach($signParams as $k => $v)
		{
			if(!empty($sign))
			{
				$sign .= '&';
			}
			
			$sign .= $k . '=' . $v;
		}
		
		$sign = strtoupper(md5($sign));
		
		if($info['sign'] != $sign)
		{
			echo "<result>1</result>";
			
			//记录日志
			return 0;
			
			exit();
		}
		
		$reSignParams = array();
		
		$reSignParams['sign']       = $info['sign'];
		
		$reSignParams['customerid'] = $info['customerid'];
		
		$reSignParams['ordermoney'] = $info['ordermoney'];
		
		$reSignParams['sd51no']     = $info['sd51no'];
		
		$reSignParams['state']      = $info['state'];
		
		$reSignParams['key']        = $zhifukaPayConf['paykey'];
		
		$reSign = '';
		
		foreach($reSignParams as $k => $v)
		{
			if(!empty($reSign))
			{
				$reSign .= '&';
			}
			
			$reSign .= $k . '=' . $v;
		}
		
		$reSign = strtoupper(md5($reSign));
		
		if($info['resign'] != $reSign)
		{
			echo "<result>1</result>";
			//记录日志
			return 0;
			
			exit();
		}
		
		if($info['state'] == 1)
		{
			$orderid = $info['sdcustomno'];
			
			$paylog  = M('distribution_pay_paylog')->where(array('payid' => $orderid))->find();
			
			if(is_array($paylog))
			{
				$buyid   = $paylog['buyid'];
				$buyname = $paylog['buyname'];
				$payflag = $paylog['payflag'];
				$egold   = $paylog['egold'];
				$money   = $paylog['money'];
				
				if($payflag == 0)
				{
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

                    $this->addEgoldTOChannelByWeiXin($paylog,$money);
					
					echo "<result>1</result>";
					
					return 1;
				}
				else
				{
					echo "<result>1</result>";
					
					return 1;
				}
			}
			else
			{
				echo "<result>0</result>";
				
				return 0;
			}
		}
		else if($info['state'] == 2)
		{
			//当充值失败后同步商户系统订单状态
			//此处编写商户系统处理订单失败流程
			//............
			//............
			//商户在接受到网关通知时，应该打印出<result>1</result>标签，以供接口程序抓取信息，以便于我们获取是否通知成功的信息，否则订单会显示没有通知商户
			
			echo "<result>1</result>";
			
			//记录订单处理日志
			
			exit();
		}
	}
	
	public function generateWeiXinAppPayInfo($payID,$money,$openID)
	{
		$weixinPay = C('WEIXIN_APP_PAY_CONF');
		
		$urlvars                     = array();
		
		//应用ID
		$urlvars['appid']            = $weixinPay['appid'];
		
		//商户号
		$urlvars['mch_id']           = $weixinPay['mch_id'];
		
		//设备号
		$urlvars['device_info']      = $weixinPay['device_info'];
		
		//商品描述
		$urlvars['body']             = $weixinPay['body'];
		
		//随机数生成
		$urlvars['nonce_str']        = weixinPayGetRandChar(16);
		
		//商品外部交易号，必填,每次测试都须修改
		$urlvars['out_trade_no']     = $urlvars['mch_id'] . date("YmdHis") . $payID;
		
		//标价金额，单位为分
		$urlvars['total_fee']        = $money;
		
		//终端IP，获取服务器的IP
		$urlvars['spbill_create_ip'] = $weixinPay['ip'];
		
		//通知地址
		$urlvars['notify_url']       = $weixinPay['notify_url'];
		
		//交易类型
		$urlvars['trade_type']       = $weixinPay['trade_type'];
		
		$urlvars['openid']           = $openID;
		
		ksort($urlvars);
		
		$sign    = weixinPayToUrlParams($urlvars);
		
		$keyStr  = "&key=" . $weixinPay['paykey']; //支付key
		
		$sign   .= $keyStr;
		
		$sign    = strtoupper(md5($sign));
		
		$urlvars['sign'] = $sign;
		
		$dataXML = weixinPayToXml($urlvars);
		
		$apiUrl  = $weixinPay['unifiedorder_url'];
		
		$startTimeStamp = weixinPayGetMillisecond();//请求开始时间
		
		$response       = weixinPayPostXmlCurl($dataXML, $apiUrl);
		
		$result = weixinPayFromXml($response);//将$response转换为array
		//file_put_contents('23.txt',var_export($result,true));
		
		if($result['return_code'] == 'FAIL')
		{
			$errorMessage = '签名错误';
			
			echo json_encode_ex(array('status' => 0,'message' => $errorMessage));
			
			return;
		}
		else if($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS')
		{
			$signUrlVars = array();
			
			$signUrlVars['appId']     = $result['appid'];//应用ID
			
			$signUrlVars['timeStamp'] = substr($startTimeStamp, 0, 10);//应用ID
			
			$signUrlVars['nonceStr']  = weixinPayGetRandChar(16);;//随机字符串
			
			$signUrlVars['package']   = "prepay_id=" . $result['prepay_id'];//预支付交易会话标识
			
			$signUrlVars['signType']  = 'MD5';
			
			//生成签名
			ksort($signUrlVars);
			
			$resSignStr = weixinPayToUrlParams($signUrlVars);
			
			$keyStr = "&key=" . $weixinPay['paykey']; //支付key
			
			$resSignStr .= $keyStr;
			
			$resSign = strtoupper(md5($resSignStr));
			
			$signUrlVars['paySign']  = $resSign;
			
			$signUrlVars['orderID']  = $urlvars['out_trade_no'];
			
			return $signUrlVars;
		}
	}
	
	/**
     * edit by muyi 2017/05/31
     * 修改不同
	 * 获取用户的openid
	 *
	 * @return mixed
	 */
	public function getWXUserOpenID1($backUrl)
	{
        $urlArr = array();
        $fromid=cookie('fromid');
        $weixinPay = C('WEIXIN_APP_PAY_CONF');
        $urlArr['appid']         = $weixinPay['appid'];
        $pos1 = stripos($_SERVER['REQUEST_URI'],'buyEgold');
        $pos2 = stripos($_SERVER['REQUEST_URI'],'cid'); 
        if($pos1){
            if($pos2){
                $pos1=false;
            }
        }
        if(!empty($fromid)&&$pos1===false){			
            $channelWxModel=M('distribution_channel_wx');
            $wxinfo=$channelWxModel->where(array('channelid'=>cookie('fromid')))->find();//获取渠道微信公众号信息
            if($wxinfo){
                $urlArr['appid']=$wxinfo['appid'];
            }
			
        }
		//var_dump($urlArr);exit;
		$backUrl                 = 'http://wap.kyread.com' . $backUrl;
				
		$urlArr['redirect_uri']  = $backUrl;

		$urlArr['response_type'] = 'code';
		
		$urlArr['scope']         = 'snsapi_base';
		
		$urlArr['state']         = 'STATE';
		
		$str = http_build_query($urlArr);
		
		$weixinAuthorizeURL = "https://open.weixin.qq.com/connect/oauth2/authorize?";//请求CODE
		
		$redirectURL = $weixinAuthorizeURL . $str . '#wechat_redirect';
		
		//var_dump($redirectURL);exit;
		
		redirect($redirectURL);
		
//		echo("<script> top.location.href='" . $redirectURL . "'</script>");
		
		exit;
	}
	
	
	/**
     * edit by muyi 2017/05/31
     * 修改不同
	 * 获取用户的openid
	 *
	 * @return mixed
	 */
	public function getWXUserOpenID($backUrl)
	{

        $urlArr = array();
        $weixinPay = C('WEIXIN_APP_PAY_CONF');
        $urlArr['appid']         = $weixinPay['appid'];
        
		
		if($backUrl == 'index')
		{
			$urlArr['scope'] = 'snsapi_userinfo';
			
			$urlArr['scope'] = 'snsapi_base';
		}
		else
		{
			$urlArr['scope'] = 'snsapi_base';
		}
		
		$backUrl                 = 'http://wap.kyread.com/' . $backUrl . '.html';
		
		
		$saleid=I('saleid');
		if($saleid){
            $backUrl                 = $backUrl.'?saleid='.$saleid;
        }
		
		$urlArr['redirect_uri']  = urldecode($backUrl);
		
		$urlArr['response_type'] = 'code';
		
		$urlArr['scope']         = 'snsapi_base';
		
		$urlArr['state']         = 'STATE';
		
		$str = http_build_query($urlArr);
		
		$weixinAuthorizeURL = "https://open.weixin.qq.com/connect/oauth2/authorize?";//请求CODE
		
		$redirectURL = $weixinAuthorizeURL . $str . '#wechat_redirect';
		//var_dump($redirectURL);exit;
		
		redirect($redirectURL);
		
//		echo("<script> top.location.href='" . $redirectURL . "'</script>");
		
		exit;
	}
	
	/**
	 * 增加用户访问记录
	 */
	public function addUserAccessLog()
	{
		$uid       = cookie('uid');
		
		$channleID = cookie('channel_id');
		
		$todayTime = strtotime(date('Y-m-d'));
		
		$condition['accesstime'] = array('egt',$todayTime);
		
		$condition['uid']        = $uid;
		
		if($channleID)
		{
			$condition['channelid']  = $channleID;
		}
		
		$count = M('distribution_user_access_log')->where($condition)->count();
		
		if(!$count)
		{
			$data['uid']         = $uid;
			
			$data['uname']       = cookie('uname');
			
			$data['channelid']   = cookie('channel_id');
			
			$data['channelname'] = cookie('channel_name');
			
			$data['ip']          = getIP();
			
			$data['accesstime']  = time();
			if(cookie('ptid')>0){
                $data['ptid']          = cookie('ptid');
            }
			
			M('distribution_user_access_log')->add($data);
		}
	}
	
	/**
	 * 获取用户的openid
	 *
	 * @return mixed
	 */
	public function testGetWXUserOpenID($backUrl)
	{
		$weixinPay = C('WEIXIN_APP_PAY_CONF');
		
		$urlArr = array();
		
		$urlArr['appid']         = $weixinPay['appid'];
		
		if($backUrl == 'index')
		{
			$urlArr['scope'] = 'snsapi_userinfo';
			
			$urlArr['scope'] = 'snsapi_base';
		}
		else
		{
			$urlArr['scope'] = 'snsapi_base';
		}
		
		$backUrl                 = 'http://wap.kyread.com/' . $backUrl . '.html';
		
		$urlArr['redirect_uri']  = urldecode($backUrl);
		
		$urlArr['response_type'] = 'code';
		
		$urlArr['scope']         = 'snsapi_base';
		
		$urlArr['state']         = 'STATE';
		
		$str = http_build_query($urlArr);
		
		$weixinAuthorizeURL = "https://open.weixin.qq.com/connect/oauth2/authorize?";//请求CODE
		
		$redirectURL = $weixinAuthorizeURL . $str . '#wechat_redirect';
		
//		echo("<script> top.location.href='" . $redirectURL . "'</script>");
		
		$response = file_get_contents($redirectURL);
		
		file_put_contents('$response.txt',print_r($response,true));
		
		exit;
	}
	
	public function asWeiXinAccess($url)
	{
	
	}
	
	public function testVIP()
    {
        $orderid=4067;
        $paylog      = M('distribution_pay_paylog')->where(array('payid' => $orderid))->find();


        if(is_array($paylog))
        {
            $buyid   = $paylog['buyid'];
            $buyname = $paylog['buyname'];
            $payflag = $paylog['payflag'];
            $egold   = $paylog['egold'];
            $money   = $paylog['money'];

            if($payflag == 0)
            {
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


               $res= M('distribution_pay_paylog')->where(array('payid' => $orderid))->save($paylogData);

               // var_dump($res);exit;
                $this->addEgoldTOChannelByWeiXin($paylog,$money);
            }
        }

}
}
