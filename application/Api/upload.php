<?php


defined('BASEPATH') or exit('No direct script access allowed');



if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");

    $this->api->response($this->api->json($error), 400);
}


                

if (isset($this->api->_request['sheet_file'])) {
    $sheet_file=$this->api->_request['sheet_file'];
} else {
    $error = array('success' => false, "msg" => "Invalid input for sheet file");

    $this->api->response($this->api->json($error), 400);
}


                

if (isset($this->api->_request['sheet_url'])) {
    $sheet_url=$this->api->_request['sheet_url'];
} else {
    $error = array('success' => false, "msg" => "Invalid input for sheet url");

    $this->api->response($this->api->json($error), 400);
}

if ($this->user['user_role']=='Evaluator') {
    $this->load->model('SheetsModel');

    $sheet=$this->SheetsModel->get_sheet_by_file($sheet_file);

    if ($this->user['user_name']==$sheet['evaluator_code']) {
        if (file_exists($sheet_url)) {
            $this->load->library('curl');

            $body['file'] = new CurlFile($sheet_url);

            $body['sheet_file'] = $sheet_file;

            $body['username']=$this->user['user_name'];

            echo $response=$this->curl->upfile($body, 'savefile');

            //$obj=json_decode($response);

            //$this->api->response($response, 200);
        } else {
            $data['success']=false;

            $data['message']="Checked file does not exist";
        }

        $this->api->response($this->api->json($data), 200);
    } else {
        $data['success']=false;

        $data['message']='Cant verify evaluator';

        $this->api->response($this->api->json($data), 200);
    }
} else {
    $error = array('success' => false, "msg" => "Invalid User");

    $this->api->response($this->api->json($error), 404);
}
