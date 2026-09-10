<?php

$url = $_GET['url'] ?? '';
if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
  http_response_code(400);
  exit('Invalid URL');
}

// Set content-type as PDF
header('Content-Type: application/pdf');

// Disable caching
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Read and output PDF
readfile($url);