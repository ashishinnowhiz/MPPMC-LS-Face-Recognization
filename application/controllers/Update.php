<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Update extends CI_Controller
{

    public function index()
    {
            $this->db->where('sheet_synced', '1');
            $this->db->where('final_marks', '0.00');
            $query = $this->db->get('sheets');
            $sheets = $query->result_array();
        foreach ($sheets as $sheet) {
            if (file_exists('answersheets/'.$sheet['allocation_id'].'/'.$sheet['sheet_file'])) {
                if (filesize('answersheets/'.$sheet['allocation_id'].'/'.$sheet['sheet_file'])<10000) {
                    echo $sheet['sheet_file']."-";
                    $sheet_data['sheet_synced']=0;
                    $sheet_data['sheet_status']='Pending';
                    $this->db->where('allocation_id', $sheet['allocation_id']);
                    $this->db->where('sheet_file', $sheet['sheet_file']);
                    if ($this->db->update('sheets', $sheet_data)) {
                        $allocation['allocation_sync_status']='Pending';
                        $allocation['allocation_synced_files']=0;
                        $this->db->where('allocation_id', $sheet['allocation_id']);
                        if ($this->db->update('allocation_log', $allocation)) {
                            echo "updated<br/>";
                        }
                    } else {
                        echo "error<br/>";
                    }
                }
            }
        }
    }
    
    public function assign($count = 1)
    {
        $this->load->model('EvaluatorsModel');
        $data['evaluators'] = $this->EvaluatorsModel->get_evaluators();
        foreach ($data['evaluators'] as $evaluator) {
                        $where_array= array(
                            'subject_code' => $evaluator['subject_code'],
                            'medium_code' => $evaluator['medium_code']
                        );
                        $sheet['sheet_status']='Assigned';
                        $sheet['examiner_username']=$evaluator['examiner_username'];
                        $sheet['evaluator_code']=$evaluator['evaluator_username'];
                        $this->db->where($where_array);
                        $this->db->where('sheet_status', 'Pending');
                        $this->db->where('sheet_synced', 1);
                        $this->db->limit($count);
            if ($this->db->update('sheets', $sheet)) {
                echo "<br/>".$this->db->affected_rows()." Assigned to ".$evaluator['evaluator_username'];
            } else {
                echo "<br/>Error Assigning to ".$evaluator['evaluator_username'];
            }
        }
    }
    
    public function alter()
    {
        if ($this->db->query("ALTER TABLE sheets MODIFY COLUMN sheet_file VARCHAR(40) NOT NULL")) {
            echo "<br/>Field length altered";
        }
        if ($this->db->query("ALTER TABLE `sheets` ADD UNIQUE( `sheet_file`, `allocation_id`)")) {
            echo "<br/>Unique key added";
        }
        unlink(__FILE__);
    }
}
