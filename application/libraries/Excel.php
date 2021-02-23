<?php 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Excel
{
    var $CI;

    function __construct()
    {
        $this->CI =& get_instance();
    }

    function SampleExcel()
    {
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

        $sheet = $spreadsheet->getActiveSheet();

        // manually set table data value
        $sheet->setCellValue('A1', 'Gipsy Danger'); 
        $sheet->setCellValue('A2', 'Gipsy Avenger');
        $sheet->setCellValue('A3', 'Striker Eureka');
        
        $writer = new Xlsx($spreadsheet); // instantiate Xlsx
 
        $filename = 'list-of-jaegers'; // set filename for excel file to be exported
 
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');	// download file 
    }

    function render_excel($data=array(),$fileName='Reports',$jquery=false)
    {
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

        $sheet = $spreadsheet->getActiveSheet();

        // add style to the header
            $styleArray = array(
                'font' => array(
                'bold' => true,
                ),
                'alignment' => array(
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ),
                'borders' => array(
                    'bottom' => array(
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => array('rgb' => '333333'),
                    ),
                ),
                'fill' => array(
                'type'       => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'rotation'   => 90,
                'startcolor' => array('rgb' => '0d0d0d'),
                'endColor'   => array('rgb' => 'f2f2f2'),
                ),
            );
            $style_upto ='A1:Z1';
            if(!empty($data['display_columns']))
            {
                $start_char = 'A1';
                $char_range = range('A','Z');
                $count = count($data['display_columns'])-1;
                $end_char = $char_range[$count].'1';

                $style_upto = $start_char.':'.$end_char;
            }
            

            $spreadsheet->getActiveSheet()->getStyle($style_upto)->applyFromArray($styleArray);
            
        // auto fit column to content
        foreach(range('A', 'Z') as $columnID) {
            $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        // manually set table data value
        if(!empty($data['display_columns']))
        {
               
            $char_range = range('A','Z');
            $row =1;
            foreach($data['display_columns'] as $key=>$column)
            {
                
                    $sheet->setCellValue($char_range[$key].$row, $column);
                    // $printheader[$char_range[$key].$row] = $column;
                
            }
            $rows = 2;
            foreach ($data['log_data'] as $val){
                foreach($data['display_fileds'] as $df_key=>$df)
                {
                    $sheet->setCellValue($char_range[$df_key].$rows, $val->$df);
                    // $printheaders['d'.$rows][$df] = $val->$df;
                }
                $rows++;
            } 

            if(isset($data['custom_fields']))
            {
                $rows = count($data['log_data']) + 2;
                foreach($data['custom_fields'] as $val)
                {
                    foreach($data['display_fileds'] as $df_key=>$df)
                    {
                        $sheet->setCellValue($char_range[$df_key].$rows, $val->$df);
                    
                    }
                    $rows++;
                }
                
            }
        }
    //    print_a($sheet,true);
        $writer = new Xlsx($spreadsheet);
        $fileName = $fileName.".xlsx";
        $writer->save("uploads/reports/".$fileName);
        if($jquery)
        {
            return base_url("uploads/reports/".$fileName);
        }
        else
        {

            header("Content-Type: application/vnd.ms-excel");
            redirect(base_url('/')."uploads/reports/".$fileName); 
                
        }
    }

    


}