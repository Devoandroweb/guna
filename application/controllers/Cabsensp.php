<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabsensp extends CI_Controller {
    private $sTable = 'master_absen_grup_shift_pola';
	public function __construct(){
        parent::__construct();
        $this->load->model('mabsensp','absensp');
        //$this->load->library('csvimport');
	}

	public function index(){
        $this->load->helper('url');
        $this->load->view('header');
        $this->load->view('absensp_view', NULL);
	}

    function list_absensp() {
        $aColumns = array('grup','urutan','shift','lastupdate','user_id');
        $sIndexColumn = 'grup';
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
                            $btn = '<a class="btn btn-smd btn-primary" href="cabsensp/edit/'.$aRow['grup'].'/'.$aRow['urutan'].'"><i class="glyphicon glyphicon-pencil"></i></a> 
                                <a class="btn btn-smd btn-danger" href="cabsensp/delete/'.$aRow['grup'].'/'.$aRow['urutan'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['grup'], $aRow['urutan'], $aRow['shift'], $aRow['lastupdate'],$aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($grup,$urutan){
        $this->db->where('grup',$grup);
        $this->db->where('urutan',$urutan);
        $data['query'] = $this->db->get($this->sTable);
        $data['grup'] = $grup;
        $data['urutan'] = $urutan;
        $this->load->view('header');
        $this->load->view('absensp_edit', $data);
    }

	public function delete($grup,$urutan){
        $this->absensp->delete_by_id($grup,$urutan);
        echo json_encode(array("status" => TRUE));
        redirect('cabsensp');
	}

    function tambah(){
        $this->load->view('header');
        $this->load->view('absensp_add');
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $data = array(
                'shift' => $this->input->post('shift'),
                'lastupdate' => $last,
                'user_id' => $this->input->post('user_id')
            );
            $grup = $this->input->post('grup');
            $urutan = $this->input->post('urutan');
            $this->db->where('grup', $grup);
            $this->db->where('urutan', $urutan);
            $this->db->update($this->sTable, $data);
            redirect('cabsensp');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $data = array(
            'grup' => $this->input->post('grup'),
            'urutan' => $this->input->post('urutan'),
            'shift' => $this->input->post('shift'),
            'lastupdate' => $last,
            'user_id' => $this->input->post('user_id')
        );
        $this->absensp->add_record($data);
        redirect('cabsensp');
    }


}
