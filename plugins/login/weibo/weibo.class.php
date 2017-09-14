
<?php
/*
 * 微博登陆插件
 */

class weibo extends \Think\Model\RelationModel
{
	public $appid;
	public $secret;
	public $returnUrl;
	public $scope;
	
	static private $authorizeURL   = 'https://api.weibo.com/oauth2/authorize';//OAuth2的authorize接口
	
	static private $accessTokenURL = 'https://api.weibo.com/oauth2/access_token';
	
	static private $userInfoURL    = 'https://api.weibo.com/2/users/show.json';
	
	public function __construct($config)
	{
		$this->appid      = $config['appid'];
		
		$this->secret     = $config['appkey'];
		
		$this->returnUrl  = $config['return_url'];
		
		$this->scope      = $config['scope'];
	}
	
	//构造要请求的参数数组，无需改动
	public function login()
	{
		$params = array();
		$params['client_id']     = $this->appid;
		$params['redirect_uri']  = $this->returnUrl;
		$params['response_type'] = 'code';
		$params['state']         = md5(uniqid(rand(), TRUE));
		$params['display']       = 'mobile';
		
		$url = self::$authorizeURL . '?' . http_build_query($params);
		
		echo("<script> top.location.href='" . $url . "'</script>");
		
		exit;
	}

	public function respon()
	{
		$pos         = strpos($_SERVER['REQUEST_URI'],'?') + 1;
		
		$requestStr  = substr($_SERVER['REQUEST_URI'],$pos);
		
		$requestList = $this->responseStrToList($requestStr);
		
		$code        = $requestList['code'];
		
		$params = array();
		$params['client_id']     = $this->appid;
		$params['client_secret'] = $this->secret;
		$params['grant_type']    = 'authorization_code';
		$params['code']          = $code;
		$params['redirect_uri']  = $this->returnUrl;
		
		if($code)
		{
			$this->code = $code;
			
			$tokenUrl   = self::$accessTokenURL . '?' .http_build_query($params);
			
			$result     = $this->get_weibo_contents($tokenUrl,1);
			
			$result     = json_decode($result,true);
			
			$userInfoParams['access_token'] = $result['access_token'];
			$userInfoParams['uid']          = $result['uid'];
			
			$userInfoUrl = self::$userInfoURL . '?' .http_build_query($userInfoParams);
			
			$userInfo    = $this->get_weibo_contents($userInfoUrl,'');
			
			$userInfo    = json_decode($userInfo,true);
			
			return array
			(
				'uname'    => $userInfo['id'],//微博唯一ID
				'oauth'    => 'weibologin',
				'nickname' => $userInfo['screen_name'],
				'head_pic' => $userInfo['avatar_hd']
			);
		}
		else
		{
			exit("No code");
		}
	}

	private function get_weibo_contents($url,$data)
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		if($data)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
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
