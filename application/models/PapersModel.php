<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PapersModel extends CI_Model
{

    public function get_papers()
    {
                    $this->db->where('paper_deleted', '0');
                    $query = $this->db->get('papers');
                    return $query->result_array();
    }
    
    public function get_paper($id)
    {
                    $this->db->where('paper_id', $id);
                    $query = $this->db->get('papers');
                    return $query->row_array();
    }
    public function get_papers_count()
    {
                    //$this->db->where('paper_deleted', '0');
                    return $this->db->count_all_results('papers');
    }
    public function get_paper_by_code($code)
    {
                    $this->db->select('papers.*,subjects.subject_name');
                    $this->db->from('papers');
                    $this->db->join('subjects','subjects.subject_code=papers.subject_code');
                    $this->db->where('papers.paper_code', $code);
                    $query = $this->db->get();
                    return $query->row_array();
    }
    public function get_papersdetails($paper_code)
    {
                    $this->db->where('paper_code', $paper_code);
                    $query = $this->db->get('papers');
                    return $query->row_array();
    }
    public function add_paper($paper)
    {
                    $paper['paper_created_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
        if ($this->check_paper_code($paper['paper_code'])>0) {
            $data['success']=false;
            $data['error']=409;
            $data['message']='paper code Exist';
            return $data;
        }
                    
        if ($this->db->insert('papers', $paper)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }
    public function update_paper($paper)
    {
                    $existing=$this->get_paper_by_code($paper['paper_code']);
        if ($existing['paper_updated_time']<$paper['paper_updated_time']) {
            //$paper['paper_updated_time']=date('Y-m-d h:i:s',time());
            $this->db->where('paper_code', $paper['paper_code']);
            if ($this->db->update('papers', $paper)) {
                $data['success']=true;
                $data['message']='Successfully Updated';
                return $data;
            } else {
                $data['success']=false;
                $data['message']='Error Updating Data';
                return $data;
            }
        } else {
            $data['success']=false;
            $data['message']='No Update';
            return $data;
        }
    }
    public function delete_paper($id)
    {
                    $paper['paper_deleted']=1;
                    $this->db->where('paper_id', $id);
        if ($this->db->update('papers', $paper)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function check_paper_code($code)
    {
                    $this->db->where('paper_code', $code);
                    $this->db->limit(1);
                    $query = $this->db->get('papers');
                    return $query->num_rows();
    }
}
