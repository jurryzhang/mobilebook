<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/16 0016
 * Time: 下午 3:16
 * 书籍模型
 */

namespace Mobile\Model;
use Think\Model;
class BookModel extends Model
{
    protected $tableName='article_article';

    /**
     * @param $token
     * @return array|
     * 获取API中的书籍
     */
    public function getApiBookidList($token)
    {
        $apiModel=M('article_api');
        $data=$apiModel->field('bookIDs')->where(array('token'=>$token))->select();
        if($data){
            $bookids=explode('|',$data[0]['bookIDs']) ;
            foreach ($bookids as $k=>$v){
                $bookids[$k]=intval($v);
            }
        }else{
            $this->error=$apiModel->getError();
            return false;
        }
        return $bookids;
    }

    /**
     * @return bool|mixed
     * 获取书籍信息
     */
    public function getBookinfo()
    {
        $bookid=I('bookid');
        $bookinfo=$this->find($bookid);
        if($bookinfo){
            $bookinfo['cover']=getBookCoverUrl($bookid);
            $bookinfo['size']=(string)(round($bookinfo['size']/2));
            return $bookinfo;
        }else{
            return false;
        }
    }

    /*
     * 获取章节列表
     */
    public function getChapterlist()
    {
        $chapterModel=M('article_chapter');
        $where=array(
            'articleid'=>I('bookid'),
            'chaptertype'=>0
        );
        $chapters=$chapterModel->where($where)->order('chapterorder asc')->select();
        if($chapters){
            return $chapters;
        }else{
            $this->error=$chapterModel->getError();
            return false;
        }

    }

    /**
     * 获取章节列表的ID
     */

    public function getChapterIdlist()
    {
        $chapterModel=M('article_chapter');
        $where=array(
                'articleid'=>I('bookid'),
                'chaptertype'=>0
             );
        $chapterids=$chapterModel->field('chapterid')->where($where)->order('chapterorder asc')->select();
        if($chapterids){
            $tempids=array();
            foreach ($chapterids as $k=>$v){
                $tempids[]=$v['chapterid']+0;
            }
            $chapterids=$tempids;
            return $chapterids;
        }else{
            $this->error=$chapterModel->getError();
            return false;
        }

    }

    /*
     * 获取章节信息
     */
    public function getChapterinfo()
    {
        $chapterModel=M('article_chapter');
        $where=array(
            'articleid'=>I('bookid'),
            'chapterid'=>I('chapterid')
        );
        $chapter=$chapterModel->where($where)->order('chapterorder asc')->select();
        if($chapter){
            $chapter=$chapter[0];
            $chapter['content']=$this->getContent();
            $chapter['size']=(string)(round($chapter['size']/2));
            if($chapter['content']){
                return $chapter;
            }else{
                return false;
            }

        }else{
            $this->error=$chapterModel->getError();
            return false;
        }

    }

    /**
     * 获取章节内容
     */

    public function getContent()
    {
        $contentModel = M('obook_ocontent');
        $articleID = I('bookid');
        $chapterID = I('chapterid');
        $filePath = C('TXT_FILE_DIR') . '/' . floor($articleID / 1000) . '/' . $articleID . '/' . $chapterID . ".txt";
        $flag = file_exists($filePath);
        if ($flag) {
            $content = file_get_contents($filePath, true);
            $content = mb_convert_encoding($content, 'UTF-8', "GBK");
            return $content;
        } else {
            $tmp = $contentModel->where(array('ochapterid' => $chapterID))->find();
            if ($tmp) {
                $content = $tmp['ocontent'];
                return $content;
            } else {
                $this->error = $contentModel->getError();
                return false;
            }

        }
    }

//获取章节语音文件

