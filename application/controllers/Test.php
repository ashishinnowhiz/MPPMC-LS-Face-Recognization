<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
    }
    
    public function index($method, $folder = '')
    {
        $this->load->model('EvaluationModel');
        $evals=$this->EvaluationModel->get_evals($sheet_file);

                $i=0;
                $arr = array();
                foreach ($evals as $row) {
                    $obj=json_decode($row['sheet_json_marks']);
                    foreach($obj AS $que){
                        if($que->allotedMarks!='-'){
                            $arr[$i][]=$que->Question_No;
                        }
                    }
                    $i=$i+1;
                }
                $skipped=array_values(array_diff($arr[0], $arr[1]));

                
                $difference=abs($evals[0]['evaluation_marks']-$evals[1]['evaluation_marks']);
                $percent=($difference/$total_marks)*100;
                if (intval($multieval->third)>$percent) {
                    $finalise=true;
                }
                if (sizeof($skipped) > 0) {
                    $finalise=false;
                }
    }
    public function marking(){
        $this->load->view('test/marking');
    }
    
}
