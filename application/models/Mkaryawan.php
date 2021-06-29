<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 1000); 
ini_set('memory_limit','2048M');
class Mkaryawan extends CI_Model {
    var $table = 'master_karyawan';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    public function upload_file($filename){
        $this->load->library('upload');
        $config['upload_path'] = './csv/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '2048';
        $config['overwrite'] = true;
        $config['file_name'] = $filename;
    
        $this->upload->initialize($config);
        if($this->upload->do_upload('file')){
            $return = array('result' => 'success', 'file' => $this->upload->data(), 'error' => '');
            return $return;
        } else {
            $return = array('result' => 'failed', 'file' => '', 'error' => $this->upload->display_errors());
            return $return;
        }
    }

    public function insert_multiple($data){
        $this->db->insert_batch('master_karyawan', $data);
    }

    function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function add_record($data) {
        $this->db->insert($this->table, $data);
        return;
    }

    public function delete_by_id($nik){
        $this->db->where('nik', $nik);
        $this->db->delete($this->table);
    }

    function insert_csv($data) {
        $e = date('Y-m-d',strtotime($data['tanggal_lahir']));
        $c = date('Y-m-d',strtotime($data['akhir_kontrak']));
        $sql = $this->db->insert_string('master_karyawan', $data) . " ON DUPLICATE KEY UPDATE nama='$data[nama]', 
        jenis_kelamin='$data[jenis_kelamin]', tanggal_lahir='$e', agama='$data[agama]', 
        status_perkawinan='$data[status_perkawinan]', alamat='$data[alamat]', telepon='$data[telepon]', 
        email='$data[email]', departemen='$data[departemen]', grup='$data[grup]', jabatan='$data[jabatan]', 
        tanggal_masuk='$data[tanggal_masuk]', akhir_kontrak='$c', status='$data[status]', npwp='$data[npwp]', 
        bank='$data[bank]', no_rekening='$data[no_rekening]', pemilik_rekening='$data[pemilik_rekening]', 
        periode_penggajian='$data[periode_penggajian]', mata_uang='$data[mata_uang]', pph21_metode='$data[pph21_metode]', 
        bpjs_kesehatan='$data[bpjs_kesehatan]', enroll='$data[enroll]', lastupdate='$data[lastupdate]', user_id='$data[user_id]'";
        $this->db->query($sql);
    }
}
