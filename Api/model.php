<?php


defined('BASEPATH') or exit('No direct script access allowed');



if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");

    $this->api->response($this->api->json($error), 400);
}


                   $paper_code=$this->api->_request['paper_code'];


                   $this->load->model('PapersModel');


                   $paper=$this->PapersModel->get_paper_by_code($paper_code);


                    

if (sizeof($paper)>0) {
    $data['success']=true;

    $data['paper_model_question']='answersheets/'.$paper['paper_model_question'];

    $data['paper_model_answer']='answersheets/'.$paper['paper_model_answer'];
} else {
    $data['success']=false;

    $data['message']='Wrong Paper Code';
}


                    


                   $this->api->response($this->api->json($data), 200);
