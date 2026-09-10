<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user']) || ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator')) {
            redirect('login');
        }
                $this->load->model('SettingsModel');
    }
    public function index()
    {
        $settings = $this->SettingsModel->get_settings();
        if (sizeof($settings)>0) {
            $data=array();
            foreach ($settings as $setting) {
                $data[$setting['setting_for']]=$setting['setting_json'];
            }
            
            $this->load->view('header');
            $this->load->view('settings', $data);
            $this->load->view('footer');
        } else {
            echo "no settings in records";
        }
    }
    
    public function update($setting_for)
    {
        if ($setting_for=='ftp') {
            $this->load->library('ftp');
            $config['hostname'] = $this->input->post('host');
            $config['username'] = $this->input->post('username');
            $config['password'] = $this->input->post('password');
            $config['port'] = $this->input->post('port');
            if ($this->ftp->connect($config)) {
                $list = $this->ftp->list_files('/');
                if (!in_array('synced', $list)) {
                    $this->ftp->mkdir('synced');
                }
                if (!in_array('uploads', $list)) {
                    $this->ftp->mkdir('uploads');
                }
                                                        
                                        $folders = $this->ftp->list_files('/uploads/');
                                        $this->load->model('RegionsModel');
                                        $data['regions'] = $this->RegionsModel->get_regions();
                foreach ($data['regions'] as $region) {
                    if (!in_array($region['region_code'], $folders)) {
                        $this->ftp->mkdir('uploads/'.$region['region_code']);
                    }
                }
            } else {
                $ftperror=true;
            }
        }
        if (isset($ftperror)) {
            $this->session->set_flashdata('error', 'Error in connecting FTP Server Please input correct details');
        } else {
            $update_data['setting_json']=json_encode($this->input->post);
            $response=$this->SettingsModel->update_setting($setting_for, $update_data);
            if (!$response['success']) {
                $this->session->set_flashdata('error', $response['message']);
            } else {
                $this->session->set_flashdata('success', 'Data Updated Succesfully');
            }
        }
                    redirect(base_url().'settings');
    }

    //code written by vikas
    
    public function evaluation_status(){
        $status = json_encode($this->input->post());
        $data['setting_json'] = $status;
        $this->SettingsModel->update_setting('evaluation',$data);
        $this->session->set_flashdata('success', 'Evaluation Setting Updated Successfully');
        redirect('welcome');
    }
    //code end here
}
