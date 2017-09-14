<?php
/**
 * Created by PhpStorm.
 * User: burn
 * Date: 2017/3/14
 * Time: 下午4:11\
 *
 * 免费读书小程序接口
 * ============================================================================
 * * 版权所有 2015-2027 河南趣读信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.qudukeji.com
 * ----------------------------------------------------------------------------
 * ============================================================================
 *
 *
 */

namespace Mobile\Controller;
use Think\AjaxPage;
use Think\Page;

class AppletController extends MobileBaseController
{
	/**
	 * 小程序接口，获取首页
	 */
	public function getHomeArticle()
	{
		$tmpBookList = M('app_slidebanner')->where(0)->select();
		
		$coverList   = explode('|',$tmpBookList[0]['booksCover']);
		
		$bookList    = explode('|',$tmpBookList[0]['booksID']);
		
		//顶部banner轮播书
		foreach($bookList as $key => $item)
		{
			$flag = articleExist($item);
			
			if($flag)
			{
				$tmpBook['bookid']    = $item;
				
				$tmpBook['bookcover'] = $coverList[$key];
				
				$bannerBookList[]     = $tmpBook;
			}
		}
		
		$result['slidebanner'] = $bannerBookList;
		
		$bookList = $this->getHotCommendBook(0);
		
		$result['handpick'] = $bookList;
		
		if($result)
		{
			echo json_encode_ex(array('status' => '1' , 'message' => '获取信息成功','result' => $result),JSON_UNESCAPED_UNICODE);
			
			return;
		}
		else
		{
			echo json_encode_ex(array('status' => '0' , 'message' => '获取信息失败'),JSON_UNESCAPED_UNICODE);
			
			return;
		}
	}
	
	
	/**
	 * 第三方登录接口
	 *
	 * 请求字段：openid         ： openid
	 *         face_img       ： 头像地址
	 *         login_type     ： 登录方式，0：weixinlogin；1：qqlogin；2： weibologin；3：visitorlogin
	 *         user_nickname  ： 用户昵称
	 *         sex            ： 用户性别
	 */
	public function thirdPartyLogin()
	{
		$openID = $_REQUEST['openid'];
		
		if(!$openID)
		{
			echo json_encode_ex(array('status' => 0,'message' => '获取第三方用户信息失败！'));
			
			return;
		}
		
		$faceImg = trim($_REQUEST['face_img']);
		
		if($faceImg)
		{
			$faceImg = iconv("UTF-8","GBK//IGNORE",$faceImg);
		}
		else
		{
			$faceImg = DEFAULT_FACE_IMG;
		}
		
		if(isset($_REQUEST['login_type']))
		{
			$loginType = getThirdPartyLoginType(trim($_REQUEST['login_type']));
		}
		else
		{
			echo json_encode_ex(array('status' => 0,'message' => '获取登录方式失败！'));
			
			return;
		}
		
		if(isset($_REQUEST['user_nickname']))
		{
			$userNickname = trim($_REQUEST['user_nickname']);
			
			$userNickname = iconv("UTF-8","GBK//IGNORE",$userNickname);
		}
		else
		{
			if(trim($_REQUEST['login_type']) != 3)
			{
				$userNickname = DEFAULT_NICKNAME . getRandChar(10);
			}
			else
			{
				$userNickname = DEFAULT_VISITOR_NICKNAME . getRandChar(12);
			}
		}
		
		if(isset($_REQUEST['sex']))
		{
			$sex = trim($_REQUEST['sex']);
		}
		else
		{
			$sex = '0';
		}
		
		$count = M('system_users')->where(array('uname' => $openID))->count();
		
		if($count)//存在该用户
		{
			$userResult = M('system_users')->where(array('uname' => $openID))->find();
			
			$userInfo['uid']        = $userResult['uid'] ;//用户名id
			
			$userInfo['uname']      = iconv("GBK","UTF-8",$userResult['uname']);//用户名
			
			$userInfo['name']       = iconv("GBK","UTF-8",$userResult['name']);//用户昵称
			
			$userInfo['sign']       = iconv("GBK","UTF-8",$userResult['sign']);//签名
			
			$userInfo['phone']      = $userResult['mobile'];//邮箱
			
			$userInfo['email']      = $userResult['email'];//邮箱
			
			$userInfo['score']      = $userResult['score'];//积分
			
			$userInfo['egold']      = $userResult['egold'];//金币
			
			$userInfo['isvip']      = $userResult['isvip'];//是否是vip
			
			$userInfo['experience'] = $userResult['experience'];//经验值
			
			$userInfo['sex']        = $userResult['sex'];//性别
			
			$userInfo['faceImg']    = $userResult['faceImg'] ? $userResult['faceImg'] : DEFAULT_FACE_IMG;//头像
			
			$userInfo['logintype']  = $userResult['logintype'];//登录方式
			
			if(isset($userResult['uid']) && $userResult['uid'] != 0)
			{
				$setting['lastip'] = getIP();
				
				$setting['logindate'] = date('Y-m-d', time());
				
				$setting = serialize($setting);
				
				$setting = filterStr($setting);
				
				$data['lastlogin'] = time();
				
				$data['setting'] = $setting;
				
				$result = M('system_users')->where(array('uname' => $openID))->save($data);
				
				if($result)
				{
					$status = 1;
					
					setcookie("user_id", $userResult['uid'], time() + 315360000);
					
					setcookie("user_name", $userResult['uname'], time() + 315360000);
					
					setcookie("user_email", $userResult['email'], time() + 315360000);
					
					setcookie("user_phone", $userInfo['phone'], time() + 315360000);
				}
				else
				{
					$errorMessage = '登录失败，请重新登录！';
					
					$status = 0;
				}
			}
		}
		else//新用户
		{
			$data['uname']        = $openID;
			
			$data['name']         = $userNickname;
			
			$data['pass']         = DEFAULT_PASSWORD;
			
			$data['groupid']      = '3';
			
			$data['regdate']      = time();//注册时间
			
			$setting['regip']     = getIP();
			
			$setting['logindate'] = date('Y-m-d',time());
			
			$setting['lastip']    = getIP();
			
			$setting              = serialize($setting);
			
			$setting              = filterStr($setting);
			
			$data['setting']      = $setting;
			
			$data['faceImg']      = $faceImg;
			
			$data['sex']          = $sex;
			
			$data['logintype']    = $loginType['login_type'];
			
			$data['lastlogin']    = $data['regdate'];
			
			$uid = M('system_users')->add($data);
			
			if($uid)
			{
				$userInfo['uid']        = $uid;//用户名id
				
				$userInfo['uname']      = $openID;//用户名
				
				$userInfo['name']       = iconv("GBK","UTF-8",$userNickname);//用户昵称
				
				$userInfo['sign']       = '';//签名
				
				$userInfo['phone']      = '';//手机
				
				$userInfo['email']      = '';//邮箱
				
				$userInfo['score']      = '0';//积分
				
				$userInfo['egold']      = '0';//金币
				
				$userInfo['isvip']      = '0';//是否是vip
				
				$userInfo['experience'] = '0';//经验值
				
				$userInfo['sex']        = '0';//性别
				
				$userInfo['faceImg']    = $faceImg;//性别
				
				$userInfo['logintype']  = $loginType['login_type'];//登录方式
				
				setcookie("user_id",$uid,time() + 315360000);
				
				setcookie("user_name",$userInfo['uname'],time() + 315360000);
				
				setcookie("user_phone",'',time() + 315360000);
				
				$status = 1;
			}
			else
			{
				$errorMessage = '登录失败，请重新登录！';
				
				$status       = 0;
			}
		}
		
		if($errorMessage)
		{
			echo json_encode_ex(array('status' => $status,'message' => $errorMessage));
		}
		else
		{
			echo json_encode_ex(array('status' => $status,'message' => '已通过' . $loginType['login_msg'] . '授权，登录成功！','result' => $userInfo));
		}
		
		return;
	}
	
	/*
	 * 获取相应的热推小说列表
	 */
	public function getHotCommendBookList()
	{
		$orderStr = '`showID` ASC';
		
		$showID   = I('id');
		
		$tmpBookList = M('app_hotcommend')->where(array('id' => $showID))->find();
		
		foreach($tmpBookList as $key => $value)
		{
			$tmpHotBook['title'] = $value['title'];
			
			$tmpHotBook['id']    = $value['id'];
			
			$tmpHotCommendBookList = explode('|',$value['booksID']);
			
			$tmpHotCommendBookList = array_slice($tmpHotCommendBookList,0,4);
			
			$tmpHotBookList        = getBookInfoFromBookList($tmpHotCommendBookList);
			
			$tmpHotBook['booklist'] = $tmpHotBookList;
			
			unset($tmpHotBookList);
			
			$hotBookList[] = $tmpHotBook;
		}
		
		if($hotBookList)
		{
			echo json_encode_ex(array('status' => 1,'message' => '获取列表成功' , 'result' => $hotBookList));
		}
		else
		{
			echo json_encode_ex(array('status' => 0,'message' => '获取列表失败'));
		}
		
		return;
	}
}