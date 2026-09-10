<?php
if (isset($this->api->_request['reason']) && preg_match('/^[a-z0-9 .\-]+$/i', $this->api->_request['reason'])) {
    $reason=$this->api->_request['reason'];
} else {
    $error = array('success' => false, "msg" => "Invalid input for reason");
    $this->api->response($this->api->json($error), 400);
}

if (isset($this->api->_request['sheet_file'])) {
    $sheet_file=$this->api->_request['sheet_file'];
} else {
    $error = array('success' => false, "msg" => "Invalid input for sheet file");
    $this->api->response($this->api->json($error), 400);
}
          

if ($this->user['user_role']=='Evaluator') {
    $sheet['sheet_file']=$sheet_file;
    $sheet['sheet_remarks']=$reason;
    if (isset($this->api->_request['other']) && preg_match('/^[a-z0-9 .\-]+$/i', $this->api->_request['other'])) {
        $sheet['reject_reason']=$this->api->_request['other'];
    }

    $this->load->model('SheetsModel');
    $sheet=$this->SheetsModel->reject_sheet($this->user['user_name'], $sheet);
    if ($sheet) {
        $data['success']=true;
        $data['message']='Status Updated';
        $this->api->response($this->api->json($data), 200);
    } else {
        $data['success']=false;
        $data['message']='Error Updating';
        $this->api->response($this->api->json($data), 200);
    }
} else {
    $error = array('success' => false, "message" => "Invalid User");
    $this->api->response($this->api->json($error), 404);
}
