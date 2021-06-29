<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabsensakitcuti extends CI_Controller {
    private $sTable = 'master_absen_sakit_cuti';
    private $sTables = 'vabsen_sakit_cuti';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mabsen_sakit_cuti','absen_sakit_cuti');
        $this->load->helper('date');
    }

    public function index(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('absensakitcuti_view');
    }

    function list_absensakitcuti() {
        $aColumns = array('no','periode','nik','nama','dari_tanggal','sampai_tanggal','status','keterangan','lastupdate','user_id');
        $sIndexColumn = 'no';
        $input =& $_POST;
        $sQuery = "SELECT COUNT(`".$sIndexColumn."`) AS row_count FROM `".$this->sTables."`";
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
                $ot = $this->session->userdata('periode');
                $sWhere = "WHERE periode='$ot'";
            }

            $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }
            
                $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
                FROM `".$this->sTables."`".$sWhere.$sOrder.$sLimit;
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
                            $btn = '<a class="text-dark mr-1" href="cabsensakitcuti/edit/'.$aRow['no'].'"><i class="fa fa-pencil"></i></a> 
                                <a class="text-dark mr-2" href="cabsensakitcuti/delete/'.$aRow['no'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>
                                <a class="text-dark" href="cabsensakitcuti/detail/'.$aRow['nik'].'"><i class="fa fa-info"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['no'], $aRow['periode'], $aRow['nik'], $aRow['nama'], 
                                $this->fungsi->dateToIndo($aRow['dari_tanggal']), $this->fungsi->dateToIndo($aRow['sampai_tanggal']), $aRow['status'], $aRow['keterangan'], 
                                $aRow['lastupdate'], $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($no){
        $this->db->where('no',$no);
        $data['query'] = $this->db->get($this->sTable);
        $data['no'] = $no;
        $this->header();
        $this->load->view('absensakitcuti_edit', $data);
    }
    
    public function delete($no){
        $this->absen_sakit_cuti->delete_by_id($no);
        echo json_encode(array("status" => TRUE));
        redirect('cabsensakitcuti');
    }

    function tambah(){
        $data['query'] = $this->db->get('content_status_aktual');
        $this->header();
        $this->load->view('absensakitcuti_add', $data);
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'dari_tanggal' => $this->input->post('dari_tanggal'),
            'sampai_tanggal' => $this->input->post('sampai_tanggal'),
            'status' => $this->input->post('status'),
            'keterangan' => $this->input->post('keterangan'),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        
        $no = $this->input->post('no');
        $this->db->where('no', $no);
        $this->db->update($this->sTable, $data);
        redirect('cabsensakitcuti');
    }

    function create(){
        $ot = $this->session->userdata('periode');
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('level');
        $status = "";
        $id_status_aktual =  $this->input->post('id_status_aktual');

        $q = "SELECT * from content_status_aktual where id_status_aktual='".$id_status_aktual."' LIMIT 1";
        $resultStatusAktual = $this->db->query($q)->result();

        foreach ($resultStatusAktual as $key) {
            $status = $key->status_aktual;
        }

        $data = array(
            'nik' => $this->input->post('nik'),
            'periode' => $ot,
            'dari_tanggal' => $this->input->post('dari_tanggal'),
            'sampai_tanggal' => $this->input->post('sampai_tanggal'),
            'id_status_aktual' => $id_status_aktual,
            'status' => $status,
            'keterangan' => $this->input->post('keterangan'),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        $this->absen_sakit_cuti->add_record($data);
        redirect('cabsensakitcuti');
    }
    public function detail($nik){
        $aColumns = array('no','periode','nik','nama','dari_tanggal','sampai_tanggal','status','keterangan','lastupdate','user_id');
        $sIndexColumn = 'no';
        $input =& $_POST;
        $sWhere = "where nik='".$nik."'";

        $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }
        
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
        FROM `".$this->sTables."`".$sWhere;
        $rResult = $this->db->query( $sQuery );

        $data['query'] = $rResult;
        $this->header();
        $this->load->view('absen_sakitcuti_detail', $data);
        
    }
    public function excel(){
        $dataResult = array();

        $aColumns = array('no','periode','nik','nama','dari_tanggal','sampai_tanggal','status','keterangan','lastupdate','user_id');
        $sIndexColumn = 'no';
        $input =& $_POST;
        
        $iColumnCount = count($aColumns);
        $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
                    FROM `".$this->sTables."`";
        $rResult = $this->db->query( $sQuery );

        
        foreach ($rResult->result_array() as $aRow) {
            $row = array();
              
                for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                    $row[] = $aRow[ $aColumns[$i] ];
                }
                $row = array($aRow['no'], $aRow['periode'], $aRow['nik'], $aRow['nama'], 
                    $aRow['dari_tanggal'], $aRow['sampai_tanggal'], $aRow['status'], $aRow['keterangan'], 
                    $aRow['lastupdate'], $aRow['user_id']);
                $dataResult = $row;
        }

        $header = array('No','Periode','Nik','Nama','Dari Tanggal','Sampai Tanggal','Status','Keterangan','Last Update','User ID');

        $data[] = $dataResult;

        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"ABSEN SAKIT/CUTI -".date("Ymdhis").".csv\"");
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
        $data['active_accordion'] = "g";
        $data['aria_expanded'] = "g";
        $data['sub_menu_show'] = "g";
        $data['active_menu'] = 32;
        $this->load->view('header',$data);
    }
}
