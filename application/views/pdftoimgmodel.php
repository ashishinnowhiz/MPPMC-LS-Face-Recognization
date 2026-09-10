<!DOCTYPE html>
<html>
<head>
  <title>PDF to JPG Rendering</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.min.js"></script>
</head> 
<body>
  <div id="pdf-container"></div>
  <script >
  
  
  // Disable printing on Ctrl+P shortcut for the entire document
window.addEventListener('keydown', function (event) {
    if (event.ctrlKey && event.key === 'p') {
        event.preventDefault();
    }
});

// Load the PDF file
const pdfUrl = '<?php echo $documenturl;?>';
//alert(pdfUrl);
// Initialize PDF.js
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.11.338/pdf.worker.min.js';

// Render PDF as JPG images
pdfjsLib.getDocument(pdfUrl).promise.then(function (pdf) {
    const numPages = pdf.numPages;
    const container = document.getElementById('pdf-container');
	saveImageCount(numPages);
    // Loop through each page and render it as JPG
    for (let pageNumber = 1; pageNumber <= numPages; pageNumber++) {
        pdf.getPage(pageNumber).then(function (page) {
            const scale = 1.5;
            const viewport = page.getViewport({ scale });

            // Prepare canvas element for rendering
            const canvas = document.createElement('canvas');
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Render the PDF page into the canvas
            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext).promise.then(function () {
                // Convert canvas to JPG image
                const image = new Image();
                image.src = canvas.toDataURL('image/jpg');

                // Prevent image from being downloadable
                image.addEventListener('contextmenu', function (event) {
                    event.preventDefault();
                });
                image.addEventListener('dragstart', function (event) {
                    event.preventDefault();
                });

                // Display the JPG image on the screen
                //container.appendChild(image);
				saveImageData(image.src,(pageNumber-1)+".jpg")
            });
        });
    }
document.getElementById('pdf-container').innerHTML = "Images converted: "+numPages; // Code With Ubes
});
function saveImageCount(imageCount) {
	const xhr = new XMLHttpRequest();
      const formData = new FormData();
      formData.append("imageCount", imageCount);
      //formData.append("allocationId", <?php echo $allocationId;?>);
      formData.append("file", '<?php echo $file;?>');

      xhr.open("POST", '<?php echo $counturl;?>', true);

      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            console.log("Image data saved successfully!");
          } else {
            console.error("Error saving image data:", xhr.responseText);
          }
        }
      };

      xhr.send(formData);
}
    function saveImageData(imageData, filename) {
      const xhr = new XMLHttpRequest();
      const formData = new FormData();
      formData.append("imageData", imageData);
      //formData.append("allocationId", <?php echo $allocationId;?>);
      formData.append("file", '<?php echo $file;?>');
      formData.append("filename", filename);

      xhr.open("POST", '<?php echo $saveurl;?>', true);

      xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            console.log("Image data saved successfully!");
          } else {
            console.error("Error saving image data:", xhr.responseText);
          }
        }
      };

      xhr.send(formData);
    }
  </script>
</body>
</html>
