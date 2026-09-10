<?php
if ($this->user['user_role']=='Evaluator') {
    $this->load->model('EvaluatorsModel');
    $evaluator=$this->EvaluatorsModel->get_evaluator_byname($this->user['user_name']);
    $where_array= array(
                        'subject_code' => $evaluator['subject_code'],
                        'medium_code' => $evaluator['medium_code']
                    );
    $this->load->model('ReportsModel');
    $stats=$this->ReportsModel->evaluator_stats(date('Y-m-d', time()), $evaluator['evaluator_username']);
                    
                    
                    
    $limit_data=$this->EvaluatorsModel->get_evaluator_limit($this->user['user_name']);
                        
    if (is_array($limit_data) && sizeof($limit_data)>0) {
        $limit=$limit_data['limit_updated_value'];
    } else {
        $this->load->model('SettingsModel');
        $setting = $this->SettingsModel->get_setting('config');
        $config=json_decode($setting['setting_json']);
        $daily_limit=$config->dailylimit;
        $limit=$daily_limit;
    }
         $limit=100;           
    if (($limit==0) or ($stats['sheet_count']<$limit)) {
        $this->load->model('SheetsModel');
        $sheet=$this->SheetsModel->get_evaluation_sheet($evaluator, $where_array);
        if ($sheet==null) {
            $data['success']=false;
            $data['message']='No More sheets available to check';
            //$this->api->response($this->api->json($data), 200);
        } else {
            $data=$sheet;
            $data['success']=true;
            $data['url']='answersheets/'.$sheet['allocation_id'].'/'.$sheet['sheet_file'];
            
            $setting = $this->SettingsModel->get_setting('multieval');
            $multieval=json_decode($setting['setting_json']);

            $tmpJSON=json_decode($sheet['sheet_json_marks']);
            if ($multieval->multieval=='1' && $multieval->naAdjust=='2' && $sheet['sheet_eval']=='Correct'){
                $data['evalType'] = 'NACorrection';
                $data['notAttempted'] = $tmpJSON->diffNA;
                $data['bestObj'] = $tmpJSON->bestObj;
            } elseif ($multieval->multieval=='1' && $multieval->naVerify=='1' && $sheet['sheet_eval']=='Verify'){
                $data['evalType'] = 'NAVerification';
                $data['notAttempted'] = $tmpJSON;
            }
            /*
            if ($multieval->multieval=='1' && $multieval->naVerify=='1'  && $multieval->naAdjust=='0') {
                $notAttempted = array();
                $this->load->model('EvaluationModel');
                $evals=$this->EvaluationModel->get_evals($sheet['sheet_file']);
                if (sizeof($evals) >= intval($multieval->levels)) {
                    foreach ($evals as $row) {
                        if ($row['evaluation_na_verified']=='0') {
                            $obj=json_decode($row['sheet_json_marks']);
                            $index = 0;
                            foreach ($obj as $que) {
                                if ($que->allotedMarks=='NA' && ($que->Question_No != 'parent') && ($que->Question_No != 'condition')) {
                                    $notAttempted[]=$index;
                                }
                                $index=$index+1;
                            }
                        }
                    }
                    
                    //$skipped1=array_values(array_diff($arr[0], $arr[1]));
                    //$skipped2=array_values(array_diff($arr[1], $arr[0]));
                    //$data['skipped'] = array_merge($skipped1,$skipped2);
                    
                    if (sizeof($notAttempted)>0) {
                        $data['notAttempted'] = $notAttempted;
                    }
                }
            }
            */
        }
    } else {
        $data['success']=false;
        $data['message']='You reached your daily limit to check sheets';
        //$this->api->response($this->api->json($data), 200);
    }
} elseif ($this->user['user_role']=='Head_Evaluator') {
    $this->load->model('SheetsModel');
    $sheet=$this->SheetsModel->get_re_evaluation_sheet($this->user['user_name']);
    if ($sheet==null) {
        $data['success']=false;
        $data['message']='No sheets available to recheck';
        //$this->api->response($this->api->json($data), 200);
    } else {
        $data=$sheet;
        
        $data['success']=true;
		$data['url']='answersheets/'.$sheet['allocation_id'].'/'.$sheet['sheet_file'];
         /*$filename=$sheet['evaluator_code']."_".str_replace(".pdf", "", $sheet['sheet_file'])."_checked.pdf";

        $data['url']='answersheets/uploads/'.$sheet['evaluation_date'].'/'.$filename; */
    }
} elseif ($this->user['user_role']=='Head_Marker') {

    $this->load->model('SheetsModel');
    $sheet=$this->SheetsModel->get_re_marking_sheet($this->user['user_name']);
    if ($sheet==null) {
        $data['success']=false;
        $data['message']='No sheets available to recheck';
        //$this->api->response($this->api->json($data), 200);
    } else {
        $data=$sheet;
        
        $data['success']=true;
		$data['url']='answersheets/'.$sheet['allocation_id'].'/'.$sheet['sheet_file'];
         /*$filename=$sheet['evaluator_code']."_".str_replace(".pdf", "", $sheet['sheet_file'])."_checked.pdf";

        $data['url']='answersheets/uploads/'.$sheet['evaluation_date'].'/'.$filename; */
    }
}else {
    $data['success']=false;
    $data['message']='Invalid User';
    //$error = array('success' => false, "msg" => "Invalid User");
                       //$this->api->response($this->api->json($error), 404);
}
                
if (isset($data['paper_code'])) {
            $this->load->model('PapersModel');
            $paper=$this->PapersModel->get_paper_by_code($data['paper_code']);
            $data['paper_model_question']='answersheets/'.$paper['paper_model_question'];
			
            $data['subject_name']=$paper['subject_name']; // Code With Ubes
            $data['subject_code']=$paper['subject_code']; // Code With Ubes
            $data['paper_total_marks']=$paper['paper_total_marks'];
            $data['paper_all_marks']=$paper['paper_all_marks'];
            $data['paper_passing_marks']=$paper['paper_passing_marks'];
            $data['min_eval_time']=$paper['min_eval_time'];
// Code With Ubes Start
			// if(strpos($data['sheet_file'],"R052H21")===0){
			//	$data['paper_model_answer']='answersheets/uploads/model/052e.pdf';
			//}elseif(strpos($data['sheet_file'],"R150H21")===0){
			//	$data['paper_model_answer']='answersheets/uploads/model/150m.pdf';
			//}elseif(strpos($data['sheet_file'],"R231H21")===0){
			//	$data['paper_model_answer']='answersheets/uploads/model/231b.pdf';
			//}else{ 
			//	$data['paper_model_answer']='answersheets/'.$paper['paper_model_answer'];
			//} 
			$data['paper_model_answer']='answersheets/'.$paper['paper_model_answer'];
// Code With Ubes End
}
if (isset($data['sheet_file'])) {
    $_SESSION['sheet_file']=$data['sheet_file'];
    $file = file($data['url']);
    $endfile= trim($file[count($file) - 1]);
    $n="%%EOF";
    if ($endfile === $n) {
    } else {
        $reject['sheet_file']=$data['sheet_file'];
        $reject['sheet_remarks']='Other';
        $reject['reject_reason']='Corrupted Sheet';
        $this->SheetsModel->reject_sheet($this->user['user_name'], $reject);
        
        $corrupt['success']=false;
        $corrupt['message']='Sheet was corrupted. Please request new sheet to evaluate';
        $this->api->response($this->api->json($corrupt), 200);
    }
}
    
$this->api->response($this->api->json($data), 200);
