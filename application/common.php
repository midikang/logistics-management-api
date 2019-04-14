<?php
/**
 * @Author: wanwan
 * @Date:   2019-01-08 14:28:03
 * @Last Modified by:   shiwan
 * @Last Modified time: 2019-04-14 19:58:19
 */
namespace app\home\controller;
use think\Db;
// 应用公共文件
function getCurrDate(){
	return $date=date();
}

function returnEmpty(){
	$error=['status'=>0,'msg'=>'请求参数为空',];
	return json($error);
}

function toJson($res){
	$response=['status'=>1,'msg'=>'success'];
	$returnData=['msg'=>$response,'data'=>$res];
	return json($returnData);
}

function returnData($data=null,$status,$msg){
	$error=['status'=>$status,'msg'=>$msg,'time'=>time()];
	$success=['status'=>$status,'msg'=>$msg,'time'=>time(),'data'=>$data];
	if($status==-1){
		return json($error);
	}else{
		// $returnData=['msg'=>$success,'data'=>$data];
		return json($success);
	}
}
//生成任意位数随机数
function generate_code($length) {
    return rand(pow(10,($length-1)), pow(10,$length)-1);
}
//正则获取小括号里的内容
function getBracketsData($str)
{
	$result = array();
	preg_match_all("/(?:\()(.*)(?:\))/i",$str, $result);
	return $result[1][0];
}
//生成订单号
function setRepair_num($DB){
	$cur_date = strtotime(date('Y-m-d'));//今天
	$date = date('Ymd');
	$countToday = Db::name($DB)->where('date', '>', $cur_date)->count('id');
	$tmpLen = strlen(strval($countToday));
	if($tmpLen==1){
		return $date.'000'.$countToday;
	}elseif($tmpLen==2){
		return $date.'00'.$countToday;
	}else if($tmpLen==1){
		return $date.'0'.$countToday;
	}else{
		return $date.$countToday;
	}
	// return $date = date("Ymd");
}


function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }
        $cookie_file = dirname(__FILE__).'/cookie.txt';
		//$cookie_file = tempnam("tmp","cookie");

		//先获取cookies并保存
		$ch = curl_init($url); //初始化
		curl_setopt($ch, CURLOPT_HEADER, 0); //不返回header部分
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //返回字符串，而非直接输出
		curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file); //存储cookies
		curl_exec($ch);
		// echo $cookie_file;
		// exit;
		curl_close($ch);

        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
		curl_setopt($ch,CURLOPT_COOKIE,$cookie_file);
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        
        return $data;
    }
 // 生成唯一id
function create_guid() {
    // $charid = strtoupper(md5(uniqid(mt_rand(), true)));
    $charid = md5(uniqid(mt_rand(), true));
    $hyphen = chr(45);// "-"
    $uuid = 
     substr($charid, 0, 8).$hyphen
    .substr($charid, 8, 4).$hyphen
    .substr($charid,12, 4).$hyphen
    .substr($charid,16, 4).$hyphen
    .substr($charid,20,12);
    return $uuid;
}
/*
	$username 报修人姓名
	$status 当前进度
	$area 区域
	$admin 管理员姓名
	$worker 维修工姓名
	$reason  转交原因
 */
function setProcess($username='',$status,$area='',$admin='',$worker='',$reason=''){
	switch ($status) {
		case 1:
			$statustxt = '申报';
			$infoTxt = '['.$username.']'.'于'.date('Y-m-d h:i:s').'通过微信报修,系统自动将任务分配给'.'['.$area.']'.','.',等待受理人'.'['.$admin.']'.'进行受理';
			break;
		case 2:
			$statustxt = '审核';
			$infoTxt = '被受理人'.'['.$admin.']'.'审核';
			break;
		case 3:
			$statustxt = '派工';
			$infoTxt = '受理人'.'['.$admin.']'.'指派维修工'.'['.$worker.']'.'承修';
			break;
		case 4:
			$statustxt = '转单';
			$infoTxt = '被受理人'.'['.$admin.']'.'转到受理人'.'['.$worker.']'.'原因是'.'['.$reason.']';
			break;
		case 5:
			$statustxt = '完工';
			$infoTxt = '['.$worker.']'.'于'.date('Y-m-d h:i:s').'完成修理操作';
			break;
		default:
			$statustxt = '未知进度';		
	};
	$setTime = time();
	if($status == 3){
		$setTime = strtotime('+1minute');
	}
	$process = array(
		"status" => $statustxt,
		"info" => $infoTxt,
		"time" => $setTime
	);
	return $process;
}
/*
	该方法用于维修工完成维修操作给学生发送消息
 */
function setMessage($send_user,$send_username,$receive_user,$receive_username){
	//固定格式消息内容
	$content = $receive_username.'同学你好！你申请的故障报修于'.'['.date('Y-m-d h:i').']'.'已被'.$send_username.'完成修理操作,请查看维修情况,并给维修工点个赞哦！';
	$message = array(
		"send_user" => $send_user,
		"send_username" => $send_username,
		"receive_user"=> $receive_user,
		"receive_username" => $receive_username,
		"content" => $content,
		"time" => time()
	);
	return $message;
}