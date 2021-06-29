<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cppht extends CI_Controller {
    private $sTable = 'master_pph21_tarif';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mppht','ppht');
	}

	public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('ppht_view', NULL);
	}

    function list_ppht() {
        $aColumns = array('kode_tarif','batas_bawah','batas_atas','tarif','lastupdate','user_id');
        $sIndexColumn = 'kode_tarif';
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
                            $btn = '<a class="text-dark mr-2" title="Edit" href="cppht/edit/'.$aRow['kode_tarif'].'"><i class="fa fa-pencil"></i></a> 
                                <a class="text-dark" title="Delete" href="cppht/delete/'.$aRow['kode_tarif'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['kode_tarif'], $this->fungsi->FormatNum($aRow['batas_atas']), $this->fungsi->FormatNum($aRow['batas_bawah']), $this->fungsi->FormatNum($aRow['tarif']), $this->fungsi->dateToIndo($aRow['lastupdate']),$aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($kode_tarif){
        $this->db->where('kode_tarif',$kode_tarif);
        $data['query'] = $this->db->get($this->sTable);
        $data['kode_tarif'] = $kode_tarif;
        $this->header();
        $this->load->view('ppht_edit', $data);
    }

	public function delete($kode_tarif){
        $this->ppht->delete_by_id($kode_tarif);
        echo json_encode(array("status" => TRUE));
        redirect('cppht');
	}

    function tambah(){
        $this->header();
        $this->load->view('ppht_add');
    }


    function update(){
        $last = date("Y-m-d h:i:s");
        $data = array(
                'batas_atas' => str_replace(",", "", $this->input->post('batas_atas')),
                'batas_bawah' => str_replace(",", "", $this->input->post('batas_bawah')),
                'tarif' => str_replace(",", "", $this->input->post('tarif')),
                'lastupdate' => $last,
                'user_id' => $this->session->userdata('nama')
            );
            $kode_tarif = $this->input->post('kode_tarif');
            $this->db->where('kode_tarif', $kode_tarif);
            $this->db->update($this->sTable, $data);
            redirect('cppht');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $data = array(
            'batas_atas' => str_replace(",", "", $this->input->post('batas_atas')),
            'batas_bawah' => str_replace(",", "", $this->input->post('batas_bawah')),
            'tarif' => str_replace(",", "", $this->input->post('tarif')),
            'lastupdate' => $last,
            'user_id' => $this->session->userdata('nama')
        );
        $this->ppht->add_record($data);
        redirect('cppht');
    }
     function header(){
        $data['active_accordion'] = "c";
        $data['aria_expanded'] = "c";
        $data['sub_menu_show'] = "c";
        $data['active_menu'] = 16;
        $this->load->view('header',$data);
    }


}
