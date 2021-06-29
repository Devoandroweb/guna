<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 1000); 
ini_set('memory_limit','2048M');
class Mabsen extends CI_Model {
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

    public function delete_by_id($no){
        $this->db->where('no', $no);
        $this->db->delete($this->table);
    }

    public function update_absen($status_aktual,$keterangan,$waktu,$kondisi,$shift,$no){
        $this->db->query("update master_mesin set status_aktual='$status_aktual',keterangan='$keterangan',waktu='$waktu',kondisi='$kondisi',shift='$shift' where no='$no'");
    }

    function insert_csv($f,$in,$tw,$tgl1,$tgl2,$periode) {
        if ($in['kondisi'] == "CIn" and (!empty($tw->row()->shift))) {
            $ie = $tw->row()->shift;
            $this->db->query("INSERT INTO master_mesin VALUES ('','$tgl1','$in[enroll]','$periode','Hadir','','$f',
            '$in[kondisi]','$ie','','','')");
            //return;
            $per = $this->db->query("SELECT periode FROM trans_periode WHERE selesai>='$tgl1' and mulai<='$tgl1'")->row();
            if (empty($per)) {
                $this->db->query("update master_mesin set periode='' where enroll='$in[enroll]' and tanggal='$tgl1'");
            } else {
                $pe = $per->periode;
                $this->db->query("update master_mesin set periode='$pe' where enroll='$in[enroll]' and tanggal='$tgl1'");
            }
        } elseif ($in['kondisi'] == "CIn" and empty($tw->row()->shift)) {
            $this->db->query("INSERT INTO master_mesin VALUES ('','$tgl1','$in[enroll]','$periode','Hadir','Terlambat','$f',
            '$in[kondisi]','','','','')
            ON DUPLICATE KEY UPDATE tanggal='$tgl1',enroll='$in[enroll]',status_aktual='Hadir',keterangan='Terlambat',kondisi='$in[kondisi]',
            shift='',kondisi_baru='',status='',operasi=''");
            //return;
            $per = $this->db->query("SELECT periode FROM trans_periode WHERE selesai>='$tgl1' and mulai<='$tgl1'")->row();
            if (empty($per)) {
                $this->db->query("update master_mesin set periode='' where enroll='$in[enroll]' and tanggal='$tgl1'");
            } else {
                $pe = $per->periode;
                $this->db->query("update master_mesin set periode='$pe' where enroll='$in[enroll]' and tanggal='$tgl1'");
            }
        } elseif ($in['kondisi'] == "COut") {
            //$ie = $tw->row()->shift;
            $this->db->query("INSERT INTO master_mesin VALUES ('','$tgl1','$in[enroll]','$periode','Hadir','','$f',
            '$in[kondisi]','','','','')");
            //return;
            $per = $this->db->query("SELECT periode FROM trans_periode WHERE selesai>='$tgl1' and mulai<='$tgl1'")->row();
            if (empty($per)) {
                $this->db->query("update master_mesin set periode='' where enroll='$in[enroll]' and tanggal='$tgl1'");
            } else {
                $pe = $per->periode;
                $this->db->query("update master_mesin set periode='$pe' where enroll='$in[enroll]' and tanggal='$tgl1'");
            }
        } 
    }
}
