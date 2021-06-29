<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cthr extends CI_Controller {
    private $sTable = 'vthr';
    
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
        $this->load->view('thr_view', $data);
	}

    function list_thr() {
        $aColumns = array('periode','nik','nama','bank','no_rekening','thr','metode_pph21','pph21_thr','thp');
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
                            $btn = '<a target="_blank" title="Laporan THR : '.$aRow['nama'].'" class="text-dark mr-1" href="claporanthr/thr/'.$aRow['nik'].'/'.$aRow['periode'].'"><i class="fa fa-navicon"></i></a>
                                <a target="_blank" title="Laporan All THR : '.$aRow['periode'].'" class="text-dark" href="claporanallthr/thr/'.$aRow['periode'].'"><i class="fa fa-tasks"></i></a>';
                            

                            $row = array($btn,$aRow['periode'],$aRow['nik'],$aRow['nama'],$aRow['bank'],
                            $aRow['no_rekening'],$this->fungsi->FormatNum($aRow['thr']), $aRow['metode_pph21'],$this->fungsi->FormatNum($aRow['pph21_thr']),$this->fungsi->FormatNum($aRow['thp']));
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }
    public function excel(){
        $dataResult = array();
        $aColumns = array('periode','nik','nama','bank','no_rekening','thr','metode_pph21','pph21_thr','thp');
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
                $aRow['no_rekening'],$aRow['thr'], $aRow['metode_pph21'],$aRow['pph21_thr'],$aRow['thp']);
                $dataResult = $row;
        }
        $header = array('Periode','NIK','Nama','Bank','No Rekening','THR','Metode PPH21','PPH21 THR','THP');

        $data[] = $dataResult;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"TRANSAKSI THR -".date("Ymdhis").".csv\"");
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
        $data['active_menu'] = 27;
        $this->load->view('header',$data);
    }
}
