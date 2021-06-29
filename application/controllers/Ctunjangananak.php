<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ctunjangananak extends CI_Controller {
    private $sTable = 'master_tunjangan_anak';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mtunjangan_anak','tunjangan_anak');
        $this->load->helper('permalink_helper');
	}

	public function index(){
        $this->header();
        $this->load->view('tunjangananak_view');
	}

    function list_tunjangan_anak() {
        $aColumns = array('status_perkawinan','nilai','lastupdate','user_id');
        $sIndexColumn = 'status_perkawinan';
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
                $sWhere = "";
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
                            $btn = '<a class="btn btn-sm text-dark" title="Delete" href="ctunjangananak/delete/'.$aRow['status_perkawinan'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa  fa-trash-o"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['status_perkawinan'], $this->fungsi->FormatNum($aRow['nilai']), $this->fungsi->dateToIndo($aRow['lastupdate']), $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    public function delete($status_perkawinan){
        $this->tunjangan_anak->delete_by_id($status_perkawinan);
        echo json_encode(array("status" => TRUE));
        redirect('ctunjangananak');
    }

    function tambah(){
        $this->header();
        $this->load->view('tunjangananak_add');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'status_perkawinan' => $this->input->post('status_perkawinan'),
            'nilai' => str_replace(",", "", $this->input->post('nilai')),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        $this->tunjangan_anak->add_record($data);
        redirect('ctunjangananak');
    }
    function header(){
        $data['active_accordion'] = "d";
        $data['aria_expanded'] = "d";
        $data['sub_menu_show'] = "d";
        $data['active_menu'] = 18;
        $this->load->view('header',$data);
    }
}
