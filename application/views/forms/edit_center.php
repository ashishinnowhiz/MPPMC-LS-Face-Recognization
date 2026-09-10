                                        <div class="form-group">
                                            <label>Center Name</label>
                                            <input name='name' class="form-control" value='<?php echo $center['center_name']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Center Code</label>
                                            <input name='code' class="form-control" placeholder="Enter Code" value='<?php echo $center['center_code']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea name='address' class="form-control"><?php echo $center['center_address']; ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input name='phone' class="form-control" placeholder="with STD Code" value='<?php echo $center['center_phone']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Contact Person</label>
                                            <input name='person' class="form-control" placeholder="Full Name" value='<?php echo $center['center_contact_person']; ?>'>
                                        </div>
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input name='email' class="form-control" placeholder="Email Id" value='<?php echo $center['center_email']; ?>'>
                                        </div>
                                        <input name='id' type='hidden' value='<?php echo $center['center_id']; ?>'>
