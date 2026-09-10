<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MediumsModel extends CI_Model
{

    public function get_mediums()
    {
                    $this->db->where('medium_deleted', '0');
                    $query = $this->db->get('mediums');
                    return $query->result_array();
    }
    
    public function get_medium($id)
    {
                    $this->db->where('medium_id', $id);
                    $query = $this->db->get('mediums');
                    return $query->row_array();
    }
    
    public function add_medium($medium)
    {
        if ($this->check_medium_code($medium['medium_code'])>0) {
            $data['success']=false;
            $data['message']='Medium Code '.$medium['medium_code'].' Exist';
            return $data;
        }
        if ($this->db->insert('mediums', $medium)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
    public function update_medium($medium_id, $medium)
    {
        if ($this->check_medium_code($medium['medium_code'])>0) {
            $data['success']=false;
            $data['message']='Medium Code '.$medium['medium_code'].' Exist';
            return $data;
        }
                    $this->db->where('medium_id', $medium_id);
        if ($this->db->update('mediums', $medium)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    public function delete_medium($id)
    {
                    $medium['medium_deleted']=1;
                    $this->db->where('medium_id', $id);
        if ($this->db->update('mediums', $medium)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function check_medium_code($code)
    {
                    $this->db->where('medium_code', $code);
                    $query = $this->db->get('mediums');
                    return $query->num_rows();
    }
}
