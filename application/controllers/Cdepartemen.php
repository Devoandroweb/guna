<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cdepartemen extends CI_Controller {
    private $sTable = 'content_departemen';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mdepartemen','departemen');
        $this->load->helper('permalink_helper');
	}

	public function index(){
        $this->header();
        $this->load->view('departemen_view', NULL);
	}

    function list_departemen() {
        $aColumns = array('departemen','lastupdate','user_id','id_departemen');
        $sIndexColumn = 'departemen';
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
                            $btn = '<a class="text-dark mr-1" href="cdepartemen/delete/'.$aRow['id_departemen'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>
                            <a class="text-dark" href="cdepartemen/update/'.$aRow['departemen'].'""><i class="fa fa-pencil"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['departemen'], $this->fungsi->dateToIndo($aRow['lastupdate']), $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }
    
	public function delete($id_departemen){
        $this->id_departemen->delete_by_id_departemen($id_departemen);
        echo json_encode(array("status" => TRUE));
        redirect('cdepartemen');
	}

    function tambah(){
        $this->header();
        $this->load->view('departemen_add');
    }
    function update($departemen){
        $this->db->select("*");
        $this->db->from($this->sTable);
        $this->db->where("departemen",$departemen);
        $query = $this->db->get();

        $data['query'] = $query;

        $this->load->view("header");
        $this->load->view("departemen_edit",$data);
    }
    function save_update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $id_departemen = $this->input->post('id_departemen');

        $data = array(
            'departemen' => toAscii($this->input->post('departemen')),
            'lastupdate' => $last,
            'user_id' => $nm,
        );

        $this->db->where('id_departemen', $id_departemen);
        $this->db->update($this->sTable, $data);
        redirect("cdepartemen");
    }
    function create(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'departemen' => toAscii($this->input->post('departemen')),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        $this->departemen->add_record($data);
        redirect('cdepartemen');
    }

    function header(){
        $data['active_accordion'] = "b";
        $data['aria_expanded'] = "b";
        $data['sub_menu_show'] = "b";
        $data['active_menu'] = 3;
        $this->load->view('header',$data);
    }
    
}
