<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cgajikaryawan extends CI_Controller {
    private $sTable = 'vlist_gaji_karyawan';
    private $sTables = 'master_gaji_karyawan';
    private $sTabless = 'master_gaji';
    
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mgajikaryawan','gajikaryawan');
        $this->load->library('csvimport');
	}

	public function index(){
        $data['query'] = $this->db->get($this->sTabless);
        $this->load->helper('url');
        $this->header();
        $this->load->view('gajikaryawan_view', $data);
	}

    public function search(){
        $keyword = $this->uri->segment(3);
        $data = $this->db->from('master_karyawan')->like('nik',$keyword)->get();
        foreach($data->result() as $row)
        {
            $arr['query'] = $keyword;
            $arr['suggestions'][] = array(
                'value' =>$row->nik,
                'nama' =>$row->nama,
                'enroll' =>$row->enroll,
            );
        }
        echo json_encode($arr);
    }

    function list_gajikaryawan() {
        $ot = $this->session->userdata('periode');
        $sql = "CALL vlist_gaji_karyawan";
        $aColumns = array();
        $query = $this->db->query($sql, $aColumns);
        //$data['employees'] = $this->gajikaryawan->get_records();
        //$this->load->view('gajikaryawan_view', $data);
        //$this->gajikaryawan->get_records();

        //$aColumns = array('nik','nama','departemen','jabatan','GajiPokok','TunjanganJabatan','TunjanganKehadiran',
            //'TunjanganTransportdanMakan','TunjanganMakanOT','Bonus','THR','OverTime','PotonganTerlambat',
            //'PotonganPph21','PotonganJamsostek','PotonganBPJSKesehatan','CicilanPinjaman');
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
                $sWhere = " WHERE periode='$ot'";
            }

            $aQueryColumns = array();
            foreach ($aColumns as $col) {
                if ($col != ' ') {
                    $aQueryColumns[] = $col;
                }
            }
            //$output = ""; dimatikan aja
            $this->db->reconnect();
            foreach ($query->result_array() as $aRow) {
                $p = $this->session->userdata('periode');
                $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();
                if ($q->status == "OPEN"){
                    $row = array_values($aRow);
                    $btn = array('<a class="text-dark mr-1" title="Edit" href="cgajikaryawan/edit/'.$aRow['nik'].'"><i class="fa fa-pencil"></i></a> 
                        <a class="text-dark mr-2" title="Delete" href="cgajikaryawan/delete/'.$aRow['nik'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i> </a> <a class="text-dark" title="Detail" href="cgajikaryawan/detail/'.$aRow['nik'].'"> <i class="fa fa-info"></i></a>');
                    $data[] = array_merge($btn,$row);
                    $output['aaData'] = $data;
                } else if ($q->status == "CLOSE"){
                    $row = array_values($aRow);
                    $btn = array('<i class="fa fa-pencil"></i>
                        <i class="fa fa-trash-o"></i>');
                    $data[] = array_merge($btn,$row);   
                    $output['aaData'] = $data;
                }
            }
            echo json_encode($output);
    }

    function edit($nik){
        $periode = $this->session->userdata('periode');
        $dat['quer'] = $this->db->get('trans_periode');
        $this->db->where('nik',$nik);
        $data['query'] = $this->db->get($this->sTables);
        $data['querys'] = $this->db->get($this->sTabless);
        $this->db->where('status','1');

        $data['nik'] = $nik;
        $this->header();
        $this->load->view('gajikaryawan_edit', $data+$dat);
    }

    function importcsv(){
        $periode = $this->session->userdata('periode');
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '100000';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
            $this->load->view('gajikaryawan_upload', $data);
        } else {
            $file_data = $this->upload->data();
            $file_path =  './uploads/'.$file_data['file_name'];
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                $path_to_file = './uploads/'.$file_data['file_name'];
                unlink($path_to_file);
                foreach ($csv_array as $row) {
                    $in = array(
                        'nik' => $row['nik'],
                        'kode_gaji' => $row['kode_gaji'],
                        'nilai_gaji' => $row['nilai_gaji'],
                        'lastupdate' => '',
                        'user_id' => ''
                    );
                    $this->gajikaryawan->insert_csv($in);
                    }
                }
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'cgajikaryawan/upload');
            } 
    }

    function upload(){
        $this->header();
        $this->load->view('gajikaryawan_upload');
    }

	public function delete($nik){
        $this->gajikaryawan->delete_by_id($nik);
        echo json_encode(array("status" => TRUE));
        redirect('cgajikaryawan');
	}

    function tambah(){
        $data['querys'] = $this->db->get($this->sTabless);
        $this->header();
        $this->load->view('gajikaryawan_add', $data);
    }

    function update(){
        $jum = $this->input->post('jum');
        for ($i=1; $i<=$jum; $i++){
            $last = date("Y-m-d h:i:s");
            $data = array(
                'nilai_gaji' => str_replace(",", "", $this->input->post('nilai_gaji'.$i)),
                'lastupdate' => $last,
                'user_id' => $this->input->post('user_id')
            );
            $nik = $this->input->post('nik');
            $kode_gaji = $this->input->post('kode_gaji'.$i);
            $this->db->where('nik', $nik);
            $this->db->where('kode_gaji', $kode_gaji);
            $this->db->update($this->sTables, $data);
        }
        redirect('cgajikaryawan');
    }

    function create(){
        $ot = $this->session->userdata('periode');
        $tanggal_cicilan = date("Y-m-d");
        $jum = $this->input->post('jum');
        $nik = $this->input->post('nik');
        for ($i=1; $i<=$jum; $i++){
            $last = date("Y-m-d h:i:s");
            $data = array(
                'nik' => $this->input->post('nik'),
                'kode_gaji' => $this->input->post('kode_gaji'.$i),
                'nilai_gaji' => str_replace(",", "", $this->input->post('nilai_gaji'.$i)),
                'lastupdate' => $last,
                'user_id' => $this->input->post('user_id')
            );
        $this->gajikaryawan->add_record($data,$ot,$last);
        }
        $this->updatePeriodeGaji($ot);
        redirect('cgajikaryawan');

    }
    function detail($nik){
        $periode = $this->session->userdata('periode');
        $dat['quer'] = $this->db->get('trans_periode');
        $this->db->where('nik',$nik);
        $data['query'] = $this->db->get($this->sTables);
        $data['querys'] = $this->db->get($this->sTabless);
  
        $data['nik'] = $nik;
        $this->header();
        $this->load->view('gajikaryawan_detail', $data+$dat);
    }
    
    function header(){
        $data['active_accordion'] = "c";
        $data['aria_expanded'] = "c";
        $data['sub_menu_show'] = "c";
        $data['active_menu'] = 11;
        $this->load->view('header',$data);
    }

    function updatePeriodeGaji($periode){

        $query = "Select * from master_total_gaji where periode='".$periode."'";
        $result = $this->db->query($query);

        $totalGaji = 0;
        $qTotal = "Select * from master_gaji_karyawan_periode where periode='".$periode."'";
        $resultQTotal = $this->db->query($qTotal);

        foreach ($resultQTotal->result() as $key) {
           $totalGaji = $totalGaji + $key->nilai_gaji;
        }
        if($result->num_rows() > 0){

            $data1 = array(
                'total' => $totalGaji,
            );

            $this->db->where('periode', $periode);
            $this->db->update('master_total_gaji', $data1);
        }else{
            $data = array(
                    'periode' => $periode,
                    'total' => $totalGaji,
            );

            $this->db->insert('master_total_gaji', $data);
        }
    }
    
}
