<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cuser extends CI_Controller {
    private $sTable = 'trans_spl';
    public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "USER"){
            redirect(base_url("clogin"));
        }
        $this->load->model('muser','user');
    }

    public function index(){
        $this->load->helper('url');
        $this->load->view('header_user');
        $this->load->view('user_view');
    }

    function user_spl() {
        $aColumns = array('id_spl','nik','tanggal','periode','mulai','selesai','jumlah_jam','hari','index_jumlah',
            'keterangan','lastupdate','user_id');
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
                            $btn = '<a class="btn btn-smd btn-primary" href="cuser/edit/'.$aRow['id_spl'].'"><i class="glyphicon glyphicon-pencil"></i></a> 
                                <a class="btn btn-smd btn-danger" href="cuser/delete/'.$aRow['id_spl'].'" onclick="return confirm(\'Anda yakin?\')"><i class="glyphicon glyphicon-trash"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $row = array($btn, $aRow['id_spl'], $aRow['nik'], $aRow['tanggal'], $aRow['periode'], 
                                $aRow['mulai'], $aRow['selesai'], $aRow['jumlah_jam'], $aRow['hari'], $aRow['index_jumlah'], 
                                $aRow['keterangan'], $aRow['lastupdate'], $aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
    }

    function edit($id_spl){
        $this->db->where('id_spl',$id_spl);
        $data['query'] = $this->db->get('trans_spl');
        $data['id_spl'] = $id_spl;
        $this->load->view('header_user');
        $this->load->view('user_edit', $data);
    }

    public function delete($id_spl){
        $this->user->delete_by_id($id_spl);
        echo json_encode(array("status" => TRUE));
        redirect('cuser');
    }

    function update(){
        $nm = $this->session->userdata('nama');
        $last = date("Y-m-d h:i:s");
        $hari = $this->input->post('hari');
        $jumlah_jam = $this->input->post('jumlah_jam');
        if($hari == "Libur"){
            if ($jumlah_jam == 1){
                $index_jumlah = 7*1;
            } else if ($jumlah_jam == 2){
                $index_jumlah = 7*2;
            } else if($jumlah_jam == 3){
                $index_jumlah = (7*2)+3;
            } else if($jumlah_jam >= 4){
                $index_jumlah = (7*2)+3+4;
            }
        } else if($hari == "Kerja"){
            if ($jumlah_jam == 1){
                $index_jumlah = 1*1.5;
            } else if ($jumlah_jam == 2){
                $index_jumlah = 2+1.5;
            } else if ($jumlah_jam > 2){
                $index_jumlah = ($jumlah_jam-1)*2+(1.5);
            }
        }
        $data = array(
            'tanggal' => $this->input->post('tanggal'),
            'mulai' => $this->input->post('mulai'),
            'selesai' => $this->input->post('selesai'),
            'jumlah_jam' => $this->input->post('jumlah_jam'),
            'hari' => $this->input->post('hari'),
            'index_jumlah' => $index_jumlah,
            'keterangan' => $this->input->post('keterangan'),
            'lastupdate' => $last,
            'user_id' => $nm
        );
        $id_spl = $this->input->post('id_spl');
        $this->db->where('id_spl', $id_spl);
        $this->db->update($this->sTable, $data);
        redirect('cuser');
    }

    function tambah(){
        $data['kodeunik'] = $this->user->getkodeunik('trans_spl');
        $this->load->view('header_user');
        $this->load->view('user_add',$data);
    }

    function create(){
        $periode = $this->session->userdata('periode');
        $nm = $this->session->userdata('nama');
        $nik = $this->input->post('nik');
        $jumlah_jam = $this->input->post('jumlah_jam');
        $last = date("Y-m-d h:i:s");
        $hari = $this->input->post('hari');
        $jumlah_jam = $this->input->post('jumlah_jam');
        if($hari == "Libur"){
            if ($jumlah_jam == 1){
                $index_jumlah = 7*1;
            } else if ($jumlah_jam == 2){
                $index_jumlah = 7*2;
            } else if($jumlah_jam == 3){
                $index_jumlah = (7*2)+3;
            } else if($jumlah_jam >= 4){
                $index_jumlah = (7*2)+3+4;
            }
        } else if($hari == "Kerja"){
            if ($jumlah_jam == 1){
                $index_jumlah = 1*1.5;
            } else if ($jumlah_jam == 2){
                $index_jumlah = 2+1.5;
            } else if ($jumlah_jam > 2){
                $index_jumlah = ($jumlah_jam-1)*2+(1.5);
            } 
            //if(b.total_jam=1,(1*1.5),if(b.total_jam=2,(2*2),if(b.total_jam>2,((((b.total_jam-1)*2)+(1.5))),0)))
        }
            $data = array(
                'id_spl' => $this->input->post('id_spl'),
                'nik' => $this->input->post('nik'),
                'tanggal' => $this->input->post('tanggal'),
                'periode' => $periode,
                'mulai' => $this->input->post('mulai'),
                'selesai' => $this->input->post('selesai'),
                'jumlah_jam' => $this->input->post('jumlah_jam'),
                'hari' => $this->input->post('hari'),
                'index_jumlah' => $index_jumlah,
                'keterangan' => $this->input->post('keterangan'),
                'lastupdate' => $last,
                'user_id' => $nm
            );
        $this->user->add_record($data);
            $q3 = $this->db->query("SELECT vspl.nik, vspl.periode, Sum(vspl.jumlah_jam) AS total_jam, master_gaji_karyawan.nilai_gaji, master_gaji_karyawan.kode_gaji
            FROM vspl INNER JOIN master_gaji_karyawan ON vspl.nik = master_gaji_karyawan.nik
            GROUP BY vspl.nik, vspl.periode, master_gaji_karyawan.nilai_gaji, master_gaji_karyawan.kode_gaji
            HAVING vspl.nik='$nik' AND vspl.periode=$periode AND master_gaji_karyawan.kode_gaji=101")->row();
            if (empty($q3)){
                $total_jam = 0;
                $nilai_ot = 0;
            } else {
                $total_jam = $q3->total_jam;
                $gapok = $q3->nilai_gaji;
                if ($total_jam == 1){
                    $nilai_ot = (1*1.5*1/173)*$gapok;
                } elseif ($total_jam == 2){
                    $nilai_ot = (2*1.5*1/173)*$gapok;
                } elseif ($total_jam > 2){
                    $nil = (2*1.5*1/173)*$gapok;
                    $sisa = $total_jam - 2;
                    $nilai_sisa = ($sisa*2*1/173)*$gapok;
                    $nilai_ot = $nilai_sisa+$nil;
                }
            }
        redirect('cuser');
    }
}



