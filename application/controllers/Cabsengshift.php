<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabsengshift extends CI_Controller {
    private $sTable = 'master_absen_grup_shift';
	public function __construct(){
        parent::__construct();
        $this->load->model('mabsengshift','absengshift');
        //$this->load->library('csvimport');
	}

	public function index(){
        $this->load->helper('url');
        $this->load->view('header');
        $this->load->view('absengshift_view', NULL);
	}

    function list_absengshift() {
        $aColumns = array('grup','shift','nama_shift','masuk','pulang','kode_hari_masuk','kode_hari_pulang','lastupdate','user_id');
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
                            $btn = '<a class="btn btn-smd btn-primary" href="cabsengshift/edit/'.$aRow['grup'].'/'.$aRow['shift'].'"><i class="glyphicon glyphicon-pencil"></i></a> 
                                <a class="btn btn-smd btn-danger" href="cabsengshift/delete/'.$aRow['grup'].'/'.$aRow['shift'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['grup'], $aRow['shift'], $aRow['nama_shift'], $aRow['masuk'], 
                                $aRow['pulang'], $aRow['kode_hari_masuk'], $aRow['kode_hari_pulang'], $aRow['lastupdate'],$aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($grup,$shift){
        $this->db->where('grup',$grup);
        $this->db->where('shift',$shift);
        $data['query'] = $this->db->get($this->sTable);
        $data['grup'] = $grup;
        $data['shift'] = $shift;
        $this->load->view('header');
        $this->load->view('absengshift_edit', $data);
    }

	public function delete($grup,$shift){
        $this->absengshift->delete_by_id($grup,$shift);
        echo json_encode(array("status" => TRUE));
        redirect('cabsengshift');
	}

    function tambah(){
        $data = array();
        if($query = $this->absengshift->get_records()){
            $data['records'] = $query;
        }
        $data['query4'] = $this->db->get('content_grup');
        $this->load->view('header');
        $this->load->view('absengshift_add', $data);
    }


    function update(){
        $last = date("Y-m-d h:i:s");
        $data = array(
                'nama_shift' => $this->input->post('nama_shift'),
                'masuk' => $this->input->post('masuk'),
                'pulang' => $this->input->post('pulang'),
                'kode_hari_masuk' => $this->input->post('kode_hari_masuk'),
                'kode_hari_pulang' => $this->input->post('kode_hari_pulang'),
                'lastupdate' => $last,
                'user_id' => $this->input->post('user_id')
            );
            $grup = $this->input->post('grup');
            $shift = $this->input->post('shift');
            $this->db->where('grup', $grup);
            $this->db->where('shift', $shift);
            $this->db->update($this->sTable, $data);
            redirect('cabsengshift');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $data = array(
            'grup' => $this->input->post('grup'),
            'shift' => $this->input->post('shift'),
            'nama_shift' => $this->input->post('nama_shift'),
            'masuk' => $this->input->post('masuk'),
            'pulang' => $this->input->post('pulang'),
            'kode_hari_masuk' => $this->input->post('kode_hari_masuk'),
            'kode_hari_pulang' => $this->input->post('kode_hari_pulang'),
            'lastupdate' => $last,
            'user_id' => $this->input->post('user_id')
        );
        $this->absengshift->add_record($data);
        redirect('cabsengshift');
    }


}
