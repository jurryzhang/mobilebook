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
	 
	$book['price']         = C('SALE_PRICE') . '币/千字';
	
	$book['flag']          = $articleInfo['fullflag'] ? '完结' : '连载';
	
	$book['lastchapter']   = $articleInfo['lastchapter'];
	
	$book['lastchapterid'] = $articleInfo['lastchapterid'];
	
	$book['chaptersum']    = $articleInfo['chapters'];
	
	$sourceInfo            = M('shumeng_channels')->where(array('id' => $articleInfo['thirdsourceid']))->find();
	
	$book['sourcename']    = $sourceInfo['channelname'];
	
	$firstChapter          = M('article_chapter')->where(array('articleid' => $book['articleid'], 'size' => array('neq',0)))->order('`chapterorder` ASC')->select();
	
	$book['firstchapter']  = $firstChapter[0]['chapterid'];
	
	return $book;
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
		
		$content = str_replace("\r\n","<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$content);
		
		$content = str_replace("\n\n","<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$content);
		
		return  $content . "<br><br>";
	}
	else
	{
		$tmp = M('obook_ocontent')->where(array('ochapterid' => $chapterID))->find();
		
		$content           = $tmp['ocontent'];
		
		$content           = str_replace("\r\n","<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$content);
		
		$content           = str_replace("\n\n","<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",$content);
		
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
		return true;
	}
	
	return false;
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
	
	printVar($infoURI,'$infoURI');
	
	$pos = strpos($infoURI,".html?");
	
	if($pos !== false)
	{
		$pos += strlen(".html?");
		
		$infoURI = substr($infoURI,$pos);
		
		$infoList = explode('&', $infoURI);
		
		foreach($infoList as $item)
		{
			$itemList = explode('=', $item);
			
			$str      = str_replace('%25','%',$itemList[1]);
			
			$str      = rawurldecode($str);
			
			$result[$itemList[0]] = trim($str);
		}
		
		return $result;
	}
}