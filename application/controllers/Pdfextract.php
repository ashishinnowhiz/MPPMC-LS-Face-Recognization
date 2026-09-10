<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pdfextract extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
    }
   
    public function getUrl()
    {
        $this->extract($_GET['url']);
    }

    public function file($file, $allocationId)
    {
        echo $url = "answersheets/".$allocationId."/".$file;
        $this->extract($url);
    }
     public function filesample($file, $allocationId)
    {
        echo $url = "answersheets/".$file;
        $this->extract($url);
    }

    public function extract($url)
    {
        $path=$url.'img';
        require_once APPPATH."/third_party/PdfToText/PdfToText.phpclass";
        $pdf        =  new PdfToText($url, PdfToText::PDFOPT_DECODE_IMAGE_DATA) ;
        $imageCount    =  count($pdf -> Images) ;

        $countFile=$path.'/count.txt';
        if (!file_exists($path)) {
            mkdir($path, 0775, true);
        }
        file_put_contents($countFile, $imageCount);

        if ($imageCount) {
            for ($i = 0; $i  <  $imageCount; $i ++) {
                // Get next image and generate a filename for it (there will be a file named "sample.x.jpg"
                // for each image found in file "sample.pdf")
                $img        =  $pdf -> Images [$i] ;            // This is an object of type PdfImage
                //$imgindex   =  sprintf("%02d", $i + 1) ;
                $file=$path.'/'.$i.'.jpg';

                // Save the image (the default is IMG_JPG, but you can specify another IMG_* image type by specifying it
                // as the second parameter)
                $img -> SaveAs($file);
            }
		}
			/* return $imageCount;
        }else{
			$imageCount=0;
			return $imageCount;
		} */
    }
}
