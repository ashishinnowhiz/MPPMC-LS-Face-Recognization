<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
} 

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Excel 
{
   
 public function array_to_xls($data,$filename)
{
   $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();


    for ($i = 0, $l = sizeof($data); $i < $l; $i++) { // row $i
        $j = 0;
        foreach ($data[$i] as $k => $v) { // column $j
            $sheet->setCellValueByColumnAndRow($j+1, ($i  + 1), $v);
            $j++;
        }
    }
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'.$fileName.'"');
    $writer->save('php://output');
}
    

public function xls_to_array($file)
{
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
    $worksheet = $spreadsheet->getActiveSheet();
    $cellIterator = $worksheet->getRowIterator()->current()->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(FALSE);

    $header = [];
    $arr_data = [];

    foreach ($cellIterator as $cell) {
        $column = $cell->getColumn();
        $data_value = $cell->getValue();
        
        // Skip empty cells
        if (!empty($data_value)) {
            $header[$column] = $data_value;
        }
    }

    foreach ($worksheet->getRowIterator(2) as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(FALSE);
        $row_data = [];

        foreach ($cellIterator as $cell) {
            $column = $cell->getColumn();
            $data_value = $cell->getValue();

            // Skip empty cells
            if (isset($header[$column])) {
                $row_data[$header[$column]] = $data_value;
            }
        }

        if (!empty($row_data)) {
            $arr_data[] = $row_data;
        }
    }

    return $arr_data;
}

public function array_save_as_xls($data, $filename, $savePath)
{
    // Check if the directory exists, create it if not
    if (!is_dir($savePath)) {
        mkdir($savePath, 0777, true);  // You might want to adjust the permission (0777) based on your needs
    }

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    for ($i = 0, $l = sizeof($data); $i < $l; $i++) { // row $i
        $j = 0;
        foreach ($data[$i] as $k => $v) { // column $j
            $sheet->setCellValueByColumnAndRow($j + 1, ($i + 1), $v);
            $j++;
        }
    }

    $writer = new Xlsx($spreadsheet);

    // Specify the path where you want to save the file
    $filePath = $savePath . '/' . $filename . '.xlsx';

    // Save the file to the specified path
    $writer->save($filePath);

    // Optionally, you can return the file path or perform other actions
    return true;
}


public function compress_download_and_delete_folder($folderPath, $zipFileName, $delete = true)
{
    // Validate folder path
    if (!is_dir($folderPath) || !file_exists($folderPath)) {
        return false; // Folder doesn't exist
    }

    // Create a ZipArchive object
    $zip = new ZipArchive();

    // Specify the path where you want to save the zip file
    $zipFilePath = FCPATH . $zipFileName . '.zip';

    // Open the zip file for writing
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        // Create recursive directory iterator
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath), RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real path for current file
                $filePath = $file->getRealPath();

                // Calculate relative path correctly
                $relativePath = ltrim(substr($filePath, strlen($folderPath)), DIRECTORY_SEPARATOR);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Close the zip archive
        $zip->close();

        // Set permissions of the zip file to 0777
        chmod($zipFilePath, 0777);

        // Send the zip file to the browser for download
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename="' . basename($zipFilePath) . '"');
        header('Content-Length: ' . filesize($zipFilePath));
        readfile($zipFilePath);

        // Optionally, delete the original folder and zip file
        if ($delete) {
            $this->delete_folder_recursive($folderPath);
            $this->delete_folder_recursive($zipFilePath);
        }
        return true;
    } else {
        return false; // Failed to create the zip file
    }
}


// Recursive function to delete a folder and its contents
private function delete_folder_recursive($folderPath)
{
    $files = glob($folderPath . '/*');
    foreach ($files as $file) {
        is_dir($file) ? $this->delete_folder_recursive($file) : unlink($file);
    }
    rmdir($folderPath);
}

public function save_pdf_from_url($url, $filename, $savePath)
{
    // Check if the directory exists, create it if not
    if (!is_dir($savePath)) {
        mkdir($savePath, 0777, true);  // You might want to adjust the permission (0777) based on your needs
    }

    // Download the file contents from the URL
    $fileContents = file_get_contents($url);

    // Check if the file was downloaded successfully
    if ($fileContents === false) {
        return false; // Return false indicating failure
    }

    // Specify the path where you want to save the file
    $filePath = $savePath . '/' . $filename . '.pdf';

    // Save the file contents to the specified path
    file_put_contents($filePath, $fileContents);

    return true; // Return true indicating success
}

public function save_file_from_url($url, $filename, $savePath)
{
    // Check if the directory exists, create it if not
    if (!is_dir($savePath)) {
        if (!mkdir($savePath, 0755, true)) {
            return false; // Unable to create directory
        }
    }

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL session
    $fileContents = curl_exec($ch);

    // Check if the request was successful
    if ($fileContents === false) {
        return false; // Request failed
    }

    // Get the HTTP status code
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check if the request returned HTTP code 200
    if ($httpCode !== 200) {
        return false; // Request was not successful
    }

    // Specify the path where you want to save the file
    $filePath = $savePath . '/' . $filename;

    // Save the file contents to the specified path
    $result = file_put_contents($filePath, $fileContents);

    // Check if file was saved successfully
    if ($result === false) {
        return false; // Unable to save file
    }

    // Close cURL session
    curl_close($ch);

    return true; // Return true indicating success
}




// Function to compress a folder into a zip file
public function compressFolder($folderPath, $zipFileName,$delete=true)
{
    // Validate folder path
    if (!is_dir($folderPath) || !file_exists($folderPath)) {
        return false; // Folder doesn't exist
    }

    // Create a ZipArchive object
    $zip = new ZipArchive();

    // Specify the path where you want to save the zip file
    $zipFilePath = FCPATH ."zipfiles";

    if (!file_exists($zipFilePath)) {
        if (mkdir($zipFilePath, 0777, true)) {
            echo "Folder created successfully.";
        } else {
            echo "Error: Failed to create folder.";
        }
    } else {
        echo "Folder already exists.";
    }

    $zipFilePath =  $zipFilePath."/".$zipFileName . '.zip';

    // Open the zip file for writing
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        // Create recursive directory iterator
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folderPath), RecursiveIteratorIterator::LEAVES_ONLY);

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real path for current file
                $filePath = $file->getRealPath();

                // Calculate relative path
                $relativePath = substr($filePath, strlen($folderPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Close the zip archive
        $zip->close();
        if ($delete) {
            $this->delete_folder_recursive($folderPath);
        }
        // Set permissions of the zip file to 0777
        chmod($zipFilePath, 0777);

        return $zipFilePath;
    } else {
        return false; // Failed to create the zip file
    }
}

// Function to download a file
public function downloadFile($filePath, $delete = true)
{
    // Clear output buffer
    while (ob_get_level()) {
        ob_end_clean();
    }

    if (file_exists($filePath)) {
        // Send the file to the browser for download
        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        
        // Ensure no output buffering during file reading
        flush();
        readfile($filePath);
        
        // Delete the file if needed
        if ($delete) {
            $this->delete_folder_recursive(FCPATH.'zipfiles');
        }
        exit(); // Stop further execution
    } else {
        return false; // File not found
    }
}





}
?>