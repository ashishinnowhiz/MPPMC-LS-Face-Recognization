<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Download extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user']) || ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator')) {
            redirect('login');
        }
                $this->load->model('SheetsModel');
    }
    public function index()
    {
                $this->load->library('curl');
        if ($this->curl->is_connected() && ($_SESSION[$this->config->item('exam')['exam_session']]['user_token']!='')) {
            $data['online']=true;
        } else {
            $data['online']=false;
        }
                
                $data['total'] = $this->SheetsModel->get_sheets_count();
                $data['log'] = $this->SheetsModel->get_result_log();
                
        foreach ($data['log'] as $log) {
            $data['downloaded'][$log['result_date']]=$log['result_count'];
            $data['time'][$log['result_date']]=$log['result_time'];
        }
                
                $data['results'] = $this->SheetsModel->get_results();
				//print_r($data['results']);
                $this->load->view('header');
                $this->load->view('download', $data);
                $this->load->view('footer'); 
    }
    public function json($date)
    {
                $data['sheets'] = $this->SheetsModel->get_result_sheets($date);
                $this->SheetsModel->update_result_download($date, sizeof($data['sheets']));
                $name = "RESULT".$_SESSION[$this->config->item('exam')['exam_session']]['user_center']."-".date('Ymdhis', time());
                $this->load->library('zip');
                $this->zip->add_data('data.json', json_encode($data));
                $this->zip->archive("zip/".$name.".zip");
                $this->zip->download($name.'.zip');
    }
    public function files($date)
    {
                $data['sheets'] = $this->SheetsModel->get_result_sheets($date);
                //$this->SheetsModel->update_result_download($date,sizeof($data['sheets']));
                $name = "RESULT".$_SESSION[$this->config->item('exam')['exam_session']]['user_center']."-".date('Ymdhis', time());
                $this->load->library('zip');
                $this->zip->add_data('data.json', json_encode($data));
        foreach ($data['sheets'] as $sheet) {
            $this->zip->add_data($sheet['sheet_file'], read_file('answersheets/'.$sheet['allocation_id'].'/'.substr($sheet['sheet_file'], 0, -4).'_checked.pdf'));
        }
                $this->zip->archive("zip/".$name.".zip");
                $this->zip->download($name.'.zip');
    }
    public function test()
    {
                    $sheet['sheet_status']='Checked';
                    $sheet['evaluation_marks ']='90';
                    $sheet['evaluation_date ']=date('Y-m-d', time());
                    $this->db->where('sheet_status', 'Allocated');
                    //$this->db->limit($quantity);
        if ($this->db->update('sheets', $sheet)) {
            echo  $this->db->affected_rows();
        } else {
            echo "error";
        }
    }
    public function syncresult($date)
    {
            $this->load->model('AttendanceModel');
            $attendance=$this->AttendanceModel->get_attendance($date);
            
            $data['sheets'] = $this->SheetsModel->get_result_sheets($date);
            $time=date('Y-m-d H:i:s', time());
            $this->load->model('EvaluationModel');
            $where['sync_status'] = 0;
            $evaluations = $this->EvaluationModel->get_all_evals($where);
            //print_r($evaluations);
            $this->SheetsModel->update_result_download($date, sizeof($data['sheets']));
            $name = "RESULT".$_SESSION[$this->config->item('exam')['exam_session']]['user_center']."-".date('Ymdhis', time()).".json";
            $json_data=json_encode($data);
        if (!write_file('uploads/json/'.$name, $json_data)) {    //echo 'Unable to write the file';
        }
                $this->load->library('curl');
                $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
                $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
                $body['sheets']=$data['sheets'];
                $body['attendance']=$attendance;
                $body['evals']=$evaluations;
                $response=$this->curl->call('result', 'POST', $body, $token);
                
                        $obj=json_decode($response);
                        $activity['activity_type']='Sync';
                        $activity['activity_data']=json_encode($obj->updated);
                        $this->ActivitiesModel->add_activity($activity);
        if (!$obj->eval_sync_error) {
            $this->db->set('sync_status', 1);
            $this->db->where('evaluation_time <', $time);
            $this->db->update('evaluation');
        }
            echo $response;
    }
    public function createJson($date)
    {
            $this->load->model('AttendanceModel');
            $data['attendance']=$this->AttendanceModel->get_attendance($date);
            $data['sheets'] = $this->SheetsModel->get_result_sheets($date);
            $this->load->model('EvaluationModel');
            $where['sync_status'] = 0;
            $where['evaluation_date'] = $date; 
            $data['evaluations'] = $this->EvaluationModel->get_all_evals($where);
            $data['time']=time();
            //print_r($evaluations);
            //$this->SheetsModel->update_result_download($date, sizeof($data['sheets']));
            $name = "RESULT".$date.'-'.$data['time'].".json";
            $json_data=json_encode($data);
            $output['success']=false;
            $output['message']="Data colletion for sync not done properly";
        if (write_file('uploads/json/'.$name, $json_data)) {
            $output['attendance']=sizeof($data['attendance']);
            $output['sheets']=sizeof($data['sheets']);
            $output['evaluations']=sizeof($data['evaluations']);
            $output['success']=true;
            $output['message']="File successfully created";
            $output['file']=$name;
        }
        echo json_encode($output);
    }

    public function syncJson($file, $name, $row)
    {
        $filename = "uploads/json/".$file;
        $json = file_get_contents($filename);
        $obj=json_decode($json);
        $data=$obj;
        if ($name!='' && isset($obj->$name)) {
            $data=$obj->$name;
            if ($row!='' && isset($data[$row])) {
                $data=$data[$row];
            }
        }
        //echo json_encode($data);
        $this->load->library('curl');
        $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
        $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
        $body[$name]=$data;
        $response=$this->curl->call($name, 'GET', $body, $token);
        // $obj=json_decode($response);
print_r($name,$response);
        echo $response;
    }
    public function updateSync($date, $count)
    {
        $this->SheetsModel->update_result_download($date, $count);
        $activity['activity_type']='Sync';
        $activity['activity_data']=$count." result for ".$date." synced";
        $this->ActivitiesModel->add_activity($activity);
        $output['success']=true;
        $output['message']="Record Updated";
        echo json_encode($output);
    }
}
