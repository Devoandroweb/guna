<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabsensi extends CI_Controller {
    private $sTable = 'content_insentive_kehadiran';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mabsensi','ketidakhadiran');
        $this->load->helper('permalink_helper');
	}

	public function index(){
        $this->load->view('header');
        $this->load->view('absensi_view');
	}

    function list_insentive_kehadiran() {
        $aColumns = array('id','nilai_insentive_kehadiran','lastupdate','user_id');
        $sIndexColumn = 'id';
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
                            $btn = '<a class="btn btn-smd btn-primary" href="cabsensi/edit/'.$aRow['id'].'"><i class="glyphicon glyphicon-pencil"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['id'], $aRow['nilai_insentive_kehadiran'], $aRow['lastupdate'], $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

	
    function edit($id){
        $this->db->where('id',$id);
        $data['query'] = $this->db->get($this->sTable);
        $this->load->view('header');
        $this->load->view('absensi_edit', $data);
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
                'nilai_insentive_kehadiran' => $this->input->post('nilai_insentive_kehadiran'),
                'lastupdate' => $last,
                'user_id' => $nm
            );
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->update($this->sTable, $data);
        redirect('cabsensi');
    }
}
