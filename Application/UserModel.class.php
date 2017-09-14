<?php
/**
 * Created by PhpStorm.
 * User: muyi
 * Date: 2017/7/4
 * Time: 下午 4:14
 * 用户模型
 */

namespace Mobile\Model;
use Think\Model;
class UserModel extends Model
{
    protected $tableName='distribution_system_users';

    /**
     * @param $uname用户openid
     * 给用户发送签到信息
     */
    public function sendSigninfo($uname)
    {
        //获取当前用户的公众号来源
        $fromid= cookie('fromid');
        $channelWxModel = M('distribution_channel_wx');
        $wxinfo = $channelWxModel->where(array('channelid' =>$fromid))->find();
        //拼装发送给用户的信息
        $contentstr="本日签到成功，赠送50阅读币,请明日继续签到哦~\n\n<a href='http://wap.kyread.com/Article/wxReadChapter/cid/".$fromid.".html'>☞点我继续上次阅读</a>\n\n历史阅读记录：\n\n";
        //取出阅读记录
        $bookCaseList = M('distribution_article_bookcase')->where(array('userid' => $_COOKIE['uid']))->order('`joindate` DESC')->limit(5)->select();
        foreach ($bookCaseList as $k=>$v){
            $contentstr.="<a  href='http://wap.kyread.com/Article/readChapterFromBookCase/bookcase_id/".$v['caseid']."/cid/".$fromid.".html'>☞".$v['articlename']."</a>\n\n";
        }

        $contentstr=trim($contentstr);

        $info='{
                    "touser":"'.$uname.'",
                    "msgtype":"text",
                    "text":
                    {
                         "content":"'.$contentstr.'"
                    }
                }';

        $sendUrl='https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$wxinfo['atoken'];

        //给用户发送信息
        request($sendUrl, true, "post", $info);
    }

}