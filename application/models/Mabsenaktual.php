<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mabsenaktual extends CI_Model {
    var $table = 'master_mesin';

    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    function get_records(){
        $query = $this->db->get($this->table);
        return $query->result();
    }

    function add_record($data) {
        $this->db->insert($this->table, $data);
        return;
    }

    public function delete_by_id($nik,$tanggal){
        $this->db->where('nik', $nik);
        $this->db->where('tanggal', $tanggal);
        $this->db->delete($this->table);
    }

    public function update_absen_aktual($keterangan,$shift,$status_aktual,$kondisi,$waktu,$nik,$tanggal){
        $this->db->query("update master_absen_aktual set status_aktual='$status_aktual',keterangan='$keterangan',waktu='$waktu',kondisi='$kondisi',shift='$shift' where nik='$nik' and tanggal='$tanggal'");
    }


    function insert_csv($f,$in,$tw,$tgl1,$tgl2,$periode) {
        if (!empty($tw->row())) {
            $ie = $tw->row()->shift;
            
            $last = date("Y-m-d h:i:s");
            $nm = $this->session->userdata('nama');
            //INSERT INTO users_partners (uid,pid) VALUES (1,1) ON DUPLICATE KEY UPDATE uid=uid
            $this->db->query("INSERT INTO master_mesin VALUES ('','$tgl1','$in[nik]','$periode','Hadir','','$f',
                '$in[kondisi]','$ie','$in[kondisi_baru]','$in[status]','$in[operasi]') 
                ON DUPLICATE KEY UPDATE nik='$in[nik]' and tanggal='$tgl1'");
            return;
        } else {
            $ie = "";
            if ($ie == ""){
                $ie="";
            } else {
                $ie = $tw->row()->shift;
            }
            $last = date("Y-m-d h:i:s");
            $nm = $this->session->userdata('nama');
            $this->db->query("INSERT INTO master_mesin VALUES ('','$tgl1','$in[nik]','$periode','Hadir','','$f',
                '$in[kondisi]','$ie','$in[kondisi_baru]','$in[status]','$in[operasi]') 
                ON DUPLICATE KEY UPDATE nik='$in[nik]' and tanggal='$tgl1'");
            return;
        }
    }
}
