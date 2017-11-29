<?php
/**
 * 公告模型
 */
namespace App\Model;
use Common\Model\BaseModel;
use Common\Third\AppPage;
class BulletinModel extends BaseModel{

	
	public function search(){

		//搜索
		$where = array();
		$title = I('get.title');
		if ($title) {
			$where['title'] = array('like',"%$title%");
		}
		//翻页
		$showrow = 15; //一页显示的行数
		
		$curpage = I('post.page',1); //当前的页,还应该处理非数字的情况

		$total = $this->where($where)->count();	

		$page = new AppPage($total, $showrow);
		if ($total > $showrow) {
			$data['page'] =  $page->myde_write();
		}
		
		$data['data'] = $this->where($where)->limit(($curpage - 1) * $showrow.','.$showrow)->order('id desc')->select();

		return $data;
	}

	public function _before_insert(&$data,$option){
		$data['addtime'] = time();

	}



}