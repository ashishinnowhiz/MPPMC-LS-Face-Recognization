<style>
    a{cursor:pointer;}
    div,canvas{box-sizing:border-box;}
    .pageWrap{display:none;position:relative;width:960px;border:1px solid #d3d3d3;margin:20px auto;}
    .queNo{padding:4px;border:1px solid #727272;display:block;text-align:center;color:#727272;border-radius:20%;}
    .pageNo,.toolLink{padding:6px;border:1px solid #727272;display:block;text-align:center;margin:2px;color:#727272;border-radius:20%;}
	.queNo{max-width:100%;}
    .pageNo{margin:2px auto;width:30px;}
    .clear{clear:both;}
    .toolLink{float:left;width:40px;line-height:30px;padding:10px;margin:5px 4px;border:0px;background-color:#bfcaf4;}
    .marked{background-color:#5cb85c;color:#fff;}
    .headMarked{background-color:#2a579a;color:#fff;}
    .skipped{background-color:red;color:#fff;}
    .selected{background-color:#c5c5c5;}
    .sectionNav{position:absolute;bottom:0px;padding:0px 10px;width:100%;}
    .sectionNav a{padding:6px 10px;line-height:26px;float:left;}
    #tabLinks a{color:#FFF;}
    #tabLinks .selected{background-color:#F1F1F1;color:#3b3b3b;}
    #bottomBar{font-size:11px;height:30px;position:fixed;width:100%;bottom:0px;left:0px;background-color:#e7e7e7;z-index:1006;border-top:1px solid #bfbfbf;padding:0px 10px;}
    #bottomBar a{font-size:16px;padding:6px;line-height:30px;margin:0px;}
    #markScheme td{vertical-align: middle;}
    #markLinks{padding:10px;max-height:220px;overflow-y: scroll;}
    #markLinksCustom{padding:10px;max-height:220px;overflow-y: scroll;}
    #markLinks a{margin:6px;width:40px;height:40px;border-radius:20px;background-color:#fff;display:block;float:left;text-align:center;line-height:40px;}
    #markLinksCustom a{margin:6px;width:40px;height:40px;border-radius:20px;background-color:#fff;display:block;float:left;text-align:center;line-height:40px;}
    #markLinksCustom a:hover{text-decoration:none;background-color:green;color:#fff;}
    #markLinks a:hover{text-decoration:none;background-color:green;color:#fff;}
	.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
	padding: 4px !important;text-align: center;border: 1px solid #ddd;vertical-align: middle;}
	.loader {
		margin-left: 38%;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.badge {
  margin-left:-2% !important;;
  background-color: red !important;;
 
}

.blink {
      animation: blink 2s steps(5, start) infinite;
      -webkit-animation: blink 1s steps(5, start) infinite;
    }
    @keyframes blink {
      to {
        visibility: hidden;
      }
    }
    @-webkit-keyframes blink {
      to {
        visibility: hidden;
      }
    }

.fa-bell:before {
    content: "\f0f3";
    color: #da364a;
}
</style>

<?php
$color='#FF0000';
$tabColor="#710a2c";
$user_role=$_SESSION[$this->config->item('exam')['exam_session']]['user_role'];
 if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator'){
    $color='#2a579a';
	$tabColor="#2a579a";
} 
if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker'){
    $color='#7a0909';
	$tabColor="#7a0909";
} 

?>
<div class="content">

                    <!-- Start Content-->
	<div class="container">
    <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']!='Coordinator' && isset($eval)) { ?>
                <br/><br/>
                        <!-- /.panel-heading -->
						<h1 class="text-center">Welcome Webpilot Marking</h1>
                        <div class="panel-body text-center">
                        Once you are all set to start the marking. Click the start button. 
                        System will load the answerscript<br/><br/>
                            <!--<a id='evalButton' onclick="getSheet();" class="btn btn-md btn-primary btn-block"">
                                Marker
                            </a>-->
						<input type="hidden" id="base_url" value="<?php echo base_url(); ?>">
                           
                        </div>
						<div class="text-center">
						<button type="button" id='evalButton' onclick="getSheet();" class="btn btn-primary">
                                Start
                            </button>
							<a href="<?php echo base_url(); ?>"><button type="button" class="btn btn-primary">
                                Dashboard
                            </button></a>
							</div>
						<hr>
<span id='evalMessage'></span>
<h3 style="color: red;"><i class="fa fa-info-circle" aria-hidden="true"></i> Read all instructions carefully before START MARKING</h3>
      <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <img src="<?php echo base_url(); ?>assets/images/instructions.jpeg" class="img-responsive img-thumbnail" />
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <p style="font-size: 16px;">Please check the <b>UNCHECK</b> booklets cases that were checked by you this even semester</p>
          <p style="font-size: 16px;">Please proceed by clicking directly on <b>START MARKING</b>. Kindly wait until all 42 pages turn <b>green</b> 🟢🟢🟢🟢, as it may take approximately 10 minutes for the checked booklets to open.</p>
          <p style="font-size: 16px;">When reviewing, please read the student <b>comments</b> carefully and check only the specific parts mentioned in the comments. <p>
	  <p style="font-size: 16px; color: red;"><b>Also, please do not decrease marks from the total.</b></p>
	  <p style="font-size: 16px;"><b>Please note that if marks have already been assigned to a particular question, the marking should not be changed. Instead, kindly add your comments and submit as it is</b></p>
          <p style="font-size: 16px;">If you encounter any issues, please call <a href="tel:+91-8817119597">+91-8817119597</a>.</p>
        </div>
      </div>
              

                <?php } ?>
							</div>
							</div>

<div id='evalScreen' style='display:none;'>

<div id='dragSquare' style='pointer-events: none;display:none;position:absolute;border:2px dotted #3b3b3b;z-index:10010;'>
    <div id='dragTick' style='display:none;position:relative;'>
        <div id='checkDiv' style='bottom:0%;height:30%;position:absolute;border-left:2px solid <?php echo $color; ?>;
        border-bottom:2px solid <?php echo $color; ?>;width:100%;transform: rotate(-45deg);
        transform-origin: top left;'></div>   
    </div>
    <div id='dragCross' style='display:none;width:100%;height:100%;position:relative;'>
        <div id='crossLine1' style='position:absolute;top:0;border-bottom:2px solid <?php echo $color; ?>;
        width:100%;transform: rotate(45deg);transform-origin: top left;'>
        </div>
        <div id='crossLine2' style='position:absolute;bottom:0;border-top:2px solid <?php echo $color; ?>;
        width:100%;transform: rotate(-45deg);transform-origin: top left;'>
        </div>
    </div>
    <div id='dragComment' style='display:none;'>
        <textarea id='commentInput' style='font-size:16px;color:<?php echo $color; ?>;overflow:hidden;width:100%;height:100%;background:rgba(255,255,255,0.8);' 
        onkeydown="autoCommentSize(this);" placeholder='Please Enter Comments'></textarea>
        <input type='hidden' id='commentPageId' value=''>
        <a class='btn btn-sm btn-primary' onclick="drawComment()">OK</a>&nbsp;
        <a class='btn btn-sm btn-danger' onclick="dragReset()">Cancel</a>
    </div>
    <div id='dragQuestion' style='display:none;line-height:100%;color:<?php echo $color; ?>;'>?</div>
    <div id='dragEllipse' style='display:none;border:2px solid <?php echo $color; ?>;border-radius:50%;height:100%;'></div>
    <div id='dragRectangle' style='display:none;border:2px solid <?php echo $color; ?>;height:100%;'></div>
</div>
<div id='scoreBox' style='position:absolute;z-index:10010;border:1px solid #3b3b3b;background-color:#e7e7e7;
display:none;left:25%;width:50%;top:20%;'>
    <div style='padding:10px;background-color:#fff;'>
        <a style='float:right;color:red;' onclick="hideScore()">Close</a>
        <span id='markTitle'>Marking</span>
    </div>
    <div id='markLinks'>
    </div>
    <div class='clear'></div>
</div>

<div id='trailText' style='position:absolute;z-index:10010;color:<?php echo $color; ?>;'></div>
<div id='topBar' style='position:fixed;width:100%;top:0px;left:0;height:40px;z-index:1004;background-color:<?php echo $tabColor; ?>;'>

    <div class='sectionNav'>
        <div id='tabLinks'>
            <a class='selected' onclick="selectTab('doc');" id='docTab'>Answerscript</a>
            <a onclick="selectTab('ans');" id='ansTab'>Model Answer</a>
            <a onclick="selectTab('que');" id='queTab'>Question Paper</a>
        </div>

        <div style='text-align:center;margin:auto;width:200px;'>
           <!-- <a style='color:#fff;' id='sheetFile'></a>-->
        </div>

            <div style='text-align:center;margin:auto;width:160px;color:#fff;background-color:#c5c5c5;'>
        <a style="color:#fff;padding: 4px 5px;">Time:</a><a id='timer' style="color:#fff;padding: 4px 5px;">00:00</a>
      </div>
      
      
      <div style='float:left;margin-right:3%;text-align:center;'>   
        <span style='color:#fff;padding: 6px 10px;line-height: 26px;float: left;'>Script Code:</span><a style='color:#fff;' id='sheetFile'></a>
      </div>

        <div style='float:right;text-align:right;'>
        <a class='optionLink' onclick="openFullscreen()" style='color:#fff;'><i class="fa fa-expand fa-fw"></i></a>
        <a class='optionLink' onclick="closeEvalScreen()" style='color:#fff;'><span class="glyphicon glyphicon-remove"></span></a>
        </div>
    </div>
</div>

<div id='toolBar' style='position:fixed;width:100%;top:40px;left:0;height:40px;z-index:1004;background-color:#F1F1F1;'>
        
        <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Evaluator'){ ?>
        <div style='float:right;background-color:#f2dede;text-align:center;padding:10px;'>
            <a onclick="reject()" style='color:#a94442;'><i class="fa fa-times fa-fw"></i>Reject</a>
			
        </div>
		<?php } ?>
		<!-- <div style='float:right;background-color:#c5c5c5;margin-right:42%;text-align:center;padding:10px;'>
            
			<a id='timer' class='' style='color:#31708f;'>00:00</a>
        </div> -->
        <div style='float:right;width:55%;text-align:center;padding:10px;'>
            <table width='100%'><tr><td style="width: 111px;">Subject Code:</td><td><span id='subjectCode'></span></td>
			<td style="width: 111px;">Subject Name:</td><td><span id='subjectName'></span></td></tr></table>
			 
        </div>
        <div style='text-align:center;margin-left: 25px;'>            
           
        
        <!-- ↷  ↶ ✓ ✗ ? T ✎ -->
        <a id='check' data-toggle="tooltip" data-placement="top" title="True" class='toolLink' onclick="selectTool('check')"><i class="fa fa-check fa-fw"></i></a>
        <a id='cross' data-toggle="tooltip" data-placement="top" title="False" class='toolLink' onclick="selectTool('cross')"><i class="fas fa-times"></i></a>
        <a id='question' data-toggle="tooltip" data-placement="top" title="Add Question" class='toolLink' onclick="selectTool('question')"><i class="fa fa-question fa-fw"></i></a>
        <a id='comment' data-toggle="tooltip" data-placement="top" title="Add Comment" class='toolLink' onclick="selectTool('comment')"><i class="fa fa-comment fa-fw"></i></a>
        <a id='rectangle' data-toggle="tooltip" data-placement="top" title="Draw Rectangle" class='toolLink' onclick="selectTool('rectangle')"><i class="far fa-square"></i></a>
        <a id='ellipse' data-toggle="tooltip" data-placement="top" title="Draw Circle"  class='toolLink' onclick="selectTool('ellipse')"><i class="far fa-circle"></i></a>
		 <div style="margin-left:20px;">
		 <!--<a onclick="undo();" data-toggle="tooltip" data-placement="top" title="Undo" class='toolLink' ><i class="fa fa-undo fa-fw"></i></a>                
          <a onclick="redo();" data-toggle="tooltip" data-placement="top" title="Redo" class='toolLink' ><i style='transform: scaleX(-1);' class="fa fa-undo fa-fw"></i></a>-->
			<?php //if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator'){?>
			 <a onclick="mSum();" data-toggle="tooltip" data-placement="top" title="Mark Summary" class='toolLink' ><i  class="fa fa-list-alt"></i></a> 
			 <a onclick="remove();" data-toggle="tooltip" data-placement="top" title="Remove" class='toolLink' ><i style='transform: scaleX(-1);' class="fa fa-trash fa-fw"></i></a>
		
			<?php // } ?>
			 <?php if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker'){?>
			  <a onclick="mSum();" data-toggle="tooltip" data-placement="top" title="Evalutor Mark Summary" class='toolLink' ><i  class="fa fa-list-alt"></i></a> 
			  <a onclick="mSumH();" data-toggle="tooltip" data-placement="top" title="Depty head Mark Summary" class='toolLink' ><i  class="fa fa-list-alt"></i></a> 
			 <?php } ?>
		</div>
		</div>
        <!--
        <a id='pencil' class='toolLink' onclick="selectTool('pencil')"><i class="fa fa-pencil fa-fw"></i></a>
        -->
		
		
        <span id='show'></span>
		
</div>


<div id='tabs'  style='position:fixed;width:100%;top:80px;left:0;height:80%;z-index:1003;overflow-y:scroll;background-color:#E6E6E6;'>

<div id='docDiv' class='flexHeight pageScroll' style='position:fixed;width:100%;top:80px;left:0;height:80%;z-index:1003;overflow-y:scroll;background-color:#E6E6E6;'>
<?php
    //$document = new Imagick('myfile.pdf');
    //$pageCount=$document->getNumberImages(); //returns 2
    //<img src='pdf.php?page=".$i."' alt='page".$i."' style='width:100%;'>
    $i=1;
for ($i=1; $i<=50; $i++) {
    echo "<div id='docPageDiv".$i."' class='pageDiv pageWrap'>
        <img src='' style='width:100%;'>
        <div style='position:absolute;height:20px;width:-30px;left:-30px;top:0px;color:silver;'>
        #".$i."
        </div>
        <canvas id='page".$i."' class='pageCanvas' id='myCanvas' width='958' height='1354' style='position:absolute;top:0px;left:0px;'>
    Your browser does not support the HTML5 canvas tag.
    </canvas>
        </div>";
}
?>

    <div class='pageDiv' style='position:relative;width:75%;border:1px solid #d3d3d3;margin:20px auto;background-color:#fff;padding:20px;text-align:center;width:960px;'>       
        <a class='btn btn-primary' onclick="saveImages()" style='color:#fff;'><i class="fa fa-save fa-fw"></i> Submit </a>
    </div>
</div>

<div id='queDiv' class='flexHeight pageScroll' style='display:none;position:fixed;width:100%;top:80px;left:0;height:80%;z-index:1004;overflow-y:scroll;background-color:#E6E6E6;'>
    <?php
    $i=1;
    for ($i=1; $i<=50; $i++) {
        echo "<div id='quePageDiv".$i."' class='pageDiv pageWrap'>
        <img src='' style='width:100%;'>
            <div style='position:absolute;height:20px;width:-30px;left:-30px;top:0px;color:silver;'>
            #".$i."
            </div>
        </div>";
    }
    ?>
</div>

<div id='ansDiv' class='flexHeight pageScroll' style='display:none;position:fixed;width:100%;top:80px;left:0;height:80%;z-index:1004;overflow-y:scroll;background-color:#E6E6E6;'>
    <?php
    $i=1;
    for ($i=1; $i<=150; $i++) {
        echo "<div id='ansPageDiv".$i."' class='pageDiv pageWrap'>
        <img src='' style='width:100%;'>
            <div style='position:absolute;height:20px;width:-30px;left:-30px;top:0px;color:silver;'>
            #".$i."
            </div>
        </div>";
    }
    ?>
</div>

</div>

<div class='flexHeight' id='pageNos' style='position:fixed;width:90px;bottom:30px;left:0px;height:80%;z-index:1003;overflow-y:scroll;text-align:center;z-index:1007;background-color:#f1f1f1;border:1px solid #bfbfbf;'>
<br/>Pages<br/><br/>
    
    <div id='docPageNos'>
    </div>
    <div id='ansPageNos' style='display:none;'>
    </div>
    <div id='quePageNos' style='display:none;'>
    </div>
</div>

<div class='flexHeight'  id='queNos' style='border:1px solid #bfbfbf;position:fixed;width:140px;bottom:30px;right:0px;height:80%;padding:10px;overflow-y:scroll;z-index:1007;background-color:#f1f1f1;'>
    <table id='markScheme' class='table table-striped table-hover'>
    <tr><th>Q</th><th>Marks</th></tr>
    </table>
</div>
<div id='bottomBar'>
    <a onclick="prevPage()" ><i class="fa fa-angle-left  fa-lg"></i></a>
    <a onclick="nextPage()" ><i class="fa fa-angle-right  fa-lg"></i></a>
    Page <span id='currentPage'>1</span>/<span id='totalPageCount'>1</span>
        <a id='rotate' onclick="rotate()" data-toggle="tooltip" data-placement="top" title="Rotate">
		<span class="fa-stack fa-fw">
            <i style='transform: scaleX(-1);' class="fa fa-undo fa-stack-1x"></i>
            <!--<i class=" fa-flip-vertical fa-sm"></i>-->
        </span></a>
    <a onclick="showDiv('pageNos')"><i class="fa fa-angle-double-up fa-fw"></i></a>
    <span id='message'></span>
    <div style='float:right;'>
        <a onclick="zoom('')"><i data-toggle="tooltip" data-placement="top" title="Zoom" class="fa fa-search fa-fw"></i></a>
        <a onclick="zoom('minus')"><i class="fa fa-minus fa-fw"></i></a>
        <span onclick="zoom('')" id='currentZoom'>100</span>%
        <a onclick="zoom('plus')"><i class="fa fa-plus fa-fw"></i></a>

    <a onclick="showDiv('queNos')"><i class="fa fa-angle-double-up fa-fw"></i></a> 
    Total : <span id='givenMarks'>0</span>/<span id='totalMarks'>0</span>
    </div>
</div>



<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="rejectModalLabel">Reject sheet</h4>
      </div>
      <div class="modal-body" id='rejectBody' style='max-height:400px; overflow-y: scroll;'>
                                        <div class="form-group">
                                            <label>Reason to reject</label>
                                            <select name='reason' id='rejectReason' onchange="reasonChange(this.value)" class="form-control">
                                                <option value=''>Select</option>
                                                <option value='Different Language'>Different Language</option>
                                                <option value='Different Set'>Different Set</option>
                                                <option value='Different Subject'>Different Subject</option>
												<option value='Page Are Blur'>Page Are Blur</option>
                                                <option value='Show Submit Button'>Show Submit Button</option>
                                                <option value='Other'>Other</option>
                                            </select>
                                        </div>
                                        <div id='rejectOther' class="form-group" style='display:none;'>
                                            <label>Please specify</label>
                                            <input name='other' id='otherReason' data-validation-length="max50" class="form-control">
                                        </div>
      </div>
      <div class="modal-footer">
            <a class="btn btn-primary" onclick="rejectSheet()">Reject</a>
            <a class="btn btn-default" data-dismiss="modal">Close</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="summaryHead" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="summaryModalLabel">Recheck Comments</h4>
      </div>
      <div class="modal-body"  style='max-height:400px; overflow-y: scroll;'>
        <p id="recheckComments"><p>
      </div>
      
    </div>
  </div>
</div>
<div class="modal fade" id="summaryMarker" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="summaryModalLabel">Head Mark Summary</h4>
      </div>
      <div class="modal-body" id='summaryBody' style='max-height:400px; overflow-y: scroll;'>
        <table id='summaryTableMarker' class='table table-striped table-hover'>
            <tr><th>Que</th><th>Marks</th></tr>
        </table>   
      </div>
      
    </div>
  </div>
</div>
<div class="modal fade" id="summaryModal" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="summaryModalLabel">Mark Summary</h4>
      </div>
      <div class="modal-body" id='summaryBody' style='max-height:400px; overflow-y: scroll;'>
        <table id='summaryTable' class='table table-striped table-hover'>
            <tr><th>Que</th><th>Marks</th></tr>
        </table>   

        <div class="checkbox">
            <label>
                <input id='confirmation' value="" type="checkbox"> I confirm that I have evaluated all the pages of answer booklet.
                I have evaluated without any prejudice and in a fair manner to the best of my judgement.
                </label>
        </div> 

      </div>
      <div class="modal-footer">
            <a id='finishBtn'  class="btn btn-primary" onclick="finalSave()">Finish Evaluation</a>
            <a class="btn btn-default" data-dismiss="modal">Cancel</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="verifyCheckedSheet" tabindex="-1" role="dialog" aria-labelledby="summaryModalLabel">
  <div class="modal-dialog" role="document" style="width:100%">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="summaryModalLabel">Mark Summary</h4>
      </div>
      <div class="modal-body">
	  <div class="row">
		<div class="col-lg-6">
			<div id='summaryBody' style='max-height:500px; overflow-y: scroll;'>
			<table id='summaryTable' class='table table-striped table-hover'>
				<tr><th>Que</th><th>Marks</th></tr>
			</table>   
			<div class="checkbox">
				<label>
					<input id='confirmation' value="" type="checkbox"> I confirm that I have evaluated all the pages of answer booklet.
					I have evaluated without any prejudice and in a fair manner to the best of my judgement.
				</label>
			</div> 
		  </div>
		</div>
		<div class="col-lg-6">
			<img src='' style='width:100%;'>
		</div>
	  </div>
		  
      </div> 
      <div class="modal-footer">
            <a id='finishBtn'  class="btn btn-primary" onclick="finalSave()">Finish Evaluation</a>
            <a class="btn btn-default" data-dismiss="modal">Cancel</a>
      </div>
    </div>
  </div>
</div> 
<script>
var userRole='<?php echo $user_role; ?>';
var base_url="<?php echo base_url(); ?>";
var headColor='#2a579a';
var markerColor='#7a0909';
var color='#FF0000';
var elem = document.documentElement;
</script> 
<script src="<?php echo base_url()."js/"; if($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Evaluator'){echo "eval.js";}elseif($_SESSION[$this->config->item('exam')['exam_session']]['user_role']=='Head_Marker'){echo "eval_marker.js";}else{echo "uncheck.js";} ?>"></script>
</div>
