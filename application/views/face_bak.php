

<div class="col-md-12">
  <video id="video" width="849" height="405" autoplay style="display:none"></video>
  <div id="loader"></div>
  <canvas id="canvas" width="550" height="550" style="display:none"></canvas>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body" style="text-align: center;">
      <p> <img src="<?php echo base_url('assets/images/alert.png');?>" style="width: 50px;"> </p>
      <p id='face_message' style="font-size:30px"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="analyzeFrame();" data-dismiss="modal">Close</button>
        <a href="<?php echo base_url('welcome')?>"class="btn btn-primary">Back To Dashboard</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body" style="text-align: center;">
      <p> <img src="<?php echo base_url('assets/images/alert.png');?>" style="width: 50px;"> </p>
      <p id='face_message2' style="font-size:30px"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="analyzerecheck();" data-dismiss="modal">Close</button>
        <a href="<?php echo base_url('welcome')?>"class="btn btn-primary">Back To Dashboard</a>
      </div>
    </div>
  </div>
</div>


<?php 

$face_auth_status=$_SESSION[$this->config->item('exam')['exam_session']]['face_auths'];
$face_backrun_status=$_SESSION[$this->config->item('exam')['exam_session']]['backrun'];
$interval=$_SESSION[$this->config->item('exam')['exam_session']]['interval'];
$start_marking=$_SESSION[$this->config->item('exam')['exam_session']]['start_marking'];
$profile_pic=base_url().$_SESSION[$this->config->item('exam')['exam_session']]['user_profile'];
$img = file_get_contents($profile_pic);
$data = base64_encode($img);
$start_marking_auth=$_SESSION[$this->config->item('exam')['exam_session']]['start_marking_auth'];
?>
<script>
  const face_auth_status = "<?php echo $face_auth_status?>";  
  const start_marking = "<?php echo $start_marking?>";  
  const interval = "<?php echo $interval?>";  
  const face_backrun_status = "<?php echo $face_backrun_status?>";  
  const start_marking_auth = "<?php echo $start_marking_auth?>"; 
  const sourceImages = "<?php echo $data?>";
  const url_path="<?php echo base_url("Welcome/add_image"); ?>";
  	const csrfName="<?php echo $this->security->get_csrf_token_name(); ?>";
		const csrfHash="<?php echo $this->security->get_csrf_hash();?>";
  const canvas = document.getElementById('canvas');
       
       



        let videoElement = document.getElementById('video');
        let canvasElement = document.getElementById('canvas');
        let canvasCtx = canvasElement.getContext('2d');
        let isAnalyzing = false;
        let intervalId;

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
                videoElement.srcObject = stream;
            } catch (err) {
                console.error('Error accessing the webcam:', err);
            }
        }
       // alert(start_marking_auth);
  if(face_auth_status==1){
    if(start_marking==1 && start_marking_auth!='success'){
        $('#evalButton').prop('disabled', true);
        $('#evalButton').html('Start <i class="fa fa-spinner fa-spin"></i>');
        function startAnalysis() {
            analyzeFrame();
        }
        function stopAnalysis() {
            if (isAnalyzing) {
                isAnalyzing = false;
                clearInterval(intervalId);
            }
        }

        function analyzeFrame() {
            // Continuously capture frames and send them to Amazon Rekognition for face comparison
            canvasCtx.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
            const imageData = canvasElement.toDataURL('image/jpeg', 0.8);
            compareFacesWithSourceImage(imageData);
        }

       function compareFacesWithSourceImage(targetImageData) {
    loader.style.display = 'block';
    fetch('compare_face', {
        method: "POST",
        body: JSON.stringify({
            target: targetImageData,
            source: sourceImages,
            [csrfName]: csrfHash
        }),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {

        loader.style.display = 'none';

        if (data.error) {
            console.log(data.error);
            return;
        }

        if (data.match === true) {

            $('#evalButton').prop('disabled', false);
            $('#evalButton').html('Start');

            // save image
            var dataString = {
                'image': targetImageData,
                "csrf_name": csrfHash,
                "response": JSON.stringify(data.raw),
                "status": 'SM_Authorized'
            };

            $.post(url_path, dataString);

        } else {

            $('#evalButton').prop('disabled', true);
            $("#exampleModal").modal('show');
            $('#face_message').html('Unauthorized Person Detected');

            var dataString = {
                'image': targetImageData,
                "csrf_name": csrfHash,
                "response": JSON.stringify(data.raw),
                "status": 'SM_Unauthorized'
            };

            $.post(url_path, dataString);
        }

    })
    .catch(err => {
        loader.style.display = 'none';
        console.log(err);
    });
  }
        // Call the startCamera function to start capturing live camera video
        startCamera();
        setTimeout(function(){ startAnalysis();}, 5000);
      }
      if(face_backrun_status==1){
        function analyzeFrame_back() {
        // Continuously capture frames and send them to Amazon Rekognition for face comparison
        canvasCtx.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
        const imageData = canvasElement.toDataURL('image/jpeg', 0.8);
        compareFacesWithSourceImage_bak(imageData);
        }
  function compareFacesWithSourceImage_bak(targetImageData) {
    fetch('compare_face_back', {
        method: "POST",
        body: JSON.stringify({
            target: targetImageData,
            source: sourceImages,
            [csrfName]: csrfHash
        }),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {

        console.log(data);

        if (data.error) {
            console.log(data.error);
            return;
        }

       // var sheets = $('#sheetFile_name').val();

        var dataString = {
            'image': targetImageData,
            "csrf_name": csrfHash,
            "responce": JSON.stringify(data.raw),
            "bookname": '',
            "status": data.match ? 'Authorized' : 'Unauthorized'
        };

        $.post(url_path, dataString);

        if (data.match) {
            analyzerecheck();
        } else {
            $("#exampleModal2").modal('show');
            $('#face_message2').html('Alert: Unauthorized Person Detected');
        }

    })
    .catch(err => {
        console.log(err);
    });
}


          function bac_auth(){
           
          startCamera()
          setTimeout(function(){ 
          analyzeFrame_back();
          }, interval);
          }
          function analyzerecheck(){
            setTimeout(function(){ analyzeFrame_back()}, interval);
          }
          
        }
      }
</script>