<div class="form-group">
<?php if(!empty($examiner_subjects)):?>
<ol>
    <?php foreach($examiner_subjects as $examiner_subject){
        $subject_array = $this->SubjectsModel->get_subject_code_wise($examiner_subject->subject_code);
        $subject_name = $subject_array[0]['subject_name'];
       echo "<li>".$examiner_subject->subject_code."-".$subject_name."</li>" ;
    }?>
</ol>
<?php else:?>
    <label class="muted">No Subjects to Show</label>
<?php endif;?>
</div>