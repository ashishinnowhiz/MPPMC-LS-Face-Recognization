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
		// $this->viewSheet($path);
		$this->showframe($path);
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
		//echo"<pre>";print_r($sheet);die;
		if(sizeOf($sheet)==0){
			$data['sheet_encode']=$path;
			$data['sheet_checked']=$sheetUrl;
			$data['sheet_view_count']=1;
			$this->SheetViewModel->addSheet($data);
		}
        	$this->load->library('aws');
		$url = $this->aws->get_file_url($sheetUrl);
		if($sheet[0]['sheet_view_count']>10){
			$data['sheet_file']="Limit Cross";
			$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
			// $this->SheetViewModel->updateSheet($data,$path);
			echo "<div align='center'><img src='".base_url()."web/closed-icon.webp' /></div>";die;
			$d['success']=false;
			$this->api->response($this->api->json($d), 200);
		}else{
			$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
			//$this->SheetViewModel->updateSheet($data,$path);
        	}

		$pdfContent = file_get_contents($url);
		// echo"<pre>";print_r($pdfContent);die;
		if ($pdfContent === false) {
    			echo "Failed to fetch PDF.";
    			exit;
		}

		// header('Content-Type: application/pdf');
		// header('Content-Disposition: inline; filename="document.pdf"');
		// header('Content-Length: ' . strlen($pdfContent));
		// readfile($url);
		//echo $pdfContent;

		// $pathD = base64_encode($url);
		// $d['encodedPath'] = $pathD;
                // $this->load->view("view_pdf",$d);
		exit;
		echo '<script>
    			document.addEventListener("contextmenu", function(e) {
      				e.preventDefault();
    			});

    			document.addEventListener("keydown", function(e) {
      				if ((e.ctrlKey && ["s", "u", "p"].includes(e.key.toLowerCase())) || e.key === "F12") {
					e.preventDefault();
				}
			});
  		</script>';
		echo '<iframe src="'.$url.'#toolbar=0&navpanes=0&scrollbar=0" title="" width="100%" height="100%"></iframe>';
		exit;
		// $d['url']=$url;
		// $d['success']=true;
		// $this->api->response($this->api->json($d), 200);
		// $this->load->view("qc_view_script",$d);
	}else{
		$d['success']=false;
		$this->api->response($this->api->json($d), 200);
	}
    }

    public function showframe()
    {
		$path=$this->api->_request['path'];
		$sheetUrl=base64_decode($path);

		$sheet=$this->SheetViewModel->checkSheet($path);
		if(sizeOf($sheet)==0){
			$data['sheet_encode']=$path;
			$data['sheet_checked']=$sheetUrl;
			$data['sheet_view_count']=1;
			$this->SheetViewModel->addSheet($data);
		}

		// echo"<pre>";print_r($sheet);die;

		if($sheet[0]['sheet_view_count']>=5){
			$data['sheet_file']="Limit Cross";
			$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
			$this->SheetViewModel->updateSheet($data,$path);
			echo "<div align='center'><img src='".base_url()."web/closed-icon.webp' /></div>";die;
			$d['success']=false;
			$this->api->response($this->api->json($d), 200);
		}else{
			$data['sheet_view_count']=intval($sheet[0]['sheet_view_count'])+1;
			$this->SheetViewModel->updateSheet($data,$path);
		}

		$this->load->library('aws');
		$url = $this->aws->get_file_url($sheetUrl);
		$pathD = $url.'#toolbar=0'; //base64_encode($url);

		$key = "12345678901234567890123456789012"; // 32 bytes for AES-256
		$iv = "1234567890123456"; // 16 bytes IV

		$encrypted = $this->encrypt($pathD, $key, $iv);

        $d['encodedPath'] = $encrypted; // $pathD;;
        $d['view_count'] = 5-(intval($sheet[0]['sheet_view_count'])+1);
        $this->load->view("view_pdf",$d);
    }

    public function viewClose()
    {
		include("web/limit-view-closed.php");
    }

    public function encrypt($data, $key, $iv) {
	    $cipher = "AES-256-CBC";
	    $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
	    return base64_encode($encrypted);
	}
}

