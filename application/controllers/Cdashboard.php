<?php

/**
 * 
 */
class Cdashboard extends CI_Controller{
	private $sTables = 'vabsen_sakit_cuti';
	public function __construct(){
        parent::__construct();
        if ($this->session->userdata('status') != "login" && $this->session->userdata('level') != "SYS"){
            redirect(base_url("clogin"));
        }
        $this->load->helper('permalink_helper');

        $this->updatePeriodeGaji($this->session->userdata['periode']);

	}
	public function index(){

        $data['totalKaryawan'] = $this->getAllKaryawan();
        $data['totalIzinBulanIni'] = $this->getAllIzinBulanIni();
        $data['totalAbsenBulanIni'] = $this->getAllAbsenBulanIini();
        $data['totalGajiBulanIni'] = $this->getAllGajiBulanIni();
        $data['izincuti'] = $this->getAllIzinBulanIniByLimit();
        $data['gajiPerBulan'] = $this->getTotalGajiAllMonth();

        // var_dump($this->getAllIzinBulanIniByLimit()->result_array());
        // die();

        $this->header();
        $this->load->view('dashboard', $data);
	}
	function header(){
        $data['active_accordion'] = "a";
        $data['aria_expanded'] = "";
        $data['sub_menu_show'] = "";
        $data['active_menu'] = 0;

        $this->load->view('header',$data);
    }

    function getAllKaryawan(){
        $this->db->select('*');
        $this->db->from('master_karyawan');
        $query = $this->db->get();

        return $query->num_rows();
    }

    function getAllIzinBulanIni(){

        $result = $this->db->query("SELECT * FROM `master_absen_sakit_cuti` WHERE `id_status_aktual` NOT IN (999)");
        return $result->num_rows();

    }

    function getAllIzinBulanIniByLimit(){
        // $this->db->select('*');
        // $this->db->from('master_absen_sakit_cuti');
        // $this->db->limit(5);
        // $query = $this->db->get();

        $aColumns = array('no','periode','nik','nama','dari_tanggal','sampai_tanggal','status','keterangan','lastupdate','user_id','id_status_aktual');
        $sIndexColumn = 'no';
        $input =& $_POST;
        $sLimit = "";

        $aQueryColumns = array();
        foreach ($aColumns as $col) {
            if ($col != ' ') {
                $aQueryColumns[] = $col;
            }
        }
        $sLimit = " LIMIT 5";
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", $aQueryColumns)."`
        FROM `".$this->sTables."` WHERE `id_status_aktual` NOT IN (999) ".$sLimit;
        $rResult = $this->db->query( $sQuery );

        return $rResult;
    }
    function getAllAbsenBulanIini(){
        $this->db->select('*');
        $this->db->from('master_mesin');
        $query = $this->db->get();
        return $query->num_rows();

    }

    function getAllGajiBulanIni(){
        $totalGaji = 0;
        $this->db->select('*');
        $this->db->from('master_gaji_karyawan');
        $query = $this->db->get();

        foreach ($query->result() as $key) {
            $totalGaji = $totalGaji + intval($key->nilai_gaji);
        }
        return $totalGaji;
    }

     function updatePeriodeGaji($periode){

        if ($periode != "") {
            
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
    function getTotalGajiAllMonth(){
        $qTotal = "Select * from master_total_gaji";
        $resultQTotal = $this->db->query($qTotal);
        $data = array(
            'jan' => 0,
            'feb' => 0,
            'mar' => 0,
            'apr' => 0,
            'mei' => 0,
            'apr' => 0,
            'jun' => 0,
            'jul' => 0,
            'agu' => 0,
            'sep' => 0,
            'okt' => 0,
            'nov' => 0,
            'des' => 90000000,
        );
        foreach ($resultQTotal->result() as $key) {

            $bulan = substr($key->periode, -2);
            $tahun = substr($key->periode, 0,4);

            switch ($bulan) {
                case '01':
                    $data['jan'] = $key->total;
                    break;
                case '02':
                    $data['feb'] = $key->total;
                    break;
                case '03':
                    $data['mar'] = $key->total;
                    break;
                case '04':
                    $data['apr'] = $key->total;
                    break;
                case '05':
                    $data['mei'] = $key->total;
                    break;
                case '06':
                    $data['jun'] = $key->total;
                    break;
                case '07':
                    $data['jul'] = $key->total;
                    break;
                case '08':
                    $data['agu'] = $key->total;
                    break;
                case '09':
                    $data['sep'] = $key->total;
                    break;
                case '10':
                    $data['okt'] = $key->total;
                    break;
                case '11':
                    $data['nov'] = $key->total;
                    break;
                case '12':
                    $data['des'] = $key->total;
                    break;
                default:
                    break;
            }
        }
        return $data;
    }

    function update_user_image(){
        $username = $this->session->userdata('u_name');
        $this->load->helper(array('form', 'url'));
        // $file = $this->input->post('file_upload_img');

        $config['upload_path']          = './uploads/users';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 2048000;
        $config['file_name']             = "user-".$username."-".date("Ymdhis");
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;

        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ( ! $this->upload->do_upload('user_img'))
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
                redirect('cdashboard');

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

                $this->delete_foto_by_username($username);
                $data = array('foto' => $namaFile );
                $this->db->where('u_name', $username);
                $this->db->update('tbl_user', $data);

                redirect('cdashboard');
        }
    }
    function delete_foto_by_username($username){
        $query = $this->getImgUser($username);
        $foto = "";
        foreach ($query->result() as $key) {
            $foto = $key->foto;    
        }
        $path = FCPATH."uploads/users/".$foto;
        unlink($path);
    }
    // function getImgProfile(){
        
    // }
    // function getImgUser($username){
    //     $this->db->select("foto");
    //     $this->db->from('tbl_user');
    //     $this->db->where('u_name',$username);
    //     $this->db->limit(1);
    //     $query = $this->db->get();
    //     return $query;
    // }

    function get_old_pass(){
        $pass = $this->input->post("password_lama");
        $username = $this->session->userdata("u_name");

        $this->db->select("*");
        $this->db->from("tbl_user");
        $this->db->where("text_pass",$pass);
        $this->db->where("u_name",$username);
        $result = $this->db->get()->num_rows();
        $data = false;
        if ($result == 1) {
            $data = true;
        }else{
            $data = false;
        }

        echo json_encode(array('kode' => $data));
    }

    function change_password(){
        $passbaru = $this->input->post("password_baru");
        $username = $this->session->userdata('u_name');

        $data = array(
            'pass_word' => md5($passbaru), 
            'text_pass' => $passbaru, 
        );

        $this->db->where('u_name', $username);
        $this->db->update("tbl_user", $data);

        $this->session->sess_destroy();
        redirect(base_url('clogin'));

    }
}