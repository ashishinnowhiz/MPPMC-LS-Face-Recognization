<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

 

require_once APPPATH."/third_party/PHPExcel.php";

class Excel extends PHPExcel
{

    public function __construct()
    {

        parent::__construct();
    }

    public function import($file)
    {

        

        //read file from path

        $objPHPExcel = PHPExcel_IOFactory::load($file);

        //get only the Cell Collection

        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        //extract to a PHP readable array format

        foreach ($cell_collection as $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();

            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();

            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

            //header will/should be in row 1 only. of course this can be modified to suit your need.

            if ($row == 1) {
                $header[$row][$column] = $data_value;
            } else {
                $arr_data[$row][$column] = $data_value;
            }
        }

        //send the data in an array format

        $data['header'] = $header;

        $data['values'] = $arr_data;

        

        return $data;
    }

    public function array_to_xls($data, $filename)
    {

            //activate worksheet number 1

            $this->setActiveSheetIndex(0);

            $this->getActiveSheet()->fromArray(array_keys($data[0]), null, 'A1');

            $this->getActiveSheet()->fromArray($data, null, 'A2');

            $filename=$filename.date('d-m-Y', time()).".xls"; //save our workbook as this file name

            header('Content-Type: application/vnd.ms-excel'); //mime type

            header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name

            header('Cache-Control: max-age=0'); //no cache

            //save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)

            //if you want to save it as .XLSX Excel 2007 format

            $objWriter = PHPExcel_IOFactory::createWriter($this, 'Excel5');

            //force user to download the Excel file without writing it to server's HD

            $objWriter->save('php://output');
    }

    public function xls_to_array($file)
    {

        $objPHPExcel = PHPExcel_IOFactory::load($file);

        $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

        foreach ($cell_collection as $cell) {
            $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();

            $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();

            $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();

            if ($row == 1) {
                $header[$column] = $data_value;
            } else {
                $arr_data[$row][$header[$column]] = $data_value;
            }
        }

        return $arr_data;
    }

    public function array_to_xls_new($data,$filename)
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
      
}
