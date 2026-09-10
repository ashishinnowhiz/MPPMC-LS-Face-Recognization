<?php


defined('BASEPATH') or exit('No direct script access allowed');



if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");

    $this->api->response($this->api->json($error), 400);
}


                

if ($this->user['user_role']=='Head_Evaluator') {
    $this->load->model('ExaminersModel');

    $examiner=$this->ExaminersModel->get_examiner_byname($this->user['user_name']);

    foreach ($examiner as $key => $val) {
        $data['user'][str_replace("examiner", "head_evaluator", $key)]=$val;
    }

    $this->api->response($this->api->json($data), 200);
} elseif ($this->user['user_role']=='Evaluator') {
    $this->load->model('EvaluatorsModel');

    $evaluator=$this->EvaluatorsModel->get_evaluator_byname($this->user['user_name']);

    foreach ($evaluator as $key => $val) {
        $data['user'][$key]=$val;
    }
}


                   $data['success']=true;


                   $this->api->response($this->api->json($data), 200);
