<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cperiodepph21t extends CI_Controller {
    private $sTable = 'trans_periode_pph21_tarif';
    public function __construct(){
        parent::__construct();
        $this->load->model('mperiodepph21t','periodepph21t');
        $this->load->helper('permalink_helper');
    }

    public function index(){
        $this->load->view('header');
        $this->load->view('periodepph21t_view', NULL);
    }

    function list_periodepph21t() {
        $aColumns = array('periode','periode_penggajian','segmen','jenis','nik','kode_tarif','nilai_gaji','tarif','nilai_pph21','lastupdate','user_id');
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
                            $btn = '<a class="btn btn-smd btn-danger" href="cperiodepph21t/delete/'.$aRow['periode'].'/'.$aRow['periode_penggajian'].'/'.$aRow['segmen'].'/'.$aRow['jenis'].'/'.$aRow['nik'].'/'.$aRow['kode_tarif'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['periode'], $aRow['periode_penggajian'], $aRow['segmen'], $aRow['jenis'], $aRow['nik'], $aRow['kode_tarif'], $aRow['nilai_gaji'], $aRow['tarif'], $aRow['nilai_pph21'], $aRow['lastupdate'], $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($id){
        $this->db->where('id',$id);
        $data['query'] = $this->db->get($this->sTable);
        $data['id'] = $id;
        $this->load->view('header');
        $this->load->view('agama_edit', $data);
    }
    
    public function delete($periode,$periode_penggajian,$segmen,$jenis,$nik,$kode_tarif){
        $this->periodepph21t->delete_by_id($periode,$periode_penggajian,$segmen,$jenis,$nik,$kode_tarif);
        echo json_encode(array("status" => TRUE));
        redirect('cperiodepph21t');
    }

    function tambah(){
        $this->load->view('header');
        $this->load->view('periodepph21t_add');
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $data = array(
                'agama' => $this->input->post('agama'),
                'lastupdate' => $last,
                'user_id' => $this->input->post('user_id')
            );
            $id = $this->input->post('id');
            $this->db->where('id', $id);
            $this->db->update($this->sTable, $data);
            redirect('cagama');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $data = array(
            'periode' => $this->input->post('periode'),
            'periode_penggajian' => $this->input->post('periode_penggajian'),
            'segmen' => $this->input->post('segmen'),
            'jenis' => $this->input->post('jenis'),
            'nik' => $this->input->post('nik'),
            'kode_tarif' => $this->input->post('kode_tarif'),
            'nilai_gaji' => $this->input->post('nilai_gaji'),
            'tarif' => $this->input->post('tarif'),
            'nilai_pph21' => $this->input->post('nilai_pph21'),
            'lastupdate' => $last,
            'user_id' => $this->input->post('user_id')
        );
        $this->periodepph21t->add_record($data);
        redirect('cperiodepph21t');
    }
    
}
