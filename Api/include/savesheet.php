<?php

if (isset($this->api->_request['evaluation_marks'])) {
    $marks=$this->api->_request['evaluation_marks'];
    if($marks=="NaN"){
		$this->custlog->storeLog("Save Sheet Invalid input for marks:".json_encode($marks));
		 $error = array('success' => false, "msg" => "Invalid input for marks");
		$this->api->response($this->api->json($error), 400);
	}
} else {
    $error = array('success' => false, "msg" => "Invalid input for marks");
    $this->api->response($this->api->json($error), 400);
}

if (isset($this->api->_request['sheet_file'])) {
    $sheet_file=$this->api->_request['sheet_file'];
} else {
    $error = array('success' => false, "msg" => "Invalid input for sheet file");
    $this->api->response($this->api->json($error), 400);
}

if (isset($this->api->_request['evaluation_json'])) {
    $evaluation_json=$this->api->_request['evaluation_json'];
        $i=0;
        $verifiedArr = array();
        $naArr = array();
        $marksArr = array();
        $correctArr = array();
    foreach ($evaluation_json as $que) {
        if (isset($que['oldMarks']) && $que['oldMarks']=='NA') {
            $correctArr[]=$i;
            $marksArr[$i]='NA';
        }
        if ($que['allotedMarks']!='NA') {
            $verifiedArr[]=$i;
            $marksArr[$i]=$que['allotedMarks'];
        } else {
            $naArr[]=$i;
        }
        $i=$i+1;
    }
} else {
    $error = array('success' => false, "msg" => "Invalid input for sheet file json");
    $this->api->response($this->api->json($error), 400);
}

                $this->load->model('EvaluationModel');
                $data['checked']=false;
if ($this->user['user_role']=='Evaluator') {
        $this->load->model('SettingsModel');
        $setting = $this->SettingsModel->get_setting('multieval');
        $multieval=json_decode($setting['setting_json']);
                        
        $this->load->model('SheetsModel');
                        
        $sheet['evaluation_marks']=$marks;
        $sheet['sheet_file']=$sheet_file;
        $sheet['sheet_json_marks']=json_encode($evaluation_json);
        $sheet['evaluation_time']=date('Y-m-d H:i:s', time());
        $sheet['evaluation_date']=date('Y-m-d', time());
        $assigned=$this->SheetsModel->get_file($this->user['user_name']);
        $sheet['sheet_assign_time']=$assigned['sheet_assign_time'];
        $sheet['examiner_username']=$assigned['examiner_username'];
        $total_marks=$assigned['paper_total_marks'];
    if (isset($this->api->_request['evalType'])) {
        $sheet['evaluation_type']=$this->api->_request['evalType'];
    }
    if (sizeof($naArr)==0) {
        $sheet['evaluation_na_verified'] = 1;
    }
                        
        $finalise=false;
	$this->custlog->storeLog("Save Sheet Evaluator Log:".json_encode($sheet));
    if ($this->EvaluationModel->add_eval_sheet($this->user['user_name'], $sheet)) {
                //$this->SheetsModel->add_attendance_checked($this->user['user_name']);
                $data['success']=true;
    } else {
                        $data['success']=false;
                        $data['message']='Marks not updated / Error Updating';
                        $this->api->response($this->api->json($data), 200);
    }
 

    $evals=$this->EvaluationModel->get_evals($sheet_file);
    
    $diffNA= array();
    $commonNA= array();

    if ($multieval->multieval=='1' && ($multieval->naVerify=='1' || intval($multieval->naAdjust)>=1) && (sizeof($evals)>=$multieval->levels)) {
        $arr = array();

        if (intval($multieval->naAdjust)>=1) {
            $bestObj = array();
            foreach ($evals as $key => $val) {
                $dbObj=json_decode($val['sheet_json_marks']);
                foreach ($dbObj as $i => $que) {
                    if ($que->allotedMarks!='NA'  && ($que->Question_No != 'parent') && ($que->Question_No != 'condition')) {
                        if (!isset($bestObj[$i]) || ($que->allotedMarks > $bestObj[$i])) {
                            $bestObj[$i] = $que->allotedMarks;
                        }
                    }
                }
            }
        }
        $naVerificationRequired = false;
        if (isset($this->api->_request['evalType']) && ($this->api->_request['evalType']=='NAVerification')) {
            $naVerificationRequired = true;
        }
        
        function rmGroup($leastIndex, $obj, $groups)
        {
                    $index=$leastIndex + 1;
                    $gIndex = 'group'.$index;
                    $indexes = $groups[$gIndex];
            foreach ($indexes as $val) {
                if ($obj[$val]->Question_No=='parent' || $obj[$val]->Question_No=='condition') {
                    rmGroup($val, $obj, $groups);
                } else {
                    $obj[$val]->removed=true;
                }
            }
                    return $obj;
        }
                
        foreach ($evals as $evalIndex => $row) {
            $verified = 0;
            if ($row['evaluation_na_verified']=='0') {
                $obj=json_decode($row['sheet_json_marks']);
                /* $new = array();
                $i=0;
                foreach ($obj as $que) {
                    if ($que->allotedMarks=='NA'  && ($que->Question_No != 'parent') && ($que->Question_No != 'condition')) {
                        if (in_array($i, $verifiedArr)) {
                            $que->verifiedMarks = $marksArr[$i];
                            $que->allotedMarks = $marksArr[$i];
                            $verified =  $verified + floatval($que->allotedMarks);
                        }
                    }
                    $new[]=$que;
                    $i=$i+1;
                }
                $newJson = json_encode($new);
                $obj=json_decode($newJson) */;
                $groups= array();
                $valid= array();
                $marked= array();
                $totalMarks = 0;
                
                $objReverse = array_reverse($obj, true);
                foreach ($objReverse as $index => $objRow) {
                    if (isset($this->api->_request['evalType']) && ($this->api->_request['evalType']=='NACorrection')) {
                        
                        //$obj[$index]->allotedMarks = 'NA';
                        if (in_array($index, $correctArr)) {
                            $obj[$index]->oldMarks = $obj[$index]->allotedMarks;
                            $obj[$index]->allotedMarks = $marksArr[$index];
                            $verified =  $verified + floatval($obj[$index]->allotedMarks);
                        }
                        if ($objRow->allotedMarks=='NA'  && ($objRow->Question_No != 'parent') && ($objRow->Question_No != 'condition')) {
                            if (isset($bestObj[$index])) {
                                //$diffNA[] = $index;
                            } else {
                                $commonNA[] = $index;
                            }
                        }
                    } elseif ($objRow->allotedMarks=='NA'  && ($objRow->Question_No != 'parent') && ($objRow->Question_No != 'condition')) {
                        if ($naVerificationRequired && in_array($index, $verifiedArr)) {
                            $obj[$index]->oldMarks = $obj[$index]->allotedMarks;
                            $obj[$index]->allotedMarks = $marksArr[$index];
                            $verified =  $verified + floatval($obj[$index]->allotedMarks);
                        } elseif ($multieval->naAdjust=='1' && isset($bestObj[$index])) {
                            $obj[$index]->verifiedMarks = $bestObj[$index];
                            $obj[$index]->allotedMarks = $bestObj[$index];
                            $verified =  $verified + floatval($obj[$index]->allotedMarks);
                        } elseif (($multieval->naAdjust=='2' || $multieval->naVerify=='1') && (!isset($this->api->_request['evalType']))) {
                            if (isset($bestObj[$index])) {
                                $diffNA[] = $index;
                            } else {
                                $commonNA[] = $index;
                            }
                        }
                    }


                    if ($objRow->Group!='') {
                        $gname=$objRow->Group;
                        $groupIndex = intval(substr($gname, 5)) - 1;
                        if (!array_key_exists($gname, $groups)) {
                            $groups[$gname] = array();
                            $marked[$gname] = 0;
                            $obj[$groupIndex]->allotedMarks = 0.0;
                        }
                        
                        if ($obj[$index]->allotedMarks!='NA') {
                            $marked[$gname] += 1;
                            $obj[$groupIndex]->allotedMarks += floatval($obj[$index]->allotedMarks);
                        }
                        $groups[$gname][] = $index;
                        $valid[$gname]=$objRow->Valid;
                    }
                    
                    $obj[$index]->removed = false;
                }
                
                if ($verified>0) {
                    foreach ($groups as $gname => $arr) {
                        if (isset($marked[$gname]) && ($marked[$gname] > $valid[$gname])) {
                            $rmCount=$marked[$gname]-$valid[$gname];
                            $removed=0;
                            while ($removed < $rmCount) {
                                $leastMark=null;
                                $leastIndex=null;
                                foreach ($arr as $index) {
                                    if ($obj[$index]->allotedMarks<=$leastMark || $leastIndex==null) {
                                        $leastMark=$obj[$index]->allotedMarks;
                                        $leastIndex=$index;
                                    }
                                }
                                if ($obj[$leastIndex]->Question_No=='parent' || $obj[$leastIndex]->Question_No=='condition') {
                                    $obj = rmGroup($leastIndex, $obj, $groups);
                                }
                                $obj[$leastIndex]->removed=true;
                                $removed=$removed+1;
                            }
                        }
                    }
                    
                    foreach ($obj as $objRow) {
                        if ($objRow->Question_No=='parent' || $objRow->Question_No=='condition') {
                        } else {
                            if ($objRow->removed=='true' || $objRow->allotedMarks=='NA') {
                            } else {
                                $totalMarks = $totalMarks + floatval($objRow->allotedMarks);
                            }
                        }
                    }
                    
                    $evals[$evalIndex]['evaluation_marks']=$totalMarks;
                    $evals[$evalIndex]['sheet_json_marks']=json_encode($obj);
                    
                    if ((sizeof($commonNA)==0) && (sizeof($diffNA)==0)) {
                        $evals[$evalIndex]['evaluation_na_verified']=1;
                        $update_data['evaluation_na_verified']=1;
                    }

                    $update_data['sheet_json_marks']=json_encode($obj);
                    $update_data['evaluation_marks']=$evals[$evalIndex]['evaluation_marks'];
                    
                    $this->EvaluationModel->update_eval_sheet($row['evaluation_id'], $update_data);
                }
            }
        }
    }
    
    if ($multieval->multieval=='1') {
        if ($evals) {
                $bestMarks = 0;
                $worstMarks = $total_marks;
            foreach ($evals as $evalIndex => $row) {
                if ($evals[$evalIndex]['evaluation_marks'] > $bestMarks) {
                    $bestSheet = $evals[$evalIndex];
                    $bestMarks = $evals[$evalIndex]['evaluation_marks'];
                }
                if ($evals[$evalIndex]['evaluation_marks'] < $worstMarks) {
                    $worstSheet = $evals[$evalIndex];
                    $worstMarks = $evals[$evalIndex]['evaluation_marks'];
                }
                $lastSheet=$evals[$evalIndex];
            }

            if (sizeof($evals)==1) {
                $percent=($marks/$total_marks)*100;
                                        
                if ($multieval->below_percent=='0') {
                    $multieval->below_percent=0;
                } else {
                    $multieval->below_percent=intval($multieval->below_percent);
                }
                if ($multieval->above_percent=='0') {
                    $multieval->above_percent=100;
                } else {
                    $multieval->above_percent=intval($multieval->above_percent);
                }
                                        
                if ($multieval->second=='range' && $percent>intval($multieval->below_percent) && $percent<intval($multieval->above_percent)) {
                        $finalise=true;
                        $selected_sheet=$evals[0];
                }
            } elseif (sizeof($evals)==$multieval->levels) {
                if ($multieval->third=='0') {
                    $multieval->third=100;
                } else {
                    $multieval->third=intval($multieval->third);
                }
                $difference=abs($bestMarks-$worstMarks);
                $percent=($difference/$total_marks)*100;
                if (intval($multieval->third)>$percent) {
                    $finalise=true;
                }
            } elseif (sizeof($evals)>$multieval->levels) {
                if (isset($evals[$multieval->levels]['evaluation_na_verified']) && ($evals[$multieval->levels]['evaluation_na_verified']=='1')) {
                    $finalise=true;
                }
            }
            
            if ($finalise && !isset($selected_sheet)) {
                if ($multieval->select=='first') {
                        $selected_sheet=$evals[0];
                } elseif ($multieval->select=='last') {
                        $selected_sheet=$lastSheet;
                } elseif ($multieval->select=='best') {
                    $selected_sheet=$bestSheet;
                } else {
                    $selected_sheet=$worstSheet;
                }
            }
        }
    } else {
        $finalise=true;
        $selected_sheet=$evals[0];
    }
    
    $verifiedFlag=true;
    if ($multieval->naVerify=='1' || $multieval->naAdjust=='1') {
        $verifiedFlag=false;
        if (isset($selected_sheet['evaluation_na_verified']) && ($selected_sheet['evaluation_na_verified']=='1')) {
            $verifiedFlag=true;
        }
    }
  
    if ($finalise && is_array($selected_sheet) && $verifiedFlag) {
        unset($selected_sheet['evaluation_id']);
        unset($selected_sheet['evaluation_na_verified']);
     //   $updated=$this->SheetsModel->update_evaluation_sheet($this->user['user_id'], $selected_sheet);
     if($multieval->levels>1){   
     $updated=$this->SheetsModel->update_reevaluation_sheet2($this->user['user_id'],$selected_sheet,$multieval->levels);
     } else{
     $updated=$this->SheetsModel->update_evaluation_sheet($this->user['user_id'], $selected_sheet); 
     }
        if ($updated) {
            $data['success']=true;
            $data['message']='Successfully saved';
            $data['checked']=true;
            $this->api->response($this->api->json($data), 200);
        } else {
            $data['success']=false;
            $data['message']='Marks not updated / Error Updating';
            $this->api->response($this->api->json($data), 200);
        }
    } else {
        $upData['sheet_eval'] = sizeof($evals);
        if (sizeof($diffNA)>0) {
            $upData['sheet_eval'] = 'Correct';
            $tmpJSON['diffNA'] =  $diffNA;
            $tmpJSON['bestObj'] = $bestObj;
            $upData['sheet_json_marks'] = json_encode($tmpJSON);
        } elseif (sizeof($commonNA)>0) {
            $upData['sheet_eval'] = 'Verify';
            $upData['sheet_json_marks'] = json_encode($commonNA);
        }
    
        if ($this->SheetsModel->unasssign_sheet($this->user['user_name'], $upData)) {
            $data['message'] = 'Sheet Reset Done';
        } else {
            $data['message'] = 'Sheet Reset Not Done';
        }
        $this->api->response($this->api->json($data), 200);
    }
} elseif ($this->user['user_role']=='Head_Evaluator') {
    $data['evaluation_marks']=$marks;
    $data['sheet_file']=$sheet_file;
    $data['sheet_json_marks']=json_encode($evaluation_json);
	$this->custlog->storeLog("Save Sheet Head_Evaluator Log:".json_encode($data));
    $this->load->model('SheetsModel');
    $sheet=$this->SheetsModel->update_re_evaluation_sheet($this->user['user_name'], $data, $this->user['user_id']);
    if ($sheet) {
        $this->EvaluationModel->add_attendance_checked($this->user['user_name']);
        $data['success']=true;
        $data['message']='Successfully saved';
        $data['checked']=true;
        $this->api->response($this->api->json($data), 200);
    } else {
        $data['success']=false;
        $data['message']='Error Updating';
        $this->api->response($this->api->json($data), 200);
    }
} elseif ($this->user['user_role']=='Head_Marker') {
    $data['evaluation_marks']=$marks;
    $data['sheet_file']=$sheet_file;
    $data['sheet_json_marks']=json_encode($evaluation_json);
	$this->custlog->storeLog("Save Sheet Head_Marker Log:".json_encode($data));
    $this->load->model('SheetsModel');
    $sheet=$this->SheetsModel->update_re_marking_sheet($this->user['user_name'], $data, $this->user['user_id']);
    if ($sheet) {
        $this->EvaluationModel->add_attendance_checked($this->user['user_name']);
        $data['success']=true;
        $data['message']='Successfully saved';
        $data['checked']=true;
        $this->api->response($this->api->json($data), 200);
    } else {
		$this->custlog->storeLog("Not Sheet Head_Marker Log:".json_encode($data));
        $data['success']=false;
        $data['message']='Error Updating';
        $this->api->response($this->api->json($data), 200);
    }
}else {
    $error = array('success' => false, "msg" => "Invalid User");
    $this->api->response($this->api->json($error), 404);
}
