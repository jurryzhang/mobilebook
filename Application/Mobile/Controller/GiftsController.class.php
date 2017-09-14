<?php
/**
 * Created by PhpStorm.
 * User: HIAPAD
 * Date: 2017/6/22
 * Time: 16:08
 */
namespace Mobile\Controller;
header('content-type:text/html;charset=utf-8');
use Mobile\Logic\ArticleLogic;

use Think\Page;

class GiftsController extends MobileBaseController{
    public function index(){
        $giftsModel=M('distribution_gifts');
        $userModel=M('distribution_system_users');
        $weiXinBrower = isWeiXin();
        //if($weiXinBrower){
            if(IS_AJAX){
                $uid=I('uid');
                $username=I('username');
                $articleid=I('articleid');
                $articlename=I('articlename');
                $chapterid=I('chapterid');
                $giftstype=I('giftstype');
                $count=I('count');
                $needegold=I('needegold');

                $userInfo=$userModel->where(array('uid'=>$uid))->find();
		        if($username==''){
                    $username=$userInfo['name'];
                }
                if($articlename==''){
                    $articleInfo=M('article_article')->field('articleid,articlename')->where(array('articleid'=>$articleid))->find();
                    $articlename=$articleInfo['articlename'];
                }
		

				/*if($useregold<$needegold){
					$this->ajaxReturn(array('status'=>0,'message'=>'余额不足，请充值'));
				}*/
				if($userInfo['egold']<$needegold){
					$this->ajaxReturn(array('status'=>-1,'message'=>'余额不足，请充值'));
				}else{
                    $data['userid']=$uid;
                    $data['username']=$username;
                    $data['articleid']=$articleid;
                    $data['articlename']=$articlename;
                    $data['chapterid']=$chapterid;
                    $data['giftstype']=$giftstype;
                    $data['amount']=$count;
                    $data['needegold']=$needegold;
                    $data['givetime']=time();
                    $insertid=$giftsModel->add($data);
                    if($insertid){
		
                        /*添加评论内容*/
                        $dataC['userid']=$uid;
                        $dataC['username']=$username;
                        $dataC['articleid']=$articleid;
                        $dataC['articlename']=$articlename;
                        $dataC['content']=$userInfo['name'].'打赏'.$needegold.'小说币，感谢您的努力，希望后续更加精彩';
						$dataC['ischeck']=1;
						$data['iscomment']=0;
						$dataC['reply']='';
                        $dataC['addtime']=time();
                        M('distribution_comment')->add($dataC);

                        /*添加打赏记录时更新obook表里sumgift字段*/
                        $obookInfo=$obookModel->field('sumgift,sumcomment')->where(array('articleid'=>$articleid))->find();
                        $dataObook['sumgift']=$obookInfo['sumgift']+$count;
                        $dataObook['sumcomment']=$obookInfo['sumcomment']+1;
                        $obookModel->where(array('articleid'=>$articleid))->save($dataObook);

                        $egold=$userInfo['egold']-$needegold;
                        $datau['egold']=$egold;
                        $res=$userModel->where(array('uid'=>$uid))->save($datau);
                        if($res!==false){
                            $this->ajaxReturn(array('status'=>1,'message'=>'操作成功','action'=>'dashang'));
                        }else{
                            $this->ajaxReturn(array('status'=>0,'message'=>'操作失败','action'=>'dashang'));
                        }
                    }else{
                        $this->ajaxReturn(array('status'=>0,'message'=>'赠送失败','action'=>'dashang'));
                    }
                }
            }else{
                $this->ajaxReturn(array('status'=>0,'message'=>'非法操作','action'=>'dashang'));
            }
        //}

    }

