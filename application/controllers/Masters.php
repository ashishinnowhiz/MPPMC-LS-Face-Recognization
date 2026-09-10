<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Masters extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
    }
    public function index()
    {
            
            $this->load->model('SubjectsModel');
            $data['subjects'] = $this->SubjectsModel->get_subjects();
            $this->load->model('RegionsModel');
            $data['regions'] = $this->RegionsModel->get_regions();
            $this->load->model('MediumsModel');
            $data['mediums'] = $this->MediumsModel->get_mediums();
            $this->load->model('PapersModel');
            $data['papers'] = $this->PapersModel->get_papers();
            
            $this->load->view('header');
            $this->load->view('masters', $data);
            $this->load->view('footer');
    }
}
