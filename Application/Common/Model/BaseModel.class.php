<?php
/******************************************************************
* 
* ����ģ��
* 
* 
* 
*/

namespace Common\Model;
use Think\Model;
/** 
* ���� Model
 */
 
class BaseModel extends Model{
	  /**
     * ��ȡȫ������
     * @param  string $type  tree��ȡ���νṹ level��ȡ�㼶�ṹ
     * @param  string $order ����ʽ   
     * @return array         �ṹ����
     */
    public function getTree($type='tree',$order='',$name='name',$child='id',$parent='pid'){
        // �ж��Ƿ���Ҫ����
       // �ж��Ƿ���Ҫ����
        if(empty($order)){
            $data=$this->select();
        }else{
            $data=$this->order($order.' is null,'.$order)->select();
        }
        // ��ȡ���λ��߽ṹ����
        if($type=='tree'){
            $data=\Org\Nx\Data::tree($data,$name,$child,$parent);
        }elseif($type="level"){
            $data=\Org\Nx\Data::channelLevel($data,0,'&nbsp;',$child);
        }
        return $data;
    }
    

}
?>