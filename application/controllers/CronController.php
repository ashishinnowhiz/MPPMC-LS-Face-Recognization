<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class CronController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // if (!$this->input->is_cli_request()) {
        //     exit('No direct script access allowed');
        // }

        $this->load->model('SheetsModel');
        $this->load->model('ErrorModel');
        $this->load->model('SettingsModel');
        $this->load->library('curl');
        $this->load->helper('file');
    }

    public function daily_sync()
    {
        //  error_reporting(E_ALL);
        //  ini_set('display_errors', 1);
        $settings = $this->get_remote_settings();
        if (!$settings) {
            echo "Failed to fetch remote settings.\n";
            return;
        }

        // 🔹 Step 2: Get all pending allocations
        $allocations = $this->SheetsModel->get_pending_allocations();
      
        if (empty($allocations)) {
            echo "No pending allocations.\n";
            return;
        }
        // 🔹 Step 3: Process each allocation
        foreach ($allocations as $allocation) {
            $allocation_id = $allocation['allocation_id'];
            $file = $allocation['allocation_file'];
            echo "Processing Allocation #{$allocation_id} - {$file}\n";
            if ($settings['server'] == 'ftp') {
                //get sheet table allocationid sheetfiles
                $files = $this->SheetsModel->getallocationsheet($allocation_id);
                if($files){
                foreach($files as $file){
                 $this->download_ftp($file['sheet_file'], $allocation_id, $settings['ftp']);
                 }
                } else{ echo "No File Found !!!"; exit;}
            } elseif ($settings['server'] == 's3') {
                $this->download_s3($file, $allocation_id, $settings['s3']);
            } else {
                echo "⚠️ Unknown server type: {$settings['server']}\n";
            }

            sleep(1);
        }

        echo "=== Completed Cron Sync at " . date('Y-m-d H:i:s') . " ===\n";
    }

    /**
     * Fetch remote settings using system token
     */
    private function get_remote_settings()
    {
        $response = $this->SettingsModel->get_settings_for('ftp');
        if (!$response) return false; 
        $ftp_obj = json_decode($response[0]['setting_json'], true);
            $result['server'] = 'ftp';
            $result['ftp'] = [
                'hostname' => $ftp_obj['hostname'],
                'username' => $ftp_obj['username'],
                'password' => $ftp_obj['password'],
                'port'     => $ftp_obj['port'],
                'passive'  => $ftp_obj['passive'] ?? true,
                'debug'    => false,
            ];
        return $result;
    }

    // 🔹 Reuse your FTP and S3 download logic, but remove $_SESSION references:
    private function download_ftp($file, $allocation_id, $config)
    {
        $folder = "answersheets/".$allocation_id;
        $this->load->library('ftp');
        if (!file_exists($folder)) mkdir($folder, 0777, true);
      //  $local = "{$folder}/{$file['sheet_file']}";
         $local = "answersheets/".$allocation_id."/".$file;
         if($this->ftp->connect($config)){
             $this->load->helper('file');
           if($this->ftp->download('/synced/'.$file, $local)) {
            echo "✔ Downloaded $file via FTP.\n";
            $this->SheetsModel->update_synced_sheet($file, $allocation_id);
            $this->SheetsModel->update_allocation_file_synced($allocation_id);
            $this->extract($file, $allocation_id);
        } else {
            echo "Failed FTP download for $file\n";
        }
      } else{ echo "Failed To Connect Ftp Server"; }
        $this->ftp->close();
    }

    private function download_s3($file, $allocation_id, $config)
    {
        $this->load->library('aws', $config);
        $folder = "answersheets/{$allocation_id}";
        if (!file_exists($folder)) mkdir($folder, 0777, true);
        $local = "{$folder}/{$file}";

        if ($this->aws->downloadFile('synced/' . $file, $local)) {
            echo "✔ Downloaded $file via S3.\n";
            $this->SheetsModel->update_synced_sheet($file, $allocation_id);
            $this->SheetsModel->update_allocation_file_synced($allocation_id);
            $this->extract($file, $allocation_id);
        } else {
            echo "❌ Failed S3 download for $file\n";
        }
    }
    	public function extract($file, $allocationId)
    {
		$url = "answersheets/".$allocationId."/".$file;
		
        $path=$url.'img';
		
        require_once APPPATH."/third_party/PdfToText/PdfToText.phpclass";
        $pdf        =  new PdfToText($url, PdfToText::PDFOPT_DECODE_IMAGE_DATA) ;
        $imageCount    =  count($pdf -> Images) ;
		if($imageCount>15){
			$countFile=$path.'/count.txt';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
			}
			file_put_contents($countFile, $imageCount);

			if ($imageCount!=0) {
				for ($i = 0; $i  <  $imageCount; $i ++) {
					// Get next image and generate a filename for it (there will be a file named "sample.x.jpg"
					// for each image found in file "sample.pdf")
					$img        =  $pdf -> Images [$i] ;            // This is an object of type PdfImage
					//$imgindex   =  sprintf("%02d", $i + 1) ;
					$file=$path.'/'.$i.'.jpg';

					// Save the image (the default is IMG_JPG, but you can specify another IMG_* image type by specifying it
					// as the second parameter)
					$img -> SaveAs($file);
					$this->imgCompress($file,$file,'80');
				}
			 return $imageCount;
			}else{
				$imageCount=0;
				return $imageCount;
			}
		}else{
			$this->SheetsModel->reject_sheet_auto($file, $allocation_id);
			return $imageCount;
		}
    }
	function imgCompress($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

		return $destination;
	}
}
