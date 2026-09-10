<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Allocate extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user']) || ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Admin')) {
            redirect('login');
        }
                $this->load->model('SheetsModel');
    }
    public function index()
    {
                $data['center_counts'] = $this->SheetsModel->get_center_sheets_count();
                $data['paper_counts'] = $this->SheetsModel->get_paper_sheets_count();
                $this->load->model('CentersModel');
                $data['centers'] = $this->CentersModel->get_centers();
                $this->load->model('PapersModel');
                $data['papers'] = $this->PapersModel->get_papers();
                $this->load->view('header');
                $this->load->view('allocate', $data);
                $this->load->view('footer');
    }
    
    public function center($center_code)
    {
            
            $data['center_counts'] = $this->SheetsModel->get_center_status($center_code);
            echo json_encode($data['center_counts']);
    }
    public function update()
    {
            $this->load->model('PapersModel');
            $papers= $this->PapersModel->get_papers();
            $this->input->post('center_code')."<br/>";
        if ($this->input->post('minus', true)) {
            foreach ($papers as $paper) {
                if (in_array($paper['paper_code'], $this->input->post('minus', true))) {
                    if ($this->SheetsModel->unallocate($this->input->post('center_code', true), $paper['paper_code'])) {
                        $success[]="All pending sheets of ".$paper['paper_code']." Unallocated";
                    } else {
                        $error[]="Error unallocating sheets of ".$paper['paper_code'];
                    }
                }
            }
        } else {
            foreach ($papers as $paper) {
                if ($this->input->post('v'.$paper['paper_code'], true)>0) {
                    if ($this->SheetsModel->allocate($this->input->post('center_code', true), $paper['paper_code'], $this->input->post('v'.$paper['paper_code'], true))) {
                        $success[]=$this->input->post('v'.$paper['paper_code'], true)." sheets of ".$paper['paper_code']." Allocated";
                    } else {
                        $error[]="Error allocating ".$this->input->post('v'.$paper['paper_code'], true)." sheets of ".$paper['paper_code'];
                    }
                }
            }
        }
        if (isset($error)) {
            $this->session->set_flashdata('error', implode('<br/>', $error));
        }
        if (isset($success)) {
            $this->session->set_flashdata('success', implode('<br/>', $success));
        }
            redirect(base_url().'allocate');
    }
}
