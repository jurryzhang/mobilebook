<?php
/**
 * Created by PhpStorm.
 * User: burn
 * Date: 2017/3/22
 * Time: 下午9:08
 */

namespace Mobile\Logic;
use Think\Model\RelationModel;

class ArticleLogic  extends RelationModel
{
	/**
	 * 获取用户信息
	 *
	 * @return mixed
	 */
	public function getUserInfo()
	{
		$userInfo = M('distribution_system_users')->where(array('uid' => $_COOKIE['uid'],'uname' => $_COOKIE['uname']))->find();
		
		return $userInfo;
	}
	
	/**
	 * 获取用户的购买信息
	 * @param $articleID
	 * @return array
	 */
	public function getUserOChapter($articleID)
	{
		$orderStr = '`ochapterid` ASC';
		
		$userBuyInfo = M('distribution_obook_obuyinfo')->where(array('articleid' => $articleID,'userid' => $_COOKIE['uid']))->order($orderStr)->select();
		
		$result = array();
		
		foreach($userBuyInfo as $key => $value)
		{
			$result[$value['ochapterid']] = $value['buyprice'];
		}
		
		return $result;
	}
	
	/**
	 * 查看用户是否购买该章节
	 *
	 * @param $articleID
	 * @param $chapterID
	 * @return bool
	 */
	public function getUserOChapterByChapterID($articleID,$chapterID)
	{
		$userBuyFlag = M('distribution_obook_obuyinfo')->where(array('articleid' => $articleID,'userid' => $_COOKIE['uid'],'ochapterid' => $chapterID))->count();
		
		if($userBuyFlag)
		{
			$result = true;
		}
		else
		{
			$result = false;
		}
		
		return $result;
	}
	
	/**
	 * 获取本书当前章节以后的章节列表
	 *
	 * @param $articleID
	 * @param $chapterOrder
	 * @return mixed
	 */
	public function getChapterListAfterChapterID($articleID,$chapterOrder)
	{
		$condition['articleid']    = $articleID;
		
		$condition['chapterorder'] = array('egt',$chapterOrder);
		
		$condition['_logic']       = 'AND';
		
		$orderStr = '`chapterorder` ASC';
		
		$chapterList = M('article_chapter')->where($condition)->field('articlename,chapterid,chaptername,posterid,saleprice')->order($orderStr)->select();
		
		foreach($chapterList as $key =>$value)
		{
			$chapterList[$key]['chaptername'] = filterStr($value['chaptername']);
		}
		
		return $chapterList;
	}
	
	/**
	 * 计算待购买VIP章节的价格
	 *
	 * @param $id           0：购买当前章；1：购买30章；2：购买100章；3：购买200章
	 * @param $num          购买数量
	 * @param $chapterList  vip章节list
	 */
	public function computeVipChapterPrice($id,$num,$chapterList,$userOChapterList)
	{
		$realBuyNum = $num;//实际购买数量
		
		//如果购买数量小于待购买VIP章节的数量
		if($num <= count($chapterList))
		{
			$realBuyNum = $num;
		}
		else//如果购买数量大于待购买VIP章节的数量
		{
			if($id == 3)
			{
				if(count($chapterList) == 1)
				{
					$result['show']     = 0;
					
					$result['buynum']   = $num;
					
					$result['price']    = 0;
					
					$result['authorid'] = 0;
					
					return $result;
				}
				else
				{
					$realBuyNum = count($chapterList);
				}
			}
			else
			{
				$result['show']     = 0;
				
				$result['buynum']   = $num;
				
				$result['price']    = 0;
				
				$result['authorid'] = 0;
				
				return $result;
			}
		}
		
		$totalPrice = 0;
		
		for($i = 0; $i < $realBuyNum; $i++)
		{
			if(!$userOChapterList[$chapterList[$i]['chapterid']])
			{
				$totalPrice += intval($chapterList[$i]['saleprice']);
				
				$buyList[$chapterList[$i]['chapterid']]  = $chapterList[$i]['chaptername'];
				
				$chapterSaleList[$chapterList[$i]['chapterid']]  = $chapterList[$i]['saleprice'];
			}
		}
		
		$result['show']                = 1;
		
		$result['buynum']              = $realBuyNum;
		
		$result['totalprice']          = $totalPrice;
		
		$result['authorid']            = $chapterList[0]['authorid'];
		
		$result['buychapterlist']      = $buyList ? $buyList : array_map();
		
		$result['buychapterpricelist'] = $chapterSaleList ? $chapterSaleList : array_map();
		
		
		return $result;
	}
	
