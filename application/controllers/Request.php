<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Request extends CI_Controller
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
            $this->load->model('PapersModel');
            $data['papers'] = $this->PapersModel->get_papers();
            $this->load->view('header');
            $this->load->view('request', $data);
            $this->load->view('footer');
    }

    public function update()
    {
            $this->load->model('PapersModel');
            $papers= $this->PapersModel->get_papers();
            $_SESSION[$this->config->item('exam')['exam_session']]['user_center']."<br/>";
        if ($this->input->post('minus')) {
            foreach ($papers as $paper) {
                if (in_array($paper['paper_code'], $this->input->post('minus'))) {
                    if ($this->SheetsModel->unallocate($_SESSION[$this->config->item('exam')['exam_session']]['user_center'], $paper['paper_code'])) {
                        $success[]="All pending scripts of paper code(".$paper['paper_code'].") Unallocated";
                    } else {
                        $error[]="Error unallocating scripts of paper code(".$paper['paper_code'].")";
                    }
                }
            }
        } else {
            foreach ($papers as $paper) {
                if ($this->input->post('v'.$paper['paper_code'])>0) {
                    $this->load->library('curl');
                    $body['username']=$_SESSION[$this->config->item('exam')['exam_session']]['username'];
                    $body['user_center']=$_SESSION[$this->config->item('exam')['exam_session']]['user_center'];
                    $body['paper_code']=$paper['paper_code'];
                    $body['quantity']=$this->input->post('v'.$paper['paper_code']);
                        
                    $token=$_SESSION[$this->config->item('exam')['exam_session']]['user_token'];
                        
                    $response=$this->curl->call('request', 'POST', $body, $token);
                    //echo $response;
                    $obj=json_decode($response);
                        
                    if ($obj->affected>0) {
                        if ($obj->affected<$this->input->post('v'.$paper['paper_code'])) {
                            $error[]="You requested ".$this->input->post('v'.$paper['paper_code'])." But only ".$obj->affected." scripts of paper code(".$paper['paper_code'].") was available to allocate";
                        }
                        $success[]=$obj->affected." scripts of paper code(".$paper['paper_code'].") Allocated";
                    } else {
                        $error[]="No more scripts of paper code(".$paper['paper_code'].") available to allocate";
                    }
                }
            }
        }
        if (isset($error)) {
            $this->session->set_flashdata('error', implode('<br/>', $error));
        }
        if (isset($success)) {
            $success[]="Allocation is processing on CS server.Please click on 'Check New Allocation' button to sync allocation";
            $this->session->set_flashdata('success', implode('<br/>', $success));
        }
            redirect('upload');
    }
        
    public function call_curl($url, $method = 'GET', $body = '')
    {
            //$url='http://cxv;
            //$body=json_encode($body);
            $timeout=30;
            $verify_ssl   = false;
            
            //'Cache-Control: no-cache',
            /*
            $headers = array(
                            'Content-Type: application/json',
                            'Content-Length: '.strlen($body)
                            );
                            */
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verify_ssl);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            //curl_setopt($ch, CURLOPTthis->input->postFIELDS, $body);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
             
            //echo $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $result;
    }
}
