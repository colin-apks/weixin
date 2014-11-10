<?php 
	/**
	* 
	*/
	class Fanyi{
		private $key;
		private $keyfrom;
		private $keyword;
		function __construct($key,$keyfrom,$keyword){
			$this->key=$key;
			$this->keyfrom=$keyfrom;
			$this->keyword=mb_substr($keyword, 2,strlen($keyword)-2,'UTF-8');
		}
		/*http://fanyi.youdao.com/openapi.do?keyfrom=qiyunkj&key=330102425&type=data&doctype=json&version=1.1&q=教室*/
		public function get_msg(){
			$url = 'http://fanyi.youdao.com/openapi.do?keyfrom='.$this->keyfrom.'&key='.$this->key.'&type=data&doctype=json&version=1.1&q=' . urlencode($this->keyword);//有道翻译API
			$fanyiJson = file_get_contents($url);//获取url数据,返回Json数据
            return json_decode($fanyiJson, true);//json 转换成 数组
		}

		public function exec(){
			$fanyiArr=$this->get_msg();
			$extension= "【查询】".str_repeat(" ", 1). $fanyiArr['query'] . "\n【翻译】".str_repeat(" ", 1) . $fanyiArr['translation'][0];//拼接返回给用户的字符串
            
            //扩展翻译
            if(isset($fanyiArr['web'])){
	            $extension .= "\n【扩展翻译】：\n";
	            foreach ($fanyiArr['web'] as $vals) {
	           	 	$v="";
	                $k=$vals['key'];
	                $n=1;
	                foreach ($vals['value'] as $val) {
	                	$v.=str_repeat(' ', 2).$n."、".$val."\n";
	                	$n++;
	                }
	               
	               $str.=$k."\n".$v;
	            }
	           
	            
	            $extension.=$str;
			}
			return $extension;
		}
	}   
 ?>