<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Upload2 extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
	            	$this->load->library('custlog');
                $this->load->model('SheetsModel');
                $this->load->model('ErrorModel');
    }
    
	public function reject($sheet_file)
    {
		$this->load->model('ApiModel');
					 $user=$_SESSION[$this->config->item('exam')['exam_session']]['user_name'];
					
         $sheet['sheet_file']=$sheet_file;
		$sheet['sheet_remarks']="File Size issue";
   
        $sheet['reject_reason']='File Size issue';
    
    $sheet=$this->SheetsModel->reject_sheet2($sheet);
		if ($sheet) {
			$data['success']=true;
			$data['message']='Status Updated';
		   // $this->api->response($this->api->json($data), 200);
			redirect("Upload2/extractScript");
		} else {
			$data['success']=false;
			$data['message']='Error Updating';
			//$this->api->response($this->api->json($data), 200);
			redirect("Upload2/extractScript");
		}
    }
	public function extractScript(){
		$this->db->where("sheet_status='Pending' or sheet_status='Assigned'");
		$data['sheets']=$this->db->get("sheets")->result_array();
		$this->load->view("header");
		$this->load->view("extract-script",$data);
		$this->load->view("footer");
	}
     public function extractNewModel($file="",$allocationId=''){
		 
         $documenturl = base_url()."answersheets/uploads/model/".$file;
		
       // $path="answersheets/".$allocationId."/".$file.'img';

        
            $data['documenturl'] = $documenturl;
            $data['saveurl'] = base_url()."upload2/saveImageModel";
            $data['counturl'] = base_url()."upload2/fileCountModel";
            //$data['allocationId'] = $allocationId;
            $data['file'] = $file;
			/*  $documenturl = $url;
          echo  $saveurl = base_url()."upload/save_image";
            $counturl = base_url()."upload/fileCount";
            $allocationId = $allocationId;
            $file = $file;*/
			
			$data['success']=true;
          //  include('Api/include/pdftoimg.php');
        $this->load->view("pdftoimgmodel",$data);
    }
 public function fileCountModel(){
	     $this->load->library('api');
	    $imageCount=$_POST['imageCount'];
	    //$allocationId=$_POST['allocationId'];
	    $file=$_POST['file'];
		$folderPath = "answersheets/uploads/model/".$file."img";
          $countFile=$folderPath.'/count.txt';
			if (!file_exists($folderPath)) {
				mkdir($folderPath, 0777, true);
			}
			
			 if (file_put_contents($countFile, $imageCount)) {
                
				$data['success']=true;
				$data['msg']="Image data saved successfully!";
              } else {
                http_response_code(500);
                $data['msg']= "Error saving image data.";
				$data['success']=false;
              }
			 // echo json_encode($data);
			  $this->api->response($this->api->json($data), 200);
 }
    public function saveImageModel(){
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
           
            $imageData = $_POST["imageData"];
            $filename = $_POST["filename"];
          //  $allocationId = $_POST["allocationId"];
            $file = $_POST["file"];
           // $count = $_POST["count"];
          $folderPath = "answersheets/uploads/model/".$file."img/";
             if(!file_exists( $folderPath)){
                
              mkdir( $folderPath , 0777, true);
              $filePath = $folderPath.$filename;
             
              $decodedImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
             
              if (file_put_contents($filePath, $decodedImageData)) {
				  $this->imgCompress($filePath, $filePath,60);
                echo "Image data saved successfully!";
              } else {
                http_response_code(500);
                echo "Error saving image data.";
              }
            }else{ 
              $filePath = $folderPath.$filename;
          
              $decodedImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
            
              if (file_put_contents($filePath, $decodedImageData)) {
				  $this->imgCompress($filePath, $filePath,60);
                echo "Image data saved successfully!";
              } else {
                http_response_code(500);
                echo "Error saving image data.";
              }
            }
          return true;
          }else{
			   return false;
		  }
		  
    }
    
    public function extractNew($file="", $allocationId=""){
		 
         $documenturl = base_url()."answersheets/".$allocationId."/".$file;
		
       // $path="answersheets/".$allocationId."/".$file.'img';

        
            $data['documenturl'] = $documenturl;
       
            $data['saveurl'] = base_url()."upload2/save_image";
            $data['counturl'] = base_url()."upload2/fileCount";
            $data['allocationId'] = $allocationId;
            $data['file'] = $file;
			/*  $documenturl = $url;
          echo  $saveurl = base_url()."upload/save_image";
            $counturl = base_url()."upload/fileCount";
            $allocationId = $allocationId;
            $file = $file;*/
			
			$data['success']=true;
          //  include('Api/include/pdftoimg.php');
        $this->load->view("pdftoimg",$data);
    }
 public function fileCount(){
	     $this->load->library('api');
	    $imageCount=$_POST['imageCount'];
	    $allocationId=$_POST['allocationId'];
	    $file=$_POST['file'];
		$folderPath = "answersheets/".$allocationId."/".$file."img";
          $countFile=$folderPath.'/count.txt';
			if (!file_exists($folderPath)) {
				mkdir($folderPath, 0777, true);
			}
			
			 if (file_put_contents($countFile, $imageCount)) {
                
				$data['success']=true;
				$data['msg']="Image data saved successfully!";
              } else {
                http_response_code(500);
                $data['msg']= "Error saving image data.";
				$data['success']=false;
              }
			 // echo json_encode($data);
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

   public function processAllServer($filename) {
//      error_reporting(E_ALL);
//  ini_set('display_errors', 1);
    $batchSize = 5;  // Set the batch size to 5 rows per batch
    $offset = 0;     // Initial offset for the first batch

   
    while (true) {
        $this->db->where("(sheet_status='Pending' OR sheet_status='Assigned')");
         if($filename !=''){
          $this->db->where('sheet_file',$filename);
        }else{
           $this->db->limit($batchSize, $offset);
        }
        // Fetch a batch of 5 records
        $sheets = $this->db->get("sheets")->result_array();
        // If no records are found, break the loop
        if (empty($sheets)) {
            break;
        }
        // Process the batch
        foreach ($sheets as $sheet) {
            $this->convertPdfServer($sheet['sheet_file'], $sheet['allocation_id']);
        }
        // After processing the batch, wait for 10 seconds before continuing
        echo "Batch processed ✅. Pausing for 10 seconds...\n";
        sleep(10);  // Pause for 10 seconds
        // Update the offset for the next batch
        $offset += $batchSize;
    }
    echo "All batches processed successfully! ✅";
}
public function convertPdfServer($file, $allocationId) {
    $pdfPath = FCPATH . "answersheets/$allocationId/$file";
    $outputPath = FCPATH . "answersheets/$allocationId/".$file."img/";
    if (!is_dir($outputPath)) {
        mkdir($outputPath, 0777, true);
    }
    $imagick = new Imagick();
    $imagick->setResolution(200, 200);
    $imagick->readImage($pdfPath);
    $totalPages = $imagick->getNumberImages();
    foreach ($imagick as $i => $page) {
        $page->setImageFormat("jpg");
        $page->writeImage($outputPath . $i . ".jpg");
    }
    $finalCount = $totalPages - 1;
    // Safety check (avoid negative count)
    if ($finalCount < 0) {
        $finalCount = 0;
    }
    // Save count.txt
    $countFile = $outputPath . "count.txt";
    file_put_contents($countFile, $finalCount);
    $imagick->clear();
    $imagick->destroy();
    return true;
}
    public function save_image(){

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $imageData = $_POST["imageData"];
        
            $filename = $_POST["filename"];
            $allocationId = $_POST["allocationId"];
            $file = $_POST["file"];
           // $count = $_POST["count"];
          $folderPath = "answersheets/".$allocationId."/".$file."img/";
             if(!file_exists( $folderPath)){
                
              mkdir( $folderPath , 0777, true);
              $filePath = $folderPath.$filename;
             
              $decodedImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
             
              if (file_put_contents($filePath, $decodedImageData)) {
				  $this->imgCompress($filePath, $filePath,60);
                echo "Image data saved successfully!";
              } else {
                http_response_code(500);
                echo "Error saving image data.";
              }
            }else{ 
              $filePath = $folderPath.$filename;
          
              $decodedImageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));
            
              if (file_put_contents($filePath, $decodedImageData)) {
				  $this->imgCompress($filePath, $filePath,60);
                echo "Image data saved successfully!";
              } else {
                http_response_code(500);
                echo "Error saving image data.";
              }
            }
          return true;
          }else{
            echo "1234"; die;
			   return false;
		  }
		  
    }
    //code end here

 
}
