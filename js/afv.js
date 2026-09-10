let videoElement;
let canvasElement;
let canvasCtx;
async function startCamera(){
    try {
        const stream = await navigator.mediaDevices.getUserMedia({video:{}});
        videoElement.srcObject = stream;
    } catch(e){
        console.error("Camera error:", e);
    } 
}

function startAnalysis(){
    analyzeFrame();
}

function stopAnalysis(){
    clearInterval(intervalId);
}

function analyzeFrame(){
    if (!videoElement.srcObject) return;

    canvasCtx.drawImage(videoElement,0,0,canvasElement.width,canvasElement.height);
    const imageData = canvasElement.toDataURL("image/jpeg",0.8);

    compareFacesWithSourceImage(imageData);
}

function compareFacesWithSourceImage(targetImageData){
    const email = $("#email_auth").val();
    const role  = $("#role_auth").val();
    loader.style.display = "block";
    fetch('compare_face', {
        method: "POST",
        headers: {"Content-Type":"application/json"},
        body: JSON.stringify({
            target: targetImageData,
            source: sourceImages,
            email: email,
            role: role,
            [csrfName]: csrfHash
        })
    })
    .then(res => res.json())
    .then(data => {
        loader.style.display = "none";
        if (data.error) return console.log(data.error);
        if (data.match) {
            $("#faceauth").html('<span style="color:green">Face matched successfully</span>');
    let formData = new FormData();
    formData.append("status", "success");
    formData.append("email", email);
    formData.append("role", role);
    formData.append("response", JSON.stringify(data.raw));
    formData.append(csrfName, csrfHash);
    fetch(baseUrl + "login/faceVerification", {
        method: "POST",
        body: formData
    })
    .then(res => res.text())
    .then(res => {
        if (res === "redirect_successfully") {
            window.location.href = baseUrl;
        }
    });
    } else {
            window.location.href = logout_path + 'Face_authentication_failed';
        }
    })
    
}
document.addEventListener("DOMContentLoaded", function(){
    videoElement = document.getElementById("video");
    canvasElement = document.getElementById("canvas");
    canvasCtx = canvasElement.getContext("2d");
    startCamera();
});
