<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
class Mlpph21tahun extends CI_Model {
    var $table = 'trans_periode_pph21_tahunan';

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

    function insert_csv($in) {
            $this->db->query("INSERT INTO trans_periode_pph21_tahunan VALUES ('',
                '$in[periode]',
                '$in[nik]',
                '$in[nama]',
                '$in[pph21_metode]',
                '$in[status]',
                '$in[masa_pajak]',
                '$in[base_salary]',
                '$in[car_allow]',
                '$in[insentives_meals_others]',
                '$in[gaji]',
                '$in[tunjangan_pph]',
                '$in[premi_asuransi]',
                '$in[thr]',
                '$in[jumlah_bruto]',
                '$in[biaya_jabatan]',
                '$in[iuran_pensiun]',
                '$in[jumlah_pengurang]',
                '$in[penghasilan_netto]',
                '$in[ptkp]',
                '$in[pkp]',
                '$in[pph21]',
                '$in[lastupdate]',
                '$in[user_id]')

            ON DUPLICATE KEY UPDATE nama='$in[nama]',pph21_metode='$in[pph21_metode]',status='$in[status]',
            masa_pajak='$in[masa_pajak]',base_salary='$in[base_salary]',car_allow='$in[car_allow]',
            insentives_meals_others='$in[insentives_meals_others]',gaji='$in[gaji]',tunjangan_pph='$in[tunjangan_pph]',
            premi_asuransi='$in[premi_asuransi]',thr='$in[thr]',jumlah_bruto='$in[jumlah_bruto]',
            biaya_jabatan='$in[biaya_jabatan]',iuran_pensiun='$in[iuran_pensiun]',jumlah_pengurang='$in[jumlah_pengurang]',
            penghasilan_netto='$in[penghasilan_netto]',ptkp='$in[ptkp]',pkp='$in[pkp]',pph21='$in[pph21]',
            lastupdate='$in[lastupdate]',user_id='$in[user_id]'");
            //return;
            
        
    }
}
