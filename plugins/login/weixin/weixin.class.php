
<?php
/*
 * 微信扫码登陆插件
 */

class weixin extends \Think\Model\RelationModel
{
	public $appid;
	public $secret;
	public $return_url;
	public $scope;
	
	static 	private $authorizeURL = "https://open.weixin.qq.com/connect/qrconnect";//请求CODE
	
	static 	private $weixinAuthorizeURL = "https://open.weixin.qq.com/connect/oauth2/authorize";//请求CODE
	
	static 	private $accessTokenURL = "https://api.weixin.qq.com/sns/oauth2/access_token";//通过code获取access_token
	
	static 	private $openidURL = "https://api.weixin.qq.com/sns/userinfo";
	
	public function __construct($config)
	{
		$this->appid      = $config['appid'];
		
		$this->secret     = $config['appkey'];
		
		$this->return_url = $config['return_url'];
		
		$this->scope      = $config['scope'];
	}
	
	//构造要请求的参数数组，无需改动
	public function login()
	{
		//拼接URL
		$params = array();
		$params['appid']         = $this->appid;
		$params['redirect_uri']  = $this->return_url;
		$params['response_type'] = 'code';
		$params['scope']         = $this->scope;
		$params['state']         = 'STATE';
		
		$isWeiXin = isWeiXin();
		
		if($isWeiXin)
		{
			$dialog_url = self::$weixinAuthorizeURL . '?' . http_build_query($params) . '#wechat_redirect';
		}
		else
		{
			$dialog_url = self::$authorizeURL . '?' . http_build_query($params) . '#wechat_redirect';
		}
		
		echo("<script> top.location.href='" . $dialog_url . "'</script>");
		exit;
	}

	public function respon()
	{
		$pos         = strpos($_SERVER['REQUEST_URI'],'?') + 1;
		
		$requestStr  = substr($_SERVER['REQUEST_URI'],$pos);
		
		$requestList = $this->responseStrToList($requestStr);
		
		$code        = $requestList['code'];
		
		if($code)
		{
			$params = array();
			$params['appid']      = $this->appid;
			$params['secret']     = $this->secret;
			$params['code']       = $code;
			$params['grant_type'] = 'authorization_code';
			
			$access_token_url = self::$accessTokenURL . '?' . http_build_query($params);
			
			//通过code获取access_token
			$result = $this->get_wx_contents($access_token_url);
			
			$result = json_decode($result,true);
			
			if($result['errcode'])
			{
				echo "<h3>error:</h3>" . $result['errcode'];
				echo "<h3>msg  :</h3>" . $result['errmsg'];
				exit;
			}
			else
			{
				//获取用户个人信息（UnionID机制）
				$apiParams['access_token'] = $result['access_token'];
				$apiParams['openid']       = $result['openid'];
				
				$open_id_url = self::$openidURL . '?' . http_build_query($apiParams);
				
				$userJson = $this->get_wx_contents($open_id_url);
				
				$userInfo = json_decode($userJson,true);
				
				//获取到openid
				return array
				(
					'uname'    => $userInfo['unionid'],//支付宝用户号
					'oauth'    =>'weixinlogin',
					'nickname' => $userInfo['nickname'],
					'head_pic' => $userInfo['headimgurl']
				);
			}
		}
		else
		{
			exit("No code");
		}
	}

	private function get_wx_contents($url)
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
	
	public function responseStrToList($inputStr)
	{
		$backList = explode('&',$inputStr);
		
		foreach($backList as $key => $value)
		{
			$tmpList = explode('=',$value);
			
			$result[$tmpList[0]] = $tmpList[1];
		}
		
		return $result;
	}
}
?>
