<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cbonus extends CI_Controller {
    private $sTable = 'vbonus';
    
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        //$this->load->model('mtpgajikaryawan','tpgajikaryawan');
	}

	public function index(){
        $data['query'] = $this->db->get($this->sTable);
        $this->load->helper('url');
        $this->header();
        $this->load->view('bonus_view', $data);
	}

    function list_bonus() {
        $aColumns = array('periode','nik','nama','bank','no_rekening','bonus_tahunan','metode_pph21','pph21_bonus','thp');
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
                $sWhere = " WHERE periode='$ot' and bonus_tahunan !='0'";
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
                            $btn = '<a target="_blank" title="Laporan Bonus : '.$aRow['nama'].'" class="btn btn-sm text-dark" href="claporanbonus/bonus/'.$aRow['nik'].'/'.$aRow['periode'].'"><i class="fa fa-list"></i></a>';
                            

                            $row = array($btn,$aRow['periode'],$aRow['nik'],$aRow['nama'],$aRow['bank'],
                            $aRow['no_rekening'],$this->fungsi->FormatNum($aRow['bonus_tahunan']), $aRow['metode_pph21'],$this->fungsi->FormatNum($aRow['pph21_bonus']),$this->fungsi->FormatNum($aRow['thp']));
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }
    public function excel(){
        $dataResult = array();
        $aColumns = array('periode','nik','nama','bank','no_rekening','bonus_tahunan','metode_pph21','pph21_bonus','thp');
        $sIndexColumn = 'periode';
        $input =& $_POST;
        

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
            
            $row = array($aRow['periode'],$aRow['nik'],$aRow['nama'],$aRow['bank'],
            $aRow['no_rekening'],$aRow['bonus_tahunan'], $aRow['metode_pph21'],$aRow['pph21_bonus'],$aRow['thp']);
            $dataResult = $row;
        }

        $header = array('Periode','NIK','Nama','Bank','No Rekening','Bonus Tahunan','Metode PPH21','PPH21 Bonus','THP');

        $data[] = $dataResult;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"TRANSAKSI BONUS -".date("Ymdhis").".csv\"");
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
        $data['active_menu'] = 25;
        $this->load->view('header',$data);
    }
    
}
