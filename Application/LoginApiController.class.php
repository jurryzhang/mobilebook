<?php
/**
 * 趣读商城
 * ============================================================================
 * * 版权所有 2015-2027 河南趣读信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.qudukeji.com
 * ----------------------------------------------------------------------------
 * ============================================================================
 *
 */

namespace Mobile\Controller;
use Mobile\Logic\UsersLogic;

class LoginApiController extends MobileBaseController
{
    public $config;
    public $oauth;
    public $class_obj;

    public function __construct()
    {
        parent::__construct();
        
        $this->oauth = I('get.oauth');

        $conf = $this->getThirdLoginConf();

        $this->config = $conf; // 配置反序列化
	
        if(!$this->oauth)
        {
        	$this->error('非法操作',U('Mobile/User/login'));
        }
        
        include_once  "plugins/login/{$this->oauth}/{$this->oauth}.class.php";
        
        $class = '\\'.$this->oauth; //
        $login = new $class($this->config); //实例化对应的登陆插件
        $this->class_obj = $login;
    }

    public function login()
    {
	    $_SESSION['authorized_login'] = 1;
    	
        if(!$this->oauth)
        {
        	$this->error('非法操作',U('Mobile/User/login'));
        }

        $path = "plugins/login/{$this->oauth}/{$this->oauth}.class.php";
        
        include_once $path;

        $this->class_obj->login();
    }

    public function callback()
    {
        $data  = $this->class_obj->respon();
        
        $logic = new UsersLogic();
        $data  = $logic->thirdLogin($data);
        
        if($data['status'] != 1)
        {
        	$this->error($data['msg']);
        }
	
	    cookie('uid',$data['result']['uid']);
	
	    cookie('uname',$data['result']['uname']);
	
        $this->success('登陆成功',U('User/index'));
    }
	
	/**
	 * 获取第三方配置
	 */
    private function getThirdLoginConf()
    {
	    $thirdLoginType = I('get.oauth');
	    
	    switch ($thirdLoginType)
	    {
		    case 'weixin':
		    {
			    $conf = C('WEIXIN_LOGIN_CONF');
		    	
		    	break;
		    }
		    case 'qq':
		    {
			    $conf = C('QQ_LOGIN_CONF');
		    	
		        break;
		    }
		    case 'weibo':
		    {
			    $conf = C('WEIBO_LOGIN_CONF');
			
			    break;
		    }
	    }
	    
	    return $conf;
    }
}