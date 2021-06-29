<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Covertime extends CI_Controller {
    private $sTable = 'master_overtime';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('movertime','overtime');
        $this->load->helper('permalink_helper');
	}

	public function index(){
        $this->header();
        $this->load->view('overtime_view');
	}

    function list_overtime() {
        $aColumns = array('jam','index_hari_kerja','index_hari_libur','lastupdate','user_id');
        $sIndexColumn = 'jam';
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
                            $btn = '<a class="text-dark mr-1" title="Edit" href="covertime/edit/'.$aRow['jam'].'"><i class="fa fa-pencil"></i></a> 
                            <a class="text-dark" title="Hapus" href="covertime/delete/'.$aRow['jam'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['jam'], $aRow['index_hari_kerja'], $aRow['index_hari_libur'],
                                $this->fungsi->dateToIndo($aRow['lastupdate']), $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($jam){
        $this->db->where('jam',$jam);
        $data['query'] = $this->db->get($this->sTable);
        $data['jam'] = $jam;
        $this->header();
        $this->load->view('overtime_edit', $data);
    }

    public function delete($jam){
        $this->overtime->delete_by_id($jam);
        echo json_encode(array("status" => TRUE));
        redirect('covertime');
    }

    function tambah(){
        $this->header();
        $this->load->view('overtime_add');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'jam' => $this->input->post('jam'),
            'index_hari_kerja' => $this->input->post('index_hari_kerja'),
            'index_hari_libur' => $this->input->post('index_hari_libur'),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        $this->overtime->add_record($data);
        redirect('covertime');
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'index_hari_kerja' => $this->input->post('index_hari_kerja'),
            'index_hari_libur' => $this->input->post('index_hari_libur'),
            'lastupdate' => $last,
            'user_id' => $this->session->userdata('nama')
        );
        $jam = $this->input->post('jam');
        $this->db->where('jam', $jam);
        $this->db->update($this->sTable, $data);
        redirect('covertime');
    }
    function header(){
        $data['active_accordion'] = "d";
        $data['aria_expanded'] = "d";
        $data['sub_menu_show'] = "d";
        $data['active_menu'] = 20;
        $this->load->view('header',$data);
    }
    
}
