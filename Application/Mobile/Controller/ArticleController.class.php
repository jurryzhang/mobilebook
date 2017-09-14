<?php
/**
 * 免费读书手机网站
 * ======================================================================
 * * 版权所有 2015-2027 河南趣读信息科技有限公司，并保留所有权利。
 * 网站地址: http://www.qudukeji.com
 * ----------------------------------------------------------------------------
 * ======================================================================
 *
 */

namespace Mobile\Controller;

use Mobile\Logic\ArticleLogic;

use Think\Page;

class ArticleController extends MobileBaseController
{
    public function index()
    {
		header('content-type:text/html;charset=utf-8');
        $articleID = I('get.id');
	
        $article   = M('article_article')->field('articleid,articlename')->where(array('articleid' =>  $articleID))->find();
	
		/*Index日志查看bookid*/
        $str="--------------------------Article控制器Index--------------------------------------".PHP_EOL;
        $str.=date('Y-m-d H:i:s',time()).PHP_EOL;
        $str.="cookie中uid：".cookie('uid')."_COOKIE中uid：".$_COOKIE['uid'].PHP_EOL;
        $str.='传递的articleID：'.$articleID."书籍名称：".$article['articlename'].PHP_EOL;
        file_put_contents("./LOG_DAISY/readChapter.txt",$str,FILE_APPEND);
		
		/*Daisy*/
        if(empty($article)){
            echo "<script>";
            echo "alert('该书已下架，请查看其他书籍');";
            echo "window.history.back(-1); ";
            echo "</script>";
            exit();
        }
		
        $articles   = M('distribution_obook_obook')->field('articleid,allvisit,dayvisit,daydate,weekdate,weekvisit,monthdate,monthvisit,sumhurry,sumgift,sumcomment')->where(array('articleid' =>  $articleID))->find();
        $num=1;
        $data['allvisit'] =$articles['allvisit']+$num ;
        M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);


        //        日点击计算
        $day=date("d",time());
        if(!empty($articles['daydate'])){

            if ($day ==$articles['daydate'] ){
                $data['dayvisit'] =$articles['dayvisit']+$num ;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }else{
                $data['dayvisit'] = $num;
                $data['daydate']=$day;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }
        }else{
            $data['dayvisit'] = $num;
            $data['daydate']=$day;
            M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
        }

        //周点击计算
        $week=date("W",time());
        if(!empty($articles['weekdate'])){

            if ($week ==$articles['weekdate'] ){
                $data['weekvisit'] =$articles['weekvisit']+$num ;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }else{
                $data['weekvisit'] = $num;
                $data['weekdate']=$week;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }
        }else{
            $data['weekvisit'] = $num;
            $data['weekdate']=$week;
            M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
        }

        //月点击计算
        $month=date("m",time());
        if(!empty($articles['monthdate'])){
            if ($month ==$articles['monthdate'] ){
                $data['monthvisit'] =$articles['monthvisit']+$num ;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }else{
                $data['monthvisit'] = $num;
                $data['monthdate']=$month;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }
        }else{
            $data['monthvisit'] = $num;
            $data['monthdate']=$month;
            M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
        }

        $bookInfo = getBookInfoFromArticleInfo($article);
	
	
		/*start daidai*******************************************************/
        $bookcaseInfo=M('distribution_article_bookcase')->where(array('articleid'=>$bookInfo['articleid'],'userid'=>cookie('uid')))->find();
        $this->assign('bookcase',$bookcaseInfo);
        //dump($bookcaseInfo);

        /* $commentList=M('distribution_comment')->where(array('articleid'=>$articleID))->order('addtime DESC')->limit('5')->select(); */
		$commenWhere='articleid='.$articleID.' AND '.'(ischeck=1 OR userid='.cookie('uid').')';
        $commentList=M('distribution_comment')->field('userid,username,content,addtime,replay')->where($commenWhere)->order('addtime DESC')->limit('5')->select();

        foreach($commentList as $k=>$v){
            $userid=$v['userid'];
            $userinfo=M('distribution_system_users')
                ->field('uid,name,faceImg')
                ->where(array('uid'=>$userid))
                ->find();
            $commentList[$k]['faceImg']=$userinfo['faceImg'];
            /* $commentList[$k]['username']=$userinfo['name']; */
        }
	

        $this->assign('commentList',$commentList);
        /*打赏个数*/
        /*$giftList=M('distribution_gifts')
            ->field('sum(amount) as sum,giftstype')
            ->where(array('articleid'=>$articleID))
            ->group('giftstype')
            ->order('giftstype')
            ->select();
        $giftSum=array();
        foreach ($giftList as $key=>$val) {
            $giftSum[$val['giftstype']]=$val['sum'];
        }
        $this->assign('giftSum',$giftSum);*/
        /*用户信息*/
        $userModel=M('distribution_system_users');
        $userinfo=$userModel->field('uid,name,faceImg,egold,viptime,signtime,regdate')->where(array('uid'=>cookie('uid')))->find();
        $this->assign('userinfo',$userinfo);
    

	/*打赏，催更*/
        /*$ArticleModel=D('Book');
        $userAction=$ArticleModel->getUserAction($articleID);*/
        $this->assign('bookinfo',$articles);
        /*end daidai************************************************************************/
	
        $this->assign('book_info',$bookInfo);

        $this->display();
        
