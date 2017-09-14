<?php


/**
 * 将数据转化为json
 *
 * @param  $var
 * @return string
 * @throws Exception
 */
function json_encode_ex($var)
{
	if ($var === null)
	{
		return 'null';
	}
	
	if ($var === true)
	{
		return 'true';
	}
	
	if ($var === false)
	{
		return 'false';
	}
	
	static $reps = array
	(
		array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"', ),
		array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"', ),
	);
	
	if (is_scalar($var))
	{
		return '"' . str_replace($reps[0], $reps[1], (string) $var) . '"';
	}
	
	if (!is_array($var))
	{
		throw new Exception('JSON encoder error!');
	}
	
	$isMap = false;
	$i     = 0;
	
	foreach (array_keys($var) as $k)
	{
		if (!is_int($k) || $i++ != $k)
		{
			$isMap = true;
			
			break;
		}
	}
	
	$s = array();
	
	if ($isMap)
	{
		foreach ($var as $k => $v)
		{
			$s[] = '"' . $k . '":' . call_user_func(__FUNCTION__, $v);
		}
		
		return '{' . implode(',', $s) . '}';
	}
	else
	{
		foreach ($var as $v)
		{
			$s[] = call_user_func(__FUNCTION__, $v);
		}
		
		return '[' . implode(',', $s) . ']';
	}
}


/**
 * 过滤微信昵称中的特殊字符和表情
 * $str  微信昵称
 **/
 function filterWx($str) {
    if($str){
        $name = $str;
        $name = preg_replace('/\xEE[\x80-\xBF][\x80-\xBF]|\xEF[\x81-\x83][\x80-\xBF]/', '', $name);
        $name = preg_replace('/xE0[x80-x9F][x80-xBF]‘.‘|xED[xA0-xBF][x80-xBF]/S','?', $name);
        $return = json_decode(preg_replace("#(\\\ud[0-9a-f]{3})#ie","",json_encode($name)));
        if(!$return){
            return '';
        }
    }else{
        $return = '';
    }
    return $return;

}





/**
 * @param $curl
 * @param bool $https
 * @param string $method
 * @param null $data
 * @return mixed
 * 发送请求
 */
function request($curl, $https=true, $method='get', $data=null){
    $ch = curl_init();//初始化
    curl_setopt($ch, CURLOPT_URL, $curl);//设置访问的URL
    curl_setopt($ch, CURLOPT_HEADER, false);//设置不需要头信息
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//只获取页面内容，但不输出
    if($https){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//不做服务器认证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//不做客户端认证
    }
    if($method == 'post'){
        curl_setopt($ch, CURLOPT_POST, true);//设置请求是POST方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置POST请求的数据
    }
    $str = curl_exec($ch);//执行访问，返回结果
    curl_close($ch);//关闭curl，释放资源
    return $str;

}



/**
 *
 * 根据$articleList来获得展示内容
 *
 * @param $articleList
 * @return array
 */
function getBookInfoFromArticleList($articleList)
{
	foreach($articleList as $item)
	{
		$book['articleid']   = $item['articleid'];
		
		$book['articlename'] = $item['articlename'];
		
		$book['cover']       = getBookCoverUrl($item['articleid']);
		
		$book['author']      = $item['author'];
		
		$book['size']        = getBookWordSum($item['size']);
		
//		$book['intro']       = getBookIntro($item['intro']);
		
		$book['intro']       = $item['intro'];
		
		$book['sort']       = getSortName($item['sortid']);
		
		$book['url']         = U('Article/index',array('id' => $book['articleid']));
		
		$reusltBookList[]    = $book;
	}
	
	return $reusltBookList;
}

/**
 * 根据articleInfo来获得展示内容
 *
 * @param $articleInfo
 */