	/**
	 * 获取用户将要购买该书的章节信息
	 *
	 * @param $articleID
	 * @param $chapterOrder
	 * @return array
	 */
	public function showBuyInfo($articleID,$chapterOrder)
	{
		$chapterList  = $this->getChapterListAfterChapterID($articleID,$chapterOrder);
		
		$userOChapterList = $this->getUserOChapter($articleID);//查找用户购买该书的vip章节信息
		
		$buyList[] = $this->computeVipChapterPrice(0,1,$chapterList,$userOChapterList);
		
		$buyList[] = $this->computeVipChapterPrice(1,30,$chapterList,$userOChapterList);
		
		$buyList[] = $this->computeVipChapterPrice(2,100,$chapterList,$userOChapterList);
		
		$buyList[] = $this->computeVipChapterPrice(3,200,$chapterList,$userOChapterList);
		
		return $buyList;
	}
	
	/**
	 * 插入和更新jieqi_distribution_obook_obook，便于后期统计书籍销售情况
	 *
	 * @param $articleID
	 */
	public function insertObooKInfo($articleID)
	{
		$this->insertObook($articleID);
		
		$this->insertOchapter($articleID);
	}
	
	/**
	 * 插入和更新jieqi_distribution_obook_obook，便于后期统计书籍销售情况
	 *
	 * @param $articleID
	 */
	private function insertObook($articleID)
	{
		$obookInfo = M('distribution_obook_obook')->where(array('articleid' => $articleID))->find();
		
		if(!$obookInfo)
		{
			$sumEgold = 0;
			
			$articleInfo = M('article_article')->where(array('articleid' => $articleID))->find();
			
			$data['articleid']   = $articleInfo['articleid'];
			$data['articlename'] = $articleInfo['articlename'];
			$data['intro']       = $articleInfo['intro'];
			$data['size']        = $articleInfo['size'];
			$data['author']      = $articleInfo['author'];
			$data['sumegold']    = $sumEgold;
			$data['sumtip']      = $sumEgold;
			$data['sumhurry']    = $sumEgold;
			$data['sumgift']     = $sumEgold;
			$data['flower']      = $sumEgold;
			$data['redrose']     = $sumEgold;
			$data['yellowrose']  = $sumEgold;
			$data['bluerose']    = $sumEgold;
			$data['whiterose']   = $sumEgold;
			$data['blackrose']   = $sumEgold;
			$data['greenrose']   = $sumEgold;
			$data['sumsale']     = $sumEgold;
			
			M('distribution_obook_obook')->add($data);
		}
	}
	
	/**
	 * 插入和更新jieqi_distribution_obook_obook，便于后期统计章节销售情况
	 *
	 * @param $articleID
	 */
	private function insertOchapter($articleID)
	{
		$articleInfo  = M('article_article')->where(array('articleid' => $articleID))->find();
		
		$ochapterList = M('distribution_obook_ochapter')->where(array('articleid' => $articleID))->select();
		
		$lastOchapter = M('distribution_obook_ochapter')->where(array('articleid' => $articleID,'chapterid' => $articleInfo['vipchapterid']))->find();
		
		if(count($ochapterList) != $articleInfo['vipchapters'] || !$lastOchapter)
		{
			$condition['articleid'] = $articleID;
			
			$condition['display']   = 0;
			
			$condition['isvip']     = 1;
			
			$chapterList  = M('article_chapter')->where($condition)->select();
			
			foreach($chapterList as $chapterInfo)
			{
				$ochapterInfo = M('distribution_obook_ochapter')->where(array('chapterid' => $chapterInfo['chapterid']))->find();
				
				if(!$ochapterInfo)
				{
					$sumEgold = 0;
					
					$chapterInfo = M('article_chapter')->where(array('chapterid' => $chapterInfo['chapterid']))->find();
					
					$data['chapterid']    = $chapterInfo['chapterid'];
					$data['articleid']    = $chapterInfo['articleid'];
					$data['articlename']  = $chapterInfo['articlename'];
					$data['chaptername']  = $chapterInfo['chaptername'];
					$data['chapterorder'] = $chapterInfo['chapterorder'];
					$data['size']         = $chapterInfo['size'];
					$data['saleprice']    = $chapterInfo['saleprice'];
					$data['sumegold']     = $sumEgold;
					
					M('distribution_obook_ochapter')->add($data);
				}
			}
		}
	}
	
	/**
	 * 更新jieqi_distribution_obook_ochapter
	 *
	 * @param $chapterID
	 * @param $data
	 */
	public function updateOchapterInfo($chapterID,$data)
	{
		
	}
	
	public function insertBuyInfo()
	{
		
	}
}