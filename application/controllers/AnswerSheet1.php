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
		$sheetUrl=base64_decode($path);
//echo $sheetUrl;
			if(sizeOf($sheet)==0){
				$data['sheet_encode']=$path;
				$data['sheet_checked']=$sheetUrl;
				$data['sheet_view_count']=1;
				$this->SheetViewModel->addSheet($data);
                
                $this->load->library('aws');
            
				$url = $this->aws->get_file_url($sheetUrl);
				if($sheet[0]['sheet_view_count']>1){
					$data['sheet_file']="Limit Cross";
					$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
					$this->SheetViewModel->updateSheet($data,$path);
					echo "<div align='center'><img src='".base_url()."web/closed-icon.webp' /></div>";die;
					$d['success']=false;
					$this->api->response($this->api->json($d), 200);
				}else{
					$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
					$this->SheetViewModel->updateSheet($data,$path);
                    $this->load->library('aws');
					$url = $this->aws->get_file_url($sheetUrl);
// echo $url;
$pdfContent = file_get_contents($url);
if ($pdfContent === false) {
    echo "Failed to fetch PDF.";
    exit;
}
//$pdfData = [
//	'pdfContent' => $pdfContent
//];
header('Content-Type: application/pdf');
// header('Content-Disposition: inline; filename="document.pdf"');
header('Content-Length: ' . strlen($pdfContent));
// $this->load->view('view_pdf', $pdfData);
echo $pdfContent;
//  echo '<iframe src="'.$pdfContent.'#toolbar=0" title=""></iframe>';
exit;
echo '<script>
    
    document.addEventListener("contextmenu", function(e) {
      e.preventDefault();
    });

    
    document.addEventListener("keydown", function(e) {
      if (
        (e.ctrlKey && ["s", "u", "p"].includes(e.key.toLowerCase())) ||
        e.key === "F12"
      ) {
        e.preventDefault();
      }
    });
  </script>';
					echo '<iframe src="'.$url.'#toolbar=0&navpanes=0&scrollbar=0" title="" width="100%" height="100%"></iframe>';
					// header("Location: ".$url);
					exit;
					// $d['url']=$url;
					// $d['success']=true;
					// $this->api->response($this->api->json($d), 200);
					//$this->load->view("qc_view_script",$d);
				}
					// echo '<iframe src="'.$url.'" title=""></iframe>';
				// header("Location: ".$url);
				// exit;
				// $d['url']=$url;
				// $d['success']=true;
				// $this->api->response($this->api->json($d), 200);
				//$this->load->view("qc_view_script",$d);
			}else{
				//if($sheet[0]['sheet_view_count']>4){
				if($sheet[0]['sheet_view_count']>9){
					$data['sheet_file']="Limit Cross";
					$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
					$this->SheetViewModel->updateSheet($data,$path);
					echo "<div align='center'><img src='".base_url()."web/closed-icon.webp' /></div>";die;
					$d['success']=false;
					$this->api->response($this->api->json($d), 200);
				}else{
					$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
					$this->SheetViewModel->updateSheet($data,$path);
                    $this->load->library('aws');
					$url = $this->aws->get_file_url($sheetUrl);
// echo $url;
$pdfContent = file_get_contents($url);
if ($pdfContent === false) {
    echo "Failed to fetch PDF!";
    exit;
}
//$pdfData = [
  //      'pdfContent' => $pdfContent
//];
header('Content-Type: application/pdf');
//header('Content-Disposition: inline; filename="document.pdf"');
header('Content-Length: ' . strlen($pdfContent));
//$this->load->view('view_pdf', $pdfData);
 echo $pdfContent;
 // echo '<iframe src="'.$pdfContent.'#toolbar=0" title=""></iframe>';
exit;
echo'<script>
    
    document.addEventListener("contextmenu", function(e) {
      e.preventDefault();
    });

    
    document.addEventListener("keydown", function(e) {
      if (
        (e.ctrlKey && ["s", "u", "p"].includes(e.key.toLowerCase())) ||
        e.key === "F12"
      ) {
        e.preventDefault();
      }
    });
  </script>';
					echo '<iframe src="'.$url.'#toolbar=0&navpanes=0&scrollbar=0" title="" width="100%" height="100%"></iframe>';
					// header("Location: ".$url);
					exit;
					// $d['url']=$url;
					// $d['success']=true;
					// $this->api->response($this->api->json($d), 200);
					//$this->load->view("qc_view_script",$d);
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
