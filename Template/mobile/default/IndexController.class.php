<?php
/**
 * 免费读书手机网站
 * ============================================================================
 * * 版权所有 2015-2027 河南趣读信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.qudukeji.com
 * ----------------------------------------------------------------------------
 * ============================================================================
 *
 */

namespace Mobile\Controller;
use Think\AjaxPage;
use Think\Page;

class IndexController extends MobileBaseController
{
    public function index()
    {
	    $tmpBookList = M('app_slidebanner')->field('booksID,booksCover')->where(array('channel' => 0))->select();
	
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
			
			    $tmpBook['url']       = U('Article/index',array('id' => $tmpBook['bookid']));
			    
			    $bannerBookList[]     = $tmpBook;
		    }
	    }

        //edit by muyi 2017/05/31
        //添加不同服务站显示不同的站名
        $fromid=cookie('fromid');
        if($fromid){
            $channelWxModel=M('distribution_channel_wx');
            $wxinfo=$channelWxModel->field('channelid,sitename,wxnum')->where(array('channelid'=>cookie('fromid')))->find();
            if($wxinfo){
                $siteTitle=$wxinfo['sitename'];
                $wxnum=$wxinfo['wxnum'];

            }else{
                $wxnum='mianfeidushuwang';
                $siteTitle='书城精品';
            }
        }else{
            $wxnum='mianfeidushuwang';
            $siteTitle='书城精品';
        }

		//edit by muyi 2017/06/20
        //首页添加个人中心我的书架和上次阅读:
		$bookCaseList = M('distribution_article_bookcase')->field('userid,caseid,articlename,chaptername')->where(array('userid' => $_COOKIE['uid']))->order('`joindate` DESC')->limit(1)->select();

        $bookCaseList=$bookCaseList[0];
		if(!empty($bookCaseList['articlename'])){
			 $bookCaseList['lastread'] ="《".$bookCaseList['articlename']."》 ".$bookCaseList['chaptername'];
			 if( mb_strlen( $bookCaseList['lastread'],'utf-8')>21){
				$bookCaseList['lastread']=mb_substr($bookCaseList['lastread'],0,21,'utf-8')."…";
			 }
		}else{
			$bookCaseList['lastread']='您还没有阅读记录!';
		}
       

        $userModel=M('distribution_system_users');
        $userinfo=$userModel->field('faceImg,name')->where(array('uid'=>cookie('uid')))->find();
        $this->assign('faceImg',$userinfo['faceImg']);
        $this->assign('username',$userinfo['name']);
        $this->assign('lastbook',$bookCaseList);
		
		
		
        $this->assign('site_title',$siteTitle);
        $this->assign('wxnum',$wxnum);


        //获取精选频道的热推书
	    $hotCommondBookList = $this->getHotCommendBook(0);
	
	    $this->assign('slidebannerlist',$bannerBookList);
	    
	    $this->assign('hotcommondbooklist',$hotCommondBookList);
	
	    $this->assign('type',1);
		
		/*限时免费 start daidai*/
	    $time=time();
	    $freeBookList=M('article_article')
            ->field('articleid,articlename,author,freetime')
            ->where(array('freetime'=>array('gt',$time)))
            ->order('freetime')
            /* ->limit(3) */
            ->select();
	    foreach($freeBookList as $k=>$v){
	        $articleid=$v['articleid'];
            $freeBookList[$k]['cover']  = getBookCoverUrl($articleid);
            $freeBookList[$k]['url']    = U('Article/index',array('id' => $articleid));
        }
        $this->assign('freeBookList',$freeBookList);
	    /*end daidai*/
	    
	    $this->display();
	    
	    if(cookie('uid') && cookie('uname'))
	    {
	        $this->addUserAccessLog();
	    }
    }

	/*
	 * 获取相应的热推小说列表
	 */
	public function getHotCommendBookList()
	{
		$showID = I('show_id');
		
		$tmpCommendBook = M('app_hotcommend')->field('booksID,title')->where(array('id' => $showID))->find();
		
		$siteTitle = $tmpCommendBook['title'];
		
		$tmpHotCommendBookList  = explode('|',$tmpCommendBook['booksID']);
		
		$tmpHotBookList         = getBookInfoFromBookList($tmpHotCommendBookList);
		
		$reusltBookList         = $tmpHotBookList;
		//edit by muyi 2017/05/31
        //添加不同服务站显示不同的站名
        $fromid=cookie('fromid');
        if($fromid){
            $channelWxModel=M('distribution_channel_wx');
            $wxinfo=$channelWxModel->field('channelid,sitename,wxnum')->where(array('channelid'=>cookie('fromid')))->find();
            if($wxinfo){
                $siteTitle=$wxinfo['sitename'];
                $wxnum=$wxinfo['wxnum'];

            }else{
                $wxnum='mianfeidushuwang';
            }
        }else{
            $wxnum='mianfeidushuwang';
        }
		
		$this->assign('site_title',$siteTitle);
		$this->assign('wxnum',$wxnum);

		$this->assign('resultbooklist',$reusltBookList);

		
		$this->display('bookList');
	}
	
	/**
	 * 获取书籍的分类列表
	 */
	public function getSortList1()
	{
		$filePath = C('DIR_PATH') . C('SECONDE_SORT_FILE_PATH');
		
		include $filePath;
		
		if(isset($jieqiSort['article']) && $jieqiSort['article'])
		{
			$orderStr = '`lastupdate` DESC';
			
			foreach($jieqiSort['article'] as $key => $value)
			{
				$sortIDList[$key]['sortname'] = iconv('GBK','UTF-8',$value['caption']);
				
				$firstBook = M('article_article')->where(array('sortid' => $key))->order($orderStr)->limit(1)->field('articleid')->select();
				
				$bookInfo = getBookInfoFromBookList($firstBook[0]);
				
				if($bookInfo[0]['cover'])
				{
					$sortIDList[$key]['cover'] = $bookInfo[0]['cover'];
				}
				else
				{
					$sortIDList[$key]['cover'] = C('DEFAULT_ARTICLE_COVER');
				}
			}
		}
		
		$siteTitle = '分类';

		
		$this->assign('site_title',$siteTitle);
		
		$this->assign('resultbooklist',$sortIDList);
		
		$this->assign('type',2);
		
		$this->display('sortList');
	}


    /**
     * 获取书籍分类
     */

    public function getSortList()
    {
        //引入杰奇系统的分类文件
        $filePath = C('DIR_PATH') . C('SECONDE_SORT_FILE_PATH');
        include $filePath;
        $filePath = C('DIR_PATH') . C('FIRST_SORT_FILE_PATH');
        include $filePath;
        $color=array('#00d37d','#ff87ba','#529bff');

        //获取频道
        if (isset($jieqiFilter['article']['rgroup']) && $jieqiFilter['article']['rgroup']) {
            $groups=$jieqiFilter['article']['rgroup'];
            $temp=array();
            foreach ($groups as $k =>$v){
                $temp[$v['rgroup']]=iconv('GBK','UTF-8',$v['caption']);
            }
            $groups=$temp;
        }

        //获取所有分类
        if (isset($jieqiSort['article']) && $jieqiSort['article']) {
            $sort = array();
            foreach ($jieqiSort['article'] as $k => $v) {
                $sort[$v['group']][$k]['sortid']= $k;
                $sort[$v['group']][$k]['caption']= iconv('GBK','UTF-8',$v['caption']);
            }

            foreach ($sort as $k=>$v){
                unset($sort[$k]);
                $sort[$k]['group']=$groups[$k];
                $sort[$k]['sort']=$v;
                $sort[$k]['color']=$color[$k%3];

            }


            $siteTitle = '分类';

            $this->assign('site_title',$siteTitle);
            $this->assign('sortList',$sort);
			
			$this->assign('type',2);

            $this->display('sortList');

        }
    }



	public function getBookListFromSortID()
	{
		$sortID    = I('sort_id');
		
		$siteTitle = getSortName($sortID);
		
		$this->assign('site_title',$siteTitle);
		
		$this->assign('sort_id',$sortID);
		
		$order  = I('order_by');
		
		if(!$order)
		{
			$order = 'lastupdate';
		}
		
		$orderBy = "`" . $order . "` DESC";
		
		$count   = M('article_article')->where(array('sortid' => $sortID))->count();
		
		$page    = new Page($count,C('PAGE_SIZE'));
		
		$tmpBookList = M('article_article')->where(array('sortid' => $sortID))->order($orderBy)->limit($page->firstRow . ',' . $page->listRows)->field('articleid,articlename,author,size,intro')->select();
		
		$reusltBookList = getBookInfoFromArticleList($tmpBookList);
		
		if($order == 'lastupdate')
		{
			$showType = 1;
		}
		else
		{
			$showType = 2;
		}
		
		$this->assign('show_type',$showType);
		
		$this->assign('order_by',$order);
		
		$this->assign('resultbooklist',$reusltBookList);
		
		$this->assign('page',$page);// 赋值分页输出
		
		if($_GET['is_ajax'])
		{
			$this->display('ajaxBookListFromSortID');
		}
		else
		{
			$this->display('sortBookList');
		}
	}
	
	public function ajaxBookListFromSortID()
	{
		$sortID = I('sort_id');
		
		$order  = I('order_by');
		
		if(!$order)
		{
			$order = 'lastupdate';
		}
		
		$orderBy = "`" . $order . "` DESC";
		
		$count    = M('article_article')->where(array('sortid' => $sortID))->count();

		$page     = new AjaxPage($count,C('PAGE_SIZE'));
		
		$show     = $page->show();
		
		$tmpBookList = M('article_article')->where(array('sortid' => $sortID))->order($orderBy)->limit($page->firstRow . ',' . $page->listRows)->field('articleid,articlename,author,size,intro')->select();
		
		$reusltBookList = getBookInfoFromArticleList($tmpBookList);
		
		$this->assign('resultbooklist',$reusltBookList);

		$this->assign('page',$show);// 赋值分页输出

		$this->display();
	}
	
	/**
	 * 获取专题列表
	 */
	public function getTopicList()
	{
		$orderBy   = "`topicID` DESC";
		
		$count     = M('app_topic')->where(1)->count();
		
		$page      = new Page($count,C('PAGE_SIZE'));
		
		$topicList = M('app_topic')->where(1)->order($orderBy)->limit($page->firstRow . ',' . $page->listRows)->field('id,cover,summary,title')->select();
		
		$this->assign('topiclist',$topicList);
		
		$siteTitle = '专题';
		
		$this->assign('site_title',$siteTitle);
		
		$this->assign('type',3);
		
		$this->assign('page',$page);// 赋值分页输出
		
		if($_GET['is_ajax'])
		{
			$this->display('ajaxBookListFromSortID');
		}
		else
		{
			$this->display('topicList');
		}
	}
	
	public function ajaxTopicList()
	{
		$orderBy = "`topicID` DESC";
		
		$count     = M('app_topic')->where(1)->count();
		
		$page      = new Page($count,C('PAGE_SIZE'));
		
		$topicList = M('app_topic')->where(1)->order($orderBy)->limit($page->firstRow . ',' . $page->listRows)->field('id,cover,title')->select();
		
		$show      = $page->show();
		
		$this->assign('topiclist',$topicList);
		
		$this->assign('page',$show);// 赋值分页输出
		
		$this->display();
	}
	
	public function getBookListFromTopicId()
	{
		$topicID        = I('topic_id');
		
		$topic          = M('app_topic')->where(array('id'  => $topicID))->find();
		
		$siteTitle      = $topic['title'];
		
		$tmpBookList    = explode('|',$topic['booksID']);
		
		$reusltBookList = getBookInfoFromBookList($tmpBookList);
		
		$this->assign('site_title',$siteTitle);
		
		$this->assign('resultbooklist',$reusltBookList);
		
		$this->display('bookList');
	}
	
	public function searchBook()
	{
		header('content-type:text/html;charset=utf-8');
		$keyWord = I('key_word');
		
		$keyWord = trim($keyWord);
		
		$condition['articlename'] = array("like","%" . $keyWord . "%");
		
		$condition['author']      = array("like","%" . $keyWord . "%");
		
		$condition['_logic'] = 'OR';
		
		$articleList = M('article_article')->where($condition)->field('articleid,articlename,author,size,intro,sortid')->select();
		
		$reusltBookList = getBookInfoFromArticleList($articleList);
		
		foreach($reusltBookList as $key => $value)
		{
			$book['url']                 = $value['url'];
			
			$book['info']['articleid']   = $value['articleid'];
			
			$book['info']['articlename'] = $value['articlename'];
			
			$book['cover']               = $value['cover'];
			
			$book['info']['author']      = $value['author'];
			
			$book['info']['size']        = $value['size'];
			
			$book['info']['sort']        = $value['sort'];
			
			$book['info']['intro']       = $value['intro'];
			
			$reusltBookList[$key]        = $book;
		}
		
		$this->assign('resultbooklist',$reusltBookList);
		
		/*Daisy*/
		$this->assign('keyWord',$keyWord);
		$this->assign('action',__FUNCTION__);
		
		$this->display('bookList');
	}
	
	/**
	 * 增加用户访问记录
	 */
	private function addUserAccessLog()
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
			
			M('distribution_user_access_log')->add($data);
		}
	}
	
	/**
     * 限免书籍
     */
	public function freeLimitBookList(){       
        $time=time();
        $freeBookList=M('article_article')
            ->field('articleid,articlename,author,freetime,intro')
            ->where(array('freetime'=>array('gt',$time)))
            ->order('freetime')
            ->select();

        foreach($freeBookList as $k=>$v){
            $articleid=$v['articleid'];
            $freeBookList[$k]['cover']  = getBookCoverUrl($articleid);
            $freeBookList[$k]['url']    = U('Article/index',array('id' => $articleid));
        }

        $this->assign('freeBookList',$freeBookList);
        
        $this->display();
    }
	/**
     * 搜索书籍
     */
    public function search(){
        header('content-type:text/html;charset=utf-8');
        $keyWord = I('keyWord');
        /*热门搜索*/
        $searchModel=M('app_hotsearchword');
        $hotbookList=$searchModel->select();
        /*foreach ($hotbookList as $k=>$v) {
            $articleid=$v['bookID'];
            $articleInfo=M('article_article')->find($articleid);
        }*/
        /*点击量较高的书籍*/
        $visitBookList=M('article_article')->field('articleid,articlename,allvisit')->order('allvisit DESC')->select();
        $visitBookList=json_encode($visitBookList);

        /*$historyList=cookie('history');
        $historyList=json_decode($historyList,true);
        $historyList=array_reverse($historyList);
		$historyList=array_slice($historyList,0,10);*/

        /*$this->assign('historyList',$historyList);*/
        $this->assign('hotbookList',$hotbookList);
        $this->assign('keyWord',$keyWord);
        $this->assign('visitBookList',$visitBookList);

        $this->display();
    }
}