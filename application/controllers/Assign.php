<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Assign extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('SheetsModel');
    }
    public function index()
    {
                $data['paper_counts'] = $this->SheetsModel->get_paper_sheets_count();
                $data['examiner_counts'] = $this->SheetsModel->get_examiner_sheets_count();

                $this->load->model('PapersModel');
                $data['papers'] = $this->PapersModel->get_papers();
                
                $this->load->model('ExaminersModel');
                $data['examiners'] = $this->ExaminersModel->get_examiners();
                
                $this->load->view('header');
                $this->load->view('assign', $data);
                $this->load->view('footer');
    }
    
    public function update()
    {
            $this->load->model('PapersModel');
            $papers= $this->PapersModel->get_papers();
        if (isset($this->input->post('minus', true)){
            foreach ($papers as $paper) {
                if (in_array($paper['paper_code'], $this->input->post('minus', true)){
                    if ($this->SheetsModel->unallocate($this->input->post('center_code', true), $paper['paper_code'])) {
                        $success[]="All pending sheets of ".$paper['paper_code']." Unallocated";
                    } else {
                        $error[]="Error unallocating sheets of ".$paper['paper_code'];
                    }
                }
            }
        } else {
            foreach ($papers as $paper) {
                if ($this->input->post('v'.$paper['paper_code'])!='') {
                    if ($this->input->post('qty'.$paper['paper_code'])>0) {
                        $updated=$this->SheetsModel->assign($paper['paper_code'], $this->input->post('v'.$paper['paper_code'], true), $this->input->post('qty'.$paper['paper_code'], true));
                        if ($updated>0) {
                            $success[]=$updated." sheets of ".$paper['paper_code']." Allocated";
                        } else {
                            $error[]="Error allocating sheets of ".$paper['paper_code'];
                        }
                    } else {
                        $error[]="Wrong quantity for ".$paper['paper_code'];
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
            redirect('assign');
    }
}
