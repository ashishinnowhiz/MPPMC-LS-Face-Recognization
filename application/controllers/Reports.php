<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
                
        if (!isset($_SESSION[$this->config->item('exam')['exam_session']]['user'])) {
            redirect('login');
        }
                $this->load->model('ReportsModel');
    }
	 public function sheet_r($a)
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
    public function index()
    {
            $this->evaluators();
    }
        
     public function evaluators()
    {
            $where=array();
            $like=array();
        if (isset($_GET['date']) && $_GET['date']!='') {
            $date=$_GET['date'];
        }else{
			$date=date('Y-m-d', time());
		}  
        //code written by Vikas on 26/04/2023
        if (isset($_GET['course']) && ($_GET['course']!='')) {
            $course = $_GET['course'];
     } else {
         $course='';
     }
    //code end here
            //$where['evaluation_time >']=$date;
            //$where['evaluation_time <']=$date." 23:59:59";
            $where['attendance_date']=$date;
           if( $course  != ""){
            $where['evaluators.course_code']=$course;
           }
            //code written By Vikas 
            $this->load->model('CoursesModel');
            $this->load->model('ExaminersModel');
            $data['courses'] = $this->CoursesModel->get_courses();

            // if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator'){

            //   $user_id =  $_SESSION[$this->config->item('exam')['exam_session']]['user'];
            //   $data['users']=$this->ReportsModel->get_user_info($user_id);
            //   $user_name = $data['users'][0]['user_name'];
            //   $data['Examines'] = $this->ExaminersModel->get_examiner_byname($user_name);
            
            //     $where['evaluators.course_code'] = $data['Examines']['course_code'];
            // }
            
            //code end here
            $data['reports'] = $this->ReportsModel->get_reports($where);
            $data['date']=$date;
          //  print_r($data['reports']); die;
            $data['courseCode']=$course;//line written by vikas
           
            $this->load->view('header');
            $this->load->view('reports', $data);
            $this->load->view('footer');
    }
    public function dated_evaluators()
    {
            $where=array();
            $like=array();
        if (isset($_GET['date_from']) && ($_GET['date_from']!='')) {
            $date_from=$_GET['date_from'];
        } else {
            $date_from=date('Y-m-d', strtotime("-30 days"));
        }
        if (isset($_GET['date_to']) && ($_GET['date_to']!='')) {
            $date_to=$_GET['date_to'];
        } else {
            $date_to=date('Y-m-d', time());
        }

        //code written by Vikas on 26/04/2023
           if (isset($_GET['course']) && ($_GET['course']!='')) {
            $course = $_GET['course'];
        } else {
            $course='';
        }
       //code end here

            //$where['evaluation_time >']=$date_from;
            //$where['evaluation_time <']=$date_to." 23:59:59";
            $where['attendance_date >='] = $date_from;
            $where['attendance_date <='] = $date_to;
            
            if($course != ""){
                $where['evaluators.course_code'] = $course;//line written by vikas
            }

            //code written By Vikas 
            $this->load->model('CoursesModel');
            $data['courses'] = $this->CoursesModel->get_courses();
            //code end here
            $data['reports'] = $this->ReportsModel->get_reports($where);
           
            $data['date_from']=$date_from;
            $data['date_to']=$date_to;
            $data['courseCode']=$course;//line written by vikas
            $this->load->view('header');
            $this->load->view('reports/consolidated_evaluators', $data);
            $this->load->view('footer');
    }
    public function evaluator($evaluator, $date = '')
    {
        if ($date=='') {
            $date=date('Y-m-d', time());
        }
            $this->load->model('EvaluatorsModel');
            $data['evaluator'] = $this->EvaluatorsModel->get_evaluator_byname($evaluator);
            $data['reports'] = $this->ReportsModel->evaluator_reports($date, $evaluator);
            $data['stats'] = $this->ReportsModel->evaluator_stats($date, $evaluator);
            $data['date']=$date;
			//echo "<pre>";
	
            $this->load->view('reports/evaluators_report', $data);
    }
    public function heads()
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role'] != 'Coordinator') {
            $this->session->set_flashdata('error', 'Sorry!, you are not authorized to access last page.');
            redirect('/');
        }
            $where=array();
         if (isset($_GET['date']) && $_GET['date']!='') {
            $date=$_GET['date'];
        }else{
			$date=date('Y-m-d', time());
		}
         //code written by Vikas on 26/04/2023
         if (isset($_GET['course']) && ($_GET['course']!='')) {
            $course = $_GET['course'];
        } else {
            $course='';
        }
       //code end here

            if($course != ""){
                $where['examiners.course_code']=$course;//line added by vikas on 26/04/23
            }

            //code added by vikas on 26/04/23
            $this->load->model('CoursesModel');
            $data['courses'] = $this->CoursesModel->get_courses();
            $where['attendance_date']=$date;
            $data['reports'] = $this->ReportsModel->get_reports_heads($where);
            $data['date']=$date;
            //end here
            $data['courseCode']= $course; //line added by vikas on 26/04/23
            $this->load->view('header');
            $this->load->view('heads_reports', $data);
            $this->load->view('footer');
    }
    public function dated_heads()
    {
        $where=array();
        $like=array();
        if (isset($_GET['date_from']) && ($_GET['date_from']!='')) {
            $date_from=$_GET['date_from'];
        } else {
            $date_from=date('Y-m-d', strtotime("-30 days"));
        }
        if (isset($_GET['date_to']) && ($_GET['date_to']!='')) {
            $date_to=$_GET['date_to'];
        } else {
            $date_to=date('Y-m-d', time());
        }
         //code written by Vikas on 26/04/23
         if (isset($_GET['course']) && ($_GET['course']!='')) {
            $course = $_GET['course'];
        } else {
            $course='';
        }
        //end here
        $where['attendance_date >=']=$date_from;
        $where['attendance_date <=']=$date_to;
      
        if($course != ""){
            $where['examiners.course_code']=$course;//line added by vikas on 26/04/23
        }

        //code added by vikas on 26/04/23
        $this->load->model('CoursesModel');
        $data['courses'] = $this->CoursesModel->get_courses();
        //end here
        $data['reports'] = $this->ReportsModel->get_reports_heads($where);
        $data['date_from']=$date_from;
        $data['date_to']=$date_to;
        $data['courseCode']= $course; //line added by vikas on 26/04/23
        $this->load->view('header');
        $this->load->view('reports/consolidated_examiners', $data);
        $this->load->view('footer');
    }
        
    public function head($examiner, $date = '')
    {
        if ($date=='') {
            $date=date('Y-m-d', time());
        }
            $this->load->model('ExaminersModel');
            $data['examiner'] = $this->ExaminersModel->get_examiner_byname($examiner);
            $data['reports'] = $this->ReportsModel->head_reports($date, $examiner);
            $data['stats'] = $this->ReportsModel->head_stats($date, $examiner);
            $data['date']=$date;
            $this->load->view('reports/head_report', $data);
    }
    public function atc($date = '')
    {
        if ($date=='') {
            $date=date('Y-m-d', time());
        }
            $this->load->model('CentersModel');
            $data['center'] = $this->CentersModel->get_center_bycode($_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
            $data['reports'] = $this->ReportsModel->center_reports($date);
            $data['stats'] = $this->ReportsModel->center_stats($date);
            $data['date']=$date;
            $this->load->view('reports/center_report', $data);
    }
        
    public function atcs()
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role'] != 'Coordinator') {
            $this->session->set_flashdata('error', 'Sorry!, you are not authorized to access this page.');
            redirect('/');
        }
        if (isset($_GET['date']) && $_GET['date']!='') {
            $date=$_GET['date'];
        }else{
			$date=date('Y-m-d', time());
		}
            
            $where['evaluation_date']=$date;
            $data['report'] = $this->ReportsModel->get_reports_atcs($where);
            //var_dump($_SESSION);exit;
            $data['date']=$date;
            $this->load->view('header');
            $this->load->view('atcs', $data);
            $this->load->view('footer');
    }
    public function dated_atcs()
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role'] != 'Coordinator') {
            $this->session->set_flashdata('error', 'Sorry!, you are not authorized to access that page.');
            redirect('/');
        }
            $where=array();
        if (isset($_GET['date_from']) && ($_GET['date_from']!='')) {
            $date_from=$_GET['date_from'];
        } else {
            $date_from=date('Y-m-d', strtotime("-30 days"));
        }
        if (isset($_GET['date_to']) && ($_GET['date_to']!='')) {
            $date_to=$_GET['date_to'];
        } else {
            $date_to=date('Y-m-d', time());
        }
            $where['evaluation_date >=']=$date_from;
            $where['evaluation_date <=']=$date_to;
            $data['report'] = $this->ReportsModel->get_reports_atcs($where);
            //var_dump($_SESSION);exit;
            $data['date_from']=$date_from;
            $data['date_to']=$date_to;
            $this->load->view('header');
            $this->load->view('reports/consolidated_atcs', $data);
            $this->load->view('footer');
    }
    public function download($examiner, $date = '')
    {
        if ($date=='') {
            $date=date('Y-m-d', time());
        }
            //load our new PHPExcel library
            $this->load->library('excel');
            //activate worksheet number 1
            $this->excel->setActiveSheetIndex(0);
            //name the worksheet
            
            $this->excel->getActiveSheet()->setTitle('Local Server Reports');
            $this->excel->getActiveSheet()->mergeCells('B1:F2');
            $this->excel->getActiveSheet()->setCellValue('B1', 'Base Score Report');

            $styleArray = array( 'font' => array( 'bold' => false, 'color' => array('rgb' => '3b3b3b'), 'size' => 18, 'name' => 'Verdana' ));
            $this->excel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
            
            
            //database
            $this->load->model('ExaminersModel');
            $examiner1 = $this->ExaminersModel->get_examiner_byname($examiner);
            
            $this->excel->getActiveSheet()->setCellValue('A6', 'Name');
            $this->excel->getActiveSheet()->setCellValue('A7', 'Email');
            $this->excel->getActiveSheet()->setCellValue('A8', 'Mobile');
            
            $this->excel->getActiveSheet()->setCellValue('B6', $examiner1['examiner_name']);
            $this->excel->getActiveSheet()->setCellValue('B7', $examiner1['examiner_email']);
            $this->excel->getActiveSheet()->setCellValue('B8', $examiner1['examiner_phone']);
            
            $this->excel->getActiveSheet()->setCellValue('E6', 'Exam');
            $this->excel->getActiveSheet()->setCellValue('E7', 'Center');
            $this->excel->getActiveSheet()->setCellValue('E8', 'Date');
            
            $this->excel->getActiveSheet()->setCellValue('F6', $this->config->item('exam')['exam_code']);
            $this->excel->getActiveSheet()->setCellValue('F7', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
            $this->excel->getActiveSheet()->setCellValue('F8', $date);

            $reports = $this->ReportsModel->head_reports($date, $examiner);
            $stats = $this->ReportsModel->head_stats($date, $examiner);
            //$data['date']=$date;
        
            $this->excel->getActiveSheet()->setCellValue('A10', 'Sr No');
            $this->excel->getActiveSheet()->setCellValue('B10', 'Script File');
            $this->excel->getActiveSheet()->setCellValue('C10', 'Marker Score');
            $this->excel->getActiveSheet()->setCellValue('D10', 'Head Marker Score');
            $this->excel->getActiveSheet()->setCellValue('E10', 'Marking Status');
            $this->excel->getActiveSheet()->setCellValue('F10', 'Marking Time');
            
            
            $row=10;
            $serial=0;
        foreach ($reports as $report) {
            $serial=$serial+1;
            $row=$row+1;
            $this->excel->getActiveSheet()->setCellValue('A'.$row, $serial);
            $this->excel->getActiveSheet()->setCellValue('B'.$row, $report['sheet_id']);
            $this->excel->getActiveSheet()->setCellValue('C'.$row, $report['evaluation_marks']);
            $this->excel->getActiveSheet()->setCellValue('D'.$row, $report['head_evaluation_marks']);
            $this->excel->getActiveSheet()->setCellValue('E'.$row, $report['sheet_status']);
            $this->excel->getActiveSheet()->setCellValue('F'.$row, $report['recheck_duration']);
        }
            // read data to active sheet
            //$this->excel->getActiveSheet()->fromArray($reports,NULL,'B11' );
            
            $trow = $row+4;
            $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Total Scripts Marked Today');
            $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_count']);
            $trow++;
            $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Average Score');
            $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_avg']);
            $trow++;
            $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Min Score');
            $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_min']);
            $trow++;
            $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Max Score');
            $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_max']);

            $filename='hm_daily.xls'; //save our workbook as this file name
     
            header('Content-Type: application/vnd.ms-excel'); //mime type
     
            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
     
            header('Cache-Control: max-age=0'); //no cache
                        
            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
            //if you want to save it as .XLSX Excel 2007 format
     
            $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
     
            //force user to download the Excel file without writing it to server's HD
            $objWriter->save('php://output');
    }
    public function downloadEval($evaluator, $date = '')
    {
        if ($date=='') {
            $date=date('Y-m-d', time());
        }
            
            
        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
            
        $this->excel->getActiveSheet()->setTitle('Local Server Reports');
        $this->excel->getActiveSheet()->mergeCells('B1:F2');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Marker Daily Report');

        $styleArray = array( 'font' => array( 'bold' => false, 'color' => array('rgb' => '3b3b3b'), 'size' => 18, 'name' => 'Verdana' ));
        $this->excel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
            
            
        //database
        $this->load->model('EvaluatorsModel');
        $evaluator1 = $this->EvaluatorsModel->get_evaluator_byname($evaluator);
            
            
        $this->excel->getActiveSheet()->setCellValue('A5', 'Marker Id');
        $this->excel->getActiveSheet()->setCellValue('A6', 'Name');
        $this->excel->getActiveSheet()->setCellValue('A7', 'Email');
        $this->excel->getActiveSheet()->setCellValue('A8', 'Mobile');
            
        $this->excel->getActiveSheet()->setCellValue('B5', $evaluator1['evaluator_username']);
        $this->excel->getActiveSheet()->setCellValue('B6', $evaluator1['evaluator_name']);
        $this->excel->getActiveSheet()->setCellValue('B7', $evaluator1['evaluator_email']);
        $this->excel->getActiveSheet()->setCellValue('B8', $evaluator1['evaluator_phone']);
            
        $this->excel->getActiveSheet()->setCellValue('E6', 'Exam');
        $this->excel->getActiveSheet()->setCellValue('E7', 'Center');
        $this->excel->getActiveSheet()->setCellValue('E5', 'Date');
            
        $this->excel->getActiveSheet()->setCellValue('F6', $this->config->item('exam')['exam_code']);
        $this->excel->getActiveSheet()->setCellValue('F7', $evaluator1['center_code']);
        $this->excel->getActiveSheet()->setCellValue('F5', $date);

        $reports = $this->ReportsModel->evaluator_reports($date, $evaluator);
        $stats = $this->ReportsModel->evaluator_stats($date, $evaluator);
        //$data['date']=$date;
        
        $this->excel->getActiveSheet()->setCellValue('A10', 'Sr No');
        $this->excel->getActiveSheet()->setCellValue('B10', 'Script File');
        $this->excel->getActiveSheet()->setCellValue('C10', 'Score');
        $this->excel->getActiveSheet()->setCellValue('D10', 'Marking Status');
        $this->excel->getActiveSheet()->setCellValue('E10', 'Marking Time');
        //$this->excel->getActiveSheet()->setCellValue('E10', 'Remarks');
            
        $row=10;
        $serial=0;
        foreach ($reports as $report) {
            $serial=$serial+1;
            $row=$row+1;
            $this->excel->getActiveSheet()->setCellValue('A'.$row, $serial);
            $this->excel->getActiveSheet()->setCellValue('B'.$row, $report['sheet_id']);
            $this->excel->getActiveSheet()->setCellValue('C'.$row, $report['evaluation_marks']);
            $this->excel->getActiveSheet()->setCellValue('D'.$row, 'Checked');
            $this->excel->getActiveSheet()->setCellValue('E'.$row, $report['check_duration']);
            //$this->excel->getActiveSheet()->setCellValue('E'.$row, $report['sheet_remarks']);
        }
        // read data to active sheet
        //$this->excel->getActiveSheet()->fromArray($reports,NULL,'B11' );
            
        $trow = $row+4;
        $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Total Script Marked Today');
        $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_count']);
        $this->excel->getActiveSheet()->setCellValue('D'.$trow, 'Name');
        $trow++;
        $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Average Score');
        $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_avg']);
        $this->excel->getActiveSheet()->setCellValue('D'.$trow, 'Sign');
        $trow++;
        $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Min Score');
        $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_min']);
        $trow++;
        $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Max Score');
        $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_max']);

        $filename='marker_daily'.$evaluator1['evaluator_username'].'.xls'; //save our workbook as this file name
     
        header('Content-Type: application/vnd.ms-excel'); //mime type
     
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
     
        header('Cache-Control: max-age=0'); //no cache
                        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
     
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
     
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
    public function downloadAtc($date = '')
    {
        if ($date=='') {
            $date=date('Y-m-d', time());
        }

            
        //load our new PHPExcel library
        $this->load->library('excel');
        //activate worksheet number 1
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
            
        $this->excel->getActiveSheet()->setTitle('Local Server Reports');
        $this->excel->getActiveSheet()->mergeCells('B1:F2');
        $this->excel->getActiveSheet()->setCellValue('B1', 'Local Server Daily Report');

        $styleArray = array( 'font' => array( 'bold' => false, 'color' => array('rgb' => '3b3b3b'), 'size' => 18, 'name' => 'Verdana' ));
        $this->excel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
            
            
        //database
        $this->load->model('CentersModel');
        $center = $this->CentersModel->get_center_bycode($_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
            
            
        $this->excel->getActiveSheet()->setCellValue('A6', 'Exam');
        $this->excel->getActiveSheet()->setCellValue('A7', 'Date');
            
        $this->excel->getActiveSheet()->setCellValue('B6', $this->config->item('exam')['exam_code']);
        $this->excel->getActiveSheet()->setCellValue('B7', $date);
            
        $this->excel->getActiveSheet()->setCellValue('E6', 'Center');
            
        $this->excel->getActiveSheet()->setCellValue('F6', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);

        $reports = $this->ReportsModel->center_reports($date);
        $stats = $this->ReportsModel->center_stats($date);
        
        $this->excel->getActiveSheet()->setCellValue('A10', 'Sr No');
       // $this->excel->getActiveSheet()->setCellValue('B10', 'Script File');
        $this->excel->getActiveSheet()->setCellValue('C10', 'Marker Score');
        $this->excel->getActiveSheet()->setCellValue('D10', 'Head Marker Score');
        $this->excel->getActiveSheet()->setCellValue('E10', 'Marker');
        $this->excel->getActiveSheet()->setCellValue('F10', 'Head Marker');
        $this->excel->getActiveSheet()->setCellValue('G10', 'Status');
        $this->excel->getActiveSheet()->setCellValue('H10', 'Remarks');
            
        $row=10;
        $serial=0;
        foreach ($reports as $report) {
            $serial=$serial+1;
            $row=$row+1;
            $this->excel->getActiveSheet()->setCellValue('A'.$row, $serial);
           // $this->excel->getActiveSheet()->setCellValue('B'.$row, $report['sheet_file']);
            $this->excel->getActiveSheet()->setCellValue('C'.$row, $report['evaluation_marks']);
            $this->excel->getActiveSheet()->setCellValue('D'.$row, $report['head_evaluation_marks']);
            $this->excel->getActiveSheet()->setCellValue('E'.$row, $report['evaluator_code']);
            $this->excel->getActiveSheet()->setCellValue('F'.$row, $report['examiner_username']);
            $this->excel->getActiveSheet()->setCellValue('G'.$row, $report['sheet_status']);
            $this->excel->getActiveSheet()->setCellValue('H'.$row, $report['sheet_remarks']);
        }
        // read data to active sheet
        //$this->excel->getActiveSheet()->fromArray($reports,NULL,'B11' );
            
        $trow = $row+4;
        $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Total Scripts Marked Today');
        $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_count']);
        $trow++;
        $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Average Score');
        $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_avg']);
        $trow++;
        $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Min Score');
        $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_min']);
        $trow++;
        $this->excel->getActiveSheet()->setCellValue('A'.$trow, 'Max Score');
        $this->excel->getActiveSheet()->setCellValue('B'.$trow, $stats['sheet_max']);

        $filename='ls_daily_'.$_SESSION[$this->config->item('exam')['exam_session']]['user_center'].'.xls'; //save our workbook as this file name
     
        header('Content-Type: application/vnd.ms-excel'); //mime type
     
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
     
        header('Cache-Control: max-age=0'); //no cache
                        
        //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
        //if you want to save it as .XLSX Excel 2007 format
     
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
     
        //force user to download the Excel file without writing it to server's HD
        $objWriter->save('php://output');
    }
        
    public function summary($date = '')
    {
        if ($date=='') {
            $date=date('Y-m-d', time());
        }
        $data['reports'] = $this->ReportsModel->get_summary_reports($date);
        $data['rejects'] = $this->ReportsModel->get_reject_reports_evalauator($date);
        $data['date']=$date;
        if (isset($_GET['export'])) {
            if ($_GET['export']=='html') {
                $this->load->view('reports/summary_print', $data);
            } else {
                //load our new PHPExcel library
                $this->load->library('excel');
                //activate worksheet number 1
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                    
                $this->excel->getActiveSheet()->setTitle('Marker Summary');
                $this->excel->getActiveSheet()->mergeCells('B1:F2');
                $this->excel->getActiveSheet()->setCellValue('B1', 'Marker Summary');

                $styleArray = array( 'font' => array( 'bold' => false, 'color' => array('rgb' => '3b3b3b'), 'size' => 18, 'name' => 'Verdana' ));
                $this->excel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
                    
                    
                //database
                //$this->load->model('CentersModel');
                //$center = $this->CentersModel->get_center_bycode($_SESSION[$this->config->item('exam')['exam_session']]['user_center']);

                $this->excel->getActiveSheet()->setCellValue('A4', 'Center');
                $this->excel->getActiveSheet()->setCellValue('A5', 'Date');
                    
                $this->excel->getActiveSheet()->setCellValue('B4', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
                $this->excel->getActiveSheet()->setCellValue('B5', $date);

                $this->excel->getActiveSheet()->setCellValue('E4', 'Exam');
                $this->excel->getActiveSheet()->setCellValue('E5', 'Class');
                    
                $this->excel->getActiveSheet()->setCellValue('F4', $this->config->item('exam')['exam_code']);
                $this->excel->getActiveSheet()->setCellValue('F5', '');


                foreach ($data['rejects'] as $reject) {
                    $rejected[$reject['evaluator_code']]=$reject['sheet_count'];
                }
                    
                $serial=7;
                    
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $serial, 'Sr No');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $serial, 'Marker Username');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $serial, 'Scripts Checked');
                $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $serial, 'Scripts Rejected');
                    
                foreach ($data['reports'] as $report) {
                    $serial=$serial+1;
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(1, $serial, $serial-1);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(2, $serial, $report['evaluator_code']);
                    $this->excel->getActiveSheet()->setCellValueByColumnAndRow(3, $serial, $report['sheet_count']);
                        
                    if (isset($rejected[$report['evaluator_code']])) {
                            $reject= $rejected[$report['evaluator_code']];
                    } else {
                        $reject= "0";
                    }
                        $this->excel->getActiveSheet()->setCellValueByColumnAndRow(4, $serial, $reject);
                }
                $filename='download.xls'; //save our workbook as this file name
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                //force user to download the Excel file without writing it to server's HD
                $objWriter->save('php://output');
            }
        } else {
            $this->load->view('header');
            $this->load->view('reports/summary', $data);
            $this->load->view('footer');
        }
    }
    public function reject($date = '')
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator') {
                redirect('login');
        }
        if ($date=='') {
            $date=date('Y-m-d', time());
        }   
            $this->load->model('SubjectsModel');
            $data['reports'] = $this->ReportsModel->get_reject_reports($date);
            $data['date']=$date;
            $this->load->view('header');
            $this->load->view('reports/reject', $data);
            $this->load->view('footer');
    }


