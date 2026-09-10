<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auto extends CI_Controller
{
    public function __construct()
    {
                parent::__construct();
    }
    public function process($paperCode){
        $this->db->select('allocation_id, sheet_file,sheet_id');
        $this->db->where('paper_code', $paperCode);
        $this->db->where('sheet_processed', 0);
        $this->db->limit(1);
        $query = $this->db->get('sheets');
        $sheet = $query->row_array();
        if(sizeof($sheet)>0){
            $this->db->set('sheet_processed', 1);
            $this->db->where('sheet_id', $sheet['sheet_id']);
            $this->db->update('sheets');
            $url = "answersheets/".$sheet['allocation_id']."/".$sheet['sheet_file'];
            $this->pages($url);
        }
    }

    public function pageCount($url){
        $path=$url.'img';
        $file=$path.'/count.txt';
        if (file_exists($file)) {
            $count=file_get_contents($file);
        }else{
            require_once APPPATH."/third_party/fpdf181/fpdf.php";
            require_once APPPATH."/third_party/FPDI/src/autoload.php";
            $pdf = new setasign\Fpdi\Fpdi();
            // get the page count
            $count = $pdf->setSourceFile($url);

            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            file_put_contents($file, $count);
        }
        return $count;
    }

    public function pages($url){
        $pageCount = $this->pageCount($url);
        $count=0;
        while ($count < $pageCount) {
            $this->page($count, $url);
            echo $count = $count + 1;
        }
    }

    public function getSize($url){
        require_once APPPATH."/third_party/fpdf181/fpdf.php";
        require_once APPPATH."/third_party/FPDI/src/autoload.php";
        $pdf = new setasign\Fpdi\Fpdi();

        $pdf->setSourceFile($url);
        // import page 1
        $templateId = $pdf->importPage(1);
        // get the size of the imported page
        return $pdf->getTemplateSize($templateId);
    }

    public function page($page, $url){
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
            if($size['width'] > 220){
                $multiplier=220/$size['width'];
                $res= 100 * $multiplier;
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
