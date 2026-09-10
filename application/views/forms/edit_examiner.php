                                        
                                        <input name='id' type='hidden' value='<?php echo $examiner['examiner_id']; ?>'>
                                        <div class="form-group">
                                            <label>Name</label>
                                            <input name='name' data-validation="required length"  data-validation-length="max50" data-validation-error-msg-required="examiner name is required" data-validation-error-msg-length="examiner name is not more than 50 chars" class="form-control" value='<?php echo $examiner['examiner_name']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Designation</label>
                                            <input name='designation' data-validation="length" data-validation-optional="true" data-validation-length="max50"  data-validation-error-msg-length="designation is not more than 50 chars" class="form-control" value='<?php echo $examiner['examiner_designation']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Examiner Userame</label>
                                            <input name='username' disabled="disabled" class="form-control" value='<?php echo $examiner['examiner_username']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input name='email' data-validation="required email"
                                             class="form-control" maxlength="50" placeholder="Email Id" value='<?php echo $examiner['examiner_email']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Password *</label>
                                            <input type='password' data-validation="required strength" data-validation-strength="2" data-validation-length="max15" data-validation-error-msg-length="password is not more than 15 chars" name='password' class="form-control" value='<?php echo $examiner['examiner_password']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input name='phone' data-validation-length="max10" data-validation="number length" data-validation-error-msg-length="phone number is not more than 10 chars" class="form-control" placeholder="" value='<?php echo $examiner['examiner_phone']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Head Evaluator Center</label>
                                            <select name='center' class="form-control">
                                                <?php
                                                foreach ($centers as $center) {
                                                    echo "<option value='".$center['center_code']."'>".$center['center_code']."-".$center['center_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
