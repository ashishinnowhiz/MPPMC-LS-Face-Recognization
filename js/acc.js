
         const videoElement = document.getElementById('video');
         const  canvasElement = document.getElementById('canvas');
         const  canvasCtx = canvasElement.getContext('2d');
const canvas = document.getElementById('canvas');
        let isAnalyzing = false;
        let intervalId;
        var flagcount=0;

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: {} });
                videoElement.srcObject = stream;
            } catch (err) {
                alert('Error Make sure your webcam are active');
                flagcount++
                //console.error('Error accessing the webcam:', err);
            }
        }

        function startAnalysis() {
            // if (!isAnalyzing) {
            //     isAnalyzing = true;
            //     intervalId = setInterval(analyzeFrame, 100000000000); // Analyze every 1 second (adjust as needed)
            // }
            analyzeFrame();
            // capturePhoto();
        }

        function stopAnalysis() {
            if (isAnalyzing) {
                isAnalyzing = false;
                clearInterval(intervalId);
            }
        }

        // const capturePhoto = async () => {
        //     try {
        //         // Create a canvas element to capture the video frame
        //         const canvas = document.createElement('canvas');
        //         canvas.width = video.videoWidth;
        //         canvas.height = video.videoHeight;
        //         const context = canvas.getContext('2d');
        //         context.drawImage(video, 0, 0, canvas.width, canvas.height);

        //         // Convert the canvas image to a data URL
        //         const dataURL = canvas.toDataURL('image/png');

        //         // Set the data URL as the source for the captured image element
        //         capturedImage.src = dataURL;
        //         capturedImage.style.display = 'block';
        //     } catch (error) {
        //         console.error('Error capturing photo:', error);
        //     }
        // };




    function analyzeFrame() {
    if (flagcount > 4) {
        window.location.href = logout_path + 'Face_capture_attempts_exceeded.';
        return;
    }
    canvasCtx.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
    const imageData = canvasElement.toDataURL('image/jpeg', 0.8);

    loader.style.display = 'block';
  
    fetch('detect_face', {
        method: "POST",
        body: JSON.stringify({ image: imageData,[csrfName]: csrfHash }),
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
          const faces = data.faces;
        if (!faces || faces.length === 0) {
            alert('No person detected');
           // flagcount++;
            return;
        }

        if (faces.length > 1) {
            alert('Multiple faces detected');
           // flagcount++;
            return;
        }

        // ✅ Sharpness check
        if (faces[0].Quality.Sharpness < 50) {
            alert('Please come closer to camera');
            flagcount++;
            return;
        }

        // ✅ SUCCESS
        $('#peopleauth').html('<span style="color:green">Face detected properly</span>');

        const finalImage = imageData;
        capturedImage.src = finalImage;
        capturedImage.style.display = 'block';
        $('#saveimage').show();

    })
    .catch(err => {
        loader.style.display = 'none';
        console.log(err);
    });
}

function saveimage(){
    var capturedImages = document.getElementById('capturedImage').src;
    var email = $('#email_auth').val();
    var role = $('#role_auth').val();
    var dataString = {
    'email': email,
    'user_role': role,
    'image': capturedImages,
    "csrf_name": csrfHash
    };
jQuery.ajax({
    type: 'POST',
    url: url_path,
    data: dataString,
    success: function(html) {
    if(html=='face capture and update successfully'){
          location.reload();
    }
    if(html=='Login attempts exceeded'){
          location.reload();
    }
    if(html=='Invalid email/password'){
          location.reload();
    }
    },
    error: function(jqXHR, textStatus, errorThrown) {
    console.log(textStatus, errorThrown);
    jQuery('#ajaxloading').modal('hide');
    }
});



}


        // Call the startCamera function to start capturing live camera video
        startCamera();