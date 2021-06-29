<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cgaji extends CI_Controller {
    private $sTable = 'master_gaji';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mgaji','gaji');
	}

	public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('gaji_view');
	}

    function list_gaji() {
        $aColumns = array('kode_gaji','keterangan','jenis','periode_hitung','rumus','lastupdate','user_id');
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
                            $btn = '<a class="text-dark mr-1" title="Edit" href="cgaji/edit/'.$aRow['kode_gaji'].'"><i class="fa fa-pencil"></i></a> <a class="text-dark mr-1" title="Detail" href="cgaji/detail/'.$aRow['kode_gaji'].'"><i class="fa fa-info"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['kode_gaji'], $aRow['keterangan'], $aRow['jenis'], $aRow['periode_hitung'], 
                                $aRow['rumus'], $aRow['lastupdate'],$aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($kode_gaji){
        $this->db->where('kode_gaji',$kode_gaji);
        $data['query'] = $this->db->get($this->sTable);
        $data['kode_gaji'] = $kode_gaji;
        $this->header();
        $this->load->view('gaji_edit', $data);
    }

	public function delete($kode_gaji){
        $this->gaji->delete_by_id($kode_gaji);
        $this->gaji->delete($kode_gaji);
        echo json_encode(array("status" => TRUE));
        redirect('cgaji');
	}

    function tambah(){
        $data = array();
        if($query = $this->gaji->get_records()){
            $data['records'] = $query;
        }
        $data['query4'] = $this->db->get('content_jenis_gaji');
        $this->header();
        $this->load->view('gaji_add', $data);
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $data = array(
                'keterangan' => $this->input->post('keterangan'),
                'jenis' => $this->input->post('jenis'),
                'periode_hitung' => $this->input->post('periode_hitung'),
                'rumus' => $this->input->post('rumus'),
                'lastupdate' => $last,
                'user_id' => $this->session->userdata('nama')
            );
        $kode_gaji = $this->input->post('kode_gaji');
        $this->db->where('kode_gaji', $kode_gaji);
        $this->db->update($this->sTable, $data);
        redirect('cgaji');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $data = array(
            'kode_gaji' => $this->input->post('kode_gaji'),
            'keterangan' => $this->input->post('keterangan'),
            'jenis' => $this->input->post('jenis'),
            'periode_hitung' => $this->input->post('periode_hitung'),
            'rumus' => $this->input->post('rumus'),
            'lastupdate' => $last,
            'user_id' => $this->session->userdata('nama')
        );
        $data2 = array(
            'periode_penggajian' => 'BULANAN',
            'segmen' => '1',
            'kode_gaji' => $this->input->post('kode_gaji'),
            'lastupdate' => $last,
            'user_id' => $this->session->userdata('nama')
        );
        $this->gaji->add_record($data,$data2);
        redirect('cgaji');
    }
    function detail($kode_gaji){
        $this->db->where('kode_gaji',$kode_gaji);
        $data['query'] = $this->db->get($this->sTable);
        $data['kode_gaji'] = $kode_gaji;
        $this->header();
        $this->load->view('gaji_detail', $data);
    }
     function header(){
        $data['active_accordion'] = "d";
        $data['aria_expanded'] = "d";
        $data['sub_menu_show'] = "d";
        $data['active_menu'] = 17;
        $this->load->view('header',$data);
    }


}