    /*更新obook_obook表 打赏催更评论 值*/
    public function saveObookSum()
    {
        $ArticleModel=D('Book');
        $BookModel=M('distribution_obook_obook');
        $articleIDs=$BookModel->field('articleid')->select();
        foreach ($articleIDs as $k=>$v) {
            $articleID=$v['articleid'];
            $userAction=$ArticleModel->getUserAction($articleID);
            $data['sumcomment']=$userAction['commentCount'];
            $data['sumhurry']=$userAction['urgeEgold'];
            $data['sumgift']=$userAction['giftsCount'];
            $res=$BookModel->where(array('articleid'=>$articleID))->save($data);
            if($res!==false){
                echo $articleID."YES!<br/>";
            }else{
                echo $articleID."NO!<br/>";
            }
        }

    }
    public function urgeUpdate(){
        $uegrModel=M('distribution_user_urgeupdate');
        $userModel=M('distribution_system_users');
        $weiXinBrower = isWeiXin();
        //if($weiXinBrower){
            if(IS_AJAX){
                $uid=I('uid');
                $username=I('username');
                $articleid=I('articleid');
                $articlename=I('articlename');
                $chapterid=I('chapterid');
                $wordsize=I('wordsize');
                $needegold=I('needegold');

                $userInfo=$userModel->field('name,egold')->where(array('uid'=>$uid))->find();

		if($username==''){
                    $username=$userInfo['name'];
                }
                if($articlename==''){
                    $articleInfo=M('article_article')->field('articleid,articlename')->where(array('articleid'=>$articleid))->find();
                    $articlename=$articleInfo['articlename'];
                }
				/*if($useregold<$needegold){
					$this->ajaxReturn(array('status'=>0,'message'=>'余额不足，请充值'));
					header("Location:{:U('User/buyEgold')}");
				}*/
				if($userInfo['egold']<$needegold){
					$this->ajaxReturn(array('status'=>-1,'message'=>'余额不足，请充值'));
				}else{
                    $data['userid']=$uid;
                    $data['username']=$username;
                    $data['articleid']=$articleid;
                    $data['articlename']=$articlename;
                    $data['chapterid']=$chapterid;
                    $data['wordsize']=$wordsize;
                    $data['needegold']=$needegold;
                    $data['givetime']=time();

                    $insertid=$uegrModel->add($data);
                    if($insertid){
                        /*添加评论内容*/
                        $dataC['userid']=$uid;
                        $dataC['username']=$username;
                        $dataC['articleid']=$articleid;
                        $dataC['articlename']=$articlename;
                        $dataC['content']=$userInfo['name'].'支付'.$needegold.'小说币催促文章尽快更新，要求更新'.$wordsize.'字以上';
						$dataC['ischeck']=1;
						$dataC['reply']='';
						$data['iscomment']=0;
                        $dataC['addtime']=time();
                        M('distribution_comment')->add($dataC);

                        /*添加打赏记录时更新obook表里sumhurry字段*/
                        $obookInfo=$obookModel->field('sumhurry,sumcomment')->where(array('articleid'=>$articleid))->find();
                        $dataObook['sumhurry']=$obookInfo['sumhurry']+$needegold;
                        $dataObook['sumcomment']=$obookInfo['sumcomment']+1;
                        $obookModel->where(array('articleid'=>$articleid))->save($dataObook);

                        $egold=$userInfo['egold']-$needegold;
                        $datau['egold']=$egold;
                        $res=$userModel->where(array('uid'=>$uid))->save($datau);
                        if($res!==false){
                            $this->ajaxReturn(array('status'=>1,'message'=>'操作成功','action'=>'cuigeng'));
                        }else{
                            $this->ajaxReturn(array('status'=>0,'message'=>'操作失败','action'=>'cuigeng'));
                        }
                    }else{
                        $this->ajaxReturn(array('status'=>0,'message'=>'催更失败','action'=>'cuigeng'));
                    }
                }

            }else{
                $this->ajaxReturn(array('status'=>0,'message'=>'非法操作','action'=>'cuigeng'));
            } 
       // }
    }
    /*
     * 评论列表
     * */
    public function commentList(){
        $articleid=I('articleid');
        $commentmodel=M('distribution_comment');

		$commenWhere='articleid='.$articleid.' AND '.'(ischeck=1 OR userid='.cookie('uid').')';
        /* 分页 */
        $totalRow = $commentmodel->where(/* array('articleid'=>$articleid) */$commenWhere)->count();
        $pageSize=15;
        $page = new Page($totalRow,$pageSize);
        $page->rollPage   = 4;// 分页栏每页显示的页数
        $page->setConfig('prev', '<<');
        $page->setConfig('next', '>>');
        $page->setConfig('first', '首页');
        $page->setConfig('last', '尾页');
        $pageShow= $page->show();

        $commentList=$commentmodel
            ->field('userid,username,content,addtime,reply')
            ->where($commenWhere)
            ->order('addtime DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        foreach($commentList as $k=>$v){
            $userid=$v['userid'];
            $userinfo=M('distribution_system_users')
                ->field('uid,name,faceImg')
                ->where(array('uid'=>$userid))
                ->find();
            $commentList[$k]['faceImg']=$userinfo['faceImg'];
            /* $commentList[$k]['username']=$userinfo['name']; */
        }
        $this->assign('articleid',$articleid);
        $this->assign('commentList',$commentList);
        $this->assign('page',$pageShow);
        $this->display();
    }
    /*
     * 添加评论
     * */
    public function comment(){
        $uid=cookie('uid');
        $commentModel=M('distribution_comment');
        $articleid=I('articleid');
		$chapterid=I('chapterid');
		$isredBtn=I('isredBtn');
		
		$articlename=M('article_article')->field('articlename')->where(array('articleid'=>$articleid))->find();
        $username=M('distribution_system_users')->field('name')->where(array('uid'=>$uid))->find();
		
        if(IS_AJAX){
            $content=I('content');

            $data['userid']=$uid;
            $data['username']=$username['name'];
            $data['articleid']=$articleid;
            $data['articlename']=$articlename['articlename'];
            $data['content']=$content;			
            $data['addtime']=time();
			$data['ischeck']=0;
			$data['reply']='';
			$data['iscomment']=1;
            $insertid=$commentModel->add($data);
            if($insertid){
                $this->ajaxReturn(array('status'=>1,'msg'=>'发布成功'));
            }else{
                $this->ajaxReturn(array('status'=>0,'msg'=>'发布失败'));
            }
        }
        $this->assign('articleid',$articleid);
		$this->assign('chapterid',$chapterid);
		$this->assign('isredBtn',$isredBtn);
        $this->display();
    }
	

