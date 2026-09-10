<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EvaluationModel extends CI_Model
{

    public function get_eval_count($sheet)
    {
                    $this->db->where('sheet_file', $sheet);
                    return $this->db->count_all_results('evaluation');
    }
    public function get_evals($sheet)
    {
                    $key = $this->config->item('encryption_key');
                    $this->db->select("evaluation_id,sheet_file,sheet_json_marks,sheet_assign_time,examiner_username,evaluator_username AS evaluator_code,evaluation_time,evaluation_date,evaluation_na_verified");
                    $this->db->select("AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);

                    $this->db->where('sheet_file', $sheet);
                    $this->db->where('evaluation_type', 'Regular');
                    $query = $this->db->get('evaluation');
                    return $query->result_array();
    }
     public function get_evals2($sheet)
    {
                    $key = $this->config->item('encryption_key');
                    $this->db->select("evaluation_id,sheet_file,sheet_json_marks,sheet_assign_time,examiner_username,evaluator_username AS evaluator_code,evaluation_time,evaluation_date,evaluation_na_verified");
                    $this->db->select("AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);

                    $this->db->where('sheet_file', $sheet);
                    $this->db->where('evaluation_type', 'Regular');
                    $this->db->order_by('evaluation_id','DESC');
                    $query = $this->db->get('evaluation');
                    return $query->result_array();
    }
    public function get_all_evals($where_array = '')
    {
                    $key = $this->config->item('encryption_key');
                    $this->db->select("sheet_file,sheet_json_marks,sheet_assign_time,examiner_username,evaluator_username,evaluation_time,evaluation_date,,evaluation_type,evaluation_na_verified");
                    $this->db->select("AES_DECRYPT(evaluation_marks,UNHEX(SHA2('".$key."',512))) AS evaluation_marks", false);

        if ($where_array!='') {
            $this->db->where($where_array);
        }
                    $query = $this->db->get('evaluation');
                    return $query->result_array();
    }

    public function add_eval_sheet($evaluator, $data)
    {
                    $marks=$data['evaluation_marks'];
                    unset($data['evaluation_marks']);
                    $sheet=$data;
                    $sheet['evaluator_username']=$evaluator;
                    
                    $key = $this->config->item('encryption_key');
                    $this->db->set('evaluation_marks', "AES_ENCRYPT('{$marks}',UNHEX(SHA2('".$key."',512)))", false);
                    
        if ($this->db->insert('evaluation', $sheet)) {
            $this->add_attendance_checked($evaluator);
            //$this->add_under_checked($evaluator);
            $this->add_attendance_checked($data['examiner_username'], 'under_checked');
            return true;
        } else {
            return false;
        }
    }

    public function update_eval_sheet($id, $data)
    {
                    $marks=$data['evaluation_marks'];
                    unset($data['evaluation_marks']);
                    $sheet=$data;
                    
                    $key = $this->config->item('encryption_key');
                    $this->db->set('evaluation_marks', "AES_ENCRYPT('{$marks}',UNHEX(SHA2('".$key."',512)))", false);
        $this->db->where('evaluation_id', $id);
        if ($this->db->update('evaluation', $sheet)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function add_attendance_checked($user, $column = 'attendance_checked')
    {
        $this->db->where('attendance_date', date('Y-m-d', time()));
        $this->db->where('attendance_user', $user);
        $this->db->set($column, $column.'+1', false);
        $this->db->limit(1);
        $this->db->update('attendance');
        $affected=$this->db->affected_rows();
        if ($affected>0) {
            return true;
        } elseif ($column=='under_checked') {
            if ($this->add_he_entry($user)) {
                return $this->add_attendance_checked($user, 'under_checked');
            } else {
                return false;
            }
        }
    }
    public function add_under_checked($evaluator)
    {
        $this->db->select('examiner_username');
        $this->db->where('evaluator_username', $evaluator);
        $query = $this->db->get('evaluators');
        $eval=$query->row_array();
        $affected=$this->add_attendance_checked($eval['examiner_username'], 'under_checked');
        if ($affected>0) {
            return true;
        } else {
            if ($this->add_he_entry($eval['examiner_username'])) {
                return $this->add_under_checked($evaluator);
            } else {
                return false;
            }
        }
    }
    public function add_he_entry($username)
    {
                        $time="00:00:00";
                        $attendance['attendance_user']=$username;
                        $attendance['attendance_login']=$time;
                        $attendance['attendance_logout']=$time;
                        $attendance['attendance_date']=date('Y-m-d', time());
        if ($this->db->insert('attendance', $attendance)) {
            return true;
        } else {
            return false;
        }
    }
}