function getBookInfoFromArticleInfo($articleInfo)
{
	$book['articleid']     = $articleInfo['articleid'];
	
	$book['articlename']   = $articleInfo['articlename'];
	
	$book['cover']         = getBookCoverUrl($articleInfo['articleid']);
	
	$book['author']        = $articleInfo['author'];
	
	$book['size']          = getBookWordSum($articleInfo['size']);
	
	$book['intro']         = $articleInfo['intro'];
	 
	$book['price']         = getBookPrice($articleInfo['articleid']) . '币/千字';
	
	$book['flag']          = $articleInfo['fullflag'] ? '完结' : '连载';
	
	$chapter = getLastChapter($articleInfo['articleid']);
	
	$book['lastchapter']   = $chapter['chapter'];
	
	$book['lastchapterid'] = $chapter['chapterid'];
	
	$book['chaptersum']    = M('article_chapter')->where(array('articleid' => $book['articleid']))->count();
	
	$sourceInfo            = M('shumeng_channels')->where(array('id' => $articleInfo['thirdsourceid']))->find();
	
	$book['sourcename']    = $sourceInfo['channelname'];
	
	$firstChapter          = M('article_chapter')->where(array('articleid' => $book['articleid'], 'size' => array('neq',0)))->order('`chapterorder` ASC')->limit(1)->select();
	
	$book['firstchapter']  = $firstChapter[0]['chapterid'];
	
	return $book;
}

//获取书籍售价

function getBookPrice($articleid){
	$bookInfo = M('article_article')->field('peregold')->where(array('articleid'=>$articleid))->find();
	$peregold = !$bookInfo['peregold']?C('SALE_PRICE'):$bookInfo['peregold'];
	return $peregold;
}

function getLastChapter($articleID)
{
	$chapterList = M('article_chapter')->where(array('articleid' => $articleID))->order('`chapterorder` desc')->limit(1)->select();
	
	if($chapterList)
	{
		$chapter['chapterid'] = $chapterList[0]['chapterid'];
		
		$chapter['chapter'] = $chapterList[0]['chaptername'];
	}
	
	return $chapter;
}


function getThirdPartyLoginType($loginID)
{
	switch ($loginID)
	{
		case 0:
		{
			$loginType['login_type'] = 'weixinlogin';
			
			$loginType['login_msg']  = '微信';
			
			break;
		}
		case 1:
		{
			$loginType['login_type'] = 'qqlogin';
			
			$loginType['login_msg']  = 'qq';
			
			break;
		}
		case 2:
		{
			$loginType['login_type'] = 'weibologin';
			
			$loginType['login_msg']  = '微博';
			
			break;
		}
		case 3:
		{
			$loginType['login_type'] = 'visitorlogin';
			
			$loginType['login_msg']  = '游客登录';
			
			break;
		}
	}
	
	return $loginType;
}

//生成随机字符串

/*
 * 生成随机字符串
 *
 * burn添加，2016-12-22
 *
 */
function getRandChar($length)
{
	$str    = null;
	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	$max    = strlen($strPol) - 1;
	
	for($i = 0;$i < $length;$i++)
	{
		$str .= $strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	}
	
	return $str;
}

/**
 * 过滤字符串
 *
 * @param $inputStr
 * @return mixed
 */
function filterStr($inputStr)
{
	$inputStr = trim($inputStr);
	
	$result   = str_replace("\n",'',$inputStr);
	
	$result   = str_replace("\r",'',$result);
	
	$result   = str_replace("&nbsp;",' ',$result);
	
	$result   = str_replace("<br />",'',$result);
	
	$result   = str_replace("&quot;",'""',$result);
	
	return $result;
}

/**
 * 过滤字符串
 *
 * @param $inputStr
 * @return mixed
 */
/**
 * 根据$articleID和$chapterID来获得书籍内容
 *
 * @param $articleID
 * @param $chapterID
 * @return mixed
 */
function getChapterContent($articleID,$chapterID)
{
	$filePath = C('TXT_FILE_DIR') . '/' . floor($articleID / 1000) . '/' . $articleID. '/' . $chapterID . ".txt";
	
	$flag = file_exists($filePath);
	
	if($flag)
	{
		$content = file_get_contents($filePath,true);
		
		$content = mb_convert_encoding($content,'UTF-8',"GBK");
		
		 $content = str_replace("\r\n\r\n","</p><p>　 ",$content);
		 $content = str_replace("\n\n\n\n","</p><p>　 ",$content);

        $content = str_replace("\r\n","</p><p>　 ",$content);

        $content = str_replace("\n\n","</p><p>　 ",$content);
        $content = str_replace("，",",",$content);
		
		return  $content . "<br><br>";
	}
	else
	{
		$tmp = M('obook_ocontent')->where(array('ochapterid' => $chapterID))->find();
		
		$content           = $tmp['ocontent'];
		
		 $content = str_replace("\r\n\r\n","</p><p>　 ",$content);
		 $content = str_replace("\n\n\n\n","</p><p>　 ",$content);

        $content = str_replace("\r\n","</p><p>　 ",$content);

        $content = str_replace("\n\n","</p><p>　 ",$content);
        $content = str_replace("，",",",$content);
		
		$result['content'] = $content;
		
		$size              = strlen($result['content']);
		
		$result['size']    = $size;
	}
	
	return  $content . "<br><br>";
}

