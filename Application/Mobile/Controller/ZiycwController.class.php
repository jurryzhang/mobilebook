<?php
/**
 * Created by PhpStorm.
 * User: muyi
 * Date: 2017/5/16 0016
 * Time: 下午 3:06
 * 最爱原创网对外接口
 */
namespace Mobile\Controller;
use Think\Controller;

class ZiycwController extends Controller
{
    /**
     * 获取书籍列表
     */
    public function booklist()
    {
      $token=$this->checkToken();
      $bookModel=D('Book');
      $bookIDs=$bookModel->getApiBookidList($token);
      if($bookIDs){
          $return_arr=array(
              "code"=>"001",
              "data"=>$bookIDs
          );
          $this->ajaxReturn($return_arr);
      }else{
          $this->dataError();
      }
    }


    /**
     * 获取书籍信息
     */

    public function bookinfo(){
        $this->checkBook();
        $bookModel=D('Book');
        $bookinfo=$bookModel->getBookinfo();
        if($bookinfo){
            $return_arr=array(
                "code"=>"001",
                "data"=>array(
                         "id"=> $bookinfo['articleid']+0,

                        "name" =>$bookinfo['articlename'],//小说名

                        "author"=>$bookinfo['author'],//作者名

                        "brief"=> $bookinfo['intro'],//小说简介

                        "cover"=>$bookinfo['cover'],//封面地址 带http://的网络可访问地址

                        "full"=>$bookinfo['fullflag']+0,//是否完本 0连载1完本

                        "category"=>$bookinfo['sortid'],//对应的分类ID，下翻查看

                        "words"=>$bookinfo['size'],//小说总字数

                        "dtime"=>date("Y-m-d H:i:s",$bookinfo['lastupdate']),//最后修改日期

                        "ptime"=>date("Y-m-d H:i:s",$bookinfo['postdate']),//发布日期
                )
            );
            $this->ajaxReturn($return_arr);
        }else{
            $this->dataError();
        }
    }


    /**
     * 章节列表信息
     */
    public function chapterlist()
    {
        $this->checkBook();
        $bookModel=D('Book');
        $chapters=$bookModel->getChapterIdlist();
        if($chapters){
            $return_arr=array(
                "code"=>"001",
                "data"=>$chapters
            );
            $this->ajaxReturn($return_arr);
        }else{
            $this->dataError();
        }
        
    }

    /**
     * 获取章节详情
     */
    public function chapterinfo()
    {
        $this->checkBook();
        $bookModel=D('Book');
        $chapter=$bookModel->getChapterinfo();
        if($chapter){
            $return_arr=array(
                "code"=>"001",
                "data"=>array(
                    "id"=> $chapter['chapterid']+0,

                    "title" =>$chapter['chaptername'],//章节名

                    "sell"=>$chapter['isvip'],//是否VIP

                    "words"=> $chapter['size'],//章节字数

                    "content"=>$chapter['content'],//章节内容

                    "ptime"=>date("Y-m-d H:i:s",$chapter['postdate']),//发布日期
                )
            );
            $this->ajaxReturn($return_arr);
        }else{
            $this->dataError();
        }
        
    }

    /**
     * 获取分类名
     */

    public function categoryName()
    {
        $this->checktoken();
       $bookModel=D('Book');
       $sortid=I('categoryid');
       $sortname=$bookModel->getSortName($sortid);
        if($sortname){
            $return_arr=array(
                "code"=>"001",
                "data"=>array('category'=>$sortname)
            );
            $this->ajaxReturn($return_arr);
        }else{
            $this->dataError();
        }
    }

    /**
     * 获取所有分类
     */
    public function category()
    {
        $this->checktoken();
        $bookmodel=D('Book');
        $category=$bookmodel->getsort();
        if($category){
            $return_arr=array(
                "code"=>"001",
                "data"=>$category
            );
            $this->ajaxReturn($return_arr);
        }else{
            $this->dataError();
        }
    }

    /**
     * @return 检查是否有权限爬这本书
     */

    private function checkBook()
    {
        $token=$this->checktoken();
        $bookid=I('bookid')+0;
        $bookModel=D('Book');
        $bookIDs=$bookModel->getApiBookidList($token);
        if(in_array($bookid,$bookIDs)){
            return true;
        }else{
            $return_arr=array(
                "code"=>"004",
                "data"=>array("error"=>"您没有权限获取这个本书的信息!")
            );
            $this->ajaxReturn($return_arr);
        }

    }



    /**
     * @return mixed
     * 检查密钥是否合法
     */
    private function checkToken()
    {
        //获取密钥
        if(I('token')){
            $token=I('token');
        }elseif (I('sign')){
            $token=I('sign');
        }else{
            $return_arr=array(
                "code"=>"000",
                "data"=>array("error"=>"没有密钥,请联系管理员获取!")
            );
            $this->ajaxReturn($return_arr);
        }

        //核对密钥
        $count=M('article_api')->where(array('token'=>$token))->count();
        if ($count){
            return $token;
        }else{
            $return_arr=array(
                "code"=>"002",
                "data"=>array("error"=>"您的密钥有误,请核对后重试!")
            );
            $this->ajaxReturn($return_arr);
        }
    }


    /**
     * 数据库方面的错误
     */
    private function dataError()
    {
        $return_arr=array(
            "code"=>"003",
            "data"=>array("error"=>"服务器数据错误")
        );
        $this->ajaxReturn($return_arr);
        
    }

}