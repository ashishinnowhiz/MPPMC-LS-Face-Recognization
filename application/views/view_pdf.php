<!DOCTYPE html>
<html>
<head>
  <title>Secure PDF Viewer</title>
  <link type="text/css" rel="stylesheet" href="css/pdf/style.css" />
</head>
<body oncontextmenu="return false;">
  <div id="loaderOverlay">
    <div class="loaderBox">
      <div class="loader"></div>
      <div class="loaderText">Loading Please Wait...</div>
    </div>
  </div>

  <div id="alertOverlay">
    <div id="customAlert">
      <h3 id="customAlertMsg"></h3>
      <button onclick="closeCustomAlert()">OK</button>
    </div>
  </div>
  <div id="pdfContainer"></div>

  <!-- PDF.js library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/crypto-js@4.2.0/crypto-js.min.js"></script>

  <script>
    function decrypt(encryptedBase64, keyStr, ivStr) {
      const key = CryptoJS.enc.Utf8.parse(keyStr);
      const iv = CryptoJS.enc.Utf8.parse(ivStr);
      const encrypted = CryptoJS.enc.Base64.parse(encryptedBase64);

      const decrypted = CryptoJS.AES.decrypt(
        { ciphertext: encrypted },
        key,
        {
          iv: iv,
          mode: CryptoJS.mode.CBC,
          padding: CryptoJS.pad.Pkcs7
        }
      );

      return decrypted.toString(CryptoJS.enc.Utf8);
    }

    // Detect mobile device
    if (/Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent)) {
      alert("PDF viewing is restricted on mobile devices.");
      document.body.innerHTML = "<h2 style='text-align:center;margin-top:20%;color:red;'>PDF viewing is not allowed on mobile devices.</h2>";
      throw new Error("Blocked on mobile device");
    }

    const encrypted = '<?= $encodedPath ?>';
    const key = "12345678901234567890123456789012";
    const iv = "1234567890123456";

    const url1 = decrypt(encrypted, key, iv);

    const url = 'Api/proxy.php?url=' + encodeURIComponent(url1);
    const container = document.getElementById('pdfContainer');

    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    pdfjsLib.getDocument(url).promise.then(function(pdf) {

      if (devToolsOpened) {
        blockPDFView();
        return;
      }
      var viewCount = '<?= $view_count ?>';
      if(viewCount == '0'){
        showCustomAlert("This is your last attempt out of 5");
      }else{
        showCustomAlert("You have <?= $view_count ?> attempts remaining out of 5");
      }

      for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
        pdf.getPage(pageNumber).then(function(page) {
          const scale = 1.5;
          const viewport = page.getViewport({ scale: scale });

          const canvas = document.createElement('canvas');
          const context = canvas.getContext('2d');
          canvas.height = viewport.height;
          canvas.width = viewport.width;

          const renderContext = {
            canvasContext: context,
            viewport: viewport
          };

          // page.render(renderContext);
          page.render(renderContext).promise.then(function () {
            document.getElementById('loaderOverlay').style.display = 'none'; // Hide loader
          });
          container.appendChild(canvas);
        });
      }
    });

    // Disable keyboard shortcuts
    document.addEventListener('keydown', function(e) {
      if (
        e.key === 'F12' ||
        (e.ctrlKey && ['s', 'p', 'u'].includes(e.key.toLowerCase())) ||
        (e.ctrlKey && e.shiftKey && ['I', 'J'].includes(e.key.toUpperCase()))
      ) {
        e.preventDefault();
        // alert('This action is disabled.');
      }
    });

    document.addEventListener('keyup', function (e) {
      if (e.key === 'PrintScreen') {
        const overlay = document.createElement('div');
        overlay.style.position = 'fixed';
        overlay.style.top = 0;
        overlay.style.left = 0;
        overlay.style.width = '100vw';
        overlay.style.height = '100vh';
        overlay.style.backgroundColor = 'black';
        overlay.style.opacity = 1;
        overlay.style.zIndex = 9999;
        document.body.appendChild(overlay);

        setTimeout(() => {
          document.body.removeChild(overlay);
        }, 1000); // Hide after 1 sec
      }
    });
  </script>

  <script>
    function showCustomAlert(msg) {
      document.getElementById('customAlertMsg').innerHTML = msg;
      document.getElementById('alertOverlay').style.display = 'flex';
    }

    function closeCustomAlert() {
      document.getElementById('alertOverlay').style.display = 'none';
    }
  </script>

  <script>
    let devToolsOpened = false;

    function detectDevTools() {
      const threshold = 160; // Some devtools panels reduce viewport
      const widthDiff = window.outerWidth - window.innerWidth > threshold;
      const heightDiff = window.outerHeight - window.innerHeight > threshold;

      if (widthDiff || heightDiff) {
        devToolsOpened = true;
        blockPDFView();
      }
    }

    window.addEventListener('resize', detectDevTools);
    setInterval(detectDevTools, 1000);

    function blockPDFView() {
      const container = document.getElementById('pdfContainer');
      container.innerHTML = '<h2 style="color:red;text-align:center;margin-top:20%;">PDF viewing is disabled when Developer Tools are open.</h2>';
      document.getElementById('loaderOverlay').style.display = 'none';
    }
  </script>
</body>
</html>
