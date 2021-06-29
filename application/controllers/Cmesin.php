<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cmesin extends CI_Controller {
    private $sTable = 'master_absen_aktual_temp';
    public function __construct(){
        parent::__construct();
        if($this->session->userdata('status') != "login"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mabsenaktual_temp','absenaktual_temp');
        //$this->load->library('csvimport');
        $this->load->helper('date');
        //$this->load->library('excel_reader2');
        //include_once ( APPPATH."libraries/excel_reader2.php"); 
    }

    public function index(){
        //$result = $this->csvreader->parse_file('uploads/1616-3.csv');
        //$data['csvData'] =  $result;
        //$this->absenaktual_temp->add_record($result);
            
        //print_r($data);
        $this->load->helper('url');
        $this->load->view('header');
        $this->load->view('view_csv');
    }

    function list_absenaktual_temp() {
        $aColumns = array('no_akun','no','nama','waktu','kondisi','kondisi_baru','status','operasi');
        $sIndexColumn = 'no_akun';
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
                $o = substr($ot, 0, -2);
                $t = substr($ot, 4, 2);
                $per =  $o.'-'.$t ;
                $sWhere = " ";
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
                    $btn = '<a class="btn btn-smd btn-primary" href="cmesin/edit/'.$aRow['no_urut'].'"><i class="glyphicon glyphicon-pencil"></i></a> 
                    <a class="btn btn-smd btn-danger" href="cmesin/delete/'.$aRow['no_urut'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                        for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                            $row[] = $aRow[ $aColumns[$i] ];
                        }
                    $row = array($btn, $aRow['no_urut'],$aRow['no_akun'],$aRow['nama'],$aRow['waktu'],
                        $aRow['kondisi'],$aRow['kondisi_baru'],$aRow['status'],$aRow['operasi']);
                    $output['aaData'][] = $row;
            }
            echo json_encode( $output );
    }

    function edit($nik,$tanggal){
        $this->db->where('nik',$nik);
        $this->db->where('tanggal',$tanggal);
        $data['query'] = $this->db->get($this->sTable);
        $data['nik'] = $nik;
        $data['tanggal'] = $tanggal;
        $this->load->view('header');
        $this->load->view('absenaktual_edit', $data);
    }
    
    public function delete($nik,$tanggal){
        $this->absenaktual->delete_by_id($nik,$tanggal);
        echo json_encode(array("status" => TRUE));
        redirect('cabsenaktual');
    }

    function tambah(){
        $data['query'] = $this->db->get('content_status_aktual');
        $this->load->view('header');
        $this->load->view('absenaktual_add', $data);
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $status_aktual = $this->input->post('status_aktual');
        $masuk = $this->input->post('masuk');
        $pulang = $this->input->post('pulang');
        $shift = $this->input->post('nama_shift');
        $lastupdate = $last;
        $user_id = $this->input->post('user_id');
        $nik = $this->input->post('nik');
        $tanggal = $this->input->post('tanggal');
        $this->db->where('nik', $nik);
        $this->db->where('tanggal', $tanggal);
        $this->absenaktual->update_absen_aktual($shift,$status_aktual,$masuk,$pulang,$nik,$tanggal);
        redirect('cabsenaktual');
    }

    function create(){
        $ot = $this->session->userdata('periode');
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'nik' => $this->input->post('nik'),
            'tanggal' => $this->input->post('tanggal'),
            'periode' => $ot,
            'status_aktual' => $this->input->post('status_aktual'),
            'keterangan' => $this->input->post('keterangan'),
            'masuk' => $this->input->post('mulai'),
            'pulang' => $this->input->post('selesai'),
            'shift' => $this->input->post('shift'),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        $this->absenaktual->add_record($data);
        redirect('cabsenaktual');
    }

    function upload(){
        $this->load->view('header');
        $this->load->view('mesin_upload');
    }

    function importcsv(){
        $periode = $this->session->userdata('periode');
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '100000';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
            $this->load->view('absenaktual_upload', $data);
        } else {
            $file_data = $this->upload->data();
            $file_path =  './uploads/'.$file_data['file_name'];
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                $path_to_file = './uploads/'.$file_data['file_name'];
                unlink($path_to_file);
                foreach ($csv_array as $row) {
                    $in = array(
                        'nik' => $row['nik'],
                        'tanggal' => "",
                        'periode' => "",
                        'status_aktual' => "",
                        'keterangan' => "",
                        'masuk' => $row['masuk'],
                        'pulang' => $row['pulang'],
                        'lastupdate' => "",
                        'user_id' => ""
                    );

                    $fe = date_create($row['masuk']);
                    $kl = date_format($fe, 'H:i');
                    $tgl1 = date_format($fe, 'Y-m-d');
                    $tgl2 = date('Y-m-d', strtotime('-1 days', strtotime($tgl1)));

                    if (($kl >= date_format(date_create('00:01'), 'H:i')) and ($kl <= date_format(date_create('01:00'), 'H:i'))){
                        $tw = $this->db->query("select nama_shift as shift FROM master_karyawan INNER JOIN master_absen_grup_shift ON master_karyawan.grup = master_absen_grup_shift.grup
                        where master_karyawan.nik = '".$row['nik']."' and masuk_valid_awal>=time('23:30') and masuk_valid_akhir<=time('00:30')");
                    } else {
                        $tw = $this->db->query("select nama_shift as shift FROM master_karyawan INNER JOIN master_absen_grup_shift ON master_karyawan.grup = master_absen_grup_shift.grup
                        where master_karyawan.nik = '".$row['nik']."' and '".$kl."' >= master_absen_grup_shift.masuk_valid_awal
                        and '".$kl."' <= master_absen_grup_shift.masuk_valid_akhir");
                    }
                    $this->absenaktual->insert_csv($in,$tw,$tgl1,$tgl2,$periode);
                    }
                }
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'cabsenaktual/upload');
            } 
    }
}
