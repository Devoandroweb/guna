<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Cperiodepph21 extends CI_Controller {
    private $sTable = 'trans_periode_pph21';
    private $dataExcel = [];
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mperiodepph21','periodepph21');
        $this->load->helper('permalink_helper');
    }

    public function index(){
        $this->header();
        $this->load->view('periodepph21_view', NULL);
    }

    function calculate() {
        $sql = "CALL hitung_pph21";
        $XPeriode = $this->session->userdata('periode');
        $XPeriode_Penggajian = 'BULANAN';
        $XSegmen = '1';
        $query = $this->db->query("$sql ('$XPeriode','$XPeriode_Penggajian','$XSegmen')");
        redirect('cperiodepph21');
    }

    function list_periodepph21() {
        $aColumns = array('periode','nik','nama','status_perkawinan','npwp','departemen', 
            'jabatan','over_time','base','tunjangan_transport','tunjangan_kendaraan','tunjangan_makan','tunjangan_anak',
            'sales_incentive','bonus','adjustment_plus','jkm_perusahaan','jkk_perusahaan','jht_perusahaan',
            'jkn_perusahaan','jpn_perusahaan','penghasilan_kotor','jht_karyawan','jpn_karyawan','biaya_jabatan',
            'total_pengurang','netto','netto_setahun','ptkp_gaji','penghasilan_kena_pajak','pph21_gaji_setahun',
            'pph21_gaji_sebulan','tambahan_non_npwp','metode_pph21','pph21_nett','lastupdate','user_id');
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
                            // $btn = '<a class="btn btn-smd btn-danger" href="cperiodepph21/delete/'.$aRow['nik'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>
                            // <a target="_blank" class="btn btn-smd btn-info" href="claporanpph21/tahunan/'.$aRow['nik'].'"><i class="glyphicon glyphicon-list"></i></a>';
                            $btn = '<a class="text-dark" href="cperiodepph21/detail/'.$aRow['nik'].'"><i class="fa fa-info"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }

                                $row = array($btn,$aRow['periode'],$aRow['nik'],$aRow['nama'],$aRow['status_perkawinan'],
                                    $aRow['npwp'],$aRow['departemen'], 
                                    $aRow['jabatan'],$aRow['over_time'],$aRow['base'],$aRow['tunjangan_transport'],
                                    $aRow['tunjangan_kendaraan'],$aRow['tunjangan_makan'],$aRow['tunjangan_anak'],
                                    $aRow['sales_incentive'],$aRow['bonus'],$aRow['adjustment_plus'],
                                    $aRow['jkm_perusahaan'],$aRow['jkk_perusahaan'],$aRow['jht_perusahaan'],
                                    $aRow['jkn_perusahaan'],$aRow['jpn_perusahaan'],$aRow['penghasilan_kotor'],
                                    $aRow['jht_karyawan'],$aRow['jpn_karyawan'],$aRow['biaya_jabatan'],
                                    $aRow['total_pengurang'],$aRow['netto'],$aRow['netto_setahun'],$aRow['ptkp_gaji'],
                                    $aRow['penghasilan_kena_pajak'],$aRow['pph21_gaji_setahun'],
                                    $aRow['pph21_gaji_sebulan'],$aRow['tambahan_non_npwp'],$aRow['metode_pph21'],
                                    $aRow['pph21_nett'],$aRow['lastupdate'],$aRow['user_id']);
                            $output['aaData'][] = $row;
                            $this->dataExcel = $row;
                        
                        
                    }
                    echo json_encode( $output );
    }

    public function delete($periode,$periode_penggajian,$segmen,$nik){
        $this->periodepph21->delete_by_id($periode,$periode_penggajian,$segmen,$nik);
        echo json_encode(array("status" => TRUE));
        redirect('cperiodepph21');
    }
    public function detail($nik){
        $data['query'] = $this->periodepph21->select_by_nik($nik);
        $this->header();
        $this->load->view('periodepph21_detail',$data);

    }
    public function excel(){
        $dataResult = array();
         $aColumns = array('periode','nik','nama','status_perkawinan','npwp','departemen', 
            'jabatan','over_time','base','tunjangan_transport','tunjangan_kendaraan','tunjangan_makan','tunjangan_anak',
            'sales_incentive','bonus','adjustment_plus','jkm_perusahaan','jkk_perusahaan','jht_perusahaan',
            'jkn_perusahaan','jpn_perusahaan','penghasilan_kotor','jht_karyawan','jpn_karyawan','biaya_jabatan',
            'total_pengurang','netto','netto_setahun','ptkp_gaji','penghasilan_kena_pajak','pph21_gaji_setahun',
            'pph21_gaji_sebulan','tambahan_non_npwp','metode_pph21','pph21_nett','lastupdate','user_id');
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
                // $btn = '<a class="btn btn-smd btn-danger" href="cperiodepph21/delete/'.$aRow['nik'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>
                // <a target="_blank" class="btn btn-smd btn-info" href="claporanpph21/tahunan/'.$aRow['nik'].'"><i class="glyphicon glyphicon-list"></i></a>';
                // $btn = '<a class="text-dark" href="cperiodepph21/detail/'.$aRow['nik'].'"><i class="fa fa-info"></i></a>';
                for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                    $row[] = $aRow[ $aColumns[$i] ];
                }

                $row = array($aRow['periode'],$aRow['nik'],$aRow['nama'],$aRow['status_perkawinan'],
                    $aRow['npwp'],$aRow['departemen'], 
                    $aRow['jabatan'],$aRow['over_time'],$aRow['base'],$aRow['tunjangan_transport'],
                    $aRow['tunjangan_kendaraan'],$aRow['tunjangan_makan'],$aRow['tunjangan_anak'],
                    $aRow['sales_incentive'],$aRow['bonus'],$aRow['adjustment_plus'],
                    $aRow['jkm_perusahaan'],$aRow['jkk_perusahaan'],$aRow['jht_perusahaan'],
                    $aRow['jkn_perusahaan'],$aRow['jpn_perusahaan'],$aRow['penghasilan_kotor'],
                    $aRow['jht_karyawan'],$aRow['jpn_karyawan'],$aRow['biaya_jabatan'],
                    $aRow['total_pengurang'],$aRow['netto'],$aRow['netto_setahun'],$aRow['ptkp_gaji'],
                    $aRow['penghasilan_kena_pajak'],$aRow['pph21_gaji_setahun'],
                    $aRow['pph21_gaji_sebulan'],$aRow['tambahan_non_npwp'],$aRow['metode_pph21'],
                    $aRow['pph21_nett'],$aRow['lastupdate'],$aRow['user_id']);

                $dataResult = $row;
            
            
        }




        $header = array('Periode','NIK','Nama','Status Perkawinan','NPWP','Departemen', 
            'Jabatan','Over Time','Base','Tunjangan Transport','Tunjangan Kendaraan','Tunjangan Makan','Tunjangan Anak',
            'Sales Incentive','Bonus','Adjustment Plus','JKM Perusahaan','JKK Perusahaan','JHT Perusahaan',
            'JKN Perusahaan','JPN Perusahaan','Penghasilan Kotor','JHT Karyawan','JPN Karyawan','Biaya Jabatan',
            'Total Pengurang','Netto','Netto Setahun','PTKP Gaji','Penghasilan Kena Pajak','PPH21 Gaji Setahun',
            'PPH21 Gaji Sebulan','Tambahan Non NPWP','Metode PPH21','PPH21 Nett','Last Update','User ID');


        $data[] = $dataResult;
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"test".".csv\"");
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
        $data['active_menu'] = 24;
        $this->load->view('header',$data);
    }

}
