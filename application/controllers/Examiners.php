<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Examiners extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user']) || ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator')) {
            redirect('login');
        }
                $this->load->model('ExaminersModel');
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = '')
    {       $this->load->model('SubjectsModel');
            $this->load->library('pagination');
            $config['base_url'] = base_url().'examiners/page/';
            $config['total_rows'] = 4;
            $config['per_page'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            
            $this->load->model('CentersModel');
            $data['centers'] = $this->CentersModel->get_centers();
            $data['examiners'] = $this->ExaminersModel->get_examiners();
            $this->load->view('header');
            $this->load->view('examiners', $data);
            $this->load->view('footer');
    }

    //Code Written By Vikas
    public function viewSubjectList($examiner_id){
        $this->load->model('SubjectsModel');
        $this->load->model('ExaminersModel');
        $data['examiner']=$this->ExaminersModel->get_examiner($examiner_id);
        $examiner_username = $data['examiner']['examiner_username'];
        $data['examiner_subjects'] = $this->ExaminersModel->get_examiner_subjects($examiner_username);
        $this->load->view('forms/examiner_subject_list',$data);
    }
    //Code End Here
}
