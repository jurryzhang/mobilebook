
<?php

class qq extends \Think\Model\RelationModel
{
	//回调地址
	public $return_url;
	public $app_id;
	public $app_secret;
	public $scope;
	
	static private $authorizeURL   = 'https://graph.qq.com/oauth2.0/authorize';//Step1：获取Authorization Code
	
	static private $accessTokenURL = 'https://graph.qq.com/oauth2.0/token';//Step2：通过Authorization Code获取Access Token
	
	static private $openidURL      = 'https://graph.qq.com/oauth2.0/me';//获取用户OpenID_OAuth2.0
	
	static private $openAPIURL     = 'https://graph.qq.com/user/get_user_info';//QQ登录提供了用户信息/动态同步/日志/相册/微博等OpenAPI
	
	public function __construct($config)
	{
		$this->app_id     = $config['appid'];
		$this->app_secret = $config['appkey'];

		$this->return_url = $config['return_url'];
		
		$this->scope      = $config['scope'];
	}
	
	//构造要请求的参数数组，无需改动
	public function login()
	{
		//拼接URL
		$params = array();
		$params['response_type'] = 'code';
		$params['client_id']     = $this->app_id;
//		$params['redirect_uri']  = urlencode($this->return_url);
		$params['redirect_uri']  = $this->return_url;
		$params['state']         = md5(uniqid(rand(), TRUE));
		$params['display']       = 'mobile';
		$params['scope']         = $this->scope;
		
		$dialog_url = self::$authorizeURL . '?' . http_build_query($params);
		
		echo("<script> top.location.href='" . $dialog_url . "'</script>");
		exit;
	}

	public function respon()
	{
		$pos = strpos($_SERVER['REQUEST_URI'],'?') + 1;

		$requestStr = substr($_SERVER['REQUEST_URI'],$pos);

		$requestList = $this->responseStrToList($requestStr);

		$code = $requestList["code"];
		
		//拼接URL
		$params = array();
		$params['grant_type']    = 'authorization_code';
		$params['client_id']     = $this->app_id;
		$params['client_secret'] = $this->app_secret;
		$params['code']          = $code;
		$params['redirect_uri']  = $this->return_url;
		
		$token_url = self::$accessTokenURL . '?' . http_build_query($params);
		
		//通过Authorization Code获取Access Token
		$response = $this->get_contents($token_url);
		
		$pos = strpos($response,'callback( ');
		
		if($pos !== false)
		{
			$response = substr($response,$pos + strlen('callback( '),strlen($response) - strlen('callback( ') - 3);
			
			$params = json_decode($response,true);
			
			printVar($params,'$params');
			
			if($params['error'])
			{
				echo "<h3>error:</h3>" . $params['error'];
				echo "<h3>msg  :</h3>" . $params['error_description'];
				
				exit;
			}
		}
		else
		{
			$params = $this->responseStrToList($response);
			
			//使用Access Token来获取用户的OpenID
			$graph_url = self::$openidURL ."?access_token=" . $params['access_token'];
			
			$str = $this->get_contents($graph_url);
			
			if (strpos($str, "callback") !== false)
			{
				$lpos = strpos($str, "(");
				$rpos = strrpos($str, ")");
				$str  = substr($str, $lpos + 1, $rpos - $lpos -1);
				
				$user = json_decode($str);
			}
			else
			{
				$user = $this->responseStrToList($str);
			}
			
			if (isset($user->error))
			{
				echo "<h3>error:</h3>" . $user->error;
				echo "<h3>msg  :</h3>" . $user->error_description;
				exit;
			}
			
			//调用OpenAPI接口
			$apiParams = array();
			$apiParams['access_token']       = $params['access_token'];
			$apiParams['oauth_consumer_key'] = $this->app_id;
			$apiParams['openid']             = $user->openid;
			
			$openAPIUrl  = self::$openAPIURL ."?" .http_build_query($apiParams);
			
			$responseStr = $this->get_contents($openAPIUrl);
			
			$userJson    = json_decode($responseStr);
			
			//获取到openid
			return array
			(
				'uname'    => $user->openid,//支付宝用户号
				'oauth'    => 'qqlogin',
				'nickname' => $userJson->nickname,
				'head_pic' => $userJson->figureurl_qq_2
			);
		}
	}

	public function get_contents($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, TRUE);
		
		curl_setopt($ch, CURLOPT_URL, $url);
		$response =  curl_exec($ch);
		curl_close($ch);

		//-------请求为空
		if(empty($response))
		{
			exit("50001");
		}

		return $response;
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
