<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cabsenaktual extends CI_Controller {
    private $sTable = 'master_mesin';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mabsenaktual','absenaktual');
        $this->load->library('csvimport');
        $this->load->helper('date');
    }

    public function index(){
        $this->load->helper('url');
        $this->load->view('header');
        $this->load->view('absenaktual_view');
    }

    function list_absenaktual() {
        $ot = $this->session->userdata('periode');
        $sql = "CALL rekap_absens ($ot)";
        $aColumns = array();
        $parameters = array();
        $query = $this->db->query($sql, $aColumns);
        $sIndexColumn = 'nik';
        $input =& $_POST;
            
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
            $output = "";
            $this->db->reconnect();
            foreach ($query->result_array() as $aRow) {
                $p = $this->session->userdata('periode');
                $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();
                    
                        for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                            $row[] = $aRow[ $aColumns[$i] ];
                        }
                    $row = array_values($aRow);
                    $btn = array('<a class="btn btn-smd btn-primary" href="cabsenaktual/edit/'.$aRow['nik'].'/'.$aRow['tanggal'].'"><i class="glyphicon glyphicon-pencil"></i></a> 
                        <a class="btn btn-smd btn-danger" href="cabsenaktual/delete/'.$aRow['nik'].'/'.$aRow['tanggal'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>
                    
                    ');
                    $data[] = array_merge($btn,$row);
                    $output['aaData'] = $data;
                $row = array();
                    
            }
            echo json_encode( $output );
    }

    function edit($nik,$tanggal){
        $this->db->where('nik',$nik);
        $this->db->where('tanggal',$tanggal);
        //$data['query'] = $this->db->get($this->sTable);
        $data['nik'] = $nik;
        $data['tanggal'] = $tanggal;
        $this->load->view('header');
        $this->load->view('absenaktual_edit', $data);
    }
    
    public function delete($nik,$tanggal){
        $this->absenaktual->delete_by_id($nik,$tanggal);
        echo json_encode(array("status" => TRUE));
        redirect('cabsenaktual');
    }

    function tambah(){
        $data['query'] = $this->db->get('content_status_aktual');
        $this->load->view('header');
        $this->load->view('absenaktual_add', $data);
    }

    function update(){
        $last = date("Y-m-d h:i:s");
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

        $nik = $this->input->post('nik');
        $tanggal = $this->input->post('tanggal');

        $this->db->where('nik', $nik);
        $this->db->where('tanggal', $tanggal);
        $this->db->update($this->sTable, $data);
        redirect('cabsenaktual');
    }

    function create(){
        $ot = $this->session->userdata('periode');
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'tanggal' => $this->input->post('tanggal'),
            'nik' => $this->input->post('nik'),
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
        $this->absenaktual->add_record($data);
        redirect('cabsenaktual');
    }

    function upload(){
        $this->load->view('header');
        $this->load->view('absenaktual_upload');
    }

    function importcsv(){
        $periode = $this->session->userdata('periode');
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '100000';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
            $this->load->view('absenaktual_upload', $data);
        } else {
            $file_data = $this->upload->data();
            $file_path =  './uploads/'.$file_data['file_name'];
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                $path_to_file = './uploads/'.$file_data['file_name'];
                unlink($path_to_file);
                foreach ($csv_array as $row) {
                    $tex = $row['kondisi'];
                    $rep = str_replace("/","", $tex);
                    $in = array(
                        'no' => "",
                        'tanggal' => "",
                        'nik' => 'PLI-'.$row['nik'],
                        'periode' => $periode,
                        'waktu' => $row['waktu'],
                        'kondisi' => $rep,
                        'kondisi_baru' => $row['kondisi_baru'],
                        'status' => $row['status'],
                        'operasi' => $row['operasi']
                    );

                    $m = DateTime::createFromFormat('d/m/Y H:i', $row['waktu']);
                    $f = $m->format('Y-m-d H:i:s');

                    $fe = date_create($row['waktu']);
                    $kl = $m->format('H:i:s');
                    $tgl1 = $m->format('Y-m-d');
                    //$tg = date_format($fe, 'Y-m-d H:i');
                    $tgl2 = date('Y-m-d', strtotime('-1 days', strtotime($tgl1)));

                    if (($kl >= date_format(date_create('00:01'), 'H:i')) and ($kl <= date_format(date_create('01:00'), 'H:i'))){
                        $tw = $this->db->query("select nama_shift as shift FROM master_karyawan INNER JOIN master_absen_grup_shift ON master_karyawan.grup = master_absen_grup_shift.grup
                        where master_karyawan.nik = 'PLI-".$row['nik']."' and masuk_valid_awal>=time('23:30') and masuk_valid_akhir<=time('00:30')");
                    } else {
                        $tw = $this->db->query("select nama_shift as shift FROM master_karyawan INNER JOIN master_absen_grup_shift ON master_karyawan.grup = master_absen_grup_shift.grup
                        where master_karyawan.nik = 'PLI-".$row['nik']."' and '".$kl."' >= master_absen_grup_shift.masuk_valid_awal
                        and '".$kl."' <= master_absen_grup_shift.masuk_valid_akhir");
                    }
                    $this->absenaktual->insert_csv($f,$in,$tw,$tgl1,$tgl2,$periode);
                    }
                }
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'cabsenaktual/upload');
        } 
    }
}
