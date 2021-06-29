<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cperiodepenggajiank extends CI_Controller {
    private $sTable = 'master_periode_penggajian_komponen';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mperiodepenggajiank','periodepenggajiank');
        $this->load->helper('permalink_helper');
	}

	public function index(){
        // $this->load->view('header');
        $this->header();
        $this->load->view('periodepenggajiank_view');
	}

    function list_periodepenggajiank() {
        $aColumns = array('periode_penggajian','segmen','kode_gaji','keterangan','lastupdate','user_id');
        $sIndexColumn = 'periode_penggajian';
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
            
                $sQuery = "SELECT master_periode_penggajian_komponen.periode_penggajian, master_periode_penggajian_komponen.segmen, master_periode_penggajian_komponen.kode_gaji, master_gaji.keterangan, master_periode_penggajian_komponen.lastupdate, master_periode_penggajian_komponen.user_id
FROM master_periode_penggajian_komponen INNER JOIN master_gaji ON master_periode_penggajian_komponen.kode_gaji = master_gaji.kode_gaji".$sWhere.$sOrder.$sLimit;
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
                            $btn = '<a class="text-dark mr-1" title="Delete" href="cperiodepenggajiank/delete/'.$aRow['periode_penggajian'].'/'.$aRow['segmen'].'/'.$aRow['kode_gaji'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>
                            <a class="text-dark" title="Edit" href="cperiodepenggajiank/update/'.$aRow['periode_penggajian'].'/'.$aRow['segmen'].'/'.$aRow['kode_gaji'].'"><i class="fa fa-pencil"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['periode_penggajian'], $aRow['segmen'], $aRow['kode_gaji'], $aRow['keterangan'], $this->fungsi->dateToIndo($aRow['lastupdate']), $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

	public function delete($periode_penggajian,$segmen,$kode_gaji){
        $this->periodepenggajiank->delete_by_id($periode_penggajian,$segmen,$kode_gaji);
        echo json_encode(array("status" => TRUE));
        redirect('cperiodepenggajiank');
	}
    function update($periode_penggajian,$segmen,$kode_gaji){
            $this->db->select("*");
            $this->db->from($this->sTable);
            $this->db->where("periode_penggajian",$periode_penggajian);
            $this->db->where("segmen",$segmen);
            $this->db->where("kode_gaji",$kode_gaji);
            $query = $this->db->get()->row();

            $data['query'] = $query;
            $this->db->select('kode_gaji');
            $this->db->from('master_gaji');
            $data['gaji'] = $this->db->get()->result();

            $this->header();
            $this->load->view("periodepenggajiank_edit",$data);

    }   
    function save_update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');

        $periode_penggajian_lama = $this->input->post('periode_penggajian_lama');
        $segmen_lama = $this->input->post('segmen_lama');
        $kode_gaji_lama = $this->input->post('kode_gaji_lama');

        $periode_penggajian = $this->input->post('periode_penggajian');
        $segmen = $this->input->post('segmen');
        $kode_gaji = $this->input->post('kode_gaji');

        $data = array(
            'periode_penggajian' => $this->input->post('periode_penggajian'),
            'segmen' => $this->input->post('segmen'),
            'kode_gaji' => $this->input->post('kode_gaji'),
            'lastupdate' => $last,
            'user_id' => $nm,
        );

        $this->db->where('periode_penggajian', $periode_penggajian_lama);
        $this->db->where('segmen', $segmen_lama);
        $this->db->where('kode_gaji', $kode_gaji_lama);
        $this->db->update($this->sTable, $data);
        redirect("cperiodepenggajiank");

    }
    function tambah(){
        $data['query'] = $this->db->get('master_gaji');
        $this->header();
        $this->load->view('periodepenggajiank_add', $data);
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $data = array(
            'periode_penggajian' => toAscii($this->input->post('periode_penggajian')),
            'segmen' => $this->input->post('segmen'),
            'kode_gaji' => $this->input->post('kode_gaji'),
            'lastupdate' => $last,
            'user_id' => $this->session->userdata('nama')
        );
        $this->periodepenggajiank->add_record($data);
        redirect('cperiodepenggajiank');
    }
    function detail(){
        
    }
    function header(){
        $this->load->helper('url');
        $data['active_accordion'] = "c";
        $data['aria_expanded'] = "c";
        $data['sub_menu_show'] = "c";
        $data['active_menu'] = 36;
        $this->load->view('header',$data);
    }
}
