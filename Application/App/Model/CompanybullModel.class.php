<?php
/**
*  ��ҵ���˹���ģ��
*/
namespace App\Model;
use Common\Model\BaseModel;
use Common\Third\AppPage;
class CompanybullModel extends BaseModel{
	
	protected $tableName = 'company_bull';
	
	public function search(){
		$where = array();
		
			
		$where['a.user_id'] = I('post.user_id');
		
		//��ҳ
		$showrow = 15; //һҳ��ʾ������
		
		$curpage = I('post.page',1); //��ǰ��ҳ,��Ӧ�ô�������ֵ����
		
		$total = $this->alias('a')->field(array("count(b.bull_id)"=>"countstats",'a.*'))->join('left join __COMPANYBULL_STATS__ b on b.bull_id = a.id')
		->where($where)->count();	
		
		$page = new AppPage($total, $showrow);
		if ($total > $showrow) {
			$data['page'] =  $page->myde_write();
		}
		
		$data['data'] = $this->alias('a')
		->field(array("count(b.bull_id)"=>"countstats",'a.*'))
		->join('left join __COMPANYBULL_STATS__ b on b.bull_id = a.id')
		->where($where)		
		->group('a.title')//user_id
		->order('a.addtime desc')//a.id
		->limit(($curpage - 1) * $showrow.','.$showrow)		
		->select();
		//print_r($this->_Sql());
		return $data;
	}
	
	
	//���ǰ
	public function _before_insert(&$data, $option){
		
		$data['addtime'] = time();
		$data['user_id'] = I('post.user_id');
		$data['pic']  =  json_encode(app_upload_image('/Uploads/Companybull'));
		//$data['pic']  =  '/Uploads/Companybull/2017-12-01/5a20ce89b28a2.jpg';
		$data['file'] = json_encode(app_upload_bull('/Uploads/Companybull/file'),JSON_UNESCAPED_UNICODE);
		
	}
	
	
	//������ͳ��
	public function zanstats(){
		
		$where = [];
		$m = M('Companybull_zan');
		$where['bull_id']=I('post.bull_id');
		
		$zan = $m->where($where)->count();
		return $zan;
		
		
	}
	
	
	
	
	
	
	
	

}