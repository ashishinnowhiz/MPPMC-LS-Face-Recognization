<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pdfimage extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
    }
    public function paper($paperCode)
    {
        $this->db->select('allocation_id, sheet_file,sheet_id');
        $this->db->where('paper_code', $paperCode);
        $this->db->where('sheet_processed', 0);
        $this->db->limit(1);
        $query = $this->db->get('sheets');
        $sheet = $query->row_array();
        if (sizeof($sheet)>0) {
            $this->db->set('sheet_processed', 1);
            $this->db->where('sheet_id', $sheet['sheet_id']);
            $this->db->update('sheets');
            $this->file($sheet['sheet_file'], $sheet['allocation_id']);
        }
    }

    public function pageCount($url)
    {
        $path=$url.'img';
        $file=$path.'/count.txt';
        if (file_exists($file)) {
            $count=file_get_contents($file);
        } else {
            $image = new Imagick();
            $image->pingImage($url);
            $count =  $image->getNumberImages();
            $image->clear();
            $image->destroy();
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($file, $count);
        }
        return $count;
    }

    public function file($file, $allocationId)
    {
        echo $url = "answersheets/".$allocationId."/".$file;
        $this->extract($url);
    }

    public function getUrl()
    {
        $this->byurl($_GET['url']);
    }

    public function extract($url)
    {
        $path=$url.'img';
        require_once APPPATH."/third_party/PdfToText/PdfToText.phpclass";
        $pdf        =  new PdfToText($url, PdfToText::PDFOPT_DECODE_IMAGE_DATA) ;
        $imageCount    =  count($pdf -> Images) ;
        if ($imageCount) {
            for ($i = 0; $i  <  $imageCount; $i ++) {
                // Get next image and generate a filename for it (there will be a file named "sample.x.jpg"
                // for each image found in file "sample.pdf")
                $img        =  $pdf -> Images [$i] ;            // This is an object of type PdfImage
                //$imgindex   =  sprintf("%02d", $i + 1) ;
                $file=$path.'/'.$i.'.jpg';
                // Save the image (the default is IMG_JPG, but you can specify another IMG_* image type by specifying it
                // as the second parameter)
                $img -> SaveAs($file) ;
            }
        }
    }

    public function byurl($url)
    {
        $pageCount = $this->pageCount($url);
        $count=0;
        while ($count < $pageCount) {
            $this->page($count, $url);
            echo $count = $count + 1;
        }
    }
    
    public function getSize($url)
    {
        $path=$url.'img';
        $file=$path.'/300.png';
        if (!file_exists($file)) {
            $im = new imagick();
            $im->setResolution(300, 300);
            $im->readImage($url.'[0]');
            $im->setImageFormat('png');
            $im->setImageBackgroundColor('white');
            $im->setImageAlphaChannel(11);
            $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($file, $im);
            $im->clear();
            $im->destroy();
        }
        $size = getimagesize($file);
        $w = $size[0]/30;

        $res['width'] = $size[0]/30;
        $res['height'] = $size[1]/30;
        $res['multiplier'] = 1366/$size[0];
        if ($w < 210) {
            $res['width'] = 210;
            $res['height'] = (210 * $size[1])/$size[0];
        } elseif ($w > 300) {
            $res['width'] = 300;
            $res['height'] = (300 * $size[1])/$size[0];
        }
        return $res;
    }

    public function page($page, $url)
    {
        $path=$url.'img';
        $file=$path.'/'.$page.'.png';
        if (file_exists($file)) {
            /* $im = imagecreatefrompng($file);
            header('Content-Type: image/png');
            imagepng($im);
            imagedestroy($im); */
        } else {
            $im = new imagick();
            $size = $this->getSize($url);
            if ($size['multiplier'] < 1) {
                $res= 300 * $size['multiplier'];
                $im->setResolution($res, $res);
            }
            //$im->setResolution(300, 300);
            $im->readImage($url.'['.$page.']');
            //$im->scaleImage(800,0);
            $im->setImageFormat('png');
            $im->setImageBackgroundColor('white');
            $im->setImageAlphaChannel(11);
            $im->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN);
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($file, $im);
            $im->clear();
            $im->destroy();
        }
    }
}
