<div class="form-group">
<?php if(!empty($marker_subjects)):?>
<ol>
    <?php foreach($marker_subjects as $marker_subject){
        $subject_array = $this->SubjectsModel->get_subject_code_wise($marker_subject->subject_code);
        $subject_name = $subject_array[0]['subject_name'];
       echo "<li>".$marker_subject->subject_code."-".$subject_name."</li>" ;
    }?>
</ol>
<?php else:?>
    <label class="muted">No Subjects to Show</label>
<?php endif;?>
</div>