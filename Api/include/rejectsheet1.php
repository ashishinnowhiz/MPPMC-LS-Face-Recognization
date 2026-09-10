<?php

if (isset($this->api->_request['sheet_file'])) {
    $sheet_file=$this->api->_request['sheet_file'];
} else {
    $error = array('success' => false, "msg" => "Invalid input for sheet file");
    $this->api->response($this->api->json($error), 400);
}
          


    $sheet['sheet_file']=$sheet_file;
    $sheet['sheet_remarks']="File Size issue";
   
        $sheet['reject_reason']='File Size issue';
    

    $this->load->model('SheetsModel');
    $sheet=$this->SheetsModel->reject_sheet($this->user['user_name'], $sheet);
    if ($sheet) {
        $data['success']=true;
        $data['message']='Status Updated';
        $this->api->response($this->api->json($data), 200);
		redirect("Upload2/extractScript");
    } else {
        $data['success']=false;
        $data['message']='Error Updating';
        $this->api->response($this->api->json($data), 200);
		redirect("Upload2/extractScript");
    }

