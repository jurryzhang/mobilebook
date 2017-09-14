<?php
/*
**
* creat by muyi 2017/05/09
*/
namespace Mobile\Model;
use Think\Model;

class WechatModel extends Model{
    protected $_appid;
    protected $_appsecret;
    protected $_token;
    public $_channelid;
	protected $tableName='distribution_system_users';

    private $tpl = array(
        //发送文本消息模板
        'text' => '	<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>',

        //发送图片消息模板
        'image' => '<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[image]]></MsgType>
							<Image>
							<MediaId><![CDATA[%s]]></MediaId>
							</Image>
							</xml>',
        //发送图文消息模板
        'list' => 	'<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>%s</ArticleCount>
							<Articles>
							%s
							</Articles>
							</xml>',
        'item' => 	'<item>
							<Title><![CDATA[%s]]></Title> 
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
							</item>',
        //音乐消息
        'music' => '<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[music]]></MsgType>
							<Music>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<MusicUrl><![CDATA[%s]]></MusicUrl>
							<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
							<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
							</Music>
							</xml>'
    );


    public function initset($appid,$appsecret,$token,$channelid){
        $this->_appid = $appid;
        $this->_appsecret = $appsecret;
        $this->_token = $token;
        $this->_channelid = $channelid+0;		
    }

    /**
     * _addMedia()：添加素材
     **/
    public function _addMedia($type, $file){
        $curl='https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$this->_getAccesstoken().'&type='.$type;
        $data['type']=$type;
        $data['media']='@'.$file;
        $result = $this->_request($curl, true, "post", $data);
        return $result;
    }

    /**
     * _getUserList()：获取用户列表
     **/
    public function _getUserlist(){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$this->_getAccesstoken();
        $content = $this->_request($url);
        $content = json_decode($content);
        $users = $content->data->openid;
        return $users;
    }

    /**
     * _sendAll()：群发
     **/
    public function _sendAll($content){
        $tpl = '{
		   "touser":[
		   %s
		   ],
			"msgtype": "text",
			"text": { "content": "%s"}
		}';
        $curl = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$this->_getAccesstoken();

        $users = $this->_getUserlist();
        for($i=0;$i<count($users);$i++){
            $u .= '"'.$users[$i].'"';
            if($i < count($users) -1)
                $u .= ',';
        }

        $data = sprintf($tpl,$u,$content);
        $result = $this->_request($curl, true, "post", $data);
        $result = json_decode($result);
        if($result->errcode == 0)
            echo "发送ok！";
    }


    /**
     * _queryMenu()：查询菜单
     **/
    public function _queryMenu(){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this->_getAccesstoken();
        $menu = $this->_request($url);
        //file_put_contents('./tmp',$menu);//仅限于调试使用
        return $menu;
    }

    /**
     * _deleteMenu()：删除菜单
     **/
    public function _deleteMenu(){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$this->_getAccesstoken();
        $result = $this->_request($url);
        $result = json_decode($result);
        if($result->errcode == 0)
            echo "菜单删除ok！";

    }

    /**
     * _createMenu()：创建菜单
     **/
    public function _createMenu($menu){
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->_getAccesstoken();
        $result = $this->_request($url, true, "post", $menu);
        $result = json_decode($result);		
        if($result->errcode == 0)
            echo "菜单创建ok!";
    }

    /**
     *_request():发出请求
     *@curl:访问的URL
     *@https：安全访问协议
     *@method：请求的方式，默认为get
     *@data：post方式请求时上传的数据
     **/
    private function _request($curl, $https=true, $method='get', $data=null){
        $ch = curl_init();//初始化
        curl_setopt($ch, CURLOPT_URL, $curl);//设置访问的URL
        curl_setopt($ch, CURLOPT_HEADER, false);//设置不需要头信息
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//只获取页面内容，但不输出
        if($https){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//不做服务器认证
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);//不做客户端认证
        }
        if($method == 'post'){
            curl_setopt($ch, CURLOPT_POST, true);//设置请求是POST方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置POST请求的数据
        }
        $str = curl_exec($ch);//执行访问，返回结果
        curl_close($ch);//关闭curl，释放资源
        return $str;
    }

    /**
     *_getAccesstoken()：获取access token
     **/
    public function _getAccesstoken(){
        if($this->_channelid!=0) {
            $channelWxModel = M('distribution_channel_wx');
            $wxinfo = $channelWxModel->where(array('channelid' => $this->_channelid))->find();
            if ($wxinfo) {
                if ((!empty($wxinfo['atoken']) && !empty($wxinfo['tokentime']) && (time() - $wxinfo['tokentime'] > 60))||empty($wxinfo['atoken'])) {
                    $content = $this->_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->_appid."&secret=".$this->_appsecret); //获取access token的json对象
                    file_put_contents($file, $content); //保存json对象到指定文件
                    $content = json_decode($content);//进行json解码
                    $wxtoken['atoken']=$content->access_token;
                    $wxtoken['tokentime']=time();
                    $channelWxModel->where(array('channelid'=>$this->_channelid))->save($wxtoken);
                    return $content->access_token;
                }else{
                    return $wxinfo['atoken'];
                }
            }
        }else{
            $file = './accesstoken'; //用于保存access token
            if(file_exists($file)){ //判断文件是否存在
                $content = file_get_contents($file); //获取文件内容
                $content = json_decode($content);//json解码
				//判断文件是否过期
                if(time()-filemtime($file)<60){
					  return $content->access_token;//返回access token
				}else{
					 $content = $this->_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->_appid."&secret=".$this->_appsecret); //获取access token的json对象
					file_put_contents($file, $content); //保存json对象到指定文件
					$content = json_decode($content);//进行json解码
					return $content->access_token;//返回access token
				}
                  
            }
            $content = $this->_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->_appid."&secret=".$this->_appsecret); //获取access token的json对象
            file_put_contents($file, $content); //保存json对象到指定文件
            $content = json_decode($content);//进行json解码
            return $content->access_token;//返回access token
        }
    }

    /**
     *_getTicket():获取ticket，用于以后换取二维码
     *@expires_secords：二维码有效期（秒）
     *@type ：二维码类型（临时或永久）
     *@scene：场景编号
     **/
    public function _getTicket($expires_secords = 604800, $type = "temp", $scene = 1){
        if($type == "temp"){//临时二维码的处理
            $data = '{"expire_seconds":'.$expires_secords.', "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene.'}}}';//临时二维码生成所需提交数据
            return $this->_request("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->_getAccesstoken(),true, "post", $data);//发出请求并获得ticket
        } else { //永久二维码的处理
            $data = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$scene.'}}}';//永久二维码生成所需提交数据
            return $this->_request("https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$this->_getAccesstoken(),true, "post", $data);//发出请求并获得ticket
        }
    }

    /**
     *_getQRCode():获取二维码
     *@expires_secords：二维码有效期（秒）
     *@type：二维码类型
     *@scene：场景编号
     **/
    public function _getQRCode($expires_secords,$type,$scene){
        $content = json_decode($this->_getTicket($expires_secords,$type,$scene));//发出请求并获得ticket的json对象
        $ticket = $content->ticket;//获取ticket
        $image = $this->_request("https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket)
        );//发出请求获得二维码图像
        //$file = "./".$type.$scene.".jpg";// 可以将生成的二维码保存到本地，便于使用
        //file_put_contents($file, $image);//保存二维码
        return $image;
    }
    /**
     * valid()：第一次接入微信平台时验证
     **/
    public function valid()//检查安全性
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){//检查签名是否一致
            echo $echoStr;//验证成功后，输出
            exit;
        }
    }
    /**
     * responseMsg()：响应微信平台发送的消息
     **/
    public function responseMsg()//所有的被动消息处理都从这里开始
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//获得用户发送信息
		//file_put_contents('./postStr.txt',var_export($postStr,true));
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);//解析XML到对象
		//file_put_contents('./postObj.txt',var_export($postObj,true));
        switch($postObj->MsgType){
            case 'event': //事件处理
                $this->_doEvent($postObj);
                break;
            case 'text': //文本处理
                $this->_doText($postObj);
                break;
            case 'image': //图片处理
                $this->_doImage($postObj);
                break;
            case 'voice': //声音处理
                $this->_doVoice($postObj);
                break;
            case 'video': //视频处理
                $this->_doVideo($postObj);
                break;
            case 'location'://定位处理
                $this->_doLocation($postObj);
                break;
            default: exit;
        }
		
    }

    /**
     *_doLocation():处理定位消息
     *@postObj:响应的消息对象
     **/
    private function _doLocation($postObj){
        $str = sprintf($tpltext,$postObj->FromUserName,$postObj->ToUserName,time(),"您所在的位置是经度：".$postObj->Location_Y."，纬度：".$postObj->Location_X."。");
        echo $str;
    }

    /**
     *_doEvent():处理事件消息
     *@postObj:响应的消息对象
     **/
    private function _doEvent($postObj){ //事件处理
        switch($postObj->Event){
            case  'subscribe': //订阅
                $this->_doSubscribe($postObj);
                break;
            case 'unsubscribe': //取消订阅
                $this->_doUnsubscribe($postObj);
                break;
            case 'CLICK':
                $this->_doClick($postObj);
                break;
            default:;
        }
    }

    /**
     *_doClick():处理菜单点击事件
     *@postObj:响应的消息对象
     **/
    private function _doClick($postObj){
        /*if($postObj->EventKey == 'kefu'){
            $this->_doText($postObj,'kefu');
        }*/
        /*daisy*/
        if($postObj->EventKey == 'kefu'){
            $this->_doKefu($postObj);
        }
    }

    /**
     *_doSubscribe():处理关注事件
     *@postObj:响应的消息对象
     **/
    private function _doSubscribe($postObj){
		
		$channelWxModel = M('distribution_channel_wx');
        $wxinfo = $channelWxModel->where(array('channelid' => $this->_channelid))->find();
        if($wxinfo){
            $wxname=$wxinfo['wxname'];
			$str = sprintf($this->tpl['text'],$postObj->FromUserName,$postObj->ToUserName,time(),"欢迎关注".$wxname." 点我：<a href='http://wap.kyread.com/Article/weiXinUserReadChapter/cid/".$this->_channelid.".html'>☞继续阅读☜</a>");
			echo $str;
        }	
		
		
        //还可以保存用户的信息到数据库
        
		//file_put_contents('follow.txt',$str);
		 $user=$this->where("uname='".$postObj->FromUserName."'")->find();
        if($user){
            $url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->_getAccesstoken().'&openid='.$postObj->FromUserName.'&lang=zh_CN ';
            $userJson = file_get_contents($url);
            $userInfo = json_decode($userJson,true);
            $userInfo['nickname']=filterWx($userInfo['nickname']);
            $userInfo['headimgurl'] = $userInfo['headimgurl'] ? $userInfo['headimgurl'] : DEFAULT_FACE_IMG;
            $userInfo['nickname']   = $userInfo['nickname'] ? $userInfo['nickname'] : '读者' . date('YmdHis',time());
            $user['name']=$userInfo['nickname'];
            $user['faceImg']= $userInfo['headimgurl'];
            $user['sex']= $userInfo['sex'];
            $user['isfollow']=1;
            $this->save($user);
        }
		//file_put_contents('unfollow.txt',$user['uname'].'===='.$user['isfollow']);
    }

    /**
     *_doUnsubscribe():处理取消关注事件
     *@postObj:响应的消息对象
     **/
    private function _doUnsubscribe($postObj){
        //把用户的信息更改为取消关注
        $user=$this->where("uname='".$postObj->FromUserName."'")->find();
        if($user){
            $user['isfollow']=0;
           // $this->save($user);
        }
		//file_put_contents('unfollow.txt',$user['uname'].'===='.$user['isfollow']);
    }

    //图灵机器人
    private function _robot($postObj,$key="")
    {

        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        if (!empty($key)) {
            $keyword=$key;
        }
        $time = time();
        $data = array("key"=>"b5979fd8e8c64b07863eb35a639b043b","info"=>"$keyword","userid"=>"3275dc93-1baf-4ba9-a2e8-d65c539412cd");
        $paramstring = http_build_query($data);
        $str=$this->_request("http://www.tuling123.com/openapi/api",false,"post",$paramstring);
        $str=json_decode($str,true);
        $content="";
        if($str['code']=="100000"){//文本信息
            $content=$str['text'].'(机器人自动回复)';
        }else if($str['code']=="200000"){//连接信息
            $content="你需要的信息:<a href='".$str['url']."'>点击这里</a>";
        }else if($str['code']=="302000"){//新闻信息
            $news =$str['list'];
            $it="";
            for($i=0;$i<4;$i++)
                $it .= sprintf($this->tpl['item'],$news[$i]['article'],$news[$i]['article'],$news[$i]['icon'],$news[$i]['detailurl']);
            $content = sprintf($this->tpl['list'],$postObj->FromUserName,$postObj->ToUserName,time(),4, $it);
            echo $content;
            exit();
        }else if($str['code']=="308000"){//菜谱信息
            $list =$str['list'];
            $it="";
            for($i=0;$i<4;$i++)
                $it .= sprintf($this->tpl['item'],$list[$i]['name'],$list[$i]['info'],$list[$i]['icon'],$list[$i]['detailurl']);
            $content = sprintf($this->tpl['list'],$postObj->FromUserName,$postObj->ToUserName,time(),4, $it);
            echo $content;
            exit();
        }

        return $content;
    }

    /**
     *_doText():处理文本消息
     *@postObj:响应的消息对象
     **/
    private function _doText($postObj,$key='',$kefu=''){
		$this->_dokeyWord($postObj);		
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();			
		 if(!empty($key)){
            $keyword=$key;
        }
		
        if(!empty( $keyword ))
        {
			$bookModel=D('Book');
            $booklist=$bookModel->field('articlename,articleid')->where("articlename like '%".$keyword."%'")->limit(0,5)->select();
            $contentStr='';
            if($booklist){
                foreach ($booklist as $k=>$v){
                    $contentStr.=$v['articlename'];
                    $contentStr.="<a href='http://wap.kyread.com/Article/index/id/".$v['articleid']."/cid/".$this->_channelid.".html'>【点击查看】</a>\n";
                }
            }elseif ($keyword=='kefu'){
				if($kefu!=''){
					$contentStr ='请加客服微信:'.$kefu;
				}else{
					$this->_doKefu($postObj);
				}
                
            }elseif($keyword=='客服'){
				$this->_doKefu($postObj);
			}else{
				/* $contentStr =$this->_robot($postObj); */
				$contentStr ="如需反馈意见或问题,请点击右下角↘用户中心→联系客服\n或直接回复->'客服'";
			}
            
            $msgType = "text";
            $resultStr = sprintf($this->tpl['text'], $fromUsername, $toUsername, $time, $contentStr);
            file_put_contents('./1.txt', $resultStr);
            echo $resultStr;
        }
        exit;
    }

    /**
     *_sendMusic():发送音乐
     *@postObj:响应的消息对象
     **/
    private function _sendMusic($postObj){
        $content = $postObj->Content;
        $content = mb_substr($content,2,mb_strlen($content,'utf-8')-2,'utf-8');//删除字符串前两个字符（删除”歌曲“）
        $arr = explode('@',$content);//分解歌曲和歌手到数组
        $song = $arr[0];
        $singer = '';
        if(isset($arr[1])){//生成有歌曲和歌手的音乐搜索地址
            $singer = $arr[1];
            $curl = 'http://box.zhangmen.baidu.com/x?op=12&count=1&title='.$arr[0].'$$'.$arr[1].'$$$$';
        }
        else //搜索仅有歌曲的地址
            $curl = 'http://box.zhangmen.baidu.com/x?op=12&count=1&title='.$arr[0].'$$';
        $response = $this->_request($curl, false);//开始搜索
        $res = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);//搜索结果解析
        $encode = $res->url->encode;
        $decode = $res->url->decode;
        $musicurl = mb_substr($encode, 0, mb_strrpos($encode, '/', 'utf-8') + 1,'utf-8').
            mb_substr($decode, 0, mb_strrpos($decode, '&', 'utf-8'),'utf-8');
        file_put_contents('./tmp', mb_substr($encode, 0, mb_strrchr($encode, '/', 'utf-8') + 1,'utf-8'));//生成歌曲的实际地址
        $str = sprintf($this->tpl['music'],$postObj->FromUserName,$postObj->ToUserName,time(),$arr[0],$arr[1],$musicurl,$musicurl,"FZIwAG_Vzbj0zEelbUScRJmExgKJG0x6D9krMv0wiTYwC3PLR_HiGPD58gHY4P3q");//发送歌曲到用户
        echo $str;
        exit;
    }

    /**
     *_doImage():处理图片消息
     *@postObj:响应的消息对象
     **/
    private function _doImage($postObj){
        $str = sprintf($this->tpl['text'],$postObj->FromUserName,$postObj->ToUserName,time(),"您发送的图片:<a href='".$postObj->PicUrl."'>查看</a>");
        echo $str;
    }
	
	
	
    /**
     *关键词回复
     */

    public function _dokeyWord($postObj,$key=''){
        $keyword = trim($postObj->Content);
        if (!empty($key)) {
            $keyword=$key;
        }
		/* file_put_contents('./keyword.txt',$keyword); */

        //获取关键字回复内容和链接
        $keywordModel=M('distribution_wx_keyword');
        $where=array('fromid'=>$this->_channelid,
                        'keyword'=>$keyword
            );
        $info=$keywordModel->where($where)->find();
		
        if($info){
            //获取图片
            $picModel=M('distribution_pic');
            $pic=$picModel->field('pic')->order('rand()')->limit(1)->select();
            $pic=$pic[0]['pic'];
            $pic='http://admin.kyread.com'.$pic;
            
            $it = sprintf($this->tpl['item'],$info['title'],$info['content'],$pic,$info['url']);
            $content = sprintf($this->tpl['list'],$postObj->FromUserName,$postObj->ToUserName,time(),1, $it);
			file_put_contents('./keyword.txt',$content);
            echo $content;
            exit();
        }
    }

	

    /**
     *checkSignature():验证签名
     **/
    private function checkSignature()
    {
		
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = $this->_token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );		

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

	/**
     * @param $postObj
     * @param string $media_id
     * 客服二维码发送
     */
    public function _sendImage($postObj,$media_id=''){
        $str = sprintf($this->tpl['image'],$postObj->FromUserName,$postObj->ToUserName,time(),$media_id);
        echo $str;
    }
	public function _doKefu($postObj){
		$channelWxModel = M('distribution_channel_wx');
            $wxinfo = $channelWxModel->where(array('channelid' => $this->_channelid))->find();
            if(!empty($wxinfo['kefu'])){
                $this->_doText($postObj,'kefu',$wxinfo['kefu']);
            }else{
                if(!empty($wxinfo['media_id'])){
                    $this->_sendImage($postObj,$wxinfo['media_id']);
                }else{
                    $res=$this->_addMedia('image','./kefu.jpg');
                    $res=json_decode($res,true);
                    $data['media_id']=$res['media_id'];
                    $channelWxModel->where(array('channelid' => $this->_channelid))->save($data);
                    $this->_sendImage($postObj,$res['media_id']);
                }

            }
	}
	
}