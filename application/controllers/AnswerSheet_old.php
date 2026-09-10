<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AnswerSheet extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
                $this->load->model('SheetViewModel');
				$this->load->library('api');
    }
    public function index()
    {
		//echo $this->api->_request['path'];
        if(isset($this->api->_request['path']) && $this->api->_request['path']!=''){
			$path=$this->api->_request['path'];
			$this->viewSheet($path);
		}else{
			
			$path='';
		}
    }
	public function viewSheet()
    {
		$path=$this->api->_request['path'];
		if($path!=''){
		$sheet=$this->SheetViewModel->checkSheet($path);
			if(sizeOf($sheet)==0){
				$data['sheet_encode']=$path;
				$data['sheet_checked']=base64_decode($path);
				$data['sheet_view_count']=1;
				$this->SheetViewModel->addSheet($data);
				$d['success']=true;
				$this->api->response($this->api->json($d), 200);
			}else{
				//if($sheet[0]['sheet_view_count']>4){
				if($sheet[0]['sheet_view_count']>2){
					$data['sheet_file']="Limit Cross";
					$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
					$this->SheetViewModel->updateSheet($data,$path);
					$d['success']=false;
					$this->api->response($this->api->json($d), 200);
				}else{
					$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
					$this->SheetViewModel->updateSheet($data,$path);
					$d['success']=true;
					$this->api->response($this->api->json($d), 200);
				}
			}
		}else{
			$d['success']=false;
			$this->api->response($this->api->json($d), 200);
		}
		
	}
	public function viewClose()
    {
		include("web/limit-view-closed.php");
	}
	 
}
