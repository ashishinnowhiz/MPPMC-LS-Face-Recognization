<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ExaminersModel extends CI_Model
{

    public function get_examiners()
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
        }
                    $this->db->where('examiner_deleted', '0');
                    $query = $this->db->get('examiners');
                    return $query->result_array();
    }
//Code Added By Vikas
    public function get_examiners_m()
    {
                        $this->db->where('examiner_username', $_SESSION[$this->config->item('exam')['exam_session']]['user_name']);
            $this->db->where('examiner_deleted', '0');
             $q = $this->db->get('examiners');
              foreach($q->result_array() as $result){
      
                                        $this->db->where('examiner_org', $result['examiner_org']);
                                        $this->db->where('examiner_designation', 'Head_Evaluator');
                                        $this->db->where('examiner_deleted', '0');
                                        $query = $this->db->get('examiners');
                                  return $query->result_array();
                          }
    }
    
    
//Code End Here
    
    public function get_examiner($id)
    {
                    $this->db->where('examiner_id', $id);
                    $query = $this->db->get('examiners');
                    return $query->row_array();
    }
    public function get_examiner_byname($user_name)
    {
                    $this->db->where('examiner_username', $user_name);
                    $query = $this->db->get('examiners');
                    return $query->row_array();
    }
    public function add_examiner($examiner)
    {
        if ($this->check_examiner_username($examiner['examiner_username'])>0) {
            $data['success']=false;
            $data['error']=409;
            $data['message']='Examiner Username '.$examiner['examiner_username'].' Exist';
            return $data;
        }
        if ($this->db->insert('examiners', $examiner)) {
            $data['success']=true;
            $data['message']='Successfully Added';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Adding Data';
            return $data;
        }
    }

     //code written by vikas
     public function add_examiner_subject($examiner_subject)
     {
        if ($this->check_examiner_subject($examiner_subject['examiner_username'],$examiner_subject['course_code'],$examiner_subject['subject_code'])>0) {
            $data['success']=false;
            $data['error']=409;
            $data['message']='The Examiner Username '.$examiner_subject['examiner_username'].' is already assigned'.$examiner_subject['course_code'].'and'.$examiner_subject['subject_code'];
            return $data;
        }
         if ($this->db->insert('examiner_course_subject', $examiner_subject)) {
             $data['success']=true;
             $data['message']='Successfully Added';
             return $data;
         } else {
             $data['success']=false;
             $data['message']='Error Adding Data';
             return $data;
         }
     }

    public function check_examiner_subject($username,$courseCode,$subjectCode){
        $this->db->where(['examiner_username'=>$username,'course_code'=>$courseCode,'subject_code'=>$subjectCode]);
        $query = $this->db->get('examiner_course_subject');
        return $query->num_rows();
    }

     public function get_examiner_subjects($username){
            $this->db->where('examiner_username',$username);
            $query = $this->db->get('examiner_course_subject');
            return $query->result();
     }
     //code end here
    public function update_examiner($examiner)
    {
                    $examiner['examiner_updated_time']=date('Y-m-d h:i:s', time());
                    $examiner['examiner_updated_by']=$_SESSION[$this->config->item('exam')['exam_session']]['user'];
                    $this->db->where('examiner_username', $examiner['examiner_username']);
        if ($this->db->update('examiners', $examiner)) {
            $data['success']=true;
            $data['message']='Successfully Updated';
            return $data;
        } else {
            $data['success']=false;
            $data['message']='Error Updating Data';
            return $data;
        }
    }
    public function delete_examiner($id)
    {
                    $examiner['examiner_deleted']=1;
                    $this->db->where('examiner_id', $id);
        if ($this->db->update('examiners', $examiner)) {
            return true;
        } else {
            return false;
        }
    }
    
    
    public function check_examiner_username($code)
    {
                    $this->db->where('examiner_username', $code);
                    $query = $this->db->get('examiners');
                    return $query->num_rows();
    }
}
