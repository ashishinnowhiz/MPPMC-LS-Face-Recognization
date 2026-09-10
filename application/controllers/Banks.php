<?php 

class Banks extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->load->model("BanksModel");
        $this->load->model("SubjectsModel");
        $this->load->library('form_validation');
        $this->load->library("curl");
    }

    public function index(){
        $subject_code = $_GET['filter'];
        $this->page($subject_code);
    }

    public function page($subject_code = ""){

        if($subject_code != ""){
            $data['subject_code'] = $subject_code;
        }
        $data['subjects'] = $this->SubjectsModel->get_subjects();
        $data['details'] = $this->BanksModel->get_bank_details();
        $data['banks'] = array();

        for($i = 0 ;  $i < count($data['details']) ; $i++){
            $examiner = $this->BanksModel->get_examiner_by_email($data['details'][$i]['user_email'],$subject_code);

            if(!empty($examiner)){
                $examiner_name = $examiner['examiner_name'];
                $examiner_designation = $examiner['examiner_designation'] == "Chief_Examiner" ? "Reviewer Examiner" : "Examiner";
            }else{
                $examiner =$this->BanksModel->get_evaluator_by_email($data['details'][$i]['user_email'],$subject_code);
                $examiner_name = $examiner['evaluator_name'];
                $examiner_designation = "Associate Examiner";
            }

            $data['details'][$i]['examiner_name'] = $examiner_name;
            $data['details'][$i]['role'] = $examiner_designation;
        }

        for($i = 0 ; $i < count($data['details']) ; $i++){
            if($data['details'][$i]['examiner_name'] != ""){
                array_push($data['banks'],$data['details'][$i]);
            }
        }
        
        $this->load->view('header');
        $this->load->view('bank-detail',$data);
        $this->load->view('footer');
    }


    
    public function addBankDetails(){
        // $this->load->library('encryption');

        $this->form_validation->set_rules('beneName','Beneficiary Name','required');
        $this->form_validation->set_rules('account','Accoun Number','required');
        $this->form_validation->set_rules('bene_bank_name','Beneficiary Bank Name','required');
        $this->form_validation->set_rules('branch_name','Branch Name','required');
        $this->form_validation->set_rules('ifsc_code','IFSC Code','required');
        $this->form_validation->set_rules('bank_type','Bank Type','required');
        $this->form_validation->set_rules('city','city','required');
        $this->form_validation->set_rules('pan','PAN Number','required');

        if ($this->form_validation->run() === true) {
            $email = $_SESSION[$this->config->item('exam')['exam_session']]['user_email'];
            $insert = array(
                'beneficiary_name'=>$this->input->post('beneName'),
                'account_number'=>$this->input->post('account'),
                'beneficiary_bank_name'=>$this->input->post('bene_bank_name'),
                'branch_name'=>$this->input->post('branch_name'),
                'ifsc_code'=>$this->input->post('ifsc_code'),
                'account_type'=>$this->input->post('bank_type'),
                'city'=>$this->input->post('city'),
                'pan'=>$this->input->post('pan'),
                'user_email'=>$email
            );

            $result = $this->BanksModel->addBankDetails($insert,$email);

            if($result == true){
                $this->session->set_flashdata('success', "Bank Details Added Successfully.");
            }else{
                $this->session->set_flashdata('error', "Your Bank Details are alerady Exist.");
            }

        } else {
           $this->session->set_flashdata('error', validation_errors());
       }
       redirect('welcome');

    }

    public function exportBankDetails($subject_code = "") {
       
        $data['details'] = $this->BanksModel->get_bank_details();
        $data['banks'] = array();
    
        for($i = 0; $i < count($data['details']); $i++) {
            $user_email = $data['details'][$i]['user_email'];
            $examiner = $this->BanksModel->get_examiner_by_email($user_email, $subject_code);
    
            if(!empty($examiner)) {
                $examiner_name = $examiner['examiner_name'];
                $examiner_designation = $examiner['examiner_designation'] == "Chief_Examiner" ? "Review Examiner" : "Examiner";
            } else {
                $examiner = $this->BanksModel->get_evaluator_by_email($user_email, $subject_code);
                $examiner_name = $examiner['evaluator_name'];
                $examiner_designation = "Associate Examiner";
            }
    
            array_unshift($data['details'][$i], $examiner_designation);
            array_unshift($data['details'][$i], $examiner_name);
        }
    
        for($i = 0; $i < count($data['details']); $i++) {
            $data['details'][$i]['account_number'] = $data['details'][$i]['account_number'];
            if(!empty($data['details'][$i][0])) {
                array_push($data['banks'], $data['details'][$i]);
            }
        }
    
        $header = [
            0 => "Examiner Name",
            1 => "Designation",
            "user_email" => "Email",
            "beneficiary_name" => "Beneficiary Name",
            "account_number" => "Account Number",
            "beneficiary_bank_name" => "Bank Name",
            "branch_name" => "Branch Name",
            "ifsc_code" => "IFSC Code",
            "account_type" => "Account Type",
            "city" => "City",
            "pan" => "PAN Card Number"
        ];
    
        array_unshift($data['banks'], $header);
    
        $filename = "BankDetails_" . date('Y-m-d') . ".xlsx";
        $this->load->library('excel');
        $this->excel->array_to_xls_new($data['banks'], $filename);
    }
    
    public function viewBanksInfo(){
        $data['details'] = $this->BanksModel->get_bank_details();
        echo json_encode($data['details']);
    }

    public function syncBankDetails() {
        $this->output->set_content_type('application/json');
        try {
            $details = $this->BanksModel->get_bank_details();
            
            $body = $details;
            
            $token = $_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
            
            $response = $this->curl->call("banks", "POST", $body, $token);
            
            echo $response;
        } catch (Exception $error) {
            echo $error->getMessage();
        }
    }
    

}

?>