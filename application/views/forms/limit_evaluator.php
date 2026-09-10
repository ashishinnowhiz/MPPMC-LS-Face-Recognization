    <div class="form-group">
        <label for="limit">Evaluator daily limit for sheets</label>
        <input type='number' id='limit' name='limit' value='<?php echo $limit; ?>' class="form-control" min="<?php echo $limit; ?>" max="<?php echo intval($limit)+20; ?>" data-validation="required number" data-validation-length="1-3">
    </div>
    <div class="form-group">
        <label for="remark">Remark</label>
        <input type='text' id='remark' name='remark' value='' class="form-control" data-validation="required alphanumeric" data-validation-allowing=" ">
        <span class="help-block form-info">write remark/reason for increasing the limit</span>
    </div>
    <input name='user' type='hidden' value='<?php echo $evaluator['evaluator_username']; ?>'><br/>
