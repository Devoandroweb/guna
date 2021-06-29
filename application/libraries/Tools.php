<?php
class Tools{
    function exportdata($table,$title,$desc,$namafile,$folderpath=''){
        $CI=& get_instance();
        $CI->load->library('phpexcel');
        $CI->load->database();
        $CI->load->library('PHPExcel/iofactory');
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle($title)
                    ->setDescription($desc);
        $objPHPExcel->setActiveSheetIndex(0);
        
        $space=0;
        $space+=1;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$space,$title);
        $space+=1;
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$space,$desc);
        $fields = $CI->db->list_fields($table);
        $countfield=count($fields)-1;
        $space+=2;
        $col=-1;
        for($colC=0;$colC<=$countfield;$colC++){
            $col+=1;
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$space,$fields[$colC]);
        }
        
        $result=$CI->db->get($table);
        $dataarray=array();
        $space+=1;
        foreach($result->result() as $row){
            $dcol=-1;
            $dataarray=$row;
            foreach($dataarray as $row2){
                $dcol+=1;                
            $objPHPExcel->getActiveSheet()->setCellValueExplicitByColumnAndRow($dcol,$space,$row2,PHPExcel_Cell_DataType::TYPE_STRING);
            }
            $space+=1;
        }
        
        $col+=1;
        $space+=1;
        $filename=$namafile.'.xls';
        if($folderpath!=""){
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save($folderpath.'/'.$filename);
        }else{
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0');
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
        }
    }
    

    function DateToIndo($date){
        $BulanIndo = array("01","02","03","04","05","06","07","08","09","10","11","12");
        $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
        $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
        $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
        $result = $tahun . "-" . $BulanIndo[(int)$bulan-1] . "-". $tgl;
        return($result);
    }


    function importdata($file,$table,$startRow,$checkPrimary=FALSE){
        $CI=& get_instance();
        $CI->load->database();
        $CI->load->library('phpexcel');
        $CI->load->library('PHPExcel/iofactory');
        $objPHPExcel = new PHPExcel();
        try{
            $inputFileType = IOFactory::identify($file);
            $objReader = IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($file);
        } catch (Exception $ex) {
            die("Tidak dapat mengakses file ".$ex->getMessage());
        }
        
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        
        for ($row = $startRow; $row <= $highestRow; $row++){
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,NULL,TRUE,FALSE);
            $startCol=0;
            if($checkPrimary==TRUE){
                $startCol=0;
            }else{
                $startCol=1;
            }
            $fields = $CI->db->list_fields('master_karyawan');
            $countfield=count($fields)-1;
            $datacol=array();
            $dataxl=array();
            $colXl=-1;
            for($col=$startCol;$col<=$countfield;$col++){
                $colXl+=1;                
                $datacol[]=$fields[$col];
                $dataxl[]=$rowData[0][$colXl];
            }
            $data=array_combine($datacol,$dataxl);
            //$f = "'.$data[tanggal_lahir].'";
            //$s = str_replace("/","-",$data[tanggal_lahir]);
            $e = date_format(date_create(str_replace("/","-",$data[tanggal_lahir])), 'Y-m-d');
            //$te = date_format($da, 'd/m/Y');
            $sql = $CI->db->insert_string('master_karyawan', $data) . " ON DUPLICATE KEY UPDATE nama='$data[nama]', jenis_kelamin='$data[jenis_kelamin]', tanggal_lahir='$e', agama='$data[agama]', status_perkawinan='$data[status_perkawinan]', alamat='$data[alamat]', telepon='$data[telepon]', email='$data[email]', departemen='$data[departemen]', grup='$data[grup]', jabatan='$data[jabatan]', akhir_kontrak='$data[akhir_kontrak]', status='$data[status]', npwp='$data[npwp]', bank='$data[bank]', no_rekening='$data[no_rekening]', pemilik_rekening='$data[pemilik_rekening]', periode_penggajian='$data[periode_penggajian]', mata_uang='$data[mata_uang]', enroll='$data[enroll]', lastupdate='$data[lastupdate]', user_id='$data[user_id]'";
            $CI->db->query($sql);
        }
    }      
}
?>