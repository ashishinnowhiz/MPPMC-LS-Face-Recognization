<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RegionsModel extends CI_Model
{

    public function get_regions()
    {
                    $this->db->where('region_deleted', '0');
                    $query = $this->db->get('regions');
                    return $query->result_array();
    }
    
    public function get_region($id)
    {
                    $this->db->where('region_id', $id);
                    $query = $this->db->get('regions');
                    return $query->row_array();
    }
    
    public function add_region($region)
    {
        if ($this->check_region_code($region['region_code'])>0) {
            $data['success']=false;
            $data['message']='Region Code '.$region['region_code'].' Exist';
            return $data;
        }
        if ($this->db->insert('regions', $region)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
    public function update_region($region_id, $region)
    {
        if ($this->check_region_code($region['region_code'])>0) {
            $data['success']=false;
            $data['message']='Region Code '.$region['region_code'].' Exist';
            return $data;
        }
                    $this->db->where('region_id', $region_id);
        if ($this->db->update('regions', $region)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    public function delete_region($id)
    {
                    $region['region_deleted']=1;
                    $this->db->where('region_id', $id);
        if ($this->db->update('regions', $region)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function check_region_code($code)
    {
                    $this->db->where('region_code', $code);
                    $query = $this->db->get('regions');
                    return $query->num_rows();
    }
}
