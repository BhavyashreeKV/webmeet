<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once("vendor/dompdf/dompdf/autoload.inc.php");
use Dompdf\Dompdf;
define("DOMPDF_ENABLE_HTML5PARSER", true);
define("DOMPDF_ENABLE_FONTSUBSETTING", true);
define("DOMPDF_UNICODE_ENABLED", true);
define("DOMPDF_DPI", 120);
define("DOMPDF_ENABLE_REMOTE", true);

class Pdfgenerator {


  public function generate($html, $filename='Reports', $stream=TRUE, $paper = 'A4', $orientation = "portrait",$jquery=false)
  {
    $dompdf = new DOMPDF();
    $dompdf->loadHtml($html);
    $dompdf->setPaper($paper, $orientation);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf", array("Attachment" => 0));
        exit;
    } else {
        $output = $dompdf->output();
        file_put_contents('uploads/reports/'.$filename.'.pdf', $output);
        header("Content-disposition: attachment; filename=".$filename.".pdf");
        if($jquery)
        {
            return base_url("uploads/reports/".$filename.".pdf");
        }
        else
        {
            header("Content-type: application/pdf");
            readfile("uploads/reports/".$filename.".pdf");
        }
        
        /* return $dompdf->output(); */
    }
  }

  public function render_table($data=array())
  {
     
      $table_start = '<table border=1>';$table_end='</table>';
      $table_head_start = '<head><tr bgcolor="#838383">';$table_head_end = '</tr></thead>';
      $table_body_start = '<tbody>';$table_body_end = '</tbody>';
        
      foreach($data['display_columns'] as $key=>$column)
        {
            $table_head_start .= '<th>'.$column.'</th>';
        }
        $thead = $table_head_start.$table_head_end;
        $tb_row = '';
        foreach ($data['log_data'] as $val){
            $tb_row .= '<tr>';
            foreach($data['display_fileds'] as $df_key=>$df)
            {

                $tb_row .= '<td>'.$val->$df.'</td>'; 
                
            }
            $tb_row .= '</tr>';
            
        } 
        $tbody = $table_body_start.$tb_row.$table_body_end;
        
        $table = $table_start.$thead.$tbody.$table_end;


        return $table;
  }
}