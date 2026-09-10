<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ReConvertImage extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user']) || ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Admin')) {
           // redirect('login');
        }
                $this->load->model('ErrorModel');
    }
    public function convertfile($allocation_id)
    {
		
		$d['convertImage'] = $this->ErrorModel->get_unconvert_list($allocation_id);
		$total=0;
		$coverted=0;
		$unconvert=0;
		foreach($d['convertImage'] as $result)
		{
			$file=$result['script_name'];
			$allocation_id=$result['allocation_id'];
			$file_error_log_id['file_error_log_id']=$result['file_error_log_id'];
			$total++;
			 if($this->extract($file, $allocation_id)!=0){
							$coverted++;
				
							
							$error['allocation_id']=$allocation_id;
							$error['script_name']=$file;
							$error['error_type']="IC";
							$error['status']="converted";
							$this->ErrorModel->update_error($error,$file_error_log_id);
							
							
						}
						else
						{
							$unconvert++;
							$error['allocation_id']=$allocation_id;
							$error['script_name']=$file;
							$error['error_log']="Cannot convert pdf to image";
							$error['error_type']="IC";
							$error['status']="pending";
							$this->ErrorModel->update_error($error,$file_error_log_id);
							
						} 
        } 
         if($total==$coverted){
			$data['success']=true;
			$data['count']=$coverted;
			$data['message']="Succesfully converted $total script to $coverted image set.";
		}else if($total==$unconvert){
			
			$data['success']=false;
			$data['count']=$coverted;
			$data['message']='Cannot convert pdf to image.';
		}else{
			$data['success']=true;
			$data['count']=$coverted;
			$data['message']="Succesfully converted $total script to $coverted image set and failed to image convert $unconvert Script.";
		} 
        echo json_encode($data);
    }
	public function extract($file, $allocationId)
    {
		$url = "answersheets/".$allocationId."/".$file;
		
        $path=$url.'img';
		
        require_once APPPATH."/third_party/PdfToText/PdfToText.phpclass";
        $pdf        =  new PdfToText($url, PdfToText::PDFOPT_DECODE_IMAGE_DATA) ;
        $imageCount    =  count($pdf -> Images) ;

        $countFile=$path.'/count.txt';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        file_put_contents($countFile, $imageCount);

        if ($imageCount!=0) {
            for ($i = 0; $i  <  $imageCount; $i ++) {
                // Get next image and generate a filename for it (there will be a file named "sample.x.jpg"
                // for each image found in file "sample.pdf")
                $img        =  $pdf -> Images [$i] ;            // This is an object of type PdfImage
                //$imgindex   =  sprintf("%02d", $i + 1) ;
                $file=$path.'/'.$i.'.jpg';

                // Save the image (the default is IMG_JPG, but you can specify another IMG_* image type by specifying it
                // as the second parameter)
                $img -> SaveAs($file);
            }
		 return $imageCount;
        }else{
			$imageCount=0;
			return $imageCount;
		} 
    }
}
