<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Practice extends CI_Controller
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
		
		//$_SESSION[$this->config->item('exam')['exam_session']]['user_role'];
        //$this->authenticate();
    }
    public function index()
    {
        $data['title']='WebPilot Marking';
                $evaluator=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
                $date=date('Y-m-d', time());
                $this->load->model('EvaluatorsModel');
                $this->load->model('ReportsModel');
                $data['evaluator'] = $this->EvaluatorsModel->get_evaluator_byname($evaluator);
                $data['reports'] = $this->ReportsModel->evaluator_reports($date, $evaluator);
                $data['stats'] = $this->ReportsModel->evaluator_stats($date, $evaluator );
                $data['date']=$date;
            $headData['eval']=true;
           /*  $this->load->view('header',$headData);
            $this->load->view('dashboard-evaluator', $data);
            $this->load->view('footer'); */
            $this->load->view('jslinks');
            $this->load->view('evalpractice',$headData,$data);
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
