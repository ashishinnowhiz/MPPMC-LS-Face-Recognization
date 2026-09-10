<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sheets extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user']) || $_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator') {
            redirect('login');
        }
        $this->load->model('SheetsModel');
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = 0)
    {
            $this->load->library('pagination');
            $config['base_url'] = base_url().'sheets/page/';
            $config['total_rows'] = $this->SheetsModel->get_sheets_count();
            $config['per_page'] = 2500;
            
            $config['num_links'] = 2;
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['num_tag_open'] = '<li style="padding-right: 10px;">';
            $config['num_tag_close'] = '</li>';
            $config['num_tag_open'] = '<li style="padding-right: 10px;">';
            $config['num_tag_close'] = '</li>';
            $config['first_tag_open'] = '<li style="padding-right: 10px;">';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li style="padding-right: 10px;">';
            $config['last_tag_close'] = '</li>';
            $config['next_tag_open'] = '<li style="padding-right: 10px;">';
            $config['next_tag_close'] = '</li>';
            $config['prev_tag_open'] = '<li style="padding-right: 10px;">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li style="padding-right: 10px;" class="paginate_button active"><a>';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li style="padding-right: 10px;">';
            $config['num_tag_close'] = '</li>';
            
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            $data['serial']=$page;
            $limit['start']=$page;
            $limit['per_page'] = $config['per_page'];
            
            $data['sheets'] = $this->SheetsModel->get_sheets($limit);
            
            $this->load->view('header');
            $this->load->view('sheets', $data);
            $this->load->view('footer');
    }
	public function sheetSearch($page = 0)
    {
			$d="";
			if(isset($_GET['txtSearch'])){
				$d=$_GET['txtSearch'];
			}
            $limit['start']=0;
            $limit['per_page'] =10000;
            $data['sheets'] = $this->SheetsModel->sheet_search($limit,$d);
            $this->load->view('header');
            $this->load->view('sheets', $data);
            $this->load->view('footer'); 
    }
    public function datewise($status = 'Pending')
    {
        if ($status=='checked') {
            //code alter by Vikas
            $where="(sheet_status='Checked' OR sheet_status='Rechecked' OR sheet_status='ReMarking')";
        } else {
            $where['sheet_status']=$status;
        }
            $data['reports'] = $this->SheetsModel->get_datewise($where);
            $data['status'] = $status;
            // echo $status;
            $this->load->view('header');
            $this->load->view('datewise', $data);
            $this->load->view('footer');
    }
    
    public function rejected()
    {       $this->load->model('SubjectsModel');
            $data['sheets'] = $this->SheetsModel->get_rejected();
            $this->load->view('header');
            $this->load->view('rejected', $data);
            $this->load->view('footer');
    }

     //Code Written By Vikas on 26/04/23
     public function reject_course_wise(){

        $course = $this->input->post('course');
        $data['selected_course'] = $course;

        if($course == 'All'){
             redirect('sheets/rejected');
        }else{
         $this->load->model('SubjectsModel');
         $subj_code = array();
         $data['subjects']= $this->SubjectsModel->get_subjects_course_wise($course);

         for($i = 0 ; $i < count($data['subjects']) ; $i++){
           array_push( $subj_code, $data['subjects'][$i]['subject_code']);
         }
         $data['sheets_subj_code'] = $this->SheetsModel->get_rejected();
         $sheet_subject = array();
         for($j = 0 ; $j < count($data['sheets_subj_code']) ; $j++){

             if(in_array($data['sheets_subj_code'][$j]['subject_code'],$subj_code)){
               array_push($sheet_subject,$data['sheets_subj_code'][$j]);
             }
         }
         $data['sheets'] = $sheet_subject;
         $this->load->view('header');
         $this->load->view('rejected', $data);
         $this->load->view('footer');
        }
 }
}
