<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctsalary extends CI_Controller {
    private $sTable = 'vtotal_salary';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mtsalary','tsalary');
        $this->load->helper('permalink_helper');
    }

    public function index(){
        $this->header();
        $this->load->view('tsalary_view');
    }

    function list_tsalary() {
        $aColumns = array('periode','periode_penggajian','segmen','nik','nama','bank','no_rekening','thp');
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
                        
                            $row = array();
                            $btn = '<a class="btn btn-sm text-danger" title="Delete" href="ctsalary/delete/'.$aRow['periode'].'/'.$aRow['periode_penggajian'].'/'.$aRow['segmen'].'/'.$aRow['nik'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($aRow['periode'], $aRow['periode_penggajian'], $aRow['segmen'], 
                                $aRow['nik'], $aRow['nama'], $aRow['bank'], $aRow['no_rekening'], $this->fungsi->FormatNum($aRow['thp']));
                            $output['aaData'][] = $row;
                        
                    }
                    echo json_encode( $output );
    }

    public function delete($periode,$periode_penggajian,$segmen,$nik){
        $this->tpph21->delete_by_id($periode,$periode_penggajian,$segmen,$nik);
        echo json_encode(array("status" => TRUE));
        redirect('ctpph21');
    }
    public function excel(){
        $dataResult = array();
        

        $aColumns = array('periode','periode_penggajian','segmen','nik','nama','bank','no_rekening','thp');
        $sIndexColumn = 'periode';
        $input =& $_POST;
        $sQuery = "SELECT COUNT(`".$sIndexColumn."`) AS row_count FROM `".$this->sTable."`";
        $rResultTotal = $this->db->query( $sQuery );
        $aResultTotal = $rResultTotal->row();
        $iTotal = $aResultTotal->row_count;
        $sLimit = "";

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
                
                    $row = array();
                    
                    for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                        $row[] = $aRow[ $aColumns[$i] ];
                    }
                    $row = array($aRow['periode'], $aRow['periode_penggajian'], $aRow['segmen'], 
                        $aRow['nik'], $aRow['nama'], $aRow['bank'], $aRow['no_rekening'], $aRow['thp']);
                    $dataResult = $row;
                
            }


        $header = array('Periode','Periode Penggajian','Segmen','NIK','Nama','Bank','No Rekening','THP');

        $data[] = $dataResult;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"TSALARY-".date("Ymdhis").".csv\"");
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
        $data['active_menu'] = 28;
        $this->load->view('header',$data);
    }
}
