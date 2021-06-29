<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Mabsenaktual_temp extends CI_Model {
    var $table = 'master_absen_aktual_temp';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function add_record($data) {
        foreach($data as $field){
            $data = array(
                'no_akun' => $field['no_akun'],
                'no' => $field['no'],
                'nama' => $field['nama'],
                'waktu' => date("Y-m-d H:i", strtotime($field['waktu'])),
                'kondisi' => $field['kondisi'],
                'kondisi_baru' => $field['kondisi_baru'],
                'status' => $field['status'],
                'operasi' => $field['operasi']
            );
            $this->db->insert($this->table, $data);
        }
        return;
    }

    public function delete_by_id($nik,$tanggal){
        $this->db->where('nik', $nik);
        $this->db->where('tanggal', $tanggal);
        $this->db->delete($this->table);
    }

    public function update_absen_aktual($shift,$status_aktual,$masuk,$pulang,$nik,$tanggal){
        $this->db->query("update master_absen_aktual set status_aktual='$status_aktual',masuk='$masuk',pulang='$pulang',shift='$shift' where nik='$nik' and tanggal='$tanggal'");
    }


    function insert_csv($in,$tw,$tgl1,$tgl2,$periode) {
        if (!empty($tw->row())) {
            $ie = $tw->row()->shift;
            $last = date("Y-m-d h:i:s");
            $nm = $this->session->userdata('nama');
            //INSERT INTO users_partners (uid,pid) VALUES (1,1) ON DUPLICATE KEY UPDATE uid=uid
            $this->db->query("INSERT INTO master_absen_aktual VALUES ('$in[nik]','$tgl1','$periode','Hadir','','$in[masuk]','$in[pulang]','$ie','$last','$nm') ON DUPLICATE KEY UPDATE nik='$in[nik]'");
            return;
        } else {
            $this->db->query("INSERT INTO master_absen_aktual VALUES ('$in[nik]','$tgl1','$periode','Hadir','','$in[masuk]','$in[pulang]','','','') ON DUPLICATE KEY UPDATE nik='$in[nik]'");
            return;
        }
    }
}
