<?php
/**
 * 
 */

class Fungsi{
	
	public function dateToIndo($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia
   // variabel BulanIndo merupakan variabel array yang menyimpan nama-nama bulan
        $BulanIndo = array("Januari", "Februari", "Maret",
                            "April", "Mei", "Juni",
                            "Juli", "Agustus", "September",
                            "Oktober", "November", "Desember");
        
        $result = "";
        if($date == "0000-00-00" || $date == "0000-00-00 00:00" || $date == NULL || $date == "null"){
            $result = "-";
        }else{
            $tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
            $bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
            $tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
            $jam   = substr($date, 11,5);
            $result = $tgl . "-" . $bulan . "-". $tahun. " ". $jam;
        }
        
        return($result);
    }

	function FormatNum($angka,$dec=NULL){
        $number='';
        if(empty($dec)) $dec=0; 
        $number.=number_format($angka,$dec,',','.');
        return $number;
    }

    function active_menu($number_menu,$default_menu){
        $active = "";
        if ($number_menu == $default_menu) {
            $active = "active";
        }else{
            $active = "";
        }
        return $active;
    }
    function aria_expanded($number_menu,$default_menu){
        $value = "";
        if ($number_menu == $default_menu) {
            $value = "true";
        }else{
            $value = "false";
        }
        return $value;
    }
    function sub_menu_show($number_menu,$default_menu){
        $value = "";
        if ($number_menu == $default_menu) {
            $value = "show";
        }else{
            $value = "";
        }
        return $value;
    }
    function title($titleBar,$value){

        $stringTitleBar = array(
            'a' => 'Dashboard', 
            'b' => 'Referensi', 
            'c' => 'Master', 
            'd' => 'Konfigurasi', 
            'd-1' => 'Proses', 
            'e' => 'Riwayat', 
            'f' => 'Lain', 
            'g' => 'Absensi', 
            'h' => 'Administrator', 
        );
        $titleString = array(
            0 => "Dashboard" ,
            1 => "Agama" ,
            2 => "Bank" ,
            3 => "Departemen",
            4 => "Grup" ,
            5 => "Jabatan",
            6 => "Mata Uang",
            7 => "Status Absen",
            8 => "Status Karyawan" ,
            9 => "Status Perkawaninan",
            10 => "Karyawan",
            11 => "Komponen Gaji",
            12 => "Komponen Jamsostek",
            13 => "Komponen PPh 21",
            14 => "Shift",
            15 => "PTKP PPH 21",
            16 => "PPH 21 tarif",
            17 => "Struktur Gaji",
            18 => "Tunjangan Anak",
            19 => "Jamsostek",
            20 => "Overtime",
            21 => "Bayar Gaji" ,
            22 => "Overtime" ,
            23 => "Penggajian",
            24 => "PPH 21 Gaji",
            25 => "PPh 21 Bonus Tahunan",
            26 => "PPH 21 - THR",
            27 => "Jamsostek" ,
            28 => "Total salary" ,
            29 => "Rekap PPh 21" ,
            30 => "Data Finger Print",
            31 => "Rekap Absen" ,
            32 => "Izin/Cuti",
            33 => "Pengguna" ,
            34 => "Periode",
            35 => "Back up",
            36 => "Komponen Gaji Periode",
        );

        $result = $stringTitleBar[$titleBar]." | ".$titleString[$value];
        return $result;
    }

    function convertMoneyToJt($money){
        $result = 0;
        $result = $money / 1000000;
        $string = strval(round($result,1));

        $resultString = str_replace(".", ",", $string);
        // return number_format(strval($result),0,".",",")."JT";
        return $resultString."JT";
    }
    function setBulan($value){
        $arrNamaBulan = array(
            '01'=>'Januari', 
            '02'=>'Februari', 
            '03'=>'Maret', 
            '04'=>'April', 
            '05'=>'Mei', 
            '06'=>'Juni', 
            '07'=>'Juli', 
            '08'=>'Agustus', 
            '09'=>'September', 
            '10'=>'Oktober', 
            '11'=>'November', 
            '12'=>'Desember',
        );

        return $arrNamaBulan[$value];
    }

}