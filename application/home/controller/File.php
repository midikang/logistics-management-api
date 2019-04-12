<?php
namespace app\home\controller;
use think\Db;
use think\facade\Request;
use think\Jump; 
class File extends \app\Home\controller\Base
{
	protected $schoolCode = 12714;
	protected $domain_path = 'http://127.0.0.1/composerProject/logistics-management/uploads/';
    public function uploadFile(){
	    	// 获取表单上传文件 例如上传了001.jpg
	    $file = request()->file('image');
	    if($file==null){
	    	return returnData('error',-1,'文件为空');
	    }else{
		    // 移动到框架应用根目录/uploads/ 目录下
		    $info = $file->move( '../uploads');
		    if($info){
		        $relative_path = str_replace('\\', '/', $info->getSaveName());
		        $absolute_path = $this->domain_path.$relative_path;
		        $data['relative_path'] = $relative_path;
		        // $data['absolute_path'] = $absolute_path;
		        $data['user_id'] = Request::param('user_id');
				$rst = Db::name('photo')
				    ->data($data)
				    ->insert();
				$photo_id = Db::name('photo')->getLastInsID();
			if($rst==1){
				$responseData = $data;
				// $responseData['photo_id'] = $photo_id;
				$responseData['absolute_path'] = $absolute_path;
				return returnData($responseData,1,'success');
			}else{
				return returnData('error',-1,'文件上传失败');
			}
	 	    }else{
		        // 上传失败获取错误信息
		        $error_msg = $file->getError();
		        return returnData('error',-1,$error_msg);
		    }
	    }
	    
    }

	
	

}
