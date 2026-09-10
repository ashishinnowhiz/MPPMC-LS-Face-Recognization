<?php
defined('BASEPATH') or exit('No direct script access allowed');

class JsonModel extends CI_Model
{
    
                /*
                $this->load->model('JsonModel');
                if($this->JsonModel->add_json($json)){
                    return TRUE;
                }else{
                    return FALSE;
                }
                */
                
    public function get_json()
    {
                    $this->db->where('json_synced', '0');
                    $this->db->limit(1);
                    $query = $this->db->get('json_sync');
                    return $query->row_array();
    }
    
    public function add_json($json)
    {
                    $data['json_data']=json_encode($json);
        if ($this->db->insert('json_sync', $data)) {
            return true;
        } else {
            return false;
        }
    }
    public function update_json($json_id, $json)
    {
                    $json->json_updated_time=date('Y-m-d H:i:s', time());
                    $json->json_updated_by=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                    $this->db->where('json_id', $json_for);
        if ($this->db->update('json_sync', $json)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
}