//Code Written By Vikas on 26/04/23
public function reject_course_wise($date = ''){

    if ($date=='') {
        $date=date('Y-m-d', time());
    }
       $course = $this->input->post('course');
       $data['selected_course'] = $course;
       if($course == 'All'){
            redirect('Reports/reject');
       }else{
        $this->load->model('SubjectsModel');
        $subj_code = array();
        $data['subjects']= $this->SubjectsModel->get_subjects_course_wise($course);

        for($i = 0 ; $i < count($data['subjects']) ; $i++){
          array_push( $subj_code, $data['subjects'][$i]['subject_code']);
        }
    
        $data['report_subj_code'] = $this->ReportsModel->get_reject_reports($date);
        $report_subject = array();
        for($j = 0 ; $j < count($data['report_subj_code']) ; $j++){

            if(in_array($data['report_subj_code'][$j]['subject_code'],$subj_code)){
              array_push($report_subject,$data['report_subj_code'][$j]);
            }
        }
        $data['reports'] = $report_subject;
        $data['date']=$date;

        $this->load->view('header');
        $this->load->view('reports/reject', $data);
        $this->load->view('footer');
       }
}
//code End Here

    
    public function rejectedSheets($by = '', $status = '')
    {

            $date=date('Y-m-d', time());

            $status = urldecode($status);
            $data['reports'] = $this->ReportsModel->get_reject_sheets($by, $status);
            $data['by']=$by;
            $data['status']=($by == "remarks")?$data['status']=$status:$data['status']=$by;
        if (isset($_GET['export'])) {
            if ($_GET['export']=='html') {
                $this->load->view('reports/reject_sheets_print', $data);
            } else {
                //load our new PHPExcel library
                $this->load->library('excel');
                //activate worksheet number 1
                $this->excel->setActiveSheetIndex(0);
                //name the worksheet
                    
                $this->excel->getActiveSheet()->setTitle('Rejected');
                $this->excel->getActiveSheet()->mergeCells('B1:F2');
                $this->excel->getActiveSheet()->setCellValue('B1', 'Rejected Scripts By '.$data['status']);

                $styleArray = array( 'font' => array( 'bold' => false, 'color' => array('rgb' => '3b3b3b'), 'size' => 18, 'name' => 'Verdana' ));
                $this->excel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
                    
                    
                //database
                //$this->load->model('CentersModel');
                //$center = $this->CentersModel->get_center_bycode($_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
                //var_dump($_SESSION[$this->config->item('exam')['exam_session']]['user_center']);exit;

                $this->excel->getActiveSheet()->setCellValue('A4', 'Center');
                $this->excel->getActiveSheet()->setCellValue('A5', 'Date');
                    
                $this->excel->getActiveSheet()->setCellValue('B4', $_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
                $this->excel->getActiveSheet()->setCellValue('B5', $date);

                $this->excel->getActiveSheet()->setCellValue('E4', 'Exam');
                $this->excel->getActiveSheet()->setCellValue('E5', 'Class');
                    
                $this->excel->getActiveSheet()->setCellValue('F4', $this->config->item('exam')['exam_code']);
                $this->excel->getActiveSheet()->setCellValue('F5', '');


                $export_array=array();
                foreach ($data['reports'] as $report) {
                    $row_array=array();
                    $row_array['paper_code']=$report['paper_code'];
                    $row_array['medium_code']=$report['medium_code'];
                    $row_array['region_code']=$report['region_code'];
                    $row_array['sheet_file']=$report['sheet_file'];
                    $row_array['sheet_status']=$report['sheet_status'];
                    $row_array['sheet_remarks']=$report['sheet_remarks'];
                    $row_array['evaluation_time']=$report['evaluation_time'];
                    $export_array[]=$row_array;
                }

                    //////
                    $this->excel->getActiveSheet()->fromArray(array_keys(current($export_array)), null, 'A7');
                    $this->excel->getActiveSheet()->fromArray($export_array, null, 'A8');
                    $filename='download.xls'; //save our workbook as this file name
                    header('Content-Type: application/vnd.ms-excel'); //mime type
                    header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                    header('Cache-Control: max-age=0'); //no cache
                    //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                    //if you want to save it as .XLSX Excel 2007 format
                    $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                    //force user to download the Excel file without writing it to server's HD
                    $objWriter->save('php://output');
            }
        } else {
            $this->load->view('header');
            $this->load->view('reports/reject_sheets', $data);
            $this->load->view('footer');
        }
    }
    public function reason($date = '')
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator') {
                redirect('login');
        }
        if ($date=='') {
            $date=date('Y-m-d', time());
        }
            $data['reports'] = $this->ReportsModel->get_reject_reports_by_reason($date);
            $data['date']=$date;
            $this->load->view('header');
            $this->load->view('reports/reason', $data);
            $this->load->view('footer');
    }
    public function subjectwise($date = '')
    {
        if ($_SESSION[$this->config->item('exam')['exam_session']]['user_role'] != 'Coordinator') {
            $this->session->set_flashdata('error', 'Sorry!, you are not authorized to access this page.');
            redirect('/');
        }
        if ($date=='') {
            $date=date('Y-m-d', time());
        }
                $data['subject_counts'] = $this->ReportsModel->get_subject_sheets_count();
                $this->load->model('SubjectsModel');
                $data['subjects'] = $this->SubjectsModel->get_subjects();
                $data['subject_date'] = $this->ReportsModel->get_checked_total_subject(date('Y-m-d', time()));
        if (isset($_GET['export'])) {
            if ($_GET['export']=='html') {
                $this->load->view('reports/subject_wise_print', $data);
            } else {
                    

                foreach ($data['subject_counts'] as $subcount) {
                    $subject_count[$subcount['subject_code']][$subcount['sheet_status']]=$subcount['Total'];
                }
                foreach ($data['subject_date'] as $row) {
                    $today[$row['subject_code']]=$row['sheet_count'];
                }
                                    $export_array=array();
									
				 $row_array['Sub code']="SubCode";
                 $row_array['Subject']="SubName";
                 $row_array['Tot ABs on Server']="Total";
                 $row_array['Checked Today']="Today Checked";
                 $row_array['Checked till date']="Total Checked";
                 $row_array['Remaining AB']="Pending";
                 $row_array['Rejected']="Rejected";		
				 $export_array[]=$row_array;
                foreach ($data['subjects'] as $subject) {
                    if (isset($subject_count[$subject['subject_code']])) {
                                    $pcc=$subject_count[$subject['subject_code']];
                        if (isset($pcc['Pending'])) {
                            $pc['Pending']=$pcc['Pending'];
                        } else {
                                $pc['Pending']=0;
                        }
                        if (isset($pcc['Assigned'])) {
                            $pc['Assigned']=$pcc['Assigned'];
                        } else {
                            $pc['Assigned']=0;
                        }
                        if (isset($pcc['Checked'])) {
                            $pc['Checked']=$pcc['Checked'];
                        } else {
                            $pc['Checked']=0;
                        }
                        if (isset($pcc['Rejected'])) {
                            $pc['Rejected']=$pcc['Rejected'];
                        } else {
                            $pc['Rejected']=0;
                        }
                        if (isset($pcc['Rechecked'])) {
                            $pc['Rechecked']=$pcc['Rechecked'];
                        } else {
                            $pc['Rechecked']=0;
                        }
                                    unset($pcc);
                    } else {
                                            $pc['Pending']=0;
                                            $pc['Assigned']=0;
                                                $pc['Checked']=0;
                                                $pc['Rejected']=0;
                                                $pc['Rechecked']=0;
                    }
                                                            $total=$pc['Pending']+$pc['Assigned']+$pc['Checked']+$pc['Rejected']+$pc['Rechecked'];
                                                            $subject_count[$subject['subject_code']]['Pending']=$pc['Pending'];
                    if (isset($today[$subject['subject_code']])) {
                        $today_count=$today[$subject['subject_code']];
                    } else {
                        $today_count='0';
                    }
                                                            $checked=intval($pc['Checked'])+intval($pc['Rechecked']);
                                                            $pending=intval($pc['Pending'])+intval($pc['Assigned']);
                                                
                                                            $row_array=array();
                                                            $row_array['Sub code']=$subject['subject_code'];
                                                            $row_array['Subject']=$subject['subject_name'];
                                                            $row_array['Tot ABs on Server']=$total;
                                                            $row_array['Checked Today']=$today_count;
                                                            $row_array['Checked till date']=$checked;
                                                            $row_array['Remaining AB']=$pending;
                                                            $row_array['Rejected']=$pc['Rejected'];
                                                            $export_array[]=$row_array;
                                                            unset($pc);
                }
                                        $this->load->library('excel');
                                        $this->excel->array_to_xls($export_array,'evaluation_reports');
                                       
            }
        } else {
            $this->load->view('header');
            $this->load->view('reports/subject_wise', $data);
            $this->load->view('footer');
        }
    }

     //The code is writen by vikas on 06/04/23
     public function courseWise($date = '')
     {
         if ($date=='') {
             $date=date('Y-m-d', time());
         }
         $data['subject_counts'] = $this->ReportsModel->get_subject_sheets_count();
         $this->load->model('SubjectsModel');
         $course = $this->input->post('course');
         $data['course'] = $course;
        if($course == 'All'){
         return redirect('reports/subjectwise');
        }
         $data['subjects']= $this->SubjectsModel->get_subjects_course_wise($course);
       
         if (isset($_GET['export']) || isset($_GET['exportxls'])) {
             $data['subjects'] = $this->SubjectsModel->get_subjects_course_wise($_GET['export']);
             if (!empty($data['subjects'])) {
               $this->load->view('reports/subject_wise_print',$data);
              }else{
                  return redirect('reports/courseWise');
              }
             if(!empty($_GET['exportxls'])) {
                 $data['subjects'] = $this->SubjectsModel->get_subjects_course_wise($_GET['exportxls']);
                     
                 if(!empty($data)){
                     //load our new PHPExcel library
                     $this->load->library('excel');
                     //activate worksheet number 1
                     $this->excel->setActiveSheetIndex(0);
                     //name the worksheet
                             
                     $this->excel->getActiveSheet()->setTitle('Subject Wise');
                     $this->excel->getActiveSheet()->mergeCells('B1:F2');
                     $this->excel->getActiveSheet()->setCellValue('B1', 'Subject Wise Evaluation Report');
 
                     $styleArray = array( 'font' => array( 'bold' => false, 'color' => array('rgb' => '3b3b3b'), 'size' => 18, 'name' => 'Verdana' ));
                     $this->excel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
                             
                             
                     //database
                     //$this->load->model('CentersModel');
                     //$center = $this->CentersModel->get_center_bycode($_SESSION[$this->config->item('exam')['exam_session']]['user_center']);
 
                     $this->excel->getActiveSheet()->setCellValue('A4', 'Date');
                     $this->excel->getActiveSheet()->setCellValue('B4', $date);
 
                     $this->excel->getActiveSheet()->setCellValue('E4', 'Exam');
                     $this->excel->getActiveSheet()->setCellValue('F4', $this->config->item('exam')['exam_code']);
 
                 foreach ($data['subject_counts'] as $subcount) {
                     $subject_count[$subcount['subject_code']][$subcount['sheet_status']]=$subcount['Total'];
                 }
                 foreach ($data['subject_date'] as $row) {
                     $today[$row['subject_code']]=$row['sheet_count'];
                 }
                                     $export_array=array();
                 foreach ($data['subjects'] as $subject) {
                     if (isset($subject_count[$subject['subject_code']])) {
                                     $pcc=$subject_count[$subject['subject_code']];
                         if (isset($pcc['Scanned'])) {
                             $pc['Scanned']=$pcc['Scanned'];
                         } else {
                             $pc['Scanned']=0;
                         }
                         if (isset($pcc['Allocated'])) {
                             $pc['Allocated']=$pcc['Allocated'];
                         } else {
                             $pc['Allocated']=0;
                         }
                         if (isset($pcc['Assigned'])) {
                             $pc['Assigned']=$pcc['Assigned'];
                         } else {
                             $pc['Assigned']=0;
                         }
                         if (isset($pcc['Checked'])) {
                             $pc['Checked']=$pcc['Checked'];
                         } else {
                             $pc['Checked']=0;
                         }
                         if (isset($pcc['Rejected'])) {
                             $pc['Rejected']=$pcc['Rejected'];
                         } else {
                             $pc['Rejected']=0;
                         }
                         if (isset($pcc['Rechecked'])) {
                             $pc['Rechecked']=$pcc['Rechecked'];
                         } else {
                             $pc['Rechecked']=0;
                         }
                                     unset($pcc);
                     } else {
                                             $pc['Scanned']=0;
                                             $pc['Allocated']=0;
                                             $pc['Assigned']=0;
                                             $pc['Checked']=0;
                                             $pc['Rejected']=0;
                                             $pc['Rechecked']=0;
                     }
                                                             $total=$pc['Scanned']+$pc['Allocated']+$pc['Checked']+$pc['Rejected']+$pc['Rechecked'];
                                                             $subject_count[$subject['subject_code']]['Pending']=$pc['Scanned'];
                     if (isset($today[$subject['subject_code']])) {
                         $today_count=$today[$subject['subject_code']];
                     } else {
                         $today_count=0;
                     }
                                                             $checked=intval($pc['Checked'])+intval($pc['Rechecked']);
                                                             $pending=intval($pc['Scanned'])+intval($pc['Allocated']);
                                                 
                                                             $row_array=array();
                                                             $row_array['Sub code']=$subject['subject_code'];
                                                             $row_array['Subject']=$subject['subject_name'];
                                                             $row_array['Tot ABs on Server']=$total;
                                                             $row_array['Checked Today']=$today_count;
                                                             $row_array['Checked till date']=$checked;
                                                             $row_array['Remaining AB']=$pending;
                                                             $row_array['Rejected']=$pc['Rejected'];
                                                             $export_array[]=$row_array;
                                                             unset($pc);
                 }
                                         //$this->load->library('excel');
                                         //$this->excel->array_to_xls($export_array,'evaluation_reports');
                                         $this->excel->getActiveSheet()->fromArray(array_keys(current($export_array)), null, 'A7');
                                         $this->excel->getActiveSheet()->fromArray($export_array, null, 'A8');
 
                                     $filename='download.xls'; //save our workbook as this file name
                                     header('Content-Type: application/vnd.ms-excel'); //mime type
                                     header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
                                     header('Cache-Control: max-age=0'); //no cache
                                     //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
                                     //if you want to save it as .XLSX Excel 2007 format
                                     $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
                                     //force user to download the Excel file without writing it to server's HD
                                     $objWriter->save('php://output');
                 }else{
                     return redirect('reports/courseWise');
                 }
             }
         } else {
             $this->load->view('header');
             $this->load->view('reports/subject_wise',$data);
             $this->load->view('footer');
         }
 
       
 
     }
     //code end here 06/04/23
}
