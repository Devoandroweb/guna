<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cspl extends CI_Controller {
    private $sTable = 'trans_spl';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mspl','spl');
        $this->load->library('csvimport');
    }

    public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('spl_view');
    }

    function list_spl() {
        $aColumns = array('id_spl','nik','tanggal','periode','mulai','selesai','jumlah_jam','hari',
            'index_jumlah','keterangan','lastupdate','user_id');
        $sIndexColumn = 'id_spl';
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
            if (isset($input['iSortCol_0'])) {
                $iSortingCols = intval($input['iSortingCols']);
                for ($i=0 ; $i<$iSortingCols; $i++) {
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
                $ot = $this->session->userdata('periode');
                $o = substr($ot, 0, -2);
                $t = substr($ot, 4, 2);
                $per =  $o.'-'.$t ;
                $sWhere = " WHERE periode='$ot'";
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
                            $btn = '<a class="text-dark mr-1" title="Edit" href="cspl/edit/'.$aRow['id_spl'].'/'.$aRow['nik'].'"><i class="fa fa-pencil"></i></a> 
                                <a class="text-dark mr-2" title="Delete" href="cspl/delete/'.$aRow['id_spl'].'/'.$aRow['nik'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a> <a class="text-dark" title="Detail" href="cspl/detail/'.$aRow['id_spl'].'/'.$aRow['nik'].'"><i class="fa fa-info"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['id_spl'], $aRow['nik'], $this->fungsi->dateToIndo($aRow['tanggal']), $aRow['periode'], 
                                $aRow['mulai'], $aRow['selesai'], $aRow['jumlah_jam'], $aRow['hari'], $aRow['index_jumlah'],
                                $aRow['keterangan'], $aRow['lastupdate'], $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($id_spl,$nik){
        $this->db->where('id_spl',$id_spl);
        $this->db->where('nik',$nik);
        $data['query'] = $this->db->get('trans_spl');
        $data['id_spl'] = $id_spl;
        $data['nik'] = $nik;
        $this->header();
        $this->load->view('spl_edit', $data);
    }
    
    public function ajax_edit($id){
        $data = $this->spl->get_by_id($id);
        echo json_encode($data);
    }

    public function delete($id){
        $this->spl->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
        redirect('cspl');
    }

    function tambah(){
        $data['kodeunik'] = $this->spl->getkodeunik('trans_spl');
        $this->header();
        $this->load->view('spl_add',$data);
    }

    function update(){
        $nm = $this->session->userdata('nama');
        $last = date("Y-m-d h:i:s");
        $hari = $this->input->post('hari');
        $jumlah_jam = $this->input->post('jumlah_jam');
        if($hari == "Kerja"){
            $ot = $this->db->query("select * from master_overtime where jam='$jumlah_jam'")->row();
            $otj = $ot->index_hari_kerja;
        } else if($hari == "Libur"){
            $ot = $this->db->query("select * from master_overtime where jam='$jumlah_jam'")->row();
            $otj = $ot->index_hari_libur;
        }
        $data = array(
            'tanggal' => $this->input->post('tanggal'),
            'mulai' => $this->input->post('mulai'),
            'selesai' => $this->input->post('selesai'),
            'jumlah_jam' => $this->input->post('jumlah_jam'),
            'hari' => $this->input->post('hari'),
            'index_jumlah' => $otj,
            'keterangan' => $this->input->post('keterangan'),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        $id_spl = $this->input->post('id_spl');
        $this->db->where('id_spl', $id_spl);
        $this->db->update($this->sTable, $data);
        redirect('cspl');
    }

    function create(){
        $periode = $this->session->userdata('periode');
        $nm = $this->session->userdata('nama');
        $nik = $this->input->post('nik');
        $jumlah_jam = $this->input->post('jumlah_jam');
        $last = date("Y-m-d h:i:s");
        $hari = $this->input->post('hari');
        $jumlah_jam = $this->input->post('jumlah_jam');
        
        if($hari == "Kerja"){
            $ot = $this->db->query("select * from master_overtime where jam='$jumlah_jam'")->row();
            $otj = $ot->index_hari_kerja;
        } else if($hari == "Libur"){
            $ot = $this->db->query("select * from master_overtime where jam='$jumlah_jam'")->row();
            $otj = $ot->index_hari_libur;
        }
            $data = array(
                'nik' => $this->input->post('nik'),
                'tanggal' => $this->input->post('tanggal'),
                'periode' => $periode,
                'mulai' => $this->input->post('mulai'),
                'selesai' => $this->input->post('selesai'),
                'jumlah_jam' => $this->input->post('jumlah_jam'),
                'hari' => $this->input->post('hari'),
                'index_jumlah' => $otj,
                'keterangan' => $this->input->post('keterangan'),
                'lastupdate' => $last,
                'user_id' => $nm
            );
        $this->spl->add_record($data); 
        redirect('cspl');
    }
    function detail($id_spl,$nik){
        $this->db->where('id_spl',$id_spl);
        $this->db->where('nik',$nik);
        $data['query'] = $this->db->get('trans_spl');
        $data['id_spl'] = $id_spl;
        $data['nik'] = $nik;
        $this->header();
        $this->load->view('spl_detail', $data);
    }
    public function excel(){
        $dataResult = array();

        $aColumns = array('id_spl','nik','tanggal','periode','mulai','selesai','jumlah_jam','hari',
            'index_jumlah','keterangan','lastupdate','user_id');
        $sIndexColumn = 'id_spl';
        $input =& $_POST;
        
        $iColumnCount = count($aColumns);
        $aQueryColumns = array();
        foreach ($aColumns as $col) {
            if ($col != ' ') {
                $aQueryColumns[] = $col;
            }
        }
        
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
        FROM `".$this->sTable."`";
        $rResult = $this->db->query( $sQuery );
     

        foreach ($rResult->result_array() as $aRow) {
            $row = array();
            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                $row[] = $aRow[ $aColumns[$i] ];
            }
            $row = array($aRow['id_spl'], $aRow['nik'], $aRow['tanggal'], $aRow['periode'], 
                $aRow['mulai'], $aRow['selesai'], $aRow['jumlah_jam'], $aRow['hari'], $aRow['index_jumlah'],
                $aRow['keterangan'], $aRow['lastupdate'], $aRow['user_id']);
            $dataResult= $row;
        }

        $header = array('id_spl','nik','tanggal','periode','mulai','selesai','jumlah_jam','hari',
            'index_jumlah','keterangan','lastupdate','user_id');

        $data[] = $dataResult;


        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"OVERTIME-".date("Ymdhis").".csv\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        

        $handle = fopen('php://output', 'w');

        fputcsv($handle, $header); 

        foreach ($data as $data_array) {
            fputcsv($handle, $data_array);
        }
            fclose($handle);
        exit;
    }
    function header(){
        $data['active_accordion'] = "d-1";
        $data['aria_expanded'] = "d-1";
        $data['sub_menu_show'] = "d-1";
        $data['active_menu'] = 22;
        $this->load->view('header',$data);
    }
}
