<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctransct extends CI_Controller {
    private $sTable = 'master_mesin_clear';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mtransct','transct');
        $this->load->helper('date');
    }

    public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('transct_view');
    }

    function transact() {
        $sql = "CALL absen_clear";
        $XPeriode = $this->session->userdata('periode');
        $this->db->query("$sql ('$XPeriode')");
        redirect('ctransct');
    }

    function list_transact() {
        $aColumns = array('enroll','tanggal','periode','nik','nama','status_perkawinan','departemen','jabatan','status_kerja',
            'shift','status_aktual','keterangan','jam_masuk','jam_pulang');
        $sIndexColumn = 'enroll';
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
                            
                            // <a href="ctransct/delete" class="text-dark"><i class="fa fa-trash-o"></i> </a>;
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            //$aColumns = array('no','tanggal','nik','periode','status_aktual','keterangan','waktu',
                                //'kondisi','shift','kondisi_baru','status','operasi');
                            $btn = '
                            <a href="ctransct/detail/'.$aRow['nik'].'" class="text-dark mr-1"><i class="fa fa-info"></i> </a>';
                            $row = array($btn, $aRow['enroll'], $this->fungsi->dateToIndo($aRow['tanggal']), $aRow['periode'], $aRow['nik'],
                                $aRow['nama'], $aRow['status_perkawinan'], $aRow['departemen'], 
                                $aRow['jabatan'], $aRow['status_kerja'], $aRow['shift'], $aRow['status_aktual'], 
                                $aRow['keterangan'], $aRow['jam_masuk'], $aRow['jam_pulang']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }
    public function detail($nik){
        $aColumns = array('enroll','tanggal','periode','nik','nama','status_perkawinan','departemen','jabatan','status_kerja',
            'shift','status_aktual','keterangan','jam_masuk','jam_pulang');
        $sIndexColumn = 'enroll';
        $input =& $_POST;
        $sQuery = "SELECT COUNT(`".$sIndexColumn."`) AS row_count FROM `".$this->sTable."`";
        $rResultTotal = $this->db->query( $sQuery );
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->row_count;
        $sLimit = "";
        $sWhere = "where nik='".$nik."'";

        $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }

        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
        FROM `".$this->sTable."`".$sWhere;
        $rResult = $this->db->query( $sQuery );
       
        $data['query'] = $rResult;
        $this->header();
        $this->load->view('transct_detail',$data);
    }
    public function excel(){
        $dataResult = array();

         $aColumns = array('enroll','tanggal','periode','nik','nama','status_perkawinan','departemen','jabatan','status_kerja',
            'shift','status_aktual','keterangan','jam_masuk','jam_pulang');
        $sIndexColumn = 'enroll';
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
                
                $row = array($aRow['enroll'], $aRow['tanggal'], $aRow['periode'], $aRow['nik'],
                    $aRow['nama'], $aRow['status_perkawinan'], $aRow['departemen'], 
                    $aRow['jabatan'], $aRow['status_kerja'], $aRow['shift'], $aRow['status_aktual'], 
                    $aRow['keterangan'], $aRow['jam_masuk'], $aRow['jam_pulang']);
                $dataResult = $row;
        }


        $header = array('Enroll','Tanggal','Periode','NIK','Nama','Status Perkawinan','Departemen','Jabatan','Status Kerja',
            'Shift','Status Aktual','Keterangan','Jam Masuk','Jam Pulang');

        $data[] = $dataResult;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"ABSEN COMMIT -".date("Ymdhis").".csv\"");
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
        $data['active_menu'] = 31;
        $this->load->view('header',$data);
    }

}
