<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cjamsostek extends CI_Controller {
    private $sTable = 'master_jamsostek';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mjamsostek','jamsostek');
        //$this->load->library('csvimport');
	}

	public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('jamsostek_view', NULL);
	}

    function list_jamsostek() {
        $aColumns = array('id','nama_program','bagian_perusahaan','bagian_karyawan','maksimal_dasar','kode_gaji_potongan','lastupdate','user_id');
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
                            $btn = '<a class="text-dark mr-2" title="Edit" href="cjamsostek/edit/'.$aRow['id'].'"><i class="fa fa-pencil"></i></a> 
                                <a class="text-dark mr-2" title="Delete" href="cjamsostek/delete/'.$aRow['id'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>
                                <a class="text-dark" title="Detail" href="cjamsostek/detail/'.$aRow['id'].'"><i class="fa fa-info"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['id'], $aRow['nama_program'], $this->fungsi->FormatNum($aRow['bagian_perusahaan']), $this->fungsi->FormatNum($aRow['bagian_karyawan']), $aRow['maksimal_dasar'], $aRow['kode_gaji_potongan'], $this->fungsi->dateToIndo($aRow['lastupdate']),$aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($id){
        $this->db->where('id',$id);
        // $this->db->join('master_gaji', 'master_gaji.kode_gaji = master_jamsostek.kode_gaji_potongan','inner');
        $data['query'] = $this->db->get($this->sTable);
        $data['id'] = $id;
        $this->header();
        $this->load->view('jamsostek_edit', $data);
    }

	public function delete($id){
        $this->jamsostek->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
        redirect('cjamsostek');
	}

    function tambah(){
        $data = array();
        if($query = $this->jamsostek->get_records()){
            $data['records'] = $query;
        }
        $data['query4'] = $this->db->get('master_gaji');
        $this->header();
        $this->load->view('jamsostek_add', $data);
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'nama_program' => $this->input->post('nama_program'),
            'bagian_perusahaan' => str_replace(",", "", $this->input->post('bagian_perusahaan')),
            'bagian_karyawan' => str_replace(",", "", $this->input->post('bagian_karyawan')),
            'maksimal_dasar' => str_replace(",", "", $this->input->post('maksimal_dasar')),
            'kode_gaji_potongan' => $this->input->post('kode_gaji_potongan'),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        $id = $this->input->post('id');
        $this->db->where('id', $id);
        $this->db->update($this->sTable, $data);
        redirect('cjamsostek');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'id' => "",
            'nama_program' => $this->input->post('nama_program'),
            'bagian_perusahaan' => str_replace(",", "", $this->input->post('bagian_perusahaan')),
            'bagian_karyawan' => str_replace(",", "", $this->input->post('bagian_karyawan')),
            'maksimal_dasar' => str_replace(",", "", $this->input->post('maksimal_dasar')),
            'kode_gaji_potongan' => $this->input->post('kode_gaji_potongan'),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        $this->jamsostek->add_record($data);
        redirect('cjamsostek');
    }
    public function detail($id){
        $this->db->where('id',$id);
        $data['query'] = $this->db->get($this->sTable);
        $data['id'] = $id;
        $this->header();
        $this->load->view('jamsostek_detail', $data);
    }
    function header(){
        $data['active_accordion'] = "d";
        $data['aria_expanded'] = "d";
        $data['sub_menu_show'] = "d";
        $data['active_menu'] = 19;
        $this->load->view('header',$data);
    }


}
