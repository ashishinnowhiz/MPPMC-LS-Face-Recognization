<?php


defined('BASEPATH') or exit('No direct script access allowed');



if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");

    $this->api->response($this->api->json($error), 400);
}


                

if ($this->user['user_role']=='Evaluator') {
    $this->load->model('ReportsModel');

    $_SESSION[$this->config->item('exam')['exam_session']]['user_role']='Evaluator';

    if (isset($this->api->_request['date'])) {
        $date=$this->api->_request['date'];
    } else {
        $date=date('Y-m-d', time());
    }

    $data['success']=true;

    $checksheets= $this->ReportsModel->evaluator_reports($date, $this->user['user_name']);
    $serial=0;
    $serial_array=array();
    foreach ($checksheets as $row) {
        $serial=$serial+1;
        $row['srno']=$serial;
        $serial_array[]=$row;
    }
    $data['CheckedSheets']=$serial_array;

    $stats = $this->ReportsModel->evaluator_stats($date, $this->user['user_name']);

    foreach ($stats as $key => $val) {
        $data[$key]=$val;
    }

    $data['date']=$date;

    $this->api->response($this->api->json($data), 200);
} elseif ($this->user['user_role']=='Head_Evaluator') {
    $this->load->model('ReportsModel');

    $_SESSION[$this->config->item('exam')['exam_session']]['user_role']='Head_Evaluator';

    if (isset($date)) {
        $date=date('Y-m-d', time());
    } else {
        $date=date('Y-m-d', time());
    }

    $data['success']=true;

    $reportsheets= $this->ReportsModel->head_reports($date, $this->user['user_name'], true);

    $csheets=array();
    $serial=0;

    foreach ($reportsheets as $row) {
        $serial=$serial+1;
        $row['srno']=$serial;

        $row['evaluation_marks']=$row['head_evaluation_marks'];

        $csheets[]=$row;
    }

    $data['CheckedSheets']=$csheets;


                    

    $stats = $this->ReportsModel->head_stats($date, $this->user['user_name'], true);

    foreach ($stats as $key => $val) {
        $data[$key]=$val;
    }

    $data['date']=$date;

    $this->api->response($this->api->json($data), 200);
} else {
    $error = array('success' => false, "msg" => "Invalid User");

    $this->api->response($this->api->json($error), 403);
}