/**
 * 将$inputVar内容转化为UTF-8
 *
 * @param $inputVar
 * @return mixed
 */
function dealVarTOUtf8(&$inputVar,$encoding)
{
	if(!$encoding)
	{
		$encoding = 'UTF-8';
	}
	
	if($encoding != 'UTF-8')
	{
		foreach($inputVar as $key =>  $value)
		{
			$inputVar[$key] = mb_convert_encoding($value,'UTF-8',$encoding);
		}
	}
	
	return $inputVar;
}

/**
 * 检测是否是微信打开，是的话，返回true；不是的话，返回false
 * @return bool
 */
function isWeiXin()
{
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false )
	{
		//edit by muyi 2017/05/19
		//如果返回true,客户端js不识别
		return 1;
	}
	
	return 0;
}

/**
 * 输出xml字符
 *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 **/
function toXml($inputArray)
{
	if(!is_array($inputArray) || count($inputArray) <= 0)
	{
		U('Mobile/Index/error',"数据组异常");
	}
	
	$xml = "<xml>";
	
	foreach ($inputArray as $key => $val)
	{
		$xmLStr = "<" . $key . ">" . $val . "</" . $key . ">";
		
		$xml .= $xmLStr;
	}
	
	$xml .= "</xml>";
	
	return $xml;
}

/**
 * 获取毫秒级别的时间戳 *
 *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 */
function getMillisecond()
{
	//获取毫秒的时间戳
	$time  = explode ( " ", microtime () );
	$time  = $time[1] . ($time[0] * 1000);
	$time2 = explode( ".", $time );
	$time  = $time2[0];
	
	return $time;
}

/**
 * 以post方式提交xml到对应的接口url
 *
 * @param string $xml  需要post的xml数据
 * @param string $url  url
 * @param int $second  url执行超时时间，默认30s
 * @throws WxPayException
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 */
function postXmlCurl($xml, $url, $second = 30)
{
	$ch = curl_init();
	
	//设置超时
	curl_setopt($ch,CURLOPT_TIMEOUT, $second);
	
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
	//设置header
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	
	//要求结果为字符串且输出到屏幕上
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	//post提交方式
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	
	//运行curl
	$data = curl_exec($ch);
	
	//返回结果
	if($data)
	{
		curl_close($ch);
		
		return $data;
	}
	else
	{
		$error = curl_errno($ch);
		curl_close($ch);
		
		U('Mobile/Index/error',"curl出错，错误码:$error");
	}
}

/**
 *
 * 上报数据， 上报的时候将屏蔽所有异常流程
 * @param string $usrl
 * @param int $startTimeStamp
 * @param array $data *
 *
 * burn添加，2016-12-22
 *
 * 微信支付
 */
function reportCostTime($url, $startTimeStamp, $data)
{
	//上报逻辑
	$endTimeStamp = getMillisecond();
	
	vendor('WxpayAPI.lib.WxPayData');
	
	$objInput = new WxPayReport();
	
	$objInput->SetInterface_url($url);
	$objInput->SetExecute_time_($endTimeStamp - $startTimeStamp);
	
	//返回状态码
	if(array_key_exists("return_code", $data))
	{
		$objInput->SetReturn_code($data["return_code"]);
	}
	
	//返回信息
	if(array_key_exists("return_msg", $data))
	{
		$objInput->SetReturn_msg($data["return_msg"]);
	}
	
	//业务结果
	if(array_key_exists("result_code", $data))
	{
		$objInput->SetResult_code($data["result_code"]);
	}
	
	//错误代码
	if(array_key_exists("err_code", $data))
	{
		$objInput->SetErr_code($data["err_code"]);
	}
	
	//错误代码描述
	if(array_key_exists("err_code_des", $data))
	{
		$objInput->SetErr_code_des($data["err_code_des"]);
	}
	//商户订单号
	if(array_key_exists("out_trade_no", $data))
	{
		$objInput->SetOut_trade_no($data["out_trade_no"]);
	}
	
	//设备号
	if(array_key_exists("device_info", $data))
	{
		$objInput->SetDevice_info($data["device_info"]);
	}
	
	try
	{
		report($objInput);
	}
	catch (WxPayException $e)
	{
		//不做任何处理
	}
}

