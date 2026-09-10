<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Csvexport
{
    public function array_to_csv($dataArray, $filename = 'output')
    {
        $name = $filename.".csv";
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"".$name."\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        $handle = fopen('php://output', 'w');
        
        foreach ($dataArray as $row) {
            fputcsv($handle, $row);
        }
        fclose($handle);
        exit;
    }
}
