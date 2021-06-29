<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cstatusperkawinan extends CI_Controller {
    private $sTable = 'content_status_perkawinan';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mstatusperkawinan','statusperkawinan');
        $this->load->helper('permalink_helper');
	}

	public function index(){
        $this->header();
        $this->load->view('statusperkawinan_view', NULL);
	}

    function list_statusperkawinan() {
        $aColumns = array('status_perkawinan','lastupdate','user_id');
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
                            $btn = '<a class="text-dark mr-1" title="Delete" href="cstatusperkawinan/delete/'.$aRow['status_perkawinan'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>
                            <a class="text-dark" title="Delete" href="cstatusperkawinan/update/'.$aRow['status_perkawinan'].'"><i class="fa fa-pencil"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['status_perkawinan'], $this->fungsi->dateToIndo($aRow['lastupdate']), $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

	public function delete($status_perkawinan){
        $this->statusperkawinan->delete_by_id($status_perkawinan);
        echo json_encode(array("status" => TRUE));
        redirect('cstatusperkawinan');
	}

    function tambah(){
        $this->header();
        $this->load->view('statusperkawinan_add');
    }
    function update($status_perkawinan){
        $this->db->select("*");
        $this->db->from($this->sTable);
        $this->db->where("status_perkawinan",$status_perkawinan);
        $query = $this->db->get();

        $data['query'] = $query;

        $this->header();
        $this->load->view("statusperkawinan_edit",$data);

    }
    function save_update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $id_status_perkawinan = $this->input->post('id_status_perkawinan');

        $data = array(
            'status_perkawinan' => $this->input->post('status_perkawinan'),
            'lastupdate' => $last,
            'user_id' => $nm,
        );

        $this->db->where('id_status_perkawinan', $id_status_perkawinan);
        $this->db->update($this->sTable, $data);
        redirect("cstatusperkawinan");

    }
    function create(){
        $last = date("Y-m-d h:i:s");
        $data = array(
            'status_perkawinan' => toAscii($this->input->post('statusperkawinan')),
            'lastupdate' => $last,
            'user_id' => $this->session->userdata('nama')
        );
        $this->statusperkawinan->add_record($data);
        redirect('cstatusperkawinan');
    }
    function header(){
        $data['active_accordion'] = "b";
        $data['aria_expanded'] = "b";
        $data['sub_menu_show'] = "b";
        $data['active_menu'] = 9;
        $this->load->view('header',$data);
    }
}
