<?php
/**
*  企业个人公告模型
*/
namespace App\Model;
use Common\Model\BaseModel;
use Common\Third\AppPage;
class CompanybullModel extends BaseModel{
	
	protected $tableName = 'company_bull';
	//浏览量，点赞统计数
	public function search(){
		$where = array();
		
			
		$where['a.user_id'] = I('post.user_id');
		
		//翻页
		$showrow = 15; //一页显示的行数
		
		$curpage = I('post.page',1); //当前的页,还应该处理非数字的情况
		
		$total = $this->alias('a')->field(array("count(b.bull_id)"=>"countstats",'a.*'))
		->join('left join __COMPANYBULL_STATS__ b on b.bull_id = a.id 
		')
		->where($where)->count();	
		
		$page = new AppPage($total, $showrow);
		if ($total > $showrow) {
			$data['page'] =  $page->myde_write();
		}
		
		$data['data'] = $this->alias('a')
		->field(array("count(b.bull_id)"=>"countstats",'a.*'))
		->join('left join __COMPANYBULL_STATS__ b on b.bull_id = a.id
		')
		->where($where)		
		->group('a.title')//user_id
		->order('a.addtime desc')//a.id
		->limit(($curpage - 1) * $showrow.','.$showrow)		
		->select();
		//print_r($this->_Sql());
		return $data;
	}
	
	
	//添加前
	public function _before_insert(&$data, $option){
		
		$data['addtime'] = time();
		$data['user_id'] = I('post.user_id');
		$data['pic']  =  json_encode(app_upload_image('/Uploads/Companybull'));
		//$data['pic']  =  '/Uploads/Companybull/2017-12-01/5a20ce89b28a2.jpg';
		$data['file'] = json_encode(app_upload_bull('/Uploads/Companybull/file'),JSON_UNESCAPED_UNICODE);
		
	}
	

	
	
	
	
	
	
	
	

}