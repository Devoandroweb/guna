<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cperiodej extends CI_Controller {
    private $sTable = 'trans_periode_jamsostek';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mperiodej','periodej');
        $this->load->helper('permalink_helper');
    }

    public function index(){
        $this->header();
        $this->load->view('periodej_view');
    }

    function calculate() {
        $sql = "CALL hitung_jamsostek";
        $XPeriode = $this->session->userdata('periode');
        $XPeriode_Penggajian = 'BULANAN';
        $XSegmen = '1';

        $query = $this->db->query("$sql ('$XPeriode','$XPeriode_Penggajian','$XSegmen')");
        redirect('cperiodej');
    }

    function list_periodej() {
        $aColumns = array('periode','periode_penggajian','segmen','nik','nama','nama_program','gaji_dasar',
            'maksimal_gaji_dasar','bagian_perusahaan','bagian_karyawan','nilai_perusahaan','nilai_karyawan',
            'lastupdate','user_id');
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
                        
                            // $btn = '<a class="btn btn-sm text-dark" title="Delete" href="cperiodej/delete/'.$aRow['periode'].'/'.$aRow['periode_penggajian'].'/'.$aRow['segmen'].'/'.$aRow['nik'].'/'.$aRow['nama_program'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>';
                        $btn = '<a class="btn btn-sm text-dark" title="Detail" href="cperiodej/detail/'.$aRow['nik'].'"><i class="fa fa-info"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn,$aRow['periode'], $aRow['periode_penggajian'], $aRow['segmen'], 
                                $aRow['nik'], $aRow['nama'], $aRow['nama_program'], $aRow['gaji_dasar'], $aRow['maksimal_gaji_dasar'], 
                                $aRow['bagian_perusahaan'], $aRow['bagian_karyawan'], $aRow['nilai_perusahaan'], 
                                $aRow['nilai_karyawan'], $aRow['lastupdate'], $aRow['user_id']);

                            $output['aaData'][] = $row;
                            
                        $row = array();
                    }
                    echo json_encode( $output );
    }

    public function delete($periode,$periode_penggajian,$segmen,$nik,$nama_program){
        $this->periodej->delete_by_id($periode,$periode_penggajian,$segmen,$nik,$nama_program);
        echo json_encode(array("status" => TRUE));
        redirect('cperiodej');
    }
    public function detail($nik){
        $data['query'] = $this->periodej->select_by_nik($nik);
        $this->header();
        $this->load->view('periodej_detail',$data);

    }
    public function excel(){
        $dataResult = array();

        $aColumns = array('periode','periode_penggajian','segmen','nik','nama','nama_program','gaji_dasar',
            'maksimal_gaji_dasar','bagian_perusahaan','bagian_karyawan','nilai_perusahaan','nilai_karyawan',
            'lastupdate','user_id');
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
            
                // $btn = '<a class="btn btn-sm text-dark" title="Delete" href="cperiodej/delete/'.$aRow['periode'].'/'.$aRow['periode_penggajian'].'/'.$aRow['segmen'].'/'.$aRow['nik'].'/'.$aRow['nama_program'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>';
            // $btn = '<a class="btn btn-sm text-dark" title="Detail" href="cperiodej/detail/'.$aRow['nik'].'"><i class="fa fa-info"></i></a>';
            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                $row[] = $aRow[ $aColumns[$i] ];
            }
            $row = array(
                $aRow['periode'], 
                $aRow['periode_penggajian'], 
                $aRow['segmen'], 
                $aRow['nik'], 
                $aRow['nama'], 
                $aRow['nama_program'], 
                $this->fungsi->FormatNum($aRow['gaji_dasar']), 
                $this->fungsi->FormatNum($aRow['maksimal_gaji_dasar']), 
                $aRow['bagian_perusahaan'], $aRow['bagian_karyawan'], 
                $this->fungsi->FormatNum($aRow['nilai_perusahaan']), 
                $this->fungsi->FormatNum($aRow['nilai_karyawan']), 
                $this->fungsi->dateToIndo($aRow['lastupdate']), $aRow['user_id']);

            $dataResult = $row;
                
            $row = array();
        }

        $header = array('Periode','Periode Penggajian','Segmen','NIK','Nama','Nama Program','Gaji Dasar',
            'Maksimal Gaji Dasar','Bagian Perusahaan','Bagian Karyawan','Nilai Perusahaan','Nilai Karyawan',
            'Last Update','USER ID');

        $data[] = $dataResult;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"TRANSAKSI PERIODE JAM SOSTEK -".date("Ymdhis").".csv\"");
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
        $data['active_accordion'] = "e";
        $data['aria_expanded'] = "e";
        $data['sub_menu_show'] = "e";
        $data['active_menu'] = 26;
        $this->load->view('header',$data);
    }
}
