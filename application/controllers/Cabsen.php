<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabsen extends CI_Controller {
    private $sTable = 'master_mesin';
    private $filename = "absensi";
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mabsen','absen');
        $this->load->library('csvimport');
        $this->load->helper('date');
    }

    public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('absen_view');
    }

    function transact() {
        $sql = "CALL absen_clear";
        $XPeriode = $this->session->userdata('periode');
        $this->db->query("$sql ('$XPeriode')");
        redirect('ctransct');
    }

    function list_absen() {
        $aColumns = array('no','tanggal','enroll','periode','status_aktual','keterangan','waktu','kondisi','shift',
            'kondisi_baru','status','operasi');
        $sIndexColumn = 'no';
        $input =& $_POST;
        $sQuery = "SELECT COUNT(`".$sIndexColumn."`) AS row_count FROM `".$this->sTable."`";
        $rResultTotal = $this->db->query( $sQuery );
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->row_count;
        $sLimit = "";
        if ( isset( $input['iDisplayStart'] ) && $input['iDisplayLength'] != '-1' ) {
            $sLimit = " LIMIT ".intval( $input['iDisplayStart'] ).", ".intval( $input['iDisplayLength'] );
        }
            
            $aOrderingRules = array();
            if ( isset( $input['iSortCol_0'] ) ) {
                    $iSortingCols = intval( $input['iSortingCols'] );
                    for ( $i=0 ; $i<$iSortingCols ; $i++ ) {
                            if ( $input[ 'bSortable_'.intval($input['iSortCol_'.$i]) ] == 'true' ) {
                                    $aOrderingRules[] =
                    "`".$aColumns[ intval( $input['iSortCol_'.$i] ) ]."` "
                    .($input['sSortDir_'.$i]==='asc' ? 'asc' : 'desc');
                            }
                    }
            }

            if (!empty($aOrderingRules)) {
                    $sOrder = " ORDER BY ".implode(", ", $aOrderingRules);
                    } else {
                    $sOrder = "";
            }
            
            $iColumnCount = count($aColumns);
    
            if ( isset($input['sSearch']) && $input['sSearch'] != "" ) {
                $aFilteringRules = array();
                for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                    if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' ) {
                        $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$this->db->real_escape_string( $input['sSearch'] )."%'";
                        }
                    }
                    if (!empty($aFilteringRules)) {
                        $aFilteringRules = array('('.implode(" OR ", $aFilteringRules).')');
                    }
            }
            
            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                if ( isset($input['bSearchable_'.$i]) && $input['bSearchable_'.$i] == 'true' && $input['sSearch_'.$i] != '' ) {
                    $aFilteringRules[] = "`".$aColumns[$i]."` LIKE '%".$this->db->real_escape_string($input['sSearch_'.$i])."%'";
                }
            }

            if (!empty($aFilteringRules)) {
                $sWhere = " WHERE ".implode(" AND ", $aFilteringRules);
                } else {
                $ot = $this->session->userdata('periode');
                $sWhere = "WHERE periode='$ot'";
            }

            $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }
            
                $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
                FROM `".$this->sTable."`".$sWhere.$sOrder.$sLimit;
                $rResult = $this->db->query( $sQuery );
                $sQuery = "SELECT FOUND_ROWS() AS length_count";
                $rResultFilterTotal = $this->db->query($sQuery);
                $aResultFilterTotal = $rResultFilterTotal->row();
                $iFilteredTotal = $aResultFilterTotal->length_count;

                $output = array(
                        "iTotalRecords"        => $iTotal,
                        "iTotalDisplayRecords" => $iFilteredTotal,
                        "aaData"               => array(),
                    );
                    foreach ($rResult->result_array() as $aRow) {
                        $row = array();
                            $btn = '<a class="text-dark mr-1" title="Edit" href="cabsen/edit/'.$aRow['no'].'"><i class="fa fa-pencil"></i></a> 
                                <a class="text-dark mr-2" href="cabsen/delete/'.$aRow['no'].'" title="Delete" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a> <a class="text-dark" href="cabsen/detail/'.$aRow['no'].'" title="Detail""><i class="fa fa-info"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['no'], $aRow['tanggal'], $aRow['enroll'], $aRow['periode'], 
                                $aRow['status_aktual'], $aRow['keterangan'], 
                                $aRow['waktu'], $aRow['kondisi'], $aRow['shift'], $aRow['kondisi_baru'], 
                                $aRow['status'], $aRow['operasi']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($no){
        $this->db->where('no',$no);
        $data['query'] = $this->db->get($this->sTable);
        $data['no'] = $no;
        $this->header();
        $this->load->view('absen_edit', $data);
    }
    
    public function delete($no){
        $this->absen->delete_by_id($no);
        echo json_encode(array("status" => TRUE));
        redirect('cabsen');
    }

    function tambah(){
        $data['query'] = $this->db->get('content_status_aktual');
        $data['pegawai'] = $this->db->get('master_karyawan')->result();
        $this->header();
        $this->load->view('absen_add', $data);
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'status_aktual' => $this->input->post('status_aktual'),
            'keterangan' => $this->input->post('keterangan'),
            'waktu' => $this->input->post('waktu'),
            'kondisi' => $this->input->post('kondisi'),
            'shift' => $this->input->post('nama_shift'),
            'kondisi_baru' => "",
            'status' => "",
            'operasi' => ""
        );
        
        $no = $this->input->post('no');
        $this->db->where('no', $no);
        $this->db->update($this->sTable, $data);
        redirect('cabsen');
    }

    function create(){
        $ot = $this->session->userdata('periode');
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'tanggal' => $this->input->post('tanggal'),
            'enroll' => $this->input->post('enroll'),
            'periode' => $ot,
            'status_aktual' => $this->input->post('status_aktual'),
            'keterangan' => $this->input->post('keterangan'),
            'waktu' => $this->input->post('waktu'),
            'kondisi' => $this->input->post('kondisi'),
            'shift' => $this->input->post('shift'),
            'kondisi_baru' => "",
            'status' => "",
            'operasi' => ""
        );
        $this->absen->add_record($data);
        redirect('cabsen');
    }

    function upload(){
        $this->header();
        $this->load->view('absen_upload');
    }

    public function form(){
        $this->header();
        $data = array();
        
        if(isset($_POST['preview'])){
            $upload = $this->absen->upload_file($this->filename);
            if($upload['result'] == "success"){
                include APPPATH.'third_party/phpexcel/PHPExcel.php';
                $csvreader      = PHPExcel_IOFactory::createReader('CSV');
                $loadcsv        = $csvreader->load('csv/'.$this->filename.'.csv');
                $sheet          = $loadcsv->getActiveSheet()->getRowIterator();
                $data['sheet']  = $sheet; 
            } else {
                $this->header();
                $data['upload_error'] = $upload['error'];
            }
        }
        $this->load->view('absen_upload', $data);
    }

    public function import(){
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $csvreader = PHPExcel_IOFactory::createReader('CSV');
        $loadcsv = $csvreader->load('csv/'.$this->filename.'.csv');
        $sheet = $loadcsv->getActiveSheet()->getRowIterator();
        $data = [];
        
        $numrow = 1;
        foreach($sheet as $row){
            if($numrow > 1){
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $get = array();
                foreach ($cellIterator as $cell) {
                    array_push($get, $cell->getValue());
                }

                $enroll         = $get[0];
                $no             = $get[1];
                $nama           = $get[2];
                $waktu          = $get[3];
                $kondisi        = $get[4];
                $kondisi_baru   = $get[5];
                $status         = $get[6];
                $operasi        = $get[7];
                $m = DateTime::createFromFormat('d/m/Y H:i', $waktu);

                $f = $m->format('Y-m-d H:i');
                $tanggal = $m->format('Y-m-d');
                $periode = $this->session->userdata('periode');
                $tex = $kondisi;
                $rep = str_replace("/","", $tex);
                $kl = $m->format('H:i');

                array_push($data,[
                    'tanggal'       => $tanggal, 
                    'enroll'        => $enroll,
                    'periode'       => $periode,
                    'status_aktual' => 'Hadir',
                    'keterangan'    => '',
                    'waktu'         => $f,
                    'jam'           => $kl,
                    'kondisi'       => $rep,
                    'shift'         => '',
                    'kondisi_baru'  => $kondisi_baru, 
                    'status'        => $status, 
                    'operasi'       => $operasi,
                ]);
            }
            $numrow++;
        }
        $this->absen->insert_multiple($data);
        redirect("cabsen");
    }

    function importcsv(){
        $periode = $this->session->userdata('periode');
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '100000';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
            $this->load->view('absen_upload', $data);
        } else {
            $file_data = $this->upload->data();
            $file_path =  './uploads/'.$file_data['file_name'];
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                $path_to_file = './uploads/'.$file_data['file_name'];
                unlink($path_to_file);
                foreach ($csv_array as $row) {
                    $tex = $row['kondisi'];
                    $rep = str_replace("/","", $tex);
                    //$tex_baru = $row['kondisi_baru'];
                    //$rep_baru = str_replace("/","", $tex_baru);
                    $in = array(
                        'no' => "",
                        'tanggal' => "",
                        'enroll' => $row['enroll'],
                        'periode' => $periode,
                        'waktu' => $row['waktu'],
                        'kondisi' => $rep
                    );

                    $m = DateTime::createFromFormat('d/m/Y H:i', $row['waktu']);
                    $f = $m->format('Y-m-d H:i');

                    $fe = date_create($row['waktu']);
                    $kl = $m->format('H:i:s');
                    $tgl1 = $m->format('Y-m-d');
                    $tgl2 = date('Y-m-d', strtotime('-1 days', strtotime($tgl1)));
                    
                    if (($kl >= date_format(date_create('00:01'), 'H:i:s')) and ($kl <= date_format(date_create('01:00'), 'H:i:s'))){
                        $tw = $this->db->query("select nama_shift as shift FROM master_karyawan INNER JOIN master_absen_grup_shift ON master_karyawan.grup = master_absen_grup_shift.grup
                        where master_karyawan.enroll = '".$row['enroll']."' and masuk_valid_awal>=time('23:30:00') and masuk_valid_akhir<=time('00:30:00')");
                    } else {
                        $tw = $this->db->query("select nama_shift as shift FROM master_karyawan INNER JOIN master_absen_grup_shift ON master_karyawan.grup = master_absen_grup_shift.grup
                        where master_karyawan.enroll = '".$row['enroll']."' and '".$kl."' >= master_absen_grup_shift.masuk_valid_awal
                        and '".$kl."' <= master_absen_grup_shift.masuk_valid_akhir");
                    }

                    $this->absen->insert_csv($f,$in,$tw,$tgl1,$tgl2,$periode);
                    }
                }
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'cabsen/upload');
            } 
    }
    function detail($no){
        $this->db->where('no',$no);
        $data['query'] = $this->db->get($this->sTable);
        $data['no'] = $no;
        $this->header();
        $this->load->view('absen_detail', $data);
    }
    public function excel(){
        $dataResult = array();

        $aColumns = array('no','tanggal','enroll','periode','status_aktual','keterangan','waktu','kondisi','shift',
            'kondisi_baru','status','operasi');
        $sIndexColumn = 'no';
        $input =& $_POST;
        

        $iColumnCount = count($aColumns);

        $aQueryColumns = array();
        foreach ($aColumns as $col) {
            if ($col != ' ') {
                $aQueryColumns[] = $col;
            }
        }
            $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
            FROM `".$this->sTable."`";
            $rResult = $this->db->query( $sQuery );
            

           
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
                
                for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                    $row[] = $aRow[ $aColumns[$i] ];
                }
                $row = array($aRow['no'], $aRow['tanggal'], $aRow['enroll'], $aRow['periode'], 
                    $aRow['status_aktual'], $aRow['keterangan'], 
                    $aRow['waktu'], $aRow['kondisi'], $aRow['shift'], $aRow['kondisi_baru'], 
                    $aRow['status'], $aRow['operasi']);
                $dataResult = $row;
        }
         $header = array('No','Tanggal','Enroll','Periode','Status Aktual','Keterangan','Waktu','Kondisi','Shift',
            'Kondisi Baru','Status','Operasi');

        $data[] = $dataResult;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"ABSEN-".date("Ymdhis").".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        

        $handle = fopen('php://output', 'w');

        fputcsv($handle, $header); 

        foreach ($data as $data_array) {
            fputcsv($handle, $data_array);
        }
            fclose($handle);
        exit;
    }

    function header(){
        $data['active_accordion'] = "g";
        $data['aria_expanded'] = "g";
        $data['sub_menu_show'] = "g";
        $data['active_menu'] = 30;
        $this->load->view('header',$data);
    }
}
