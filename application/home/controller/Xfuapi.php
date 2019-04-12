<?php
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Jump; 
class Xfuapi extends \app\Home\controller\Base
{
	protected $schoolCode =12714;
	protected $timestamp  ='';
	protected $baseUrl    ='http://xyhq.xafy.edu.cn/hall/api/v2/hall/web';
    public function getService(){
    	$this->timestamp=time();
		$data = array(
				"schoolCode" =>$this->schoolCode,
		        "pageSize"   => 9,
		        "pageIndex"  =>1,
		        "typeName"   => "后勤服务",
		        "itsoft"     => $this->timestamp,
		        "jsoncallback"=> "jQuery11240".generate_code(16)."_".$this->timestamp,
		        "_"           => $this->timestamp
		);
		$query = http_build_query($data);
		$url = $this->baseUrl."/getLink";//这里一定要写完整的服务页面地址，否则php程序不会运行
		try{
		    $result = file_get_contents($url.'?'.$query);
		}catch(\Exception $e){
		    return returnData('error',-1,'【xfu】接口错误');
		}
		echo getBracketsData(strval($result));
		
	}

	public function getBannerList(){
		$this->timestamp=time();
		$data = array(
				"schoolCode" =>$this->schoolCode,
		        "pageSize"   => 3,
		        "pageIndex"  =>1,
		        "typeName"   => "轮播图管理",
		        "itsoft"     => $this->timestamp,
		        "jsoncallback"=> "jQuery11240".generate_code(16)."_".$this->timestamp,
		        "_"           => $this->timestamp
		);
		$query = http_build_query($data);
		$url = $this->baseUrl."/getLink";//这里一定要写完整的服务页面地址，否则php程序不会运行
		try{
		    $result = file_get_contents($url.'?'.$query);
		}catch(\Exception $e){
		    return returnData('error',-1,'【xfu】接口错误');
		}
		echo getBracketsData(strval($result));
	}

	//便民服务
	public function getConvenientList(){
		$this->timestamp=time();
		$data = array(
				"schoolCode" =>$this->schoolCode,
		        "pageSize"   => 9,
		        "pageIndex"  =>1,
		        "typeName"   => "便民服务",
		        "itsoft"     => $this->timestamp,
		        "jsoncallback"=> "jQuery11240".generate_code(16)."_".$this->timestamp,
		        "_"           => $this->timestamp
		);
		$query = http_build_query($data);
		$url = $this->baseUrl."/getLink";//这里一定要写完整的服务页面地址，否则php程序不会运行
		try{
		    $result = file_get_contents($url.'?'.$query);
		}catch(\Exception $e){
		    return returnData('error',-1,'【xfu】接口错误');
		}
		echo getBracketsData(strval($result));
	}
	//报修
	public function getRepairList(){
		$has=Request::has('pageSize','post');
		if($has){
			$pageSize=Request::param('pageSize');
			$this->timestamp=time();
			$data = array(
					"schoolCode" =>$this->schoolCode,
			        "pageSize"   => $pageSize,
			        "itsoft"     => $this->timestamp,
			        "jsoncallback"=> "jQuery11240".generate_code(16)."_".$this->timestamp,
			        "_"           => $this->timestamp
			);
			$query = http_build_query($data);
			$url = $this->baseUrl."/getRepairList";//这里一定要写完整的服务页面地址，否则php程序不会运行
			try{
		    $result = file_get_contents($url.'?'.$query);
			}catch(\Exception $e){
			    return returnData('error',-1,'【xfu】接口错误');
			}
			echo getBracketsData(strval($result));
				// return returnData($result);
		}else{
			return returnData('error',-1,'缺少参数');
		}
		
	}
	//服务监督
	public function getInspectList(){
		$has=Request::has('pageSize','post');
		if($has){
			$pageSize=Request::param('pageSize');
			$this->timestamp=time();
			$data = array(
					"schoolCode" =>$this->schoolCode,
			        "pageSize"   => $pageSize,
			        "itsoft"     => $this->timestamp,
			        "jsoncallback"=> "jQuery11240".generate_code(16)."_".$this->timestamp,
			        "_"           => $this->timestamp
			);
			$query = http_build_query($data);
			$url = $this->baseUrl."/getInspectList";
			$result = file_get_contents($url.'?'.$query);
			try{
		    $result = file_get_contents($url.'?'.$query);
			}catch(\Exception $e){
			    return returnData('error',-1,'【xfu】接口错误');
			}
			echo getBracketsData(strval($result));
		}else{
			return returnData('error',-1,'缺少参数');
		}
		
	}

	//获取投诉详情
	public function getIntendredirect(){
			$has=Request::has('inspectId','post');
			if($has){
				$inspectId=Request::param('inspectId');
				$inspectBaseUrl = "http://xyhq.xafy.edu.cn/intendredirect/website/index/details/";
				$replyBaseUrl = "http://xyhq.xafy.edu.cn/intend/redirect/website/index/details/replyList/";
				$intendredirectUrl = $inspectBaseUrl.$inspectId."/1/10";
				$intendredirecText=file_get_contents($intendredirectUrl);
				$html_dom = new \HtmlParser\ParserDom($intendredirecText);
				$title=($html_dom->find('span.content-title',0))->getPlainText();
				$hint=$html_dom->find('div.content-hint span');
				$replyUrl = $replyBaseUrl.$inspectId."/1/10";
				$reply_text=file_get_contents($replyUrl);
				// var_dump($reply_text);
				// exit();
				$reply_html_dom = new \HtmlParser\ParserDom($reply_text);
				$reply_user=($reply_html_dom->find('a.reply-user cite'))[0]->node->nodeValue;
				$reply_time=$reply_html_dom->find('div.reply-about span',0)->getPlainText();
				$reply_body=$reply_html_dom->find('div.reply-body',0)->getPlainText();
				$data = array(
					"text_category" => ($html_dom->find('span.text-category',0))->getPlainText(),
					"title" => ($html_dom->find('span.content-title',0))->getPlainText(),
					"studentName" => $hint[0]->node->nodeValue,
				    "date"  => $hint[1]->node->nodeValue,
				    "views" => $hint[2]->node->nodeValue,
	 			    "comment" => $hint[3]->node->nodeValue,
				    "likecount" => $hint[4]->node->nodeValue,
				    "content" => $html_dom->find('div.content-text p',0)->getPlainText(),
				    "replyList" => array(
				    	"replyInfo" => $reply_text
				    	// "reply_user" => $reply_user,
				    	// "reply_time" => $reply_time,
				    	// "reply_body" => $reply_body
				    )
				);
			// echo $data->getPlainText();
			// var_dump(json($data));
			return returnData($data,1,'success');

			}else{
				return returnData('error',-1,'缺少参数');
			}
	}
	public function getRepairredirect(){
		$has=Request::has('repairId','post');
		if($has){
			$repairId=Request::param('repairId');
			$repairBaseUrl = "http://xyhq.xafy.edu.cn/repairredirect/website/repair/details/";
			$repairredirectUrl = $repairBaseUrl.$repairId;
			$repairText = file_get_contents($repairredirectUrl);
			$html_dom = new \HtmlParser\ParserDom($repairText);
			$hint = $html_dom->find('div.content-header span');
			// $process = $html_dom->find('div.liucheng p');
			$tmpProcess=array();
			foreach($html_dom->find('div.liucheng') as $div) 
			{
			       foreach($div->find('p') as $p) 
			       {
			             foreach ($p->find('span') as $span) {
			             	// var_dump($span->node->textContent);
			             	$spanData=trim($span->node->textContent);
			             		if($spanData!=''){
			             		array_push($tmpProcess,$spanData);
			             	}
			             }
			       }
			}
			$process_time=array();
			$process_status=array();
			$process_info=array();
			foreach ($tmpProcess as $key => $value) {
				if($key%3==0){
					array_push($process_time,$tmpProcess[$key]);
				}else if(($key-1)%3==0){
					array_push($process_status,$tmpProcess[$key]);
				}else{
					array_push($process_info,$tmpProcess[$key]);
				}
			}
			$data = array(
				"text_category" => trim($hint[0]->node->nodeValue),
				"studentName" => $hint[1]->node->nodeValue,
				"date"  => $hint[2]->node->nodeValue,
				"repair_num" => $hint[3]->node->nodeValue,
				"content" => $html_dom->find('div.content-hint p',0)->getPlainText(),
				"area" => $html_dom->find('div.text-foot span',0)->getPlainText(),
				"process" => array(
					"time" => $process_time,
					"status" => $process_status,
					"info"   => $process_info
				)
			);
				return returnData($data,1,'success');
			}else{
				return returnData('error',-1,'缺少参数');
			}
		
	}

	public function doLogin(){
		// echo "ahah";
		$url = 'http://authserver.xafy.edu.cn/authserver/login?service=http://xyhq.xafy.edu.cn/cas&amp;deployType=private&amp;service=http%3A%2F%2Fxyhq.xafy.edu.cn%2F&amp;schoolCode=12714&amp;schoolName=%E8%A5%BF%E5%AE%89%E7%BF%BB%E8%AF%91%E5%AD%A6%E9%99%A2&amp;root=http%3A%2F%2Fxyhq.xafy.edu.cn%2Flogin&amp;failCount=0';
        $post_data['username']       = 15311070103;
        $post_data['password']      = 070103;
        $post_data['lt'] = 'LT-77219-ca379ceHAABSEaoJLsbP2KiRLeCQP11550897920947-lzJm-cas';
        $post_data['dllt']    = 'userNamePasswordLogin';
        $post_data['execution']    = 'e3s1';
        $post_data['_eventId']    = 'submit';
        $post_data['rmShown']    = 1;
        $o = "";
        foreach ( $post_data as $k => $v ) 
        { 
            $o.= "$k=" . urlencode( $v ). "&" ;
        }
        $post_data = substr($o,0,-1);

        $res = request_post($url, $post_data);  
        $data=file_get_contents($url);
        // print_r($data);
        // var_dump($data);
        print_r($res);
        // var_dump($res);
   //      if($res){
   //      	$url = "http://xyhq.xafy.edu.cn/";  
			// echo "<script type='text/javascript'>";  
			// echo "window.location.href='$url'";  
			// echo "</script>";
        	// header("Location: http://xyhq.xafy.edu.cn/"); 
   //      	// $this->redirect('');
   //      }     
	}

	//获取链接信息
	public function getLinkInfo(){
		$has=Request::has('linkUrl','post');
		if($has){
			$linkUrl=Request::param('linkUrl');
			$data = file_get_contents($linkUrl);
			return returnData($data,1,'success');
			// echo $data;
			// var_dump(($data));
		}else{
			return returnData('error',-1,'缺少参数');
		}
	}

}