    public function getChapterAudio($articleid,$chapterid){
        $audiodir = C('AUDIO_FILE_PATH') . '/' . floor($articleid / 1000) . '/' . $articleid. '/'.$chapterid.'/';
        $audiopath = '/audio/'.floor($articleid / 1000) . '/' . $articleid. '/'.$chapterid .'/'.$chapterid. "-1.mp3";
        if(!is_dir($audiodir)){

            mkdir($audiodir,0777,true);
        }
        $filePath = $audiodir . $chapterid . ".mp3";
        //echo $filePath;exit;
        $flag = file_exists($filePath);
       //echo $flag;die;
        $data=array();
        if($flag)
        {
            $filecount = $this->countFile($audiodir);
            $data['countfile'] = $filecount;
            $data['data'] = $audiopath;
            return  $data ;
        }
        else
        {
            $txtpath = C('TXT_FILE_PATH') . '/' . floor($articleid / 1000) . '/' . $articleid. '/' . $chapterid . ".txt";
            $content = file_get_contents($txtpath,true);

            $content = mb_convert_encoding($content,'UTF-8',"GBK");

            //var_dump($txtpath);exit;
            $contentLen = mb_strlen($content);
            //echo $contentLen;exit;
            $clen = ceil($contentLen/(1024*3));
            //echo $clen;exit;
            $s=1;
            $p=0;
            $k = $clen+1;
            $speechApi = new \Baidu\AipSpeech(C('BD_APP_ID'),C('BD_API_KEY'),C('BD_SECRET_KEY'));
            while($s<$k){
                $content = mb_substr($content, $p, $s*1024, 'UTF-8');
                
                //var_dump($speechApi);exit;
                $res = $speechApi->synthesis($content, 'zh', 1, array(
                    'vol' => 5,
                ));
                $filePath =  $audiodir . $chapterid . "-".$s.".mp3"; 
                if(!is_array($res)){
                    file_put_contents($filePath, $res);
                }

                $p=$s*1024;
                $s++;

            }
            
             //$content = str_replace("\r\n\r\n","",$content);
             //$content = str_replace("\n\n\n\n","",$content);

            //$content = str_replace("\r\n","",$content);

            //$content = str_replace("\n\n","",$content);
//echo $content;exit;

            
            $filecount = $this->countFile($audiodir);
            $data['countfile'] = $filecount;
            $data['data'] = $audiopath;
            return  $data ;
        }

        
    }

    /******计算目录下文件个数*******/

    public function countFile($dir){
        $count=0;
        if(is_dir($dir)&&file_exists($dir)){ 
            $ob=scandir($dir); 
            foreach($ob as $file){ 
                if($file=="."||$file==".."){ 
                    continue; 
                } 
                $file=$dir."/".$file; 
                if(is_file($file)){ 
                    $count++; 
                }elseif(is_dir($file)){ 
                    FileCount($file); 
                } 
            } 
        }
        return $count;  
    }
        /**
         * 获取分类根据分类获取分类名
         *
         */

        public function getSortName($sortID)
    {
        $filePath = C('DIR_PATH') . C('SECONDE_SORT_FILE_PATH');

        include $filePath;

        if(isset($jieqiSort['article']) && $jieqiSort['article'])
        {
            $sortName = $jieqiSort['article'][$sortID]['caption'];
        }

        $sortName = iconv('GBK','UTF-8',$sortName);

        return $sortName;
    }


    /**
     * 获取所有分类和分类ID
     */
    public function getSort()
    {

        $filePath = C('DIR_PATH') . C('SECONDE_SORT_FILE_PATH');

        include $filePath;

        if(isset($jieqiSort['article']) && $jieqiSort['article'])
        {
            $sort=array();
            foreach ($jieqiSort['article'] as $k=>$v){
                $sort[$k]['categoryid']=$k;
                $sort[$k]['categoryname']=iconv('GBK','UTF-8',$v['caption']);
            }
            return $sort;
        }else{
            $this->error="分类获取失败";
            return false;
        }

    }
	/*
     * 用户打赏、催更、评论
     * */
    public function getUserAction($articleid){
        $giftsModel=M('distribution_gifts');
        $urgeModel=M('distribution_user_urgeupdate');
		$commentModel=M('distribution_comment'); 
        /*打赏数量*/
        $giftsList=$giftsModel->where(array('articleid'=>$articleid))->select();
        $giftsCount=0;
        foreach ($giftsList as $key=>$val) {
            $giftsCount+=$val['amount'];
        }
        /*催更书币*/
        $urgeList=$urgeModel->where(array('articleid'=>$articleid))->select();
        $urgeEgold=0;
        foreach ($urgeList as $k=>$v) {
            $urgeEgold+=$v['needegold'];
        }
		/*评论数量*/
        /* $commentCount=$commentModel->where(array('articleid'=>$articleid))->count(); */
		$commenWhere['articleid'] = $articleid;
        $commenWhere['ischeck']=1;
        $commentCount = $commentModel->where($commenWhere)->count();

        $userAction['giftsCount']=$giftsCount;
        $userAction['urgeEgold']=$urgeEgold;
        $userAction['commentCount']=$commentCount;
        
        return $userAction;
    }




}