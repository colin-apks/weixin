<?php 
	/**
	* 
	*/
	class Map{

		private $Location_X;
		private $Location_Y;
		private $FromUserName;
		private $ToUserName;
		function __construct($obj){
			$this->Location_X=$obj->Location_X;
			$this->Location_Y=$obj->Location_Y;
			$this->FromUserName=$obj->FromUserName;
			$this->ToUserName=$obj->ToUserName;
		}
		public function get_msg(){
			$urlstr = "http://api.map.baidu.com/place/v2/search?&query=酒店&location=".$this->Location_X.",".$this->Location_Y."&radius=1000&output=json&ak=C1c688756ba04c3593266b1c7f34afa3";
			$jsonstr = file_get_contents($urlstr);
			$json=json_decode($jsonstr,true);
			return $json['results'];
		}
		public function exec(){
			$arr=$this->get_msg();
			$result = "<xml>
                 <ToUserName><![CDATA[".$this->FromUserName."]]></ToUserName>
                 <FromUserName><![CDATA[".$this->ToUserName."]]></FromUserName>
                 <CreateTime>".time()."</CreateTime>
                 <MsgType><![CDATA[news]]></MsgType>
                 <ArticleCount>".count($arr)."</ArticleCount>
                 <Articles>";
                foreach($arr as $k=>$v){
                    if($k==0){
                        $picurl = "http://api.map.baidu.com/staticimage?width=640&height=320&center=".$v['location']['lng'].",".$v['location']['lat']."&zoom=15&markers=".$v['location']['lng'].",".$v['location']['lat']."&markerStyles=l,0";
                    }else{
                        $picurl = "http://api.map.baidu.com/staticimage?width=80&height=80&center=".$v['location']['lng'].",".$v['location']['lat']."&zoom=15&markers=".$v['location']['lng'].",".$v['location']['lat']."&markerStyles=l,0";
                    }
                    $result.="
                     <item>
                     <Title><![CDATA[".$v['name']." 地址：".$v['address']." 电话:".$v['telephone']."]]></Title> 
                     <Description><![CDATA[".$v['name']." 地址：".$v['address']." 电话:".$v['telephone']."]]></Description>
                     <PicUrl><![CDATA[".$picurl."]]></PicUrl>
                     <Url><![CDATA[http://api.map.baidu.com/place/detail?uid=".$v['uid']."&output=html&src=".$v['name']."&output=html]]></Url>
                     </item>";
                }
                 
                $result .= "</Articles></xml>";
                
        	return $result;

		}
	}
 ?>