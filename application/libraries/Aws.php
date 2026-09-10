<?php 
require 'vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\Rekognition\RekognitionClient;

class aws {

    private $accessKeyId;
    private $secretAccessKey;
    private $region;
    private $bucket;
    private $version;

    public function __construct(){
        $this->_CI = & get_instance();
        $this->_CI->load->model('SettingsModel');
        $data = $this->_CI->SettingsModel->get_setting('s3');
        $setting = json_decode($data['setting_json'], true);
        $this->accessKeyId = $setting['access_key'];
        $this->secretAccessKey = $setting['secret_key'];
        $this->region = $setting['region'];
        $this->bucket = $setting['bucket'];
        $this->version = $setting['version'];
    }
 
    
public function get_file_url($file) {
    $expiryTime = '+20 minutes';
    try {
        $s3 = new S3Client([
            'version' => $this->version,
            'region' => $this->region,
            'credentials' => [
                'key' => $this->accessKeyId,
                'secret' => $this->secretAccessKey,
            ],
        ]);
       // $filename ='testing-phpserver/'.$file; 
        $filename = 'ls-checked-booklet/'.$file; 
        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => $this->bucket,
            'Key' => $filename,
        ]);
        
        $request = $s3->createPresignedRequest($cmd, $expiryTime);
    
        $url = $s3->getObjectUrl($this->bucket, $filename);

        $presignedUrl = (string) $request->getUri();

        return $presignedUrl;
       // json_encode($presignedUrl, JSON_PRETTY_PRINT);
    } catch (AwsException $e) {
        return $e; // or handle the error as per your requirement
    }
}
public function detect_faces($imageBytes)
{
    $rekognition = new RekognitionClient([
            'version' => $this->version,
            'region' => $this->region,
            'credentials' => [
                'key' => $this->accessKeyId,
                'secret' => $this->secretAccessKey,
            ]
    ]);
    try {
        // Face detection
        $faceResult = $rekognition->detectFaces([
            'Image' => [
                'Bytes' => $imageBytes
            ],
            'Attributes' => ['ALL']
        ]);
           // Mobile detection (labels)
        $labelResult = $rekognition->detectLabels([
            'Image' => [
                'Bytes' => $imageBytes
            ],
            'MaxLabels' => 30,
            'MinConfidence' => 70
        ]);

        //Check mobile
        $mobileFound = false;
        $mobileKeywords = ['Mobile Phone','Phone','IPhone','Mobile'];

        foreach ($labelResult['Labels'] as $label) {
            if (in_array($label['Name'], $mobileKeywords)) {
                $mobileFound = true;
                break;
            }
        }
        echo json_encode([
            'faces' => $faceResult['FaceDetails'],
            'mobile_detected' => $mobileFound
        ]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
  }
  public function compare_faces($sourceBytes,$targetBytes){
    try{
      $rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => 'us-east-1',
            'credentials' => [
                'key' => $this->accessKeyId,
                'secret' => $this->secretAccessKey,
            ]
        ]);
        $result = $rekognition->compareFaces([
            'SourceImage' => [
                'Bytes' => $sourceBytes
            ],
            'TargetImage' => [
                'Bytes' => $targetBytes
            ],
            'SimilarityThreshold' => 80
        ]);
          $resultArray = $result->toArray();
        $match = false;
        if (!empty($resultArray['FaceMatches'])) {
            $match = true;
        }
        echo json_encode([
            'match' => $match,
            'raw' => $resultArray
        ]);
      }  catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage() // 🔥 IMPORTANT
    ]);

    }
  }
}
?>
