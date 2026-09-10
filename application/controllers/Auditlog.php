<?php

class Auditlog extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("SheetsModel");
    }

    public function index(){
      $data['sheets'] = $this->SheetsModel->get_all_script();
      $data['audits'] = $this->db->get('audit_log')->result_array();
        $this->load->view("header");
        $this->load->view("audit",$data);
        $this->load->view("footer");
    }

    public function usersLog(){
        $data = [];
        $this->load->view("header");
        $this->load->view("userslog", $data );
        $this->load->view("footer");
    }

    public function addAudit(){
        $paper_code = $_GET['sheet_file'];
        
        $data['user_name'] = $_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
        $data['sheet_file'] = $paper_code;
        $data['audit_path'] = "c:/vk/upload";
        $data['audit_time'] = date("Y-m-d H:i:s", time());
        $data['audit_remark'] = "";
        $this->db->insert('audit_log',$data);
       //echo $this->db->last_query();
        if($this->db->affected_rows() > 0){
            echo "success";
        }else{
            echo "";
        }
        
    }
    public function resMessage($result){
        if($result == "success"){
            $this->session->set_flashdata("success","Audit Created Successfully");
        }else{
            $this->session->set_flashdata("error","Something Went Wrong");     
        }

        return redirect('auditlog');
    }

    public function downloadAudit($audit_id){
        $this->load->model("AuditModel");
        $audit = $this->AuditModel->getAuditById($audit_id);
       
        if($audit){
            $sheetCode = $audit['sheet_file'];
    
            if($sheetCode != ""){
              
                $this->completedSheets($sheetCode);
                $this->answerSheets($sheetCode);
               
                $this->load->library('excel');
                $folder_path = FCPATH . 'vkfile';
               
                 $compressed = $this->excel->compressFolder($folder_path, $sheetCode.'_audit');
               
                 if($compressed){
                     $this->excel->downloadFile($compressed);
                 }else {
                    echo 'Failed to compress folder.';
                }
            } else {
                $this->session->set_flashdata("error", 'Required data is missing.');
            }
        } else {
            $this->session->set_flashdata("error", 'Audit not found.');
        }
    }

    public function completedSheets($sheetCode) {
        $sheets = $this->SheetsModel->get_sheet_by_file($sheetCode);
        $status = false;
        if (!empty($sheets)) {

            if(strtolower($sheets['sheet_status']) == "checked"){
                $user_marks = $sheets['sheet_json_marks'];
            }else if(strtolower($sheets['sheet_status']) == "remarking"){
                $user_marks = $sheets['marker_json_marks'];
            }else if(strtolower($sheets['sheet_status']) == "rechecked"){
                $user_marks = $sheets['head_json_marks'];
            }

            $marks = json_decode($user_marks, true);
    
            if (!empty($marks) && is_array($marks)) {
                $keys = array_keys($marks[0]);
    
                $title = array();
    
                for ($i = 0; $i < count($keys); $i++) {
                    $title[$keys[$i]] = $keys[$i];
                }
    
                $path = FCPATH . '/vkfile/CompletedSheets/';
                $finalExport = array_merge([$title], $marks);
    
                $this->load->library('excel');
    
                $file_name = $sheetCode; // Use the sheet code as the filename
                $result = $this->excel->array_save_as_xls($finalExport, $file_name, $path);
    
                if ($result) {
                    $status = true;
                } else {
                    $status = false;
                }

            } 
        } 

        return $status;
    }

    public function answerSheets($sheetCode) {
        $sheets = $this->SheetsModel->get_sheet_by_file($sheetCode);
        $status = false;
        if (!empty($sheets)) {

                $file = str_replace(".pdf", "", $sheetCode);

                if(strtolower($sheets['sheet_status']) == "checked"){
                    $sheet_status = "checked";
                    $user = $sheets['evaluator_code'];
                }else if(strtolower($sheets['sheet_status']) == "remarking"){
                    $sheet_status = "checked_remarking";
                    $user = $sheets['marker_username'];
                }else if(strtolower($sheets['sheet_status']) == "rechecked"){
                    $sheet_status = "checked_re";
                    $user = $sheets['examiner_username'];
                }

                $fileFormate = "answersheets/uploads/".$sheets['evaluation_date']."/".$user."_".$file."_".$sheet_status.".pdf";
                $url = base_url($fileFormate);
                $path = FCPATH.'/vkfile/AnswerSheets/';
    
                $this->load->library('excel');
    
                $file_name = $sheetCode;
                $result = $this->excel->save_file_from_url($url,$file_name,$path);
    
                if ($result) {
                    $status = true;
                } else {
                    $status = false;
                }

        } 

        return $status;
    }
    
    
    


}

?>