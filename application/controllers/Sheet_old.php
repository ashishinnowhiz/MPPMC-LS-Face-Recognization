<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sheet extends CI_Controller
{
    public $user;
    public $token;
    public function __construct()
    {
        parent::__construct();
		$this->load->library('custlog');
        $this->load->library('api');
        $this->load->model('ApiModel');
		if(!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
		$this->user['user_name']=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
		$this->user['user_role']=$_SESSION[$this->config->item('exam')['exam_session']]['user_role'];
		//$_SESSION[$this->config->item('exam')['exam_session']]['user_role'];
        //$this->authenticate();
    }
    public function index()
    {
        $data['message']='Authentication Failed';
        $this->api->response($this->api->json($data), 401);
    }
    public function authenticate()
    {
        if (isset($_SESSION[$this->config->item('exam')['exam_session']]['auth_token'])) {
            $this->token=$_SESSION[$this->config->item('exam')['exam_session']]['auth_token'];
            $this->user=$this->ApiModel->check_user_token($this->token);
            $username=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
            if ($this->user==false || ($this->user['user_name']!=$username) || ($this->user['user_login_ip']!=$_SERVER['REMOTE_ADDR'])) {
                $data['success']=false;
                $data['message']=$this->user['user_name'].' Invalid Auth Token';
                $this->api->response($this->api->json($data), 401);
            } else {
                if ($this->ApiModel->update_attendance($username)) {
                } else {
                    $error = array('success' => false, "msg" => "Error Updating Attendance");
                    $this->api->response($this->api->json($error), 403);
                }
            }
        } else {
            $error = array('success' => false, "msg" => "Authentication Error");
            $this->api->response($this->api->json($error), 403);
        }
    }

    public function get()
    {
		$this->load->model('ApiModel');
					 $user=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
					$this->ApiModel->update_login_time($user);
        include('Api/include/getsheet.php');
    }
    public function reject()
    {
		$this->load->model('ApiModel');
					 $user=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
					$this->ApiModel->update_login_time($user);
        include('Api/include/rejectsheet.php');
    }
    public function save()
    {
		$this->load->model('ApiModel');
		$user=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
		$this->ApiModel->update_login_time($user);
			$sheetpdf=explode('.',$this->api->_request['sheet_file']);
			if($this->user['user_role']=='Evaluator'){
				 $sheeturl=$this->user['user_name']."_".$sheetpdf[0]."_checked.pdf";
			}elseif($this->user['user_role']=='Head_Evaluator'){
				$sheeturl=$this->user['user_name']."_".$sheetpdf[0]."_checked_re.pdf";
			}elseif($this->user['user_role']=='Head_Marker'){
				$sheeturl=$this->user['user_name']."_".$sheetpdf[0]."_checked_remarking.pdf";
			} 
			
			 $path="answersheets/uploads/".date('Y-m-d')."/".$sheeturl;
				if(file_exists($path)){
					$this->custlog->storeLog("Sheet save:".json_encode($this->api->_request['sheet_file']));
					 include('Api/include/savesheet.php');
				}else{
					$this->custlog->storeLog("Sheet PDF Not save:".json_encode($this->api->_request['sheet_file']));
					$data['success']=false;
					$data['message']='Something went wrong. Try Again!';
					$this->api->response($this->api->json($data), 200);
				} 
			
			
        
    }
    public function details()
    {
        $url=$this->api->_request['url'];
        $path=$url.'img';
        $countFile=$path.'/count.txt';
		
        if (file_exists($countFile)==false) {
           /*  require_once APPPATH."/third_party/fpdf181/fpdf.php";
            require_once APPPATH."/third_party/FPDI/src/autoload.php";
            $pdf = new setasign\Fpdi\Fpdi();
            // get the page count
            $count = $pdf->setSourceFile($this->api->_request['url']); */

/*             $image = new Imagick();
            $image->pingImage($this->api->_request['url']);
            $count =  $image->getNumberImages();
            $image->clear();
            $image->destroy();
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($file, $count); */

            require_once APPPATH."/third_party/PdfToText/PdfToText.phpclass";
            $pdf        =  new PdfToText($url, PdfToText::PDFOPT_DECODE_IMAGE_DATA);
           $imageCount    =  count($pdf -> Images);
				
		
		 
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
			
            file_put_contents($countFile, $imageCount);
			 
            if ($imageCount) {
                for ($i = 0; $i  <  $imageCount; $i ++) {
                    // Get next image and generate a filename for it (there will be a file named "sample.x.jpg"
                    // for each image found in file "sample.pdf")
                    $img        =  $pdf -> Images [$i] ;   // This is an object of type PdfImage
                    //$imgindex   =  sprintf("%02d", $i + 1) ;
                    $file=$path.'/'.$i.'.jpg';
					
                    // Save the image (the default is IMG_JPG, but you can specify another IMG_* image type by specifying it
                    // as the second parameter)
                    $img -> SaveAs($file);
					$img='';
					$this->imgCompress($file,$file,'80');
					
					
                }
            }
        }
        $count=file_get_contents($countFile);
        $data['pageCount']=$count; //returns 2
        $data['success']=true;
        $this->api->response($this->api->json($data), 200);
    }
    public function page($page)
    {
        //$page=$_GET['num'];
        $url=$this->api->_request['url'];
        $path=$url.'img';
		 if($this->api->_request['ans']&& $this->api->_request['ans']=='ans'){
			$file=$path.'/'.$page.'.jpg';
		}else{
			if($page==0){
				$file="img/s.jpg";
			}else{
			$file=$path.'/'.$page.'.jpg';
			}
		} 
		//$file=$path.'/'.$page.'.jpg';
        if (file_exists($file)) {
            $im = imagecreatefromjpeg($file);
            header('Content-Type: image/jpg');
            imagejpeg($im);
            imagedestroy($im);
        } else {
            $im = new imagick();
            $size = $this->getSize();
            /* if($size['width'] > 300){
                $multiplier=300/$size['width'];
                $res= 100 * $multiplier;
                $im->setResolution($res, $res);
            } */
            if ($size['multiplier'] < 1) {
                $res= 300 * $size['multiplier'];
                $im->setResolution($res, $res);
            }
            //$im->setResolution(300, 300);
            $im->readImage($url.'['.$page.']');
            //$im->scaleImage(800,0);
            $im->setImageFormat('png');
            $im->setImageBackgroundColor('white');
            $im->setImageAlphaChannel(11);
            $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            //$im->setCompression(Imagick::COMPRESSION_JPEG);
            //$im->setCompressionQuality(80);

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($file, $im);
            header('Content-Type: image/png');
            echo $im;
            $im->clear();
            $im->destroy();
        }
    }

    public function rotate($page)
    {
        $data['status']=false;
        //$page=$_GET['num'];
        $url=$this->api->_request['url'];
        $path=$url.'img';
        $file=$path.'/'.$page.'.jpg';
        if (file_exists($file)) {
            $this->load->library('image_lib');
            $config['source_image'] = $file;
            $config['new_image'] = $file;
            $config['rotation_angle'] = '180';
            $this->image_lib->initialize($config);

            if (!$this->image_lib->rotate())
            {
                $data['message']=$this->image_lib->display_errors();
            }else{
                $data['status']=true;
            }
        }
        echo json_encode($data);
    }
    
    public function deletePages()
    {
        $url=$this->api->_request['url'];
        $path=$url.'img';
        /* require_once APPPATH."/third_party/fpdf181/fpdf.php";
        require_once APPPATH."/third_party/FPDI/src/autoload.php";
        $pdf = new setasign\Fpdi\Fpdi();
            // get the page count
        $pageCount = $pdf->setSourceFile($this->api->_request['url']); */

        $countfile=$path.'/count.txt';
        $pageCount=file_get_contents($countfile);

        // iterate through all pages
        for ($pageNo = 0; $pageNo < $pageCount; $pageNo++) {
            unlink($path . '/'.$pageNo.'.jpg');
        }
        $file=$path.'/count.txt';
        unlink($file);
        rmdir($path);
        $data['pageCount']=$pageCount; //returns 2
        $data['success']=true;
        $this->api->response($this->api->json($data), 200);
    }

    public function scheme()
    {
        $paper_code=$this->api->_request['paper_code'];
        $this->load->model('PapersModel');
        $paper=$this->PapersModel->get_paper_by_code($paper_code);
        if (sizeof($paper)>0) {
            if ($paper['marking_scheme_json']!='') {
                $data['success']=true;
                $data['json']=json_decode($paper['marking_scheme_json']);
                $this->api->response($this->api->json($data), 200);
            }
        }
    }
    public function addlog()
    {
        $time=date("Y-m-d H:i:s", time());
        $log['eval_page'] = $this->api->_request['page'];
        $log['eval_action'] = $this->api->_request['drawTool'];
        $details['x'] = $this->api->_request['x'];
        $details['y'] = $this->api->_request['y'];
        $details['width'] = $this->api->_request['width'];
        $details['height'] = $this->api->_request['height'];
        $details['commentText'] = $this->api->_request['commentText'];
        if ($log['eval_action']=='score') {
            $details['score'] = $this->api->_request['score'];
        }
        $log['eval_details'] = json_encode($details);
        $log['eval_que_index']=$this->api->_request['que'];
        $log['eval_time'] = $time;

        $this->load->model('EvalModel');
        $resp=$this->EvalModel->addLog($log);
        $resp['time']=$time;
        $this->api->response($this->api->json($resp), 200);
    }

    public function undo()
    {
        $this->load->model('EvalModel');
        $resp=$this->EvalModel->undo($this->api->_request['page'], $this->api->_request['action']);
        $this->api->response($this->api->json($resp), 200);
    }
    public function redo()
    {
        $this->load->model('EvalModel');
        $resp=$this->EvalModel->redo($this->api->_request['page'], $this->api->_request['action']);
        $this->api->response($this->api->json($resp), 200);
    }
    public function pageLog($page)
    {
        $this->load->model('EvalModel');
        $data['actions']=$this->EvalModel->pageLog($page);
        $data['success']=true;
        $this->api->response($this->api->json($data), 200);
    }
    public function resolution()
    {
        $data['size'] = $this->getSize();
        $data['success']=true;
        $this->api->response($this->api->json($data), 200);
    }
    public function getSize()
    {
        /* require_once APPPATH."/third_party/fpdf181/fpdf.php";
        require_once APPPATH."/third_party/FPDI/src/autoload.php";
        $pdf = new setasign\Fpdi\Fpdi();

        $pdf->setSourceFile($this->api->_request['url']);
        // import page 1
        $templateId = $pdf->importPage(1);
        // get the size of the imported page
        return $pdf->getTemplateSize($templateId); */

        $url = $this->api->_request['url'];
        $path=$url.'img';
        $file=$path.'/0.jpg';
        if (!file_exists($file)) {
            $im = new imagick();
            $im->setResolution(300, 300);
            $im->readImage($url.'[0]');
            $im->setImageFormat('png');
            $im->setImageBackgroundColor('white');
            $im->setImageAlphaChannel(11);
            $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($file, $im);
            $im->clear();
            $im->destroy();
        }
        $size = getimagesize($file);
        $w = $size[0]/30;

        $res['width'] = $size[0]/30;
        $res['height'] = $size[1]/30;
        $res['multiplier'] = 1366/$size[0];
        if ($w < 210) {
            $res['width'] = 210;
            $res['height'] = (210 * $size[1])/$size[0];
        } elseif ($w > 300) {
            $res['width'] = 300;
            $res['height'] = (300 * $size[1])/$size[0];
        }
        return $res;
    }
	public function saveMarkingLog()
    {
		$this->load->model('EvalModel');
		 $sheetMarkObj=$this->api->_request['sheetObj'];
		
		foreach($sheetMarkObj as $sheetResult){
			$log[]=array(
			'eval_page'=>$sheetResult['eval_page'],
			'eval_action'=>$sheetResult['eval_action'],
			'eval_details'=>$sheetResult['eval_details'],
			'eval_que_index'=>$sheetResult['eval_que_index'],
			'eval_time'=>$sheetResult['eval_time'],
			'eval_role'=>$sheetResult['eval_role'],
			'sheet_file'=>$_SESSION['sheet_file'],
			'user_name'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_name'],
			'user_role'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_role']
			);
		} 
		$this->custlog->storeLog("Marking Log:".json_encode($log));
		$resp=$this->EvalModel->addLogSheet($log);
		 if($resp['success']){
			$data['success']=true;
        $this->api->response($this->api->json($data), 200);
		}else{
			$this->custlog->storeLog("Marking Not Save Log:".json_encode($log));
			$data['success']=false;
			$this->api->response($this->api->json($data), 200);
		} 
		
	}
	public function getCheckedSheet()
    {
		$this->load->model('EvalModel');
		$sheetMarkObj=$this->api->_request['sheetObj'];
		
		foreach($sheetMarkObj as $sheetResult){
			$log[]=array(
			'eval_page'=>$sheetResult['eval_page'],
			'eval_action'=>$sheetResult['eval_action'],
			'eval_details'=>$sheetResult['eval_details'],
			'eval_que_index'=>$sheetResult['eval_que_index'],
			'eval_time'=>$sheetResult['eval_time'],
			'sheet_file'=>$_SESSION['sheet_file'],
			'user_name'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_name'],
			'user_role'=>$_SESSION[$this->config->item('exam')['exam_session']]['user_role']
			);
		} 
	$this->custlog->storeLog("Marking Log Get:".json_encode($log));
		$resp=$this->EvalModel->addLogSheet($log);
		 if($resp['success']){
			$data['success']=true;
        $this->api->response($this->api->json($data), 200);
		}else{
			$data['success']=false;
			$this->api->response($this->api->json($data), 200);
		} 
		
	}
    public function saveImage()
    {
        $path='uploads/images/'.$_SESSION[$this->config->item('exam')['exam_session']]['user_name'].'/'.$_SESSION['sheet_file'];
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $data = $_POST['img'];
        $file = $path . '/'.$_POST['page'].'.png';
        // remove "data:image/png;base64,"
        $data = str_replace('data:image/png;base64,', '', $data);
        // save to file
        file_put_contents($file, base64_decode($data));
        $output['file']=$file;
        $output['success']=true;
        $this->api->response($this->api->json($output), 200);
    }
    public function savePdf()
    {
		$this->load->model('ApiModel');
					 $user=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
					$this->ApiModel->update_login_time($user);
        $json=$this->api->_request['json'];
	$this->load->model('EvalModel');
		
        $header = array('Question No', 'Marks', 'Removed');
        $rows=array();
        $totalMarks = 0;
        foreach ($json as $obj) {
            if($obj['Question_No']=='parent'){
                $row = array('Que '.$obj['MinScore'], '', '');
            }else if($obj['Question_No']=='condition'){
                $row = array('Any '.$obj['MinScore'].' from '.$obj['MaxScore'], '', '');
            }else{
                $removed='';
                if ($obj['removed']=='true'){
                    $removed='Yes';
                } elseif ($obj['allotedMarks']=='NA') {
                } else {
                    $totalMarks = $totalMarks + floatval($obj['allotedMarks']);
                }
                $row = array($obj['Question_No'], $obj['allotedMarks'], $removed);
            }
            array_push($rows, $row);
        }
        $footer = array('Total', $totalMarks, '');
        //array_push($rows, $row);

        $path='uploads/images/'.$_SESSION[$this->config->item('exam')['exam_session']]['user_name'].'/'.$_SESSION['sheet_file'];
                
        $imageSize = $this->getSize();
        $size = array($imageSize['width'], $imageSize['height']);
        require_once APPPATH."/third_party/fpdf181/fpdf.php";
        $pdf = new FPDF();
        $pdf->AddPage('L', $size);
        // Select Arial bold 15
        $pdf->SetFont('Arial', 'B', 15);
        // Move to the right
        $pdf->Cell(80);
        // Framed title
        $pdf->Cell(30, 10, 'Mark Summary', 0, 0, 'C');
        // Line break
        $pdf->Ln(20);
        $pdf->FancyTable($header, $rows, $footer);
        
        $url=$this->api->_request['url'];
        $imagePath=$url.'img';
        $countfile=$imagePath.'/count.txt';
        $pageCount = intval(file_get_contents($countfile));

        // iterate through all pages
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            
            $pdf->AddPage('L', $size);
            $imageNo=$pageNo-1;
								
				
            $pdf->Image($imagePath . '/'.$imageNo.'.jpg', 0, 0, $imageSize['width'], $imageSize['height']);
            $pdf->Image($path . '/'.$pageNo.'.png', 0, 0, $imageSize['width'], $imageSize['height']);
            //unlink($path . '/'.$pageNo.'.png');
        }
        //rmdir($path);

        $lastPos = strrpos($_SESSION['sheet_file'], ".");
        $fileName = substr($_SESSION['sheet_file'], 0, $lastPos)."_checked.pdf";
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator') {
            $fileName = substr($_SESSION['sheet_file'], 0, $lastPos)."_checked_re.pdf";
        }
		if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker') {
            $fileName = substr($_SESSION['sheet_file'], 0, $lastPos)."_checked_remarking.pdf";
        }
        $path='answersheets/uploads/'.date('Y-m-d', time());
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $filePath=$path.'/'.$this->user['user_name']."_".$fileName;
        $pdf->Output($filePath, 'F');
        $data['success']=true;
        $this->api->response($this->api->json($data), 200);
    }
	function imgCompress($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}
}
