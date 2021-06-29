<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ckaryawan extends CI_Controller {
    private $sTable = 'master_karyawan';
    private $filename = "karyawan";
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->model('mkaryawan','karyawan');
        $this->load->library('csvimport');

	}

	public function index(){
        $this->header();
        $this->load->view('karyawan_view');
	}

    function upload(){
        $this->load->helper('url');
        $this->header();
        $this->load->view('karyawan_upload');
    }

    public function form(){
        $this->load->view('header');
        $data = array();
        
        if(isset($_POST['preview'])){
            $upload = $this->karyawan->upload_file($this->filename);
            if($upload['result'] == "success"){
                include APPPATH.'third_party/phpexcel/PHPExcel.php';
                $csvreader      = PHPExcel_IOFactory::createReader('CSV');
                $loadcsv        = $csvreader->load('csv/'.$this->filename.'.csv');
                $sheet          = $loadcsv->getActiveSheet()->getRowIterator();
                $data['sheet']  = $sheet; 
            } else {
                $this->load->view('header');
                $data['upload_error'] = $upload['error'];
            }
        }
        $this->load->view('karyawan_upload', $data);
    }

    public function import(){
        include APPPATH.'third_party/phpexcel/PHPExcel.php';
        $csvreader = PHPExcel_IOFactory::createReader('CSV');
        $loadcsv = $csvreader->load('csv/'.$this->filename.'.csv');
        $sheet = $loadcsv->getActiveSheet()->getRowIterator();
        $data = [];
        
        $numrow = 1;
        foreach($sheet as $row){
            if($numrow > 1){
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $get = array();
                foreach ($cellIterator as $cell) {
                    array_push($get, $cell->getValue());
                }

                $nik            = $get[0];
                $nama           = $get[1];
                $jenis_kelamin  = $get[2];
                $tanggal_lahir  = $get[3];
                $agama          = $get[4];
                $status_perkawinan  = $get[5];
                $alamat         = $get[6];
                $telepon        = $get[7];
                $email          = $get[8];
                $departemen     = $get[9];
                $grup           = $get[10];
                $jabatan        = $get[11];
                $tanggal_masuk  = $get[12];
                $akhir_kontrak  = $get[13];
                $status         = $get[14];
                $npwp           = $get[15];
                $bank           = $get[16];
                $no_rekening    = $get[17];
                $pemilik_rekening   = $get[18];
                $periode_penggajian = $get[19];
                $mata_uang          = $get[20];
                $pph21_metode       = $get[21];
                $bpjs_kesehatan     = $get[22];
                $enroll             = $get[23];
                $aktif              = $get[24];

                array_push($data, [
                    'nik'           =>$nik, 
                    'nama'          =>$nama, 
                    'jenis_kelamin' =>$jenis_kelamin, 
                    'tanggal_lahir' =>$tanggal_lahir,
                    'agama'         =>$agama, 
                    'status_perkawinan' =>$status_perkawinan, 
                    'alamat'        =>$alamat, 
                    'telepon'       =>$telepon,
                    'email'         =>$email, 
                    'departemen'    =>$departemen, 
                    'grup'          =>$grup, 
                    'jabatan'       =>$jabatan,
                    'tanggal_masuk' =>$tanggal_masuk, 
                    'akhir_kontrak' =>$akhir_kontrak, 
                    'status'        =>$status, 
                    'npwp'          =>$npwp,
                    'bank'          =>$bank, 
                    'no_rekening'   =>$no_rekening, 
                    'pemilik_rekening'  =>$pemilik_rekening, 
                    'periode_penggajian'=>$periode_penggajian,
                    'mata_uang'         =>$mata_uang, 
                    'pph21_metode'      =>$pph21_metode, 
                    'bpjs_kesehatan'    =>$bpjs_kesehatan, 
                    'enroll'            =>$enroll,
                    'aktif'             =>$aktif,
                ]);
            }
            $numrow++;
        }
        $this->karyawan->insert_multiple($data);
        redirect("ckaryawan");
    }

    function list_karyawan() {
        $aColumns = array('nik','nama','jenis_kelamin','tanggal_lahir','agama','status_perkawinan','alamat','telepon',
            'email','departemen','grup','jabatan','tanggal_masuk','akhir_kontrak','status','npwp','bank','no_rekening',
            'pemilik_rekening','periode_penggajian','mata_uang','pph21_metode','bpjs_kesehatan','enroll','aktif','lastupdate','user_id','foto');
        $sIndexColumn = 'nik';
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
                            $btn = '<a class="text-dark mr-1" href="ckaryawan/edit/'.$aRow['nik'].'"><i class="fa fa-pencil" title="Edit"></i></a> 
                                <a class="text-dark mr-1" title="Detail" href="ckaryawan/detail/'.$aRow['nik'].'"><i class="fa fa-info"></i></a> 
                                <a class="text-dark mr-1" title="Delete" href="ckaryawan/delete/'.$aRow['nik'].'" onclick="return confirm(\'Anda yakin?\')"><i class="fa fa-trash-o"></i></a>
                                <a class="text-dark data-item" onclick="uploadImage('.$aRow['nik'].')" title="Edit Image" href="ckaryawan#'.$aRow['nik'].'" data-toggle="modal"><i class="fa fa-file-image-o"></i></a>';
                            for ( $i=0 ; $i<$iColumnCount ; $i++ ) {
                                $row[] = $aRow[ $aColumns[$i] ];
                            }
                            $url = base_url("uploads/karyawan/".$aRow["foto"]);
                            $onClck = 'imgShow("'.$aRow["foto"].'")';                    
                            $foto = "<img class='img-sm rounded-circle show-img' onclick='".$onClck."' src='".$url."' alt=''>";
                            $row = array($btn, $aRow['nik'], $aRow['nama'], $aRow['jenis_kelamin'], $this->fungsi->dateToIndo($aRow['tanggal_lahir']),$foto 
                                ,$aRow['agama'], $aRow['status_perkawinan'], $aRow['alamat'], $aRow['telepon'], 
                                $aRow['email'], $aRow['departemen'], $aRow['grup'], $aRow['jabatan'], $this->fungsi->dateToIndo($aRow['tanggal_masuk']),
                                $this->fungsi->dateToIndo($aRow['akhir_kontrak']), $aRow['status'], $aRow['npwp'], $aRow['bank'], 
                                $aRow['no_rekening'], $aRow['pemilik_rekening'], $aRow['periode_penggajian'], 
                                $aRow['mata_uang'], $aRow['pph21_metode'], $aRow['bpjs_kesehatan'], $aRow['enroll'], 
                                $aRow['aktif'], $this->fungsi->dateToIndo($aRow['lastupdate']),$aRow['user_id']);
                            $output['aaData'][] = $row;
                    }
                    echo json_encode( $output );
                    
    }

    function edit($nik){
        $this->db->where('nik',$nik);
        $data['query'] = $this->db->get('master_karyawan');
        $data['nik'] = $nik;
        $this->load->helper('url');
        $this->header();
        $this->load->view('karyawan_edit', $data);
    }
    
	public function ajax_edit($nik){
		$data = $this->karyawan->get_by_id($nik);
		echo json_encode($data);
        redirect(site_url('ckaryawan'));
	}

	public function delete($nik){
        $this->delete_foto_by_nik($nik);
        $this->karyawan->delete_by_id($nik);
        echo json_encode(array("status" => TRUE));
        redirect(site_url('ckaryawan'));
	}

    function tambah(){
        $data = array();
        if($query = $this->karyawan->get_records()){
            $data['records'] = $query;
        }
        $data['query'] = $this->db->get('content_agama');
        $data['query2'] = $this->db->get('content_status_perkawinan');
        $data['query3'] = $this->db->get('content_departemen');
        $data['query4'] = $this->db->get('content_grup');
        $data['query5'] = $this->db->get('content_jabatan');
        $data['query6'] = $this->db->get('content_status_karyawan');
        $data['query7'] = $this->db->get('content_bank');
        $data['query8'] = $this->db->get('content_periode_penggajian');
        $data['query9'] = $this->db->get('content_mata_uang');
        $data['query10'] = $this->db->get('content_pph21_metode');


        $this->load->helper('url');
        $this->header();
        $this->load->view('karyawan_add', $data);
    }


    function update(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'agama' => $this->input->post('agama'),
            'status_perkawinan' => $this->input->post('status_perkawinan'),
            'alamat' => $this->input->post('alamat'),
            'telepon' => $this->input->post('telepon'),
            'email' => $this->input->post('email'),
            'departemen' => $this->input->post('departemen'),
            'grup' => $this->input->post('grup'),
            'jabatan' => $this->input->post('jabatan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'status' => $this->input->post('status'),
            'akhir_kontrak' => $this->input->post('akhir_kontrak'),
            'npwp' => $this->input->post('npwp'),
            'bank' => $this->input->post('bank'),
            'no_rekening' => $this->input->post('no_rekening'),
            'pemilik_rekening' => $this->input->post('pemilik_rekening'),
            'periode_penggajian' => $this->input->post('periode_penggajian'),
            'mata_uang' => $this->input->post('mata_uang'),
            'pph21_metode' => $this->input->post('pph21_metode'),
            'bpjs_kesehatan' => $this->input->post('bpjs_kesehatan'),
            'enroll' => $this->input->post('enroll'),
            'aktif' => $this->input->post('aktif'),
            'lastupdate' => $last,
            'user_id' => $nm,
        );
        $nik = $this->input->post('nik');
        $this->delete_foto_by_nik($nik);
        $this->db->where('nik', $nik);
        $this->db->update($this->sTable, $data);
        redirect('ckaryawan');
    }

    function create(){
        $last = date("Y-m-d h:i:s");
        $nm = $this->session->userdata('nama');
        $data = array(
            'nik' => $this->input->post('nik'),
            'nama' => $this->input->post('nama'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'tanggal_lahir' => $this->input->post('tanggal_lahir'),
            'agama' => $this->input->post('agama'),
            'status_perkawinan' => $this->input->post('status_perkawinan'),
            'alamat' => $this->input->post('alamat'),
            'telepon' => $this->input->post('telepon'),
            'email' => $this->input->post('email'),
            'departemen' => $this->input->post('departemen'),
            'grup' => $this->input->post('grup'),
            'jabatan' => $this->input->post('jabatan'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'status' => $this->input->post('status'),
            'akhir_kontrak' => $this->input->post('akhir_kontrak'),
            'npwp' => $this->input->post('npwp'),
            'bank' => $this->input->post('bank'),
            'no_rekening' => $this->input->post('no_rekening'),
            'pemilik_rekening' => $this->input->post('pemilik_rekening'),
            'periode_penggajian' => $this->input->post('periode_penggajian'),
            'mata_uang' => $this->input->post('mata_uang'),
            'pph21_metode' => $this->input->post('pph21_metode'),
            'bpjs_kesehatan' => $this->input->post('bpjs_kesehatan'),
            'enroll' => $this->input->post('enroll'),
            'aktif' => $this->input->post('aktif'),
            'lastupdate' => $last,
            'user_id' => $nm,
            'foto' => 'user-image-default.png'
        );
        $this->karyawan->add_record($data);
        redirect('ckaryawan');
    }
    function detail($nik){
        $this->db->where('nik',$nik);
        $data['query'] = $this->db->get('master_karyawan');
        $data['nik'] = $nik;
        
        $this->load->helper('url');
        $this->header();
        $this->load->view('karyawan_detail', $data);
    }


    function upload_image(){


        $nik = $this->input->post('nik');
        $this->load->helper(array('form', 'url'));
        // $file = $this->input->post('file_upload_img');

        $config['upload_path']          = './uploads/karyawan';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2048000;
        $config['file_name']             = "karyawan-".$nik."-".date("Ymdhis");
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ( ! $this->upload->do_upload('file_upload_img'))
        {
                $error = array('error' => $this->upload->display_errors());
                // $html = '<div class="alert alert-red" role="alert">
                //             Maaf Iamage yang anda masukkan salah minimal size 128kb.
                //         </div>';
                $html = "$.toast({
                              heading: 'Danger',
                              text: 'Maaf Image yang anda masukkan salah minimal size 128kb',
                              icon: 'danger',
                              loaderBg: '#ff5e5e',
                              position: 'top-center',
                              hideAfter: 5000,
                              showHideTransition: 'slide'
                            })";
                $this->session->set_flashdata('alert_img', $html);
                redirect('ckaryawan');

        }
        else
        {
                $data = array('upload_data' => $this->upload->data());
                // $html = '<div class="alert alert-success" role="alert">
                //             Selamat berhasil mengganti foto.
                //         </div>';
                $html = "$.toast({
                              heading: 'Success',
                              text: 'Selamat berhasil mengganti foto.',
                              icon: 'success',
                              loaderBg: '#6fd96f',
                              position: 'top-center',
                              hideAfter: 5000,
                              showHideTransition: 'slide'
                            })";
                $this->session->set_flashdata('alert_img', $html);
                $namaFile = $this->upload->data('file_name');

                $this->delete_foto_by_nik($nik);
                $data = array('foto' => $namaFile );
                $this->db->where('nik', $nik);
                $this->db->update($this->sTable, $data);

                redirect('ckaryawan');
        }
        

    }

    function header(){
        $this->load->helper('url');
        $data['active_accordion'] = "c";
        $data['aria_expanded'] = "c";
        $data['sub_menu_show'] = "c";
        $data['active_menu'] = 10;
        $this->load->view('header',$data);
    }

    function delete_foto_by_nik($nik){
        $this->db->select("foto");
        $this->db->from('master_karyawan');
        $this->db->where('nik',$nik);
        $this->db->limit(1);
        $query = $this->db->get();
        $foto = "";
        foreach ($query->result() as $key) {
            $foto = $key->foto;    
        }
        $path = FCPATH."uploads/karyawan/".$foto;
        unlink($path);
    }
}
