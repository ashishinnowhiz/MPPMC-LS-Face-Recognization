<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sheetcheck extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
       
                $this->load->model('SheetsModel');
    }
	public function detect_face(){
		 header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        if (!isset($input['image'])) {
            echo json_encode(['error' => 'No image received']);
            return;
		}
		 $image = str_replace('data:image/jpeg;base64,', '', $input['image']);
         $imageBytes = base64_decode($image);
		 $this->load->library('aws');
		 $response = $this->aws->detect_faces($imageBytes);
         return $response;

	}
    public function checked($a)
    {
		//echo "Welcome";
		
		$sheets=$this->db->query("select * from sheets where sheet_status='Checked' or sheet_status='Rechecked' or sheet_status='ReMarking' limit $a,15000")->result_array();
		//	print_r($sheets);
		
			foreach($sheets as $sheet){
				$sheetpdf=explode('.',$sheet['sheet_file']);
			 if($sheet['sheet_status']=='Checked'){
				 $sheeturl=$sheet['evaluator_code']."_".$sheetpdf[0]."_checked.pdf";
			}elseif($sheet['sheet_status']=='Rechecked'){
				$sheeturl=$sheet['examiner_username']."_".$sheetpdf[0]."_checked_re.pdf";
			}elseif($sheet['sheet_status']=='ReMarking'){
				$sheeturl=$sheet['marker_username']."_".$sheetpdf[0]."_checked_remarking.pdf";
			} 
			
			 $path="answersheets/uploads/".$sheet['evaluation_date']."/".$sheeturl;
				if(file_exists($path)){
					 $path."_Yes<br>";
				}else{
					echo "'".$sheet['sheet_file']."',<br>";
				} 
			
			}
	}
	 public function Reject()
    {
		 $path="answersheets/";
				//if(file_exists($path)){
					//$this->db->limit(5000,1000);
		$this->db->where("sheet_status","Rejected");
		
		//$this->db->where_in("sheet_file",$sheets);
		
		$query= $this->db->get("sheets")->result_array();
		foreach($query as $row){
		   $file=$path.$row['allocation_id']."/".$row['sheet_file'];
		   	
			if(file_exists($file)){
				echo $file."<br>";
			  unlink($file);
			}
			 $filedir=$path.$row['allocation_id']."/".$row['sheet_file']."img";
			
			 if(file_exists($filedir)){
				 echo $filedir."<br>";
				 $countfile=$filedir.'/count.txt';
				$pageCount=file_get_contents($countfile);

				
				for ($pageNo = 0; $pageNo < $pageCount; $pageNo++) {
					unlink($filedir . '/'.$pageNo.'.jpg');
				}
				$filec=$filedir.'/count.txt';
				unlink($filec);
				rmdir($filedir);
			} 
		}
	}
	 
}
