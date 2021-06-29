<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cgajikaryawanperiode extends CI_Controller {
    private $sTable = 'vlist_gaji_karyawans_periode';
    private $sTables = 'master_gaji_karyawan_periode';
    private $sTabless = 'master_gaji';
    
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mgajikaryawanperiode','gajikaryawanperiode');
	}

	public function index(){
        $data['query'] = $this->db->get($this->sTabless);
        $this->load->helper('url');
        $this->header();
        $this->load->view('gajikaryawanperiode_view', $data);
	}

    public function search(){
        $keyword = $this->uri->segment(3);
        $data = $this->db->from('master_karyawan')->like('nik',$keyword)->get();
        foreach($data->result() as $row)
        {
            $arr['query'] = $keyword;
            $arr['suggestions'][] = array(
                'value' =>$row->nik,
                'nama'   =>$row->nama,
            );
        }
        echo json_encode($arr);
    }

    function list_gajikaryawanperiode() {
        $ot = $this->session->userdata('periode');
        $sql = "CALL vlist_gaji_karyawans_periode ($ot)";
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
            // $output = ""; 
            // sip pak
            
            $this->db->reconnect();
            foreach ($query->result_array() as $aRow) {
                $p = $this->session->userdata('periode');
                $q = $this->db->query("SELECT * FROM trans_periode WHERE periode = '".$p."'")->row();
                if ($q->status == "OPEN"){
                    $row = array_values($aRow);
                    $btn = array('<a class="text-dark mr-1" title="Edit" href="cgajikaryawanperiode/edit/'.$aRow['nik'].'/'.$aRow['periode'].'"><i class="fa fa-pencil"></i></a> 
                        <a class="text-dark mr-2" title="Delete"  href="cgajikaryawanperiode/delete/'.$aRow['nik'].'/'.$aRow['periode'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>
                        <a class="text-dark " title="Detail"  href="cgajikaryawanperiode/detail/'.$aRow['nik'].'/'.$aRow['periode'].'"><i class="fa fa-info"></i></a>');
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

    function edit($nik,$periode){
        $periode = $this->session->userdata('periode');
        $dat['quer'] = $this->db->get('trans_periode');
        $this->db->where('nik',$nik);
        $this->db->where('periode',$periode);
        $data['query'] = $this->db->get($this->sTables);
        $data['querys'] = $this->db->get($this->sTabless);
        // $this->db->where('status','1');
        $this->updatePeriodeGaji($periode);

        $data['nik'] = $nik;
        $this->header();
        $this->load->view('gajikaryawanperiode_edit', $data+$dat);

    }

	public function delete($nik,$periode){
        $this->gajikaryawanperiode->delete_by_id($nik,$periode);
        $this->updatePeriodeGaji($periode);

        echo json_encode(array("status" => TRUE));
        redirect('cgajikaryawanperiode');
	}

    function tambah(){
        $data['querys'] = $this->db->get($this->sTabless);
        $data['pegawai'] = $this->db->get('master_karyawan')->result();

        $this->header();
        $this->load->view('gajikaryawanperiode_add', $data);
    }

    function update(){
        $jum = $this->input->post('jum');
        for ($i=1; $i<=$jum; $i++){
            $last = date("Y-m-d h:i:s");
            $data = array(
                'periode' => $this->input->post('periode'),
                'nilai_gaji' => str_replace(",", "", $this->input->post('nilai_gaji'.$i)),
                'lastupdate' => $last,
                'user_id' => $this->input->post('user_id')
            );
        $nik = $this->input->post('nik');
        $periode = $this->input->post('periode');
        $kode_gaji = $this->input->post('kode_gaji'.$i);
        $this->db->where('nik', $nik);
        $this->db->where('kode_gaji', $kode_gaji);
        $this->db->where('periode', $periode);
        $this->db->update($this->sTables, $data);
        }

        $nik = $this->input->post('nik');
        $ot = $this->session->userdata('periode');
        $tambah = $this->input->post('tambah');
        for ($t=1; $t<=$tambah; $t++){
            $last = date("Y-m-d h:i:s");
            $datas = array(
                'nik' => $nik,
                'kode_gaji' => $this->input->post('kode_gaji_baru'.$t),
                'periode' => $ot,
                'nilai_gaji' => $this->input->post('nilai_gaji_baru'.$t),
                'lastupdate' => $last,
                'user_id' => $this->input->post('user_id')
            );
            $this->gajikaryawanperiode->add_record($datas);
            //$this->db->update($this->sTables, $data);
        }
        $this->updatePeriodeGaji($periode);
        redirect('cgajikaryawanperiode');
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
                'periode' => $ot,
                'nilai_gaji' => str_replace(",", "", $this->input->post('nilai_gaji'.$i)),
                'lastupdate' => $last,
                'user_id' => $this->input->post('user_id')
            );
        $this->gajikaryawanperiode->add_record($data);
        }
        //$q2 = $this->db->query("SELECT nilai_gaji from master_gaji_karyawan where nik='$nik' and kode_gaji='401'")->row();
        //$q3 = $this->db->query("SELECT pinjaman from trans_loan where nik='$nik'")->row();
        //$sisa = $q3->pinjaman-$q2->nilai_gaji;
        //$this->db->query("update trans_loan set sisa_pinjaman='$sisa' where nik='$nik'");
        $this->updatePeriodeGaji($ot);
        redirect('cgajikaryawanperiode');
    }

    public function detail($nik,$periode){
        $periode = $this->session->userdata('periode');
        $dat['quer'] = $this->db->get('trans_periode');
        $this->db->where('nik',$nik);
        $this->db->where('periode',$periode);
        $data['query'] = $this->db->get($this->sTables);
        $data['querys'] = $this->db->get($this->sTabless);
        // $this->db->where('status','1');
        $data['nik'] = $nik;
        $this->header();
        $this->load->view('gajikaryawanperiode_detail', $data+$dat);
    }
    function header(){
        $data['active_accordion'] = "d-1";
        $data['aria_expanded'] = "d-1";
        $data['sub_menu_show'] = "d-1";
        $data['active_menu'] = 21;
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

            $data = array(
                'total' => $totalGaji,
            );

            $this->db->where('periode', $periode);
            $this->db->update('master_total_gaji', $data);
        }else{
            $data = array(
                    'periode' => $periode,
                    'total' => $totalGaji,
            );

            $this->db->insert('master_total_gaji', $data);
        }
    }
}
