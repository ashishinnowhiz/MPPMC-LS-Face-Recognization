<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Papers extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('PapersModel');
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = '')
    {
            $this->load->library('pagination');
            $config['base_url'] = base_url().'papers/page/';
            $config['total_rows'] = 4;
            $config['per_page'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            
            $this->load->model('SubjectsModel');
            $data['subjects'] = $this->SubjectsModel->get_subjects();
            $this->load->model('RegionsModel');
            $data['regions'] = $this->RegionsModel->get_regions();
            $this->load->model('MediumsModel');
            $data['mediums'] = $this->MediumsModel->get_mediums();
            
            $data['papers'] = $this->PapersModel->get_papers();
            $this->load->view('header');
            $this->load->view('papers', $data);
            $this->load->view('footer');
    }
    
    public function xml($paper_code)
    {
            $paper=$this->PapersModel->get_paper_by_code($paper_code);
            echo $paper['marking_scheme_json'];
    }
    public function marktable($paper_code)
    {
            $data['papersmaking'] = $this->PapersModel->get_papersdetails($paper_code);
            $this->load->view('forms/view_paper_marking', $data);
    }
}
