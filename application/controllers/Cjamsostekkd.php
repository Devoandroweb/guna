<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cjamsostekkd extends CI_Controller {
    private $sTable = 'master_jamsostek_komponen_dasar';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mjamsostekkd','jamsostekkd');
	}

	public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('jamsostekkd_view', NULL);
	}

    function list_jamsostekkd() {
        $aColumns = array('kode_gaji','lastupdate','user_id');
        $sIndexColumn = 'kode_gaji';
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
                            $btn = '<a class="btn btn-sm text-dark" title="Delete" href="cjamsostekkd/delete/'.$aRow['kode_gaji'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['kode_gaji'], $this->fungsi->dateToIndo($aRow['lastupdate']),$aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

	public function delete($kode_gaji){
        $this->jamsostekkd->delete_by_id($kode_gaji);
        echo json_encode(array("status" => TRUE));
        redirect('cjamsostekkd');
	}

    function tambah(){
        $data = array();
        if($query = $this->jamsostekkd->get_records()){
            $data['records'] = $query;
        }
        $data['query4'] = $this->db->get('master_gaji');
        $this->header();
        $this->load->view('jamsostekkd_add', $data);
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $data = array(
            'kode_gaji' => $this->input->post('kode_gaji'),
            'lastupdate' => $last,
            'user_id' => $this->session->userdata('nama')
        );
        $this->jamsostekkd->add_record($data);
        redirect('cjamsostekkd');
    }
    function header(){
        $data['active_accordion'] = "c";
        $data['aria_expanded'] = "c";
        $data['sub_menu_show'] = "c";
        $data['active_menu'] = 12;
        $this->load->view('header',$data);
    }
    


}
