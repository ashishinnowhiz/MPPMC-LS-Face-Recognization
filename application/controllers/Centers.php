<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Centers extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user']) || ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Admin')) {
            redirect('login');
        }
                $this->load->model('CentersModel');
    }
    public function index()
    {
            $this->page();
    }
    public function page($page = '')
    {
            $this->load->library('pagination');
            $config['base_url'] = base_url().'centers/page/';
            $config['total_rows'] = 4;
            $config['per_page'] = 2;
            $this->pagination->initialize($config);
            $data['pagination']=$this->pagination->create_links();
            
            $data['centers'] = $this->CentersModel->get_centers();
            $this->load->view('header');
            $this->load->view('centers', $data);
            $this->load->view('footer');
    }
    public function add()
    {
        if ($this->input->post('name', true)!='') {
            $center['center_name']=$this->input->post('name', true);
            $center['center_code']=$this->input->post('code', true);
            $center['center_address']=$this->input->post('address', true);
            $center['center_phone']=$this->input->post('phone', true);
            $center['center_contact_person']=$this->input->post('person', true);
            $center['center_email']=$this->input->post('email', true);
            $data = $this->CentersModel->add_center($center);
            if (!$data['success']) {
                    $this->session->set_flashdata('error', $data['message']);
            } else {
                $this->session->set_flashdata('success', $data['message']);
            }
                redirect(base_url().'centers');
        } else {
            $data['success']=false;
            $data['message']="Invalid Data";
        }
            echo json_encode($data);
    }
    public function update()
    {
                    $center['center_name']=$this->input->post('name');
                    $center['center_code']=$this->input->post('code');
                    $center['center_address']=$this->input->post('address');
                    $center['center_phone']=$this->input->post('phone');
                    $center['center_contact_person']=$this->input->post('person');
                    $center['center_email']=$this->input->post('email');
                    $center_id=$this->input->post('id');
                    
                    $response=$this->CentersModel->update_center($center_id, $center);
        if (!$response['success']) {
            $this->session->set_flashdata('error', $response['message']);
        } else {
            $this->session->set_flashdata('success', 'Data Updated Succesfully');
        }
                    redirect(base_url().'centers');
    }
    public function edit($center_id)
    {
            $data['center']=$this->CentersModel->get_center($center_id);
            $this->load->view('forms/edit_center', $data);
    }
    public function remove($center_id)
    {
        if ($this->CentersModel->delete_center($center_id)) {
            $status='success';
            $message='Successfully deleted';
        } else {
            $status='error';
            $message='Error deleting record';
        }
            $this->session->set_flashdata($status, $message);
            redirect(base_url().'centers');
    }
        
    public function export()
    {
            $this->load->dbutil();
            $query = $this->db->query("SELECT * FROM centers");
            $delimiter = ",";
            $newline = "\r\n";
            $enclosure = '"';
            $data=$this->dbutil->csv_from_result($query, $delimiter, $newline, $enclosure);
            $this->load->helper('file');
            $this->load->helper('download');
            force_download('centers.csv', $data);
    }
        
    public function importcsv()
    {
        $data['error'] = '';    //initialize image upload error array to empty
 
        $config['upload_path'] = 'uploads/csv/centers/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000';
 
        $this->load->library('csvimport');
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
 
 
        // If upload failed, display error
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
            $this->session->set_flashdata('error', $data['error'].$config['upload_path']);
            redirect(base_url().'centers');
        } else {
            $file_data = $this->upload->data();
            $file_path =  $config['upload_path'].$file_data['file_name'];
 
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                foreach ($csv_array as $row) {
                    $insert_data = array(
                        'center_name'=>$row['CenterName'],
                        'center_code'=>$row['CenterCode'],
                        'center_address'=>$row['Address'],
                        'center_phone'=>$row['Phone'],
                        'center_contact_person'=>$row['ContactPerson'],
                        'center_email'=>$row['Email'],
                        'center_contact_email'=>$row['ContactEmail']
                    );
                    $response=$this->CentersModel->add_center($insert_data);
                    if (!$response['success']) {
                        $data['error'][]=$response['message'];
                    }
                }
                $this->session->set_flashdata('error', implode('<br/>', $data['error']));
                
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'centers');
                //echo "<pre>"; print_r($insert_data);
            } else {
                $data['error'] = "Error occured";
            }
                $this->load->view('csvindex', $data);
        }
    }
}