	/* 测试 */
	public function saveCommentdata(){
        $data=M('distribution_comment')->select();
        foreach ($data as $k=>$v) {
            $articleid=$v['articleid'];
            $uid=$v['userid'];

            $articlename=M('article_article')->field('articlename')->where(array('articleid'=>$articleid))->find();
            $username=M('distribution_system_users')->field('name')->where(array('uid'=>$uid))->find();

            $dataA['articlename']=$articlename['articlename'];
            $dataA['username']=$username['name'];

            $res=M('distribution_comment')->where(array('articleid'=>$articleid,'userid'=>$uid))->save($dataA);
        }
        if($res!==false){
            echo "YES!!!saveCommentdata";
        }
    }

    public function saveGiftsData()
    {
        $data=M('distribution_gifts')->select();
        
        foreach ($data as $k=>$v) {
            $articleid=$v['articleid'];
            $uid=$v['userid'];

            $articlename=M('article_article')->field('articlename')->where(array('articleid'=>$articleid))->find();
            $username=M('distribution_system_users')->field('name')->where(array('uid'=>$uid))->find();

            $dataA['articlename']=$articlename['articlename'];
            $dataA['username']=$username['name'];

            $res=M('distribution_gifts')->where(array('articleid'=>$articleid,'userid'=>$uid))->save($dataA);
            
        }
        if($res!==false){
            echo "YES!!!saveGiftsData";
        }
    }

    public function saveUrgeData()
    {
        $data=M('distribution_user_urgeupdate')->select();
        foreach ($data as $k=>$v) {
            $articleid=$v['articleid'];
            $uid=$v['userid'];

            $articlename=M('article_article')->field('articlename')->where(array('articleid'=>$articleid))->find();
            $username=M('distribution_system_users')->field('name')->where(array('uid'=>$uid))->find();

            $dataA['articlename']=$articlename['articlename'];
            $dataA['username']=$username['name'];
			

            $res=M('distribution_user_urgeupdate')->where(array('articleid'=>$articleid,'userid'=>$uid))->save($dataA);
        }
        if($res!==false){
            echo "YES!!!saveUrgeData";
        }
    }

}
