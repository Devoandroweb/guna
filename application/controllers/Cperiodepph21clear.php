<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cperiodepph21clear extends CI_Controller {
    private $sTable = 'vpph21_clear_new';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mperiodepph21clear','periodepph21clear');
        $this->load->helper('permalink_helper');
    }

    public function index(){
        $this->header();
        $this->load->view('periodepph21clear_view');
    }


    function list_periodepph21clear() {
        $aColumns = array('periode','periode_penggajian','segmen','nik','nama','base','over_time',
            'tunjangan_transport','tunjangan_makan','tunjangan_kendaraan','tunjangan_anak','sales_insentive',
            'adjustment_plus','tunjangan_pph21','bpjs_ketenagakerjaan_karyawan','bpjs_kesehatan_karyawan',
            'jpn_karyawan','potongan_koperasi','potongan_mangkir','adjustment_minus','potongan_pph21','thp',
            'metode_pph21','bpjs_kesehatan_perusahaan','jpn_perusahaan','bpjs_ketenagakerjaan_perusahaan',
            'potongan_bpjs');

        $sIndexColumn = 'periode';
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
                $sWhere = " WHERE periode='$ot'";
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
                    $this->db->reconnect();
                    foreach ($rResult->result_array() as $aRow) {
                        $p = $this->session->userdata('periode');
                        $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();
                            $row = array();
                            $btn = '
                            <a target="_blank" class="text-dark mr-1" href=""><i class="fa  fa-bars"></i></a>
                            <a class="text-dark" href="Cperiodepph21clear/detail/'.$aRow['nik'].'"><i class="fa fa-info"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                        
                            $row = array($btn, $aRow['periode'],$aRow['periode_penggajian'],$aRow['segmen'],$aRow['nik'],
                                $aRow['nama'],$this->fungsi->FormatNum($aRow['base']),$aRow['over_time'],
                            $aRow['tunjangan_transport'],$aRow['tunjangan_makan'],$aRow['tunjangan_kendaraan'],$aRow['tunjangan_anak'],
                            $aRow['sales_insentive'],
                            $aRow['adjustment_plus'],$aRow['tunjangan_pph21'],$aRow['bpjs_ketenagakerjaan_karyawan'],
                            $aRow['bpjs_kesehatan_karyawan'],
                            $aRow['jpn_karyawan'],$aRow['potongan_koperasi'],$aRow['potongan_mangkir'],$aRow['adjustment_minus'],
                            $aRow['potongan_pph21'],$aRow['thp'],
                            $aRow['metode_pph21'],$aRow['bpjs_kesehatan_perusahaan'],$aRow['jpn_perusahaan'],
                            $aRow['bpjs_ketenagakerjaan_perusahaan'],
                            $aRow['potongan_bpjs']);

                            $output['aaData'][] = $row;
                        
                    }
                    echo json_encode( $output );
    }

    public function delete($periode,$periode_penggajian,$segmen,$nik){
        $this->periodepph21->delete_by_id($periode,$periode_penggajian,$segmen,$nik);
        echo json_encode(array("status" => TRUE));
        redirect('cperiodepph21');
    }
    public function detail($nik){
        $data['query'] = $this->periodepph21clear->select_by_nik($nik);
        $this->header();
        $this->load->view('periodepph21clear_detail',$data);

    }

    public function excel(){
        $dataResult = array();

        $aColumns = array('periode','periode_penggajian','segmen','nik','nama','base','over_time',
            'tunjangan_transport','tunjangan_makan','tunjangan_kendaraan','tunjangan_anak','sales_insentive',
            'adjustment_plus','tunjangan_pph21','bpjs_ketenagakerjaan_karyawan','bpjs_kesehatan_karyawan',
            'jpn_karyawan','potongan_koperasi','potongan_mangkir','adjustment_minus','potongan_pph21','thp',
            'metode_pph21','bpjs_kesehatan_perusahaan','jpn_perusahaan','bpjs_ketenagakerjaan_perusahaan',
            'potongan_bpjs');

        $sIndexColumn = 'periode';
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
       

       
        $this->db->reconnect();
        foreach ($rResult->result_array() as $aRow) {
            $p = $this->session->userdata('periode');
            $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();
                $row = array();
                for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                    $row[] = $aRow[ $aColumns[$i] ];
                }
            
                $row = array($aRow['periode'],$aRow['periode_penggajian'],$aRow['segmen'],$aRow['nik'],
                    $aRow['nama'],$aRow['base'],$aRow['over_time'],
                $aRow['tunjangan_transport'],$aRow['tunjangan_makan'],$aRow['tunjangan_kendaraan'],$aRow['tunjangan_anak'],
                $aRow['sales_insentive'],
                $aRow['adjustment_plus'],$aRow['tunjangan_pph21'],$aRow['bpjs_ketenagakerjaan_karyawan'],
                $aRow['bpjs_kesehatan_karyawan'],
                $aRow['jpn_karyawan'],$aRow['potongan_koperasi'],$aRow['potongan_mangkir'],$aRow['adjustment_minus'],
                $aRow['potongan_pph21'],$aRow['thp'],
                $aRow['metode_pph21'],$aRow['bpjs_kesehatan_perusahaan'],$aRow['jpn_perusahaan'],
                $aRow['bpjs_ketenagakerjaan_perusahaan'],
                $aRow['potongan_bpjs']);

                $dataResult = $row;
            
        }

        $header = array('Periode','Periode Penggajian','Segmen','NIK','Nama','Base','Over Time',
            'Tunjangan Transport','Tunjangan Makan','Tunjangan Kendaraan','Tunjangan Anak','Sales Insentive',
            'Adjustment Plus','Tunjangan PPH21','NPJS Ketanagakerjaan Karyawan','BPJS Kesehatan Karyawan',
            'JPN Karyawan','Potongan Koperasi','Potongan Mangkir','Adjustment Minus','Potongan PPH21','THP',
            'Metode PPH21','BPJS Kesehatan Perusahaan','JPN Perusahaan','BPJS Ketenagakerjaan Perusahaan',
            'Potongan BPJS');

        $data[] = $dataResult;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"Periode PPH21 Clear-".date("Ymdhis").".csv\"");
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
        $data['active_accordion'] = "f";
        $data['aria_expanded'] = "f";
        $data['sub_menu_show'] = "f";
        $data['active_menu'] = 29;
        $this->load->view('header',$data);
    }

}
