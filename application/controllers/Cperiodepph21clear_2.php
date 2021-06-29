<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cperiodepph21clear extends CI_Controller {
    private $sTable = 'vpph21_clear_new_2';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mperiodepph21clear','periodepph21clear');
        $this->load->helper('permalink_helper');
    }

    public function index(){
        $this->load->view('header');
        $this->load->view('periodepph21clear_view');
    }


    function list_periodepph21clear() {
        $aColumns = array('periode','periode_penggajian','segmen','nik','nama','departemen',
            'base','over_time','over_time_index','insentive_kehadiran','tunjangan_transport','tunjangan_makan','tunjangan_kendaraan',
            'tunjangan_anak','thr_bonus','sales_insentive','adjustment_plus','tunjangan_pph21','total_gross',
            'bpjs_ketenagakerjaan_karyawan','bpjs_kesehatan_karyawan','jpn_karyawan','potongan_koperasi',
            'potongan_mangkir','adjustment_minus','potongan_pph21','thp',
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
                            <a target="_blank" class="btn btn-smd btn-info" href=""><i class="glyphicon glyphicon-list"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                        
                            $row = array($btn, $aRow['periode'], $aRow['periode_penggajian'], $aRow['segmen'], 
                                $aRow['nik'], $aRow['nama'], $aRow['departemen'], 
                                $aRow['base'], $aRow['over_time'], $aRow['over_time_index'], $aRow['insentive_kehadiran'],
                                $aRow['tunjangan_transport'], $aRow['tunjangan_makan'], $aRow['tunjangan_kendaraan'], 
                                $aRow['tunjangan_anak'], $aRow['thr_bonus'], $aRow['sales_insentive'], $aRow['adjustment_plus'],
                                $aRow['tunjangan_pph21'],$aRow['total_gross'],
                                $aRow['bpjs_ketenagakerjaan_karyawan'],$aRow['bpjs_kesehatan_karyawan'],$aRow['jpn_karyawan'],
                                $aRow['potongan_koperasi'], $aRow['potongan_mangkir'],$aRow['adjustment_minus'],$aRow['potongan_pph21'],
                                $aRow['thp'],$aRow['metode_pph21'],$aRow['bpjs_kesehatan_perusahaan'], $aRow['jpn_perusahaan'], 
                                $aRow['bpjs_ketenagakerjaan_perusahaan'],$aRow['potongan_bpjs']);
                            $output['aaData'][] = $row;
                        
                    }
                    echo json_encode( $output );
    }

    public function delete($periode,$periode_penggajian,$segmen,$nik){
        $this->periodepph21->delete_by_id($periode,$periode_penggajian,$segmen,$nik);
        echo json_encode(array("status" => TRUE));
        redirect('cperiodepph21');
    }

}