        $this->insertObookInfo($articleID);
    }
	
	/**
	 * 插入和更新jieqi_distribution_obook_obook和jieqi_distribution_obook_ochapter，便于后期统计书籍销售情况
	 *
	 * @param $articleID
	 */
    private function insertObookInfo($articleID)
    {
    	$articleLogic = new ArticleLogic();
		
	
	    $articleLogic->insertObooKInfo($articleID);
    }
    
    /**
     * 书籍目录
     */
    public function getDirectory()
    {
    	$articleID = I('id');

        /*返回目录定位当前页*/
        $chapterorder = I('chapterorder')+0;
        $chapterPage = round($chapterorder/200,0);
        $chapterPage = $chapterPage+1;

        $sorttype=I('sorttype');
        $orderBy   = "`chapterorder`".$sorttype; 
          	
    	/* $orderBy   = "`chapterorder` ASC"; */

        $count     = M('article_chapter')->where(array('articleid' => $articleID))->count();
    
        $page      = new Page($count,C('PAGE_SIZE'));
        
        /*返回目录定位当前页*/
        if($chapterPage>1){
            $page->firstRow = $page->firstRow * $chapterPage;
            $page->listRows = $page->listRows * $chapterPage;
        }
    	
        $list = M('article_chapter')
            ->field('articleid,chapterid,size,chaptername,isvip,saleprice')
            ->where(array('articleid' => $articleID))
            ->order($orderBy)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
			
		/*start daidai*/
        $bookInfo=M('article_article')->field('freetime')->where(array('articleid'=>$articleID))->find();
        if($bookInfo['freetime']>time()){
            $isFreeLimit=1;
        }
        $this->assign('isFreeLimit',$isFreeLimit);
        /*end daidai*/
        
	    $this->assign('chapterlist',$list);// 赋值分页输出
	
	    $this->assign('bookid',$list[0]['articleid']);//书籍id
	
	    $this->assign('bookname',$list[0]['articlename']);//书籍id
	    
	    $this->assign('page',$page);// 赋值分页输出

        $this->assign('sorttype',$sorttype);
	    $this->assign('chapterPage',$chapterPage);

	    if($_GET['is_ajax'])
	    {
		    $this->display('ajaxBookListFromSortID');
	    }
	    else
	    {
		    $this->display();
	    }
    }
    
    public function ajaxDirectoryList()
    {
	    $articleID = I('articleid');

        $sorttype=I('sorttype');
        $orderBy   = "`chapterorder`".$sorttype;

	
	    $count     = M('article_chapter')->where(array('articleid' => $articleID))->count();
	
	    $page      = new Page($count,C('PAGE_SIZE'));

	    /* $orderBy   = "`chapterorder` ASC"; */
	
	    $list      = M('article_chapter')
            ->field('articleid,chapterid,size,chaptername,isvip,saleprice')
            ->where(array('articleid' => $articleID))
            ->order($orderBy)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
	
	    $show      = $page->show();
	
	    $this->assign('bookid',$list[0]['articleid']);//书籍id
	
	    $this->assign('bookname',$list[0]['articlename']);//书籍id
	
	    $this->assign('chapterlist',$list);// 赋值分页输出
	
	    $this->assign('page',$show);// 赋值分页输出
		
		 /*start daidai*/
        $bookInfo=M('article_article')->field('freetime')->where(array('articleid'=>$articleID))->find();
        if($bookInfo['freetime']>time()){
            $isFreeLimit=1;
        }
        $this->assign('isFreeLimit',$isFreeLimit);
        /*end daidai*/
	
	    $this->display();
    }
    
    public function readChapter()
    {
				
		$articleID = I('book_id');

        $chapterID   = I('chapter_id');

        cookie('chapterMark',$chapterID);

        $chapterInfo = M('article_chapter')
            ->where(array('chapterid' => $chapterID,'display' => 0))
            ->field('articleid,articlename,chapterid,chaptername,summary,isvip,lastupdate,poster,size,chapterorder')
            ->find();
		//dump(chapterInfo['chapterorder']);exit;
	    $flag = $this->getUserReadChapterCount($chapterInfo['chapterorder']);
         /***获取或生成百度语音***/
            $bookModel = D('Book');
            $audiofile = $bookModel->getChapterAudio($articleID,$chapterID);
            //var_dump($audiofile);exit;
            $this->assign('audiodata',$audiofile);

        /* $articleID = I('book_id'); */
		//先做插入小说
		$this->insertObookInfo($articleID);
        $articles   = M('distribution_obook_obook')->where(array('articleid' =>  $articleID))->find();
        $num=1;
        $data['allvisit'] =$articles['allvisit']+$num ;
        M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);

        //        日点击计算
        $day=date("d",time());
        if(!empty($articles['daydate'])){

            if ($day ==$articles['daydate'] ){
                $data['dayvisit'] =$articles['dayvisit']+$num ;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }else{
                $data['dayvisit'] = $num;
                $data['daydate']=$day;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }
        }else{
            $data['dayvisit'] = $num;
            $data['daydate']=$day;
            M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
        }

        //周点击计算
        $week=date("W",time());
        if(!empty($articles['weekdate'])){

            if ($week ==$articles['weekdate'] ){
                $data['weekvisit'] =$articles['weekvisit']+$num ;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }else{
                $data['weekvisit'] = $num;
                $data['weekdate']=$week;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }
        }else{
            $data['weekvisit'] = $num;
            $data['weekdate']=$week;
            M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
        }

        //月点击计算
        $month=date("m",time());
        if(!empty($articles['monthdate'])){
            if ($month ==$articles['monthdate'] ){
                $data['monthvisit'] =$articles['monthvisit']+$num ;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }else{
                $data['monthvisit'] = $num;
                $data['monthdate']=$month;
                M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
            }
        }else{
            $data['monthvisit'] = $num;
            $data['monthdate']=$month;
            M('distribution_obook_obook')->where(array('articleid' => $articleID))->save($data);
        }
        
	    if($flag)
	    {
			
			//广告显示
            $ad=$this->getAdInfo();
            $this->assign('ad',$ad);
			
			//edit by muyi 2017/06/17签到增书币
			$userModel=D('User');
            $userinfo=$userModel->field('uname,uid,name,faceImg,egold,viptime,signtime,regdate')->where(array('uid'=>cookie('uid')))->find();
            $today=strtotime(date('Y-m-d',time()));
            if($userinfo['signtime']<$today&&cookie('uid')>0&&$userinfo['regdate']<$today){
                $userinfo['signtime']=time();
                $userinfo['egold']= $userinfo['egold']+50;
                $userModel->where(array('uid'=>cookie('uid')))->save($userinfo);
				 $userModel->sendSigninfo($userinfo['uname']);
                $this->assign('sign',1);
            }else{
                $this->assign('sign',0);
            }
            $this->assign('userinfo',$userinfo);
            //dump($userinfo);/********/
			
/* 		    $articleID   = I('book_id');
		
		    $chapterID   = I('chapter_id');
		
		    $chapterInfo = M('article_chapter')->where(array('chapterid' => $chapterID,'display' => 0))->field('articleid,articlename,chapterid,chaptername,summary,isvip,lastupdate,poster,size,chapterorder')->find();
 */		
		    $this->addBookCaseChapterInfo($articleID,$chapterID);
		
		    $bookinfo      = M('article_article')->where(array('articleid' => $articleID))->field('articlename,author,freetime')->find();
		
		    $chapterInfo['author']      = $bookinfo['author'];
		    $chapterInfo['articlename']      = $bookinfo['articlename'];
		
		    $chapterInfo['prechapter']  = $this->getContextChapterID($chapterInfo['chapterorder'],$chapterInfo['articleid'],-1);
		
		    $chapterInfo['nextchapter'] = $this->getContextChapterID($chapterInfo['chapterorder'],$chapterInfo['articleid'],1);
			$chapterInfo['cover']=getBookCoverUrl($articleID);
		
			/*readchapter日志查看bookid*/
            $str="--------------------------Article控制器readchapter--------------------------------------".PHP_EOL;
            $str.=date('Y-m-d H:i:s',time()).PHP_EOL;
            $str.="cookie中uid：".cookie('uid')."--用户ID：".$userinfo['uid']."--用户名：".$userinfo['name'].PHP_EOL;
            $str.="_COOKIE中uid：".$_COOKIE['uid'].PHP_EOL;
            $str.="session中uid：".$_SESSION['uid'].PHP_EOL;
            $str.='传递的articleID：'.$articleID.'--articleid:'.$chapterInfo['articleid'].PHP_EOL;
            $str.='articlename:'.$chapterInfo['articlename'].PHP_EOL;
            $str.='传递的chapterID：'.$chapterID.'--chapterid:'.$chapterInfo['chapterid'].PHP_EOL;
            $str.='chaptername:'.$chapterInfo['chaptername'].PHP_EOL;
            $str.='author:'.$chapterInfo['author'].PHP_EOL;
            $str.='prechapter:'.$chapterInfo['prechapter'].PHP_EOL;
            $str.='nextchapter:'.$chapterInfo['nextchapter'].PHP_EOL;
            file_put_contents("./LOG_DAISY/readChapter.txt",$str,FILE_APPEND);
		
			/*start daidai 催更、打赏*/
            /*$ArticleModel=D('Book');
            $userAction=$ArticleModel->getUserAction($articleID);*/
            $this->assign('bookinfo',$articles);
            /* end daidai*/		
		
		    if($chapterInfo['isvip'] == 0)//免费章节
		    {
			    $content = getChapterContent($articleID,$chapterID);
			
			    if($content != -1)
			    {
				    $chapterInfo['content'] = $content;
				
				    $chapterInfo['size']    = intval($chapterInfo['size'] / 2);
				
				    $this->assign('chapter',$chapterInfo);
					 $this->assign('site_title',$chapterInfo['articlename']);
				
				    $this->display();
			    }
		    }
		    else//付费章节
		    {
			    $articleLogic = new ArticleLogic();
			
			    $buyFlag = $articleLogic->getUserOChapterByChapterID($articleID,$chapterID);
				 //判断是否为年费会员,如果是年费会员直接读书				
				
			    if($userinfo['viptime']>time()){
                    $buyFlag=true;
                }
				if($bookinfo['freetime']>time()){
			        $buyFlag=true;
                }
			
			    if($buyFlag)
			    {
				    $content = getChapterContent($articleID,$chapterID);
				
				    if($content != -1)
				    {
					    $chapterInfo['content'] = $content;
					
					    $chapterInfo['size']    = intval($chapterInfo['size'] / 2);
					
					    $this->assign('site_title',$chapterInfo['articlename']);
						
					    $this->assign('chapter',$chapterInfo);
					
					    $this->display('readChapter');
				    }
			    }
			    else
			    {
				    $flag = $this->autoBuyVipChapter($articleID,$chapterID);
				
				    //是否自动购买
				    if($flag===true)
				    {
					    $content = getChapterContent($articleID,$chapterID);
					
					    if($content != -1)
					    {
						    $chapterInfo['content'] = $content;
						
						    $chapterInfo['size']    = intval($chapterInfo['size'] / 2);
							 $this->assign('site_title',$chapterInfo['articlename']);						
						    $this->assign('chapter',$chapterInfo);
						
						    $this->display('readChapter');
					    }
				    }
				    elseif($flag == -1)
				    {
						$wxflag = isWeiXin();
						
						header("Content-Type:text/html;charset=utf-8");
						if($wxflag){
							 echo "<script>alert('您的余额不足，请充值！');
                                 window.location.href='/User/getWeiXinInfo/backurl/User-buyEgold.html';
                                </script>";
						}else{							
							echo "<script>alert('您的余额不足，请充值！');
                                 window.location.href='/User/buyEgold/.html';
                                </script>";
						}
				    }
				    else
				    {
						 $this->error('参数有误或服务器故障!请重试');
				    }
			    }
		    }
	    }
	    else
	    {
	        $fromid=cookie('fromid');
	        if($fromid){
                $channelWxModel=M('distribution_channel_wx');
                $wxinfo=$channelWxModel->where(array('channelid'=>cookie('fromid')))->find();
                if($wxinfo){
                   if(empty($wxinfo['subscribeurl'])){
                       $this->assign('wxnum',$wxinfo['wxnum']);
                       $this->assign('wxname',$wxinfo['wxname']);
                       $this->display('follow');
                    }else{
                        $url=$wxinfo['subscribeurl'];
                        header("Location:" .$url);
                    }
                }else{
                    header("Location:" . C('FORCED_ATTENTION_URL'));
                }
            }else{
                header("Location:" . C('FORCED_ATTENTION_URL'));
            }
	    }
    }
	
	/**
	 * @param $chapterID
	 * @param $bookID
	 * @param $direction -1是向前查找，1是向后查找
	 * @return mixed
	 */
    public function getContextChapterID($chapterOrder,$bookID,$direction)
    {
    	if($direction == 1)
	    {
		    $condition['chapterorder'] = array("gt",$chapterOrder);
		
		    $order                     = '`chapterorder` ASC';
	    }
	    else
	    {
		    $condition['chapterorder'] = array("lt",$chapterOrder);
		
		    $order                     = '`chapterorder` DESC';
	    }
	
	    $condition['articleid'] = $bookID;
	
	    $condition['size']      = array('neq',0);
	
	    $condition['_logic']    = 'AND';
	    
	    $chapter = M('article_chapter')->where($condition)->field('chapterid')->order($order)->select();
	
	    $chapterID = $chapter[0]['chapterid'];
	    
	    if(!$chapterID)
	    {
		    $chapterID = -1;
	    }
	    
        return 	$chapterID;
    }
	
    public function buyVipChapter()
    {
    	if($_COOKIE['uid'] > 0)
	    {
		    $articleID    = I('book_id');
		
		    $chapterID    = I('chapter_id');
		
		    $chapterInfo  = M('article_chapter')->where(array('chapterid' => $chapterID))->field('chaptername,chapterorder,articlename')->find();
		
		    $articleLogic = new ArticleLogic();
		
		    $buyList      = $articleLogic->showBuyInfo($articleID,$chapterInfo['chapterorder']);
		
		    $buyIndex     = I('buy_index');
		
		    $userBuyInfo  = $buyList[$buyIndex];
		
		    $userInfo     = $articleLogic->getUserInfo();
		
		    if($userInfo['egold'] >= $userBuyInfo['totalprice'])
		    {
		    	$payEgold = $userBuyInfo['totalprice'];
		    	
			    $flag     = $this->deductFromUser($_COOKIE['uid'],$payEgold);
			
			    //扣用户钱
			    if($flag)//扣钱成功
			    {
					//添加用户的详细购买信息
				    $flag = $this->addUserBuyInfo($articleID,$chapterInfo['articlename'],$userBuyInfo);
				    
				    if($flag)
				    {
					    $alertMsg = iconv('utf-8','gbk','购买成功！') ;
					    
					    echo ("<script> alert('" . $alertMsg . "'); window.location.href='" . U('Article/readChapter',array('book_id' => $articleID ,'chapter_id' => $chapterID)) ."'; </script>");
					
					    exit();
				    }
				    else
				    {
					    $alertMsg = iconv('utf-8','gbk','支付失败！') ;
					
					    echo ("<script> alert('" . $alertMsg . "'); </script>");
					
					    exit();
				    }
			    }
			    else//扣钱失败
			    {
				    $alertMsg = iconv('utf-8','gbk','支付失败！') ;
				
				    echo ("<script> alert('" . $alertMsg . "'); </script>");
				
				    exit();
			    }
		    }
		    else
		    {
			    $alertMsg = iconv('utf-8','gbk','余额不足，请充值！') ;
			
			    echo ("<script> alert('" . $alertMsg . "'); window.location.href='" . U('User/buyEgold') ."'; </script>");
			
			    exit();
		    }
	    }
	    else
	    {
		    $alertMsg = iconv('utf-8','gbk','请登录！') ;
		
		    echo ("<script> alert('" . $alertMsg . "'); window.location.href='" . U('User/login') ."'; </script>");
	    	
		    exit();
	    }
    }

	public function userBuyChapter()
	{
		if($_COOKIE['uid'] > 0)
		{
			$articleID    = I('get.book_id');

			$chapterID    = I('get.chapter_id');

			$chapterInfo  = M('article_chapter')->where(array('chapterid' => $chapterID))->field('chaptername,chapterorder')->find();
			
			$articleLogic = new ArticleLogic();

			$buyList      = $articleLogic->showBuyInfo($articleID,$chapterInfo['chapterorder']);
			
			$userInfo     = $articleLogic->getUserInfo();

			$this->assign('userbalance',$userInfo['egold']);

			$eglodName = C('DEFAULT_EGOLD_NAME');

			$this->assign('eglodname',$eglodName);

			$this->assign('chaptername',$chapterInfo['chaptername']);

			$this->assign('buylist',$buyList);

			$data = $this->fetch("showBuyInfo");
			
			echo json_encode_ex(array('status' => 1 ,'url' => $data));
			
			return;
		}
		else
		{
			echo json_encode_ex(array('status' => -1 ,'url' => U('User/login')));

			return;
		}
	}
	
	/**
	 * 购买vip章节的时候，扣用户的钱
	 *
	 * @param $authorID
	 * @param $egold
	 * @return bool
	 */
	private function deductFromUser($userID,$egold)
	{
		if($userID)
		{
			$userOldInfo   = M('distribution_system_users')->where(array('uid' => $userID))->find();
			
			$data['egold'] = $userOldInfo['egold'] - $egold;
			
			$authorInfo    = M('distribution_system_users')->where(array('uid' => $userID))->save($data);
			
			if($authorInfo!==false)
			{
				$flag = true;
			}
			else
			{
				
				$flag = false;
			}
		}
		else
		{
			
			$flag = true;
		}
		
		return $flag;
	}
	
	/**
	 * 添加用户购买信息
	 *
	 * @param $articleID
	 * @param $articleName
	 * @param $buyInfo
	 * @return bool|mixed
	 */
	private function addUserBuyInfo($articleID,$articleName,$buyInfo)
	{
		if($buyInfo)
		{
			$autoBuy = I('auto_buy');
			
			if($buyInfo['buychapterlist'] && $buyInfo['buychapterpricelist'])
			{
				foreach($buyInfo['buychapterlist'] as $chapterID => $chapterName)
				{
					$buyData['buytime']     = time();
					$buyData['userid']      = $_COOKIE['uid'];
					$buyData['username']    = $_COOKIE['uname'];
					$buyData['articleid']   = $articleID;
					$buyData['ochapterid']  = $chapterID;
					$buyData['obookname']   = $articleName;
					$buyData['chaptername'] = $chapterName;
					$buyData['buyprice']    = $buyInfo['buychapterpricelist'][$chapterID];
					$buyData['buynum']      = 1;
					$buyData['autobuy']     = $autoBuy;
					$buyData['channelid']   = cookie('channel_id');
					$buyData['channeltype'] = cookie('channel_type');
				
					$flag = M('distribution_obook_obuyinfo')->add($buyData);
					
					if($flag)
					{
						$ochapterInfo = M('distribution_obook_ochapter')->where(array('chapterid' => $chapterID))->find();
						
						$ochapterData['salenum'] = $ochapterInfo['salenum'] + 1;
						
						$ochapterData['sumegold'] = $ochapterInfo['sumegold'] + $buyInfo['buychapterpricelist'][$chapterID];
						
						$flag = M('distribution_obook_ochapter')->where(array('chapterid' => $chapterID))->save($ochapterData);
						
						$OldBookData = M('distribution_obook_obook')->where(array('articleid' => $ochapterInfo['articleid']))->find();
						
						$bookData['sumegold'] = $OldBookData['sumegold'] + $buyData['buyprice'];
						
						$bookData['sumsale']  = $OldBookData['sumsale'] + $buyData['buyprice'];
						
						M('distribution_obook_obook')->where(array('articleid' => $ochapterInfo['articleid']))->save($bookData);
					}
					else
					{
						return false;
					}
				}
				
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	/**
	 * 根据章节信息增加用户的购买信息
	 *
	 * @param $chapterID
	 * @param $autoBuy
	 * @return bool|mixed
	 *
	 */
	private function addUserBuyInfoByChapterID($chapterID,$autoBuy)
	{
		$chapterInfo = M('article_chapter')->field('articleid,chapterid,articlename,chaptername,saleprice')->where(array('chapterid' => $chapterID))->find();
		
		if($chapterInfo)
		{
			$buyData['buytime']     = time();
			$buyData['userid']      = $_COOKIE['uid'];
			$buyData['username']    = $_COOKIE['uname'];
			$buyData['articleid']   = $chapterInfo['articleid'];
			$buyData['ochapterid']  = $chapterInfo['chapterid'];;
			$buyData['obookname']   = $chapterInfo['articlename'];
			$buyData['chaptername'] = $chapterInfo['chaptername'];
			$buyData['buyprice']    = $chapterInfo['saleprice'];;
			$buyData['buynum']      = 1;
			$buyData['autobuy']     = $autoBuy;
			$buyData['channelid']   = cookie('channel_id');
			$buyData['channeltype'] = cookie('channel_type');
			
			$flag = M('distribution_obook_obuyinfo')->add($buyData);
			
			if($flag)
			{
				$ochapterInfo = M('distribution_obook_ochapter')->field('chapterid,articleid,salenum,sumegold')->where(array('chapterid' => $chapterID))->find();
				
				$ochapterData['salenum']  = $ochapterInfo['salenum'] + 1;
				
				$ochapterData['sumegold'] = $ochapterInfo['sumegold'] + $buyData['buyprice'];
				
				$flag = M('distribution_obook_ochapter')->where(array('chapterid' => $chapterID))->save($ochapterData);
				
				$OldBookData = M('distribution_obook_obook')->field('articleid,sumegold,sumsale')->where(array('articleid' => $ochapterInfo['articleid']))->find();
				
				$bookData['sumegold'] = $OldBookData['sumegold'] + $buyData['buyprice'];
				
				$bookData['sumsale']  = $OldBookData['sumsale'] + $buyData['buyprice'];
				
				M('distribution_obook_obook')->where(array('articleid' => $ochapterInfo['articleid']))->save($bookData);
				
				return $flag;
			}
			else
			{
				//echo'<PRE>'; var_dump($buyData);exit;die;
				return false;
			}
		}
		else
		{
			//echo'<PRE>'; var_dump($chapterInfo);exit;die;
			return false;
		}
	}
	
	/**
	 * 自动购买vip章节
	 *
	 * @param $articleID
	 * @param $chapterID
	 * @return bool|int
	 */
	private function autoBuyVipChapter($articleID,$chapterID)
	{
		
			$chapterInfo = M('article_chapter')->field('articleid,chapterid,saleprice')->where(array('chapterid' => $chapterID,'isvip' => 1))->find();
			
			if($chapterInfo)
			{
				$payEgold = $chapterInfo['saleprice'];
				
				//$size=$chapterInfo['size'] / 2; /*章节字数*/
                /* $sizeP=$size / 1000 * C('SALE_PRICE');
                $payEgold=round($sizeP,0); */
				
				$articleLogic = new ArticleLogic();
				
				$userInfo     = $articleLogic->getUserInfo();
				
				if($userInfo['egold'] >= $payEgold)
				{
					$flag = $this->deductFromUser($_COOKIE['uid'],$payEgold);
					
					//扣钱成功
					if($flag)
					{
						$flag = $this->addUserBuyInfoByChapterID($chapterID,1);
						
						if($flag)
						{
							return true;
						}
						else
						{
							
							return false;
						}
					}
					else
					{
							
							
						return false;
					}
				}
				else
				{
					
					return -1;//余额不足，自动购买失败，请充值
				}
			}
			else
			{
			
				return false;
			}
		
	}
	
	/**
	 * 增加书架的章节阅读信息
	 */
	public function addBookCaseChapterInfo($articleID,$chapterID)
	{
		$count = M('distribution_article_bookcase')->where(array('userid' => $_COOKIE['uid'],'articleid' => $articleID))->count();
		
		if($count)
		{
			$chapterInfo = M('article_chapter')->field('chapterid,articleid,chaptername,chapterorder,articlename')->where(array('chapterid' => $chapterID))->find();
			
			if(is_array($chapterInfo) && $chapterInfo)
			{
				$bookCaseInfo = M('distribution_article_bookcase')->where(array('userid' => $_COOKIE['uid'],'articleid' => $articleID))->find();
				
				$data['chapterid']    = $chapterID;
				
				$data['chaptername']  = $chapterInfo['chaptername'];
				
				$data['chapterorder'] = $chapterInfo['chapterorder'];
				
				$data['joindate']     = time();
				
				M('distribution_article_bookcase')->where(array('caseid' => $bookCaseInfo['caseid']))->save($data);
			}
		}
		else
		{
			$chapterInfo = M('article_chapter')->field('chapterid,articleid,chaptername,chapterorder,articlename')->where(array('chapterid' => $chapterID))->find();
			
			if(is_array($chapterInfo) && $chapterInfo)
			{
				$data['articleid']    = $chapterInfo['articleid'];
				
				$data['articlename']  = $chapterInfo['articlename'];;
				
				$data['userid']       = $_COOKIE['uid'];
				
				$data['username']     = $_COOKIE['uname'];
				
				$data['chapterid']    = $chapterID;
				
				$data['chaptername']  = $chapterInfo['chaptername'];
				
				$data['chapterorder'] = $chapterInfo['chapterorder'];
				
				$data['joindate']     = time();
				
				M('distribution_article_bookcase')->add($data);
			}
		}
	}
	
	public function readChapterFromBookCase()
	{
		$bookCaseID   = I('bookcase_id');
		
		$bookCaseInfo = M('distribution_article_bookcase')->field('articleid,chapterid')->where(array('caseid' => $bookCaseID))->find();
		$book=M('article_article')->field('articleid')->find($bookCaseInfo['articleid']);
		if($book){
            header("Location:" . U('Article/readChapter',array('book_id' => $bookCaseInfo['articleid'], 'chapter_id' => $bookCaseInfo['chapterid'])));
        }else{
			M('distribution_article_bookcase')->where(array('caseid' => $bookCaseID))->delete();
            header("Content-Type:text/html;charset=utf-8");
            echo "<script>alert('该书已下架!');
                                 window.location.href='/';
                                </script>";
        }
	}
	
	/**
	 * 判断用户是否可以继续阅读，可以继续阅读则显示章节内容，否则，跳转强制关注页面
	 *
	 * @return bool
	 */
	public function getUserReadChapterCount($chapterorder)
	{
		$flag = true;
		
		$weiXinBrower = isWeiXin();
		
		if($weiXinBrower)
		{
			$userModel=M('distribution_system_users');
			$user=$userModel->find(cookie('uid'));
			/*$_SESSION['subscribe']=$user['isfollow'];*/	
						
			if(!($_SESSION['subscribe'])){
				
				if(cookie('fromid')==''){
					$channel = M('distribution_channels')->field('channelid,pid')->where(array('channelid' => $user['channelid']))->find();
					//获取fromid
					if ($channel['pid'] == 0) {
						$pid = $channel['channelid'];
					} else {
						$pid = $channel['pid'];
					}
					cookie('fromid', $pid, 3600 * 24 * 30);
				}								
				
				$channelid=cookie('fromid')+0;
				$tokenStr=file_get_contents("http://wap.kyread.com/wx/getToken/id/".$channelid.".html");
				$url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$tokenStr."&openid=".$user['uname'];
				$userInfo=file_get_contents($url);
				$userInfo=json_decode($userInfo,true);
				$_SESSION['subscribe'] = $userInfo['subscribe'];
				
				/* 日志 */
				$wxStr="////////////////////////////////判断session_subscribe--！isset////////////////////////////////".PHP_EOL;
				$wxStr.='用户ID:'.cookie('uid')."---赋值session:".$_SESSION['subscribe'].PHP_EOL;
				$wxStr.=var_export($userInfo,true).PHP_EOL;
				file_put_contents('./LOG_DAISY/userSubscribe1.txt',$wxStr,FILE_APPEND);
			}
			
			if(!$_SESSION['subscribe'])
			{
				/* 日志 */
				$str="****************************************判断session_subscribe--！*******************************************".PHP_EOL;
				$str.="用户ID:".cookie('uid')."用户名:".$user['name']."---数据库关注状态:".$user['isfollow']."---session关注状态:".$_SESSION['subscribe']."---fromid:".cookie('fromid')."---渠道ID:".$_COOKIE['channel_id'].PHP_EOL;
				$str.=date('Y-m-d H:i:s',time()).PHP_EOL;
				file_put_contents('./LOG_DAISY/userSubscribe1.txt',$str,FILE_APPEND);
				
				if($_COOKIE['channel_id'])
				{
					$channelInfo = M('distribution_channels')->field('channelid,readchaptercount')->where(array('channelid' => $_COOKIE['channel_id']))->find();
					
					if($_COOKIE['ptid']){
						$ptinfo=M('distribution_channel_pt')->where(array('channelid' => $_COOKIE['channel_id'],'ptid'=>$_COOKIE['ptid']))->find();
					if($ptinfo){
						
						$channelInfo['readchaptercount']=$ptinfo['readchaptercount'];
					}
				   }
				   
				   
					
					/* if(isset($_SESSION['readchaptercount']))
					{
						if($_SESSION['readchaptercount'] <= $channelInfo['readchaptercount'])
						{
							$_SESSION['readchaptercount']++;
							
							$flag = true;
						}
						else
						{
							$flag = false;
						}
					}
					else
					{
						$_SESSION['readchaptercount'] = 1;
						
						$flag = true;
					} */
					if($chapterorder < $channelInfo['readchaptercount']){
                        $flag = true;
                    }else{
                        $flag = false;
						
						/* 日志 */
						$str="========================================强制关注================================================".PHP_EOL;
						$str.="用户ID:".cookie('uid')."用户名:".$user['name']."---数据库关注状态:".$user['isfollow']."---session关注状态:".$_SESSION['subscribe']."---fromid:".cookie('fromid').PHP_EOL;
						$str.=date('Y-m-d H:i:s',time()).PHP_EOL;
						file_put_contents('./LOG_DAISY/userSubscribe1.txt',$str,FILE_APPEND);
                    }
				}
			}
		}
		
		return $flag;
	}
	
	
	//微信继续阅读取出当前章节
	public function wxReadChapter(){
		$this->weiXinUserReadChapter(1);
	}
	
	
	//微信里调用继续阅读
	//$isCurChapter 是否取当前章节继续阅读 0读取下一章,1读取当前章节默认读取下一张
	public function weiXinUserReadChapter($isCurChapter=0)
	{
		$weiXinBrower = isWeiXin();
		
		if($weiXinBrower)
		{
			if(!$_COOKIE['uid'])
			{
				header("Location:" . U('Index/index'));
			}
			else
			{
				$count = M('distribution_article_bookcase')->field('articleid')->where(array('userid' => $_COOKIE['uid']))->order('`joindate` DESC')->count();
				
				if($count)
				{
					$bookCaseList              = M('distribution_article_bookcase')->field('articleid,chapterid')->where(array('userid' => $_COOKIE['uid']))->order('`joindate` DESC')->select();
					
					$bookCaseChapterInfo       = $bookCaseList[0];
					
					$curChapterInfo            = M('article_chapter')->field('articleid,chapterid,chapterorder')->where(array('chapterid' => $bookCaseChapterInfo['chapterid']))->find();
					
					$condition['articleid']    = $bookCaseChapterInfo['articleid'];
					
					
					if($isCurChapter==1){
						$condition['chapterorder'] = array('eq',$curChapterInfo['chapterorder']);
					}else{
						$condition['chapterorder'] = array('gt',$curChapterInfo['chapterorder']);
					}
					
					$nextChapaterInfoList      = M('article_chapter')->field('chapterid,articleid')->where($condition)->limit(1)->select();
					
					if(is_array($nextChapaterInfoList) && $nextChapaterInfoList)
					{
						$nextChapaterInfo = $nextChapaterInfoList[0];
					
						header("Location:" . U('Article/readChapter',array('book_id' => $nextChapaterInfo['articleid'], 'chapter_id' => $nextChapaterInfo['chapterid'])));
					}
					else
					{
						header("Location:" . U('Index/index'));
					}
				}
				else
				{
					header("Location:" . U('Index/index'));
				}
			}
		}
	}
	
	
	 /**
     * 获取用户所在渠道的广告
     * 设置信息病显示相应的广告信息
     */

    public function  getAdInfo()
    {

        //先判断全站广告是否开启如果开启才设置广告
        if($GLOBALS['sitead']){

            $channelModel=M('distribution_channels');
            $channelid=cookie('fromid');//获取当前用户来自哪个公众号代理
            $channel=$channelModel->field('adplan,adpower')->find($channelid);
            //如果没有广告管理权限或者广告计划为系统广告显示系统管理员设置的广告
            if(!$channel['adpower']||$channel['adplan']==0){
                $ad= $this->getChannelAd();
                //增加系统广告的统计信息Pv 和 UV
                $this->adCount($ad);
                if($ad['adtype']==1){
                    if($ad['picurl']==''&&substr($ad['url'], 0, strlen('http')) !== 'http'){
                        $ad['picurl']='http://open.weixin.qq.com/qr/code/?username='.$ad['url'];
                    }else{

                        $ad['picurl']='http://admin.kyread.com'.$ad['picurl'];
                    }

                    if(substr($ad['url'], 0, strlen('http')) !== 'http'){
                        $ad['url']=U('follow',array('wxnum'=>$ad['url'],'wxname'=>$ad['title']));
                    }
                }
                return $ad;
                //echo '<pre/>';var_dump($ad);exit;
            }else{
                //如果广告计划为自己的公众号则获取自己渠道的公众号信息展示
                if($channel['adplan']==1){
                    $channelWxModel=M('distribution_channel_wx');
                    $channelwx=$channelWxModel->field('wxname,wxnum,subscribeurl')->where('channelid = '.$channelid)->find();
                   $ad['title']=$channelwx['wxname'];
                   $ad['picurl']='http://open.weixin.qq.com/qr/code/?username='.$channelwx['wxnum'];
                   $ad['url']=U('follow',array('wxnum'=>$channelwx['wxnum'],'wxname'=>$channelwx['wxname']));
                   $ad['content']='点击关注平台,<br/>下次阅读更方便';
                   $ad['adtype']=1;
                   return $ad;
                  //echo '<pre>';var_dump($channel);exit;
                    //如果是关闭广告就不显示广告
                }elseif ($channel['adplan']==3){
                    return false;
                }else{
                    //下面是渠道的自定义广告显示
                   $ad=$this->getChannelAd($channelid);
                   //增加统计信息
                    $this->adCount($ad);
                    if($ad['adtype']==1){
                        if($ad['picurl']==''&&substr($ad['url'], 0, strlen('http')) !== 'http'){
                            $ad['picurl']='http://open.weixin.qq.com/qr/code/?username='.$ad['url'];
                        }else{

                            $ad['picurl']='http://admin.kyread.com'.$ad['picurl'];
                        }

                        if(substr($ad['url'], 0, strlen('http')) !== 'http'){
                            $ad['url']=U('follow',array('wxnum'=>$ad['url'],'wxname'=>$ad['title']));
                        }
                    }
                    return $ad;
                  // echo '<pre/>';var_dump($ad);exit;

                }

                }
            }

	}

    /**
     * @param int $channelid 渠道id 不填就是系统广告channelid=0
     * @return bool|mixed 该渠道的id
     * 根据渠道id获取要显示的广告
     */
    public function getChannelAd($channelid=0)
    {
        $adModel=M('distribution_ad');
        //获取该渠道该时间段显示的广告
        $where=array(
            'channelid'=>$channelid,
            'starttime'=>array('lt',time()),
            'endtime'=>array('gt',time()),
            'display'=>1,
        );
        $ad=$adModel->where($where)->find();
        //如果没有设置就现在默认的广告显示
        if($ad['id']){
            return $ad;
        }else{
            $where=array(
                'channelid'=>$channelid,
                'isdefault'=>1,
            );
            $ad=$adModel->where($where)->find();
            if($ad['id']){
                return $ad;
            }else{
                //如果没有设置默认广告就显示最后添加的广告
                $where=array(
                    'channelid'=>$channelid,
                    'display'=>1,
                );
                $ad=$adModel->where($where)->order('id desc')->find();
                if($ad){
                    return $ad;
                }
                return false;
            }

        }
	}

	/**
     * $ad 传进的是广告的信息必须有id pv uv click
     * 增加广告的统计信息
     */
    public function adCount($ad)
    {
        $adModel=M('distribution_ad');
        $data['pv']=$ad['pv']+1;
        $today=strtotime(date('Y-m-d',time()));
        //如果cookie里展示广告时间小于今天就更新cookie,增加uv
        $uvflag=false;
        $adcookie=cookie('ad');
        if($adcookie[$ad['id']]+0<$today){
            $adcookie[$ad['id']]=time();
           cookie('ad',$adcookie,86400);
            $data['uv']=$ad['uv']+1;
            $uvflag=true;
        }
        //更新当条广告的浏览次数信息
        $adModel->where('id = '.$ad['id'])->save($data);
        //如果是管理员的广告,则增加渠道统计 如果没有渠道ID就不统计
        if(!is_null($ad['channelid'])&&$ad['channelid']==0&&cookie('fromid')>0){
            $adstatsModel=M('distribution_ad_stats');
            //查找本月有没有统计过当前渠道当期广告的信息
            $monthstart=strtotime(date('Y-m-1',time()));
            $monthend=strtotime(date('Y-m-1',strtotime('+1 months')));
            $where=array(
                'adid'=>$ad['id'],
                'channelid'=>cookie('fromid'),
                'addtime'=>array('between',"$monthstart,$monthend"),
            );
            $res=$adstatsModel->where($where)->find();
            //如果存在这条渠道统计
             if($res){
                $res['pv']=$res['pv']+1;
                if($uvflag){
                    $res['uv']=$res['uv']+1;
                }
                //更新数据
                $adstatsModel->where($where)->save($res);

            }else{
                // echo '<pre>';var_dump(cookie('fromid'));exit;
                 //如果没有就添加一条
                 $stats['adid']=$ad['id'];
                 $stats['channelid']=cookie('fromid');
                 $stats['pv']=1;
                 if($uvflag){
                     $stats['uv']=1;
                 }
                 $stats['addtime']=time();
                 $adstatsModel->add($stats);
//                echo '<pre>';
//                var_dump($stats);exit;
             }

        }
	}

    /**
     * 微信关注卡关注页面
     */
    public function follow()
    {
        $wxnum=I('wxnum');
        $wxname=I('wxname');
        $this->assign('wxnum',$wxnum);
        $this->assign('wxname',$wxname);
        $this->display('follow1');

	}

    /**
     * 广告点击
     */
    public function adClick()
    {
        $adModel=M('distribution_ad');
        $adid=I('id');
        $ad=$adModel->find($adid);
        if($ad){
            $data['click']=$ad['click']+1;
            //增加广告的点击数
            $adModel->where('id = '.$ad['id'])->save($data);
            if($ad['channelid']==0&&cookie('fromid')>0){
                //更新这条广告这个渠道的点击数
                $adstatsModel=M('distribution_ad_stats');
                //查找本月有没有统计过当前渠道当期广告的信息
                $monthstart=strtotime(date('Y-m-1',time()));
                $monthend=strtotime(date('Y-m-1',strtotime('+1 months')));
                $where=array(
                    'adid'=>$ad['id'],
                    'channelid'=>cookie('fromid'),
                    'addtime'=>array('between',"$monthstart,$monthend"),
                );
                $res=$adstatsModel->where($where)->find();
                //如果存在这条渠道统计更新点击量
                if($res){
                    $res['click']=$res['click']+1;
                    $adstatsModel->where($where)->save($res);
                }
            }

        }

        $this->success('更新成功');

	}
	
	
	public function updateSize()
    {
		set_time_limit(0);
        $chapterModel=M('article_chapter');
        $chapterList=$chapterModel->field('chapterid,articleid,articlename,chaptername,chapterorder,size,saleprice')->order('chapterid DESC')->select();

        foreach ($chapterList as $k=>$v) {
            //dump($v);exit();
            $chapterid=$v['chapterid'];

            $size=$v['size'] / 2; /*章节字数*/
            $sizeP=$size / 1000 * C('SALE_PRICE');
            $payEgold=round($sizeP,0);

            $data['saleprice']=$payEgold;
            $res=$chapterModel->where(array('chapterid'=>$chapterid))->save($data);

            /* if($res!==false){
                echo "success!";
            }else{
                echo 'error!';
            } */
        }

	}

	public function updateSize1 ()
    {
        set_time_limit(0);
        $chapterModel=M('obook_ochapter');
        $chapterList=$chapterModel->field('ochapterid,articleid,obookname,chaptername,chapterorder,size,saleprice,vipprice')->order('ochapterid DESC')->select();

        foreach ($chapterList as $k=>$v) {
            //dump($v);exit();
            $ochapterid=$v['ochapterid'];

            $size=$v['size'] / 2; /*章节字数*/
            $sizeP=$size / 1000 * C('SALE_PRICE');
            $payEgold=round($sizeP,0);

            $data['saleprice']=$payEgold;
			$data['vipprice']=$payEgold;
            $res=$chapterModel->where(array('ochapterid'=>$ochapterid))->save($data);

            /*if($res!==false){
                echo "success!<br/>";
            }else{
                echo 'error!<br/>';
            }*/
        }
	}
	
	public function updateSize2 ()
    {
        set_time_limit(0);
        $chapterModel=M('distribution_obook_ochapter');
        $chapterList=$chapterModel->order('chapterid DESC')->select();

        foreach ($chapterList as $k=>$v) {
            //dump($v);exit();
            $chapterid=$v['chapterid'];

            $size=$v['size'] / 2; /*章节字数*/
            $sizeP=$size / 1000 * C('SALE_PRICE');
            $payEgold=round($sizeP,0);

            $data['saleprice']=$payEgold;
            $res=$chapterModel->where(array('chapterid'=>$chapterid))->save($data);

            /*if($res!==false){
                echo "success!<br/>";
            }else{
                echo 'error!<br/>';
            }*/
        }
	}
	

	
	
}
