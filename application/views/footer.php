<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                2019-<script>document.write(new Date().getFullYear())</script> &copy;DigiMarker  
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-right footer-links d-none d-sm-block">
                                    <a href="javascript:void(0);">About Us</a>
                                    <a href="javascript:void(0);">Contact </a>
                                    <a href="javascript:void(0);">Policy </a>
                                    <a href="javascript:void(0);">Terms & Conditions </a>
                            </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->

            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->
 </div>

<!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
		
        <!-- Vendor js -->
        <script src="<?php echo base_url(); ?>assets/js/vendor.min.js"></script>

		 <!-- third party js -->
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <!-- third party js ends -->

        <!-- Datatables init -->
        <script src="<?php echo base_url(); ?>assets/js/pages/datatables.init.js"></script>
		 <!-- Plugins js-->
        <script src="<?php echo base_url(); ?>assets/libs/flatpickr/flatpickr.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/clockpicker/bootstrap-clockpicker.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

        <!-- Init js-->
        <script src="../assets/js/pages/form-pickers.init.js"></script>




        <!-- Plugins js-->
        <script src="<?php echo base_url(); ?>assets/libs/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="<?php echo base_url(); ?>assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

        <!-- Dashboard 2 init -->
        <script src="<?php echo base_url(); ?>assets/js/pages/dashboard-2.init.js"></script>

        <!-- App js-->
        <script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
        <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator'){ ?>
<script>
            function onLoadPage(){
                //$("#basic-datepicker").flatpickr();
            if(document.getElementById('basic-datepicker').value){
                
                var mDate = document.getElementById('basic-datepicker').value;
                $("#basic-datepicker").flatpickr({
                    minDate: "2025-07-16"
                });
                $("#basic-datepicker").val(mDate);
                
            $("#minmax-datepicker").flatpickr({minDate:mDate,maxDate:""}); 
            }else{
                $("#basic-datepicker").flatpickr({
                    minDate: "2025-07-16"
                });
                var mDate="<?php echo date('Y-m-d') ?>";
                $("#minmax-datepicker").flatpickr({minDate:mDate,maxDate:""}); 
            }
            }
             
            onLoadPage();
        </script>
        <?php }else{ ?>
       <script>
			function onLoadPage(){
				//$("#basic-datepicker").flatpickr();
			if(document.getElementById('basic-datepicker').value){
				
				var mDate = document.getElementById('basic-datepicker').value;
				$("#basic-datepicker").flatpickr();
				$("#basic-datepicker").val(mDate);
				
			$("#minmax-datepicker").flatpickr({minDate:mDate,maxDate:""}); 
			}else{
				$("#basic-datepicker").flatpickr();
				var mDate="<?php echo date('Y-m-d') ?>";
				$("#minmax-datepicker").flatpickr({minDate:mDate,maxDate:""}); 
			}
			}
			 
			onLoadPage();
		</script>
    <?php } ?>

<script>
                function unsetSession(){
                 <?php 
                   $this->session->unset_userdata('success');
                   $this->session->unset_userdata('error');
                 ?>
                   
                }
                setTimeout(function() { unsetSession(); }, 2000);
            </script>

    </body>

</html>