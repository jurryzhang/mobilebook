<?php

return array(
    'LOAD_EXT_CONFIG'  => 'html,define,aLiDaYuConf',	// 加载其他自定义配置文件 html.php
//	'URL_HTML_SUFFIX'  => 'html',
//	'HTML_CACHE_ON'    => true, // 开启静态缓存
//	'HTML_CACHE_TIME'  => 60,   // 全局静态缓存有效期（秒）
//	'HTML_FILE_SUFFIX' => '.html', //设置静态缓存文件后缀
	'HTML_CACHE_RULES' => array
							(	//定义静态缓存规则
								// 定义格式1 数组方式
								//'静态地址' =>  array('静态规则', '有效期', '附加规则'),
								'index:index'=>array('{:module}_{:controller}_{:action}',TPSHOP_CACHE_TIME),  // 首页静态缓存 3秒钟
								//'index:goodsList'=>array('{:module}_{:controller}_{:action}',300),  // 列表页静态缓存 3秒钟 无参数 post 提交的很难缓存
								'index:goodsList'=>array('{:module}_{:controller}_{:action}_{id}',TPSHOP_CACHE_TIME),  // 列表页静态缓存 3秒钟
								//ajax 请求的商品列表内容在 ajaxGoodsList 函数中  S($keys,$html,300); 缓存
// 								'Goods:goodsInfo'=>array('{:module}_{:controller}_{:action}_{id}',TPSHOP_CACHE_TIME),  // 商品详情页静态缓存 3秒钟
								'Goods:goodsInfo'=>array('{:module}_{:controller}_{:action}_{id}',1),  // 商品详情页静态缓存1秒钟
								'Goods:ajaxComment'=>array('{:module}_{:controller}_{:action}_{goods_id}_{commentType}_{p}',TPSHOP_CACHE_TIME),  // 商品评论页静态缓存 3秒钟
							),
		
	//默认错误跳转对应的模板文件
	'TMPL_ACTION_ERROR'   => 'Public:tpmsg',
	//默认成功跳转对应的模板文件
	'TMPL_ACTION_SUCCESS' => 'Public:tpmsg',
	
	//默认成功跳转对应的模板文件
	'WEB_SITE_TITILE' => '免费读书',
	
	/**
	 * 图片配置
	 */
	//图片路径
	'IMAGE_FILE_DIR' => 'E:\\\\files\\\\article\\\\image',
	
	//图片url
	'IMAGE_FILE_URL' => 'http://img.mianfeidushu.com/image',
	
	//m默认图片
	'DEFAULT_ARTICLE_COVER' => 'http://www.mianfeidushu.com/modules/article/images/nocover.jpg',
	
	/**
	 * 书籍配置
	 */
	//书籍路径
	'TXT_FILE_DIR' => 'E:\\\\files\\\\article\\\\txt',
	
	//书籍url
	'TXT_FILE_URL' => 'http://img.mianfeidushu.com/txt',
	
	/**
	 * www.mianfeidushu.com的root路径
	 */
	'DIR_PATH'                 => 'C:\\\\virtualhost\\\\bacoolread\\\\configs\\\\article\\\\',//文件夹路径
	
	'FIRST_SORT_FILE_PATH'     => 'filter.php',
	'SECONDE_SORT_FILE_PATH'   => 'sort.php',
	'THIRD_SORT_FILE_PATH'     => 'sort.php',
	
	'PAGE_SIZE'                => 30,//书籍分页大小
	
	'SALE_PRICE'               => 5,//书籍分页大小
	
	'DEFAULT_FACE_IMG'         => 'http://img.mianfeidushu.com/facePic/01.png',
	
	//默认昵称
	'DEFAULT_NICKNAME'         => "书友",

	//游客默认昵称
	'DEFAULT_VISITOR_NICKNAME' => "游客",
	
	'A_LI_DA_YU_CONF_PATH'     => 'C:\\\\virtualhost\\\\bacoolread\\\\modules\\\\app\\\\common\\\\aLiDaYuConf.php',
	
	//游客默认昵称
	'DEFAULT_EGOLD_NAME'       => '小说币',
	
	/**
	 * 支付配置文件
	 */
	//微信支付配置文件
	'WEIXIN_PAY_CONF_PATH'     => 'C:\\\\virtualhost\\\\bacoolread\\\\configs\\\\pay\\\\weixinpay.php',
	
	//阿里支付配置文件
	'ALI_PAY_CONF_PATH'        => 'C:\\\\virtualhost\\\\bacoolread\\\\configs\\\\pay\\\\alipay.php',
	
	/**
	 * 第三仿登录配置文件
	 */
	//微信登录配置文件
	'WEIXIN_LOGIN_CONF_PATH'     => 'C:\\\\virtualhost\\\\bacoolread\\\\api\\\\weixin\\\\config.inc.php',
	
	//qq登录配置QQ文件
	'QQ_LOGIN_CONF_PATH'        => 'C:\\\\virtualhost\\\\bacoolread\\\\api\\\\qq\\\\config.inc.php',
	
	//微博登录配置文件
	'WEIBO_LOGIN_CONF_PATH'        => 'C:\\\\virtualhost\\\\bacoolread\\\\api\\\\weibo\\\\config.inc.php'
);