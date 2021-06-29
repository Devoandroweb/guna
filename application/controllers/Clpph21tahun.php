<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clpph21tahun extends CI_Controller {
    private $sTable = 'trans_periode_pph21_tahunan';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('Mlpph21tahun','lpph21tahun');
        $this->load->library('csvimport');
        $this->load->helper('date');
    }

    public function index(){
        $this->load->helper('url');
        $this->load->view('header');
        $this->load->view('lpph21tahun_view');
    }

    function hitungtahunpph21() {
        $sql = "CALL hitung_pph21_tahunan";
        $XPeriode = $this->session->userdata('periode');
        $this->db->query("$sql ('$XPeriode')");
        redirect('clpph21tahun_hasil');
    }

    function list_lpph21tahun() {
        $aColumns = array('id','periode','nik','nama','pph21_metode','status','masa_pajak','base_salary',
            'car_allow','insentives_meals_others','gaji','tunjangan_pph','premi_asuransi','thr','jumlah_bruto',
            'biaya_jabatan','iuran_pensiun','jumlah_pengurang','penghasilan_netto','ptkp',
            'pkp','pph21','lastupdate','user_id');
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
                            $btn = '<a target="_blank" class="btn btn-smd btn-info" href="claporanallpph21/pph21/'.$aRow['periode'].'"><i class="glyphicon glyphicon-list"></i></a>
                                <a class="btn btn-smd btn-danger" href="clpph21tahun/delete/'.$aRow['periode'].'/'.$aRow['nik'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }

                            $row = array($btn, $aRow['periode'],$aRow['nik'],$aRow['nama'],$aRow['pph21_metode'],$aRow['status'],
                                $aRow['masa_pajak'],$aRow['base_salary'], 
                                $aRow['car_allow'], $aRow['insentives_meals_others'],$aRow['gaji'],$aRow['tunjangan_pph'], 
                                $aRow['premi_asuransi'], $aRow['thr'],$aRow['jumlah_bruto'],$aRow['biaya_jabatan'], 
                                $aRow['iuran_pensiun'],$aRow['jumlah_pengurang'],$aRow['penghasilan_netto'],
                                $aRow['ptkp'],$aRow['pkp'],$aRow['pph21'],
                                $aRow['lastupdate'], $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($no){
        $this->db->where('no',$no);
        $data['query'] = $this->db->get($this->sTable);
        $data['no'] = $no;
        $this->load->view('header');
        $this->load->view('lpph21tahun_edit', $data);
    }
    
    public function delete($no){
        $this->absen->delete_by_id($no);
        echo json_encode(array("status" => TRUE));
        redirect('clpph21tahun');
    }

    function tambah(){
        $data['query'] = $this->db->get('content_status_aktual');
        $this->load->view('header');
        $this->load->view('lpph21tahun_add', $data);
    }

    function update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'status_aktual' => $this->input->post('status_aktual'),
            'keterangan' => $this->input->post('keterangan'),
            'waktu' => $this->input->post('waktu'),
            'kondisi' => $this->input->post('kondisi'),
            'shift' => $this->input->post('nama_shift'),
            'kondisi_baru' => "",
            'status' => "",
            'operasi' => ""
        );
        
        $no = $this->input->post('no');
        $this->db->where('no', $no);
        $this->db->update($this->sTable, $data);
        redirect('cabsen');
    }

    function create(){
        $ot = $this->session->userdata('periode');
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'tanggal' => $this->input->post('tanggal'),
            'enroll' => $this->input->post('enroll'),
            'periode' => $ot,
            'status_aktual' => $this->input->post('status_aktual'),
            'keterangan' => $this->input->post('keterangan'),
            'waktu' => $this->input->post('waktu'),
            'kondisi' => $this->input->post('kondisi'),
            'shift' => $this->input->post('shift'),
            'kondisi_baru' => "",
            'status' => "",
            'operasi' => ""
        );
        $this->lpph21tahun->add_record($data);
        redirect('clpph21tahun');
    }

    function upload(){
        $this->load->view('header');
        $this->load->view('lpph21tahun_upload');
    }

    function importcsv(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $periode = $this->session->userdata('periode');
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '100000';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
            $this->load->view('lpph21tahun_upload', $data);
        } else {
            $file_data = $this->upload->data();
            $file_path =  './uploads/'.$file_data['file_name'];
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                $path_to_file = './uploads/'.$file_data['file_name'];
                unlink($path_to_file);
                foreach ($csv_array as $row) {

                    $in = array(
                        'periode' => $periode,
                        'nik' => $row['nik'],
                        'nama' => $row['nama'],
                        'pph21_metode' => $row['pph21_metode'],
                        'status' => $row['status'],
                        'masa_pajak' => $row['masa_pajak'],
                        'base_salary' => $row['base_salary'],
                        'car_allow' => $row['car_allow'],
                        'insentives_meals_others' => $row['insentives_meals_others'],
                        'gaji' => $row['gaji'],
                        'tunjangan_pph' => $row['tunjangan_pph'],
                        'premi_asuransi' => $row['premi_asuransi'],
                        'thr' => $row['thr'],
                        'jumlah_bruto' => $row['jumlah_bruto'],
                        'biaya_jabatan' => $row['biaya_jabatan'],
                        'iuran_pensiun' => $row['iuran_pensiun'],
                        'jumlah_pengurang' => $row['jumlah_pengurang'],
                        'penghasilan_netto' => $row['penghasilan_netto'],
                        'ptkp' => $row['ptkp'],
                        'pkp' => $row['pkp'],
                        'pph21' => $row['pph21'],
                        'lastupdate' => $last,
                        'user_id' => $nm
                    );

                    $this->lpph21tahun->insert_csv($in);
                    }
                }
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'clpph21tahun/upload');
            } 
    }
}