/**
 * 将xml转为array
 * @param string $xml
 * @throws WxPayException
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 */
function fromXml($xml)
{
	if(!$xml)
	{
		U('Mobile/Index/error',"xml数据异常！");
	}
	
	//将XML转为array
	//禁止引用外部xml实体
	libxml_disable_entity_loader(true);
	
	return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
}

/**
 *
 * 检测签名
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 */
function checkSign($inputArray,$key)
{
	//fix异常
	if(!array_key_exists('sign', $inputArray))
	{
		U('Mobile/Index/error',"签名信息错误");
	}
	
	//签名步骤一：按字典序排序参数
	ksort($inputArray);
	
	//生成签名
	$sign = toUrlParams($inputArray);
	
	$keyStr = "&key=" . $key; //key
	
	$sign .= $keyStr;
	
	$sign  = strtoupper(md5($sign));
	
	if($inputArray['sign'] == $sign)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * 格式化参数格式化成url参数
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 */
function toUrlParams($inputAarry)
{
	$buff = "";
	
	foreach ($inputAarry as $k => $v)
	{
		if($k != "sign" && $v != "" && !is_array($v))
		{
			$buff .= $k . "=" . $v . "&";
		}
	}
	
	$buff = trim($buff, "&");
	
	return $buff;
}

/**
 *
 * 测速上报，该方法内部封装在report中，使用时请注意异常流程
 * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
 * appid、mchid、spbill_create_ip、nonce_str不需要填入
 * @param WxPayReport $inputObj
 * @param int $timeOut
 * @throws WxPayException
 * @return 成功时返回，其他抛异常
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 */
function report($inputObj, $timeOut = 1)
{
	$url = "https://api.mch.weixin.qq.com/payitil/report";
	
	//检测必填参数
	if(!$inputObj->IsInterface_urlSet())
	{
		throw new WxPayException("接口URL，缺少必填参数interface_url！");
	}
	
	if(!$inputObj->IsReturn_codeSet())
	{
		throw new WxPayException("返回状态码，缺少必填参数return_code！");
	}
	
	if(!$inputObj->IsResult_codeSet())
	{
		throw new WxPayException("业务结果，缺少必填参数result_code！");
	}
	
	if(!$inputObj->IsUser_ipSet())
	{
		throw new WxPayException("访问接口IP，缺少必填参数user_ip！");
	}
	
	if(!$inputObj->IsExecute_time_Set())
	{
		throw new WxPayException("接口耗时，缺少必填参数execute_time_！");
	}
	
	$inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
	$inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
	$inputObj->SetUser_ip($_SERVER['REMOTE_ADDR']);//终端ip
	$inputObj->SetTime(date("YmdHis"));//商户上报时间
	$inputObj->SetNonce_str(self::getNonceStr());//随机字符串
	
	$inputObj->SetSign();//签名
	$xml = $inputObj->ToXml();
	
	$startTimeStamp = getMillisecond();//请求开始时间
	$response = postXmlCurl($xml, $url, $timeOut);
	return $response;
}

function getAliPayReturnInfo()
{
	$infoURI = $_SERVER['REQUEST_URI'];
	
	$pos = strpos($infoURI,".html?");
	
	if($pos !== false)
	{
		$pos     += strlen(".html?");
		
		$infoURI  = substr($infoURI,$pos);
		
		$infoURI  = urldecode($infoURI);
		
		$infoList = explode('&', $infoURI);
		
		foreach($infoList as $item)
		{
			$itemList = explode('=', $item);
			
			$result[$itemList[0]] = trim($itemList[1]);
		}
		
		return $result;
	}
}


/*
 * 生成随机字符串
 *
 * burn添加，2016-12-22
 *
 * 微信支付
 */
function weixinPayGetRandChar($length)
{
	$str    = null;
	$strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
	$max    = strlen($strPol) - 1;
	
	for($i = 0;$i < $length;$i++)
	{
		$str .= $strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
	}
	
	return $str;
}

/**
 * 输出xml字符
 *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 **/
function weixinPayToXml($inputArray)
{
	if(!is_array($inputArray) || count($inputArray) <= 0)
	{
		$errorMessage = "数据组异常";
	}
	
	$xml = "<xml>";
	
	foreach ($inputArray as $key => $val)
	{
		$xmLStr = "<" . $key . ">" . $val . "</" . $key . ">";
		
		$xml .= $xmLStr;
	}
	
	$xml .= "</xml>";
	
	return $xml;
}

/**
 * 获取毫秒级别的时间戳 *
 *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 */
function weixinPayGetMillisecond()
{
	//获取毫秒的时间戳
	$time  = explode ( " ", microtime () );
	$time  = $time[1] . ($time[0] * 1000);
	$time2 = explode( ".", $time );
	$time  = $time2[0];
	
	return $time;
}

/**
 * 以post方式提交xml到对应的接口url
 *
 * @param string $xml  需要post的xml数据
 * @param string $url  url
 * @param int $second  url执行超时时间，默认30s
 * @throws WxPayException
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 */
function weixinPayPostXmlCurl($xml, $url, $second = 30)
{
	$ch = curl_init();
	
	//设置超时
	curl_setopt($ch,CURLOPT_TIMEOUT, $second);
	
	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
	//设置header
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	
	//要求结果为字符串且输出到屏幕上
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	
	//post提交方式
	curl_setopt($ch, CURLOPT_POST, TRUE);
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	
	//运行curl
	$data = curl_exec($ch);
	
	//返回结果
	if($data)
	{
		curl_close($ch);
		
		return $data;
	}
	else
	{
		$error = curl_errno($ch);
		curl_close($ch);
		
		$errorMessage = "curl出错，错误码:$error";
		
		return $errorMessage;
	}
}

/**
 *
 * 上报数据， 上报的时候将屏蔽所有异常流程
 * @param string $usrl
 * @param int $startTimeStamp
 * @param array $data *
 *
 * burn添加，2016-12-22
 *
 * 微信支付
 */
function weixinPayReportCostTime($url, $startTimeStamp, $data)
{
	//上报逻辑
	$endTimeStamp = weixinPayGetMillisecond();
	
	$path = "plugins/payment/WxpayAPI/lib/WxPay.Data.php";
	
	include_once $path;
	
	$objInput = new WxPayReport();
	$objInput->SetInterface_url($url);
	$objInput->SetExecute_time_($endTimeStamp - $startTimeStamp);
	
	//返回状态码
	if(array_key_exists("return_code", $data))
	{
		$objInput->SetReturn_code($data["return_code"]);
	}
	
	//返回信息
	if(array_key_exists("return_msg", $data))
	{
		$objInput->SetReturn_msg($data["return_msg"]);
	}
	
	//业务结果
	if(array_key_exists("result_code", $data))
	{
		$objInput->SetResult_code($data["result_code"]);
	}
	
	//错误代码
	if(array_key_exists("err_code", $data))
	{
		$objInput->SetErr_code($data["err_code"]);
	}
	
	//错误代码描述
	if(array_key_exists("err_code_des", $data))
	{
		$objInput->SetErr_code_des($data["err_code_des"]);
	}
	//商户订单号
	if(array_key_exists("out_trade_no", $data))
	{
		$objInput->SetOut_trade_no($data["out_trade_no"]);
	}
	
	//设备号
	if(array_key_exists("device_info", $data))
	{
		$objInput->SetDevice_info($data["device_info"]);
	}
	
	try
	{
		report($objInput);
	}
	catch (WxPayException $e)
	{
		//不做任何处理
	}
}

/**
 * 将xml转为array
 * @param string $xml
 * @throws WxPayException
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 */
function weixinPayFromXml($xml)
{
	if(!$xml)
	{
		$errorMessage = "xml数据异常！";
	}
	
	//将XML转为array
	//禁止引用外部xml实体
	libxml_disable_entity_loader(true);
	
	//	return json_decode(json_encode_ex(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	
	$xmlObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
	
	return xmlToArray($xmlObj);
}

/**
 *
 * 检测签名
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 */
function weixinPayCheckSign($inputArray,$key)
{
	//fix异常
	if(!array_key_exists('sign', $inputArray))
	{
		$errorMessage = "签名信息错误";
	}
	
	//签名步骤一：按字典序排序参数
	ksort($inputArray);
	
	//生成签名
	$sign = toUrlParams($inputArray);
	
	$keyStr = "&key=" . $key; //key
	
	$sign .= $keyStr;
	
	$sign  = strtoupper(md5($sign));
	
	if($inputArray['sign'] == $sign)
	{
		return true;
	}
	
	$errorMessage = "签名错误！";
}

/**
 * 格式化参数格式化成url参数
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 *
 */
function weixinPayToUrlParams($inputAarry)
{
	$buff = "";
	
	foreach ($inputAarry as $k => $v)
	{
		if($k != "sign" && $v != "" && !is_array($v))
		{
			$buff .= $k . "=" . $v . "&";
		}
	}
	
	$buff = trim($buff, "&");
	
	return $buff;
}

/**
 *
 * 测速上报，该方法内部封装在report中，使用时请注意异常流程
 * WxPayReport中interface_url、return_code、result_code、user_ip、execute_time_必填
 * appid、mchid、spbill_create_ip、nonce_str不需要填入
 * @param WxPayReport $inputObj
 * @param int $timeOut
 * @throws WxPayException
 * @return 成功时返回，其他抛异常
 *
 *  *
 * burn添加，2016-12-22
 *
 * 微信支付
 */
function weixinPayReport($inputObj, $timeOut = 1)
{
	$url = "https://api.mch.weixin.qq.com/payitil/report";
	
	//检测必填参数
	if(!$inputObj->IsInterface_urlSet())
	{
		throw new WxPayException("接口URL，缺少必填参数interface_url！");
	}
	
	if(!$inputObj->IsReturn_codeSet())
	{
		throw new WxPayException("返回状态码，缺少必填参数return_code！");
	}
	
	if(!$inputObj->IsResult_codeSet())
	{
		throw new WxPayException("业务结果，缺少必填参数result_code！");
	}
	
	if(!$inputObj->IsUser_ipSet())
	{
		throw new WxPayException("访问接口IP，缺少必填参数user_ip！");
	}
	
	if(!$inputObj->IsExecute_time_Set())
	{
		throw new WxPayException("接口耗时，缺少必填参数execute_time_！");
	}
	
	$inputObj->SetAppid(WxPayConfig::APPID);//公众账号ID
	$inputObj->SetMch_id(WxPayConfig::MCHID);//商户号
	$inputObj->SetUser_ip($_SERVER['REMOTE_ADDR']);//终端ip
	$inputObj->SetTime(date("YmdHis"));//商户上报时间
	$inputObj->SetNonce_str(self::getNonceStr());//随机字符串
	
	$inputObj->SetSign();//签名
	$xml = $inputObj->ToXml();
	
	$startTimeStamp = weixinPayGetMillisecond();//请求开始时间
	$response = weixinPayPostXmlCurl($xml, $url, false, $timeOut);
	return $response;
}

/**
 * object(SimpleXMLElement) 对象转换为数组
 * convert simplexml object to array sets
 * $array_tags 表示需要转为数组的 xml 标签。例：array('item', '')
 * 出错返回False
 *
 * @param object $simplexml_obj
 * @param array $array_tags
 * @param int $strip_white 是否清除左右空格
 * @return mixed
 */
function xmlToArray($simpleXmlElement)
{
	$simpleXmlElement=(array)$simpleXmlElement;
	
	foreach($simpleXmlElement as $k => $v)
	{
		if($v instanceof SimpleXMLElement ||is_array($v))
		{
			$simpleXmlElement[$k] = xmlToArray($v);
		}
	}
	return $simpleXmlElement;
}

function getResponseFromUrl($url)
{
	$ch = curl_init();
	$timeout = 30;
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch);
	curl_close($ch);
	return $file_contents;
}

function getResonseInfoFromRequesURI()
{
	$pos         = strpos($_SERVER['REQUEST_URI'],'?') + 1;
	
	$requestStr  = substr($_SERVER['REQUEST_URI'],$pos);
	
	$requestList = responseStrToList($requestStr);
	
	return $requestList;
}

function responseStrToList($inputStr)
{
	$backList = explode('&', $inputStr);
	
	foreach($backList as $key => $value)
	{
		$tmpList = explode('=', $value);
		
		$result[$tmpList[0]] = $tmpList[1];
	}
	
	return $result;
}