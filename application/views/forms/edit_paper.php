                                    
                                        <div class="form-group">
                                            <label>Paper Code</label>
                                            <input name='code' class="form-control" value='<?php echo $paper['paper_code']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Region Code/Name</label>
                                            <select name='region' class="form-control">
                                                <?php
                                                foreach ($regions as $region) {
                                                    echo "<option value='".$region['region_code']."'>".$region['region_code']."-".$region['region_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Paper Set</label>
                                            <input name='set' class="form-control" placeholder='SET Value eg. A' value='<?php echo $paper['paper_set']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Subject</label>
                                            <select name='subject' class="form-control">
                                            <option value=''>Select</option>
                                            <option value='ALL'>ALL</option>
                                                <?php
                                                foreach ($subjects as $subject) {
                                                    echo "<option value='".$subject['subject_id']."'>".$subject['subject_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Medium</label>
                                            <select name='medium' class="form-control">
                                            <option value=''>Select</option>
                                            <option value='ALL'>ALL</option>
                                                <?php
                                                foreach ($mediums as $medium) {
                                                    echo "<option value='".$medium['medium_name']."'>".$medium['medium_name']."</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Main Sheet Pages</label>
                                            <input name='sheet_pages' class="form-control" value='<?php echo $paper['paper_sheet_pages']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Supp. Sheet Pages</label>
                                            <input name='supp_pages' class="form-control" value='<?php echo $paper['paper_supp_pages']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Model Question Paper Name</label>
                                            <input name='model_question' class="form-control" value='<?php echo $paper['paper_model_question']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Model Answer Paper Name</label>
                                            <input name='model_answer' class="form-control" value='<?php echo $paper['paper_model_answer']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Marks</label>
                                            <input name='marks' class="form-control"  value='<?php echo $paper['paper_total_marks']; ?>'>
                                        </div>
                                        
                                        <input name='id' type='hidden' value='<?php echo $paper['paper_id']; ?>'>
