                                        <div class="form-group">
                                            <label>Evaluator Name *</label>
                                            <input name='name' data-validation="required length" data-validation-length="max50" data-validation-error-msg-required="evaluator name is required" data-validation-error-msg-length="evaluator name is not more than 50 chars" class="form-control" value='<?php echo $evaluator['evaluator_name']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input name='email' data-validation="email" maxlength="50" data-validation-optional="false" class="form-control" placeholder="Email Id" value='<?php echo $evaluator['evaluator_email']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Evaluator Userame *</label>
                                            <input name='username' disabled="disabled" class="form-control" value='<?php echo $evaluator['evaluator_username']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Password *</label>
                                            <input type='password' name='password' class="form-control" data-validation="required strength" data-validation-strength="2" data-validation-length="max15" data-validation-error-msg-length="password is not more than 15 chars" value='<?php echo $evaluator['evaluator_password']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Evaluator Phone</label>
                                            <input name='phone' data-validation-length="max10" data-validation="number length" data-validation-error-msg-length="phone number is not more than 10 chars" class="form-control" value='<?php echo $evaluator['evaluator_phone']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Subject </label>
                                            <select name='subject' class="form-control">
                                            <option value=''>Select</option>
                                                <?php
                                                foreach ($subjects as $subject) {
                                                    echo "<option value='".$subject['subject_code']."'>".$subject['subject_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Medium </label>
                                            <select name='medium' class="form-control">
                                            <option value=''>Select</option>
                                                <?php
                                                foreach ($mediums as $medium) {
                                                    echo "<option value='".$medium['medium_code']."'>".$medium['medium_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Head Evaluator *</label>
                                            <select name='head' data-validation="required" class="form-control">
                                            <option value=''>Select</option>
                                                <?php
                                                foreach ($examiners as $examiner) {
                                                    echo "<option value='".$examiner['examiner_username']."'>".$examiner['examiner_username']."-".$examiner['center_code']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <input name='id' type='hidden' value='<?php echo $evaluator['evaluator_id']; ?>'>
