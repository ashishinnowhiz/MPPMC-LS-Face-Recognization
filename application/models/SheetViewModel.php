<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SheetViewModel extends CI_Model
{
    public function checkSheet($id)
    {
			$this->db->where('sheet_encode ', $id);
			$this->db->limit(1);
			$query=$this->db->get('sheet_view');
			return $query->result_array();
			//echo $this->db->last_query();
    }
    public function addSheet($data)
    {
		$data['sheet_view_date']=date('Y-m-d H:i:s', time());
        if ($this->db->insert('sheet_view',$data)) {
           
            return true;
        } else {
            return false;
        }
    }
	public function updateSheet($data,$id)
    {
		$data['sheet_view_date']=date('Y-m-d H:i:s', time());
		$this->db->where('sheet_encode ', $id);
        if ($this->db->update('sheet_view',$data)) {
           
            return true;
        } else {
            return false;
        }
    }
    
   
}
