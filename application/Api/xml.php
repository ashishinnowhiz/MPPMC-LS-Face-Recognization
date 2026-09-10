<?php


defined('BASEPATH') or exit('No direct script access allowed');



if ($this->api->get_request_method()!='POST') {
    $error = array('success' => false, "msg" => "Invalid request Method");
    $this->api->response($this->api->json($error), 400);
}


                   $paper_code=$this->api->_request['paper_code'];

                   $this->load->model('PapersModel');

                   $paper=$this->PapersModel->get_paper_by_code($paper_code);

                    

if (sizeof($paper)>0) {
    if ($paper['marking_scheme_json']!='') {
        $data['success']=true;

        $note=<<<XML
<Questions>

</Questions>

XML;
        $xml = new SimpleXMLElement($note);
        $obj=json_decode($paper['marking_scheme_json']);
        foreach ($obj as $ms) {
            if($ms->Question_No!='parent' && $ms->Question_No!='condition'){
                           $question=$xml->addChild("Question", " ");

                           $question->addAttribute('No', $ms->Question_No);

                           $question->addAttribute('MinScore', $ms->MinScore);

                           $question->addAttribute('MaxScore', $ms->MaxScore);
                            $question->addAttribute('Hint', $ms->Hint);
                            $question->addAttribute('Page', $ms->Page);
                    if ($ms->Group=='') {
                        $ms->Group="0";
                    }
                                    $question->addAttribute('Group', $ms->Group);
                    if ($ms->Valid=='') {
                        $ms->Valid="0";
                    }
                            $question->addAttribute('Valid', $ms->Valid);
            }
        }

                            echo $xml->asXML();
    } else {
        $data['success']=false;
        $data['message']='Marking scheme not updated';
    }
} else {
    $data['success']=false;
    $data['message']='No More sheets available to check';
    //$this->api->response($this->api->json($data), 200);
}
