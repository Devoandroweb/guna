-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Bulan Mei 2021 pada 15.09
-- Versi server: 10.4.18-MariaDB
-- Versi PHP: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `guna`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `absen_clear` (IN `XPeriode` TEXT)  BEGIN
SET @XPeriode = XPeriode;
Delete from master_mesin_clear where periode=XPeriode;
  Insert into master_mesin_clear(enroll,tanggal,jam,periode,nik,nama,status_perkawinan,departemen,jabatan,status_kerja,status_aktual,jam_masuk) 
  select a.enroll,a.tanggal,a.jam,c.periode,b.nik,b.nama,b.status_perkawinan,b.departemen,b.jabatan,b.status as status_kerja,a.status_aktual,a.waktu
    FROM (master_karyawan b INNER JOIN master_mesin a ON b.enroll = a.enroll) INNER JOIN trans_periode c ON a.periode = c.periode
    where c.periode=@XPeriode and a.tanggal>=c.mulai and a.tanggal<=c.selesai and a.kondisi='CIn' group by a.tanggal,a.periode,a.enroll;

    UPDATE master_mesin_clear a join (select c.periode,a.enroll,c.tanggal,b.nama_shift as shift FROM (master_karyawan a JOIN master_absen_grup_shift b ON a.grup = b.grup) join master_mesin c on a.enroll=c.enroll
    where c.jam >= b.masuk_valid_awal
    and c.jam <= b.masuk_valid_akhir) b on a.periode=b.periode AND b.enroll=a.enroll and b.tanggal=a.tanggal
    set a.shift=b.shift;

    UPDATE master_mesin_clear a join (select c.periode,a.enroll,c.jam,b.nama_shift,b.masuk_valid_akhir,c.kondisi FROM (master_karyawan a JOIN master_absen_grup_shift b ON a.grup = b.grup) join master_mesin c on a.enroll=c.enroll) b on a.periode=b.periode AND b.enroll=a.enroll AND b.kondisi='CIn'
    set a.keterangan=if(a.jam >= b.masuk_valid_akhir,'Terlambat','');
    
    UPDATE master_mesin_clear a join (select waktu as jam_pulang,periode,enroll,tanggal,shift,kondisi from master_mesin) b 
    on a.periode=b.periode AND b.enroll=a.enroll and b.tanggal=a.tanggal + INTERVAL 1 DAY and a.shift = 'shift-3' and b.kondisi='COut'
    SET a.jam_pulang=b.jam_pulang;

    UPDATE master_mesin_clear a join (select waktu as jam_pulang,periode,enroll,tanggal,shift,kondisi from master_mesin) b 
    on a.periode=b.periode AND b.enroll=a.enroll and b.tanggal=a.tanggal and a.shift <> 'shift-3' and b.kondisi='COut'
    SET a.jam_pulang=b.jam_pulang;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `hitunggaji` (IN `XPeriode` TEXT, IN `XPeriode_Penggajian` TEXT, IN `XSegmen` TEXT)  BEGIN
  DELETE FROM trans_periode_gaji_karyawan where periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;
  BlockInsertSalary:BEGIN
    DECLARE DONE Boolean default FALSE;
    DECLARE SalaryId VARCHAR(25);

    DECLARE CurSalary cursor for select a.kode_gaji from master_periode_penggajian_komponen a
      join master_gaji b on a.kode_gaji=b.kode_gaji
      WHERE a.periode_penggajian=XPeriode_Penggajian and segmen=XSegmen and b.jenis<>'Lembur'
      and b.jenis<>'Potongan Jamsostek' and b.jenis<>'Potongan Pph21';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET DONE = TRUE;
    OPEN CurSalary;

    set DONE=FALSE;
    GetSalary : LOOP
      FETCH CurSalary into SalaryId;
      if DONE=TRUE then
        LEAVE GetSalary;
      end if;
      Select rumus,periode_hitung into @SQLCondition,@SalaryPeriodPayment from master_gaji where kode_gaji=SalaryId;
      Set @Period =XPeriode;
      Set @Segment=XSegmen;
      Set @Periode_Penggajian =XPeriode_Penggajian;
      Set @SalaryId=SalaryId;
      Set @DefaultUser='SYS';
      CASE
        WHEN @SalaryPeriodPayment= 'bulan' THEN
          SET @SQLStr = concat('insert into trans_periode_gaji_karyawan(periode,periode_penggajian,segmen,nik, nama,status_perkawinan,departemen,grup,jabatan,tanggal_masuk,status_kerja,kode_gaji,keterangan_gaji,jenis,nilai,lastupdate,user_id)           select distinct master_gaji_karyawan_periode.periode, master_periode_penggajian_komponen.periode_penggajian,master_periode_penggajian_komponen.segmen, master_karyawan.nik, master_karyawan.nama, master_karyawan.status_perkawinan, master_karyawan.departemen, master_karyawan.grup, master_karyawan.jabatan, master_karyawan.tanggal_masuk, master_karyawan.status,@SalaryId,master_gaji.keterangan,master_gaji.jenis,',@SQLCondition,',sysdate(),@DefaultUser from master_karyawan join master_gaji_karyawan_periode on master_gaji_karyawan_periode.nik = master_karyawan.nik join master_periode_penggajian_komponen on master_periode_penggajian_komponen.kode_gaji=master_gaji_karyawan_periode.kode_gaji join master_gaji on master_periode_penggajian_komponen.kode_gaji = master_gaji.kode_gaji where master_gaji_karyawan_periode.periode=@Period and master_periode_penggajian_komponen.periode_penggajian=@Periode_Penggajian and master_periode_penggajian_komponen.segmen=@Segment and master_gaji_karyawan_periode.periode=@Period and master_gaji_karyawan_periode.kode_gaji=@SalaryId');
        WHEN @SalaryPeriodPayment='hari' THEN
          SET @SQLStr = concat('insert into trans_periode_gaji_karyawan(periode,periode_penggajian,segmen,nik, nama,status_perkawinan,departemen,grup,jabatan,tanggal_masuk,status_kerja,kode_gaji,keterangan_gaji,jenis,nilai,lastupdate,user_id) select master_gaji_karyawan_periode.periode,trans_periode.periode_penggajian,trans_periode.segmen,master_mesin_clear.nik,master_karyawan.nama,master_karyawan.status_perkawinan,master_karyawan.departemen,master_karyawan.grup,master_karyawan.jabatan,tanggal_masuk,master_karyawan.status as status_kerja,master_gaji_karyawan_periode.kode_gaji,master_gaji.keterangan,master_gaji.jenis, sum(',@SQLCondition,'),sysdate(),@DefaultUser from master_mesin_clear join master_gaji_karyawan_periode on master_mesin_clear.nik=master_gaji_karyawan_periode.nik join trans_periode on master_mesin_clear.tanggal>=trans_periode.mulai and master_mesin_clear.tanggal<=trans_periode.selesai and master_gaji_karyawan_periode.periode=trans_periode.periode join master_karyawan on master_karyawan.nik=master_mesin_clear.nik join master_gaji on master_gaji.kode_gaji=master_gaji_karyawan_periode.kode_gaji where master_gaji_karyawan_periode.periode=@Period and trans_periode.periode_penggajian=@Periode_Penggajian and trans_periode.segmen=@Segment and master_gaji_karyawan_periode.kode_gaji=@SalaryId group by master_gaji_karyawan_periode.periode,trans_periode.periode_penggajian,trans_periode.segmen,master_mesin_clear.nik,master_mesin_clear.nama,master_mesin_clear.status_perkawinan,master_mesin_clear.departemen,master_mesin_clear.jabatan,master_mesin_clear.status_kerja,master_gaji_karyawan_periode.kode_gaji,master_gaji.keterangan,master_gaji.jenis');
        ELSE
          BEGIN
          END;
      END CASE;
      PREPARE stmt FROM @SQLStr;
      EXECUTE  stmt;
      DEALLOCATE PREPARE stmt;
    END LOOP GetSalary;
    CLOSE CurSalary;
  END BlockInsertSalary;
  Update trans_periode_gaji_karyawan a join master_karyawan b on a.nik=b.nik
  set a.npwp=b.npwp,a.bank=b.bank,a.no_rekening=b.no_rekening,a.pemilik_rekening=b.pemilik_rekening,
  a.mata_uang=b.mata_uang Where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and
  a.segmen=XSegmen;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `hitung_jamsostek` (IN `XPeriode` TEXT, IN `XPeriode_Penggajian` TEXT, IN `XSegmen` TEXT)  BEGIN
  Delete from trans_periode_jamsostek where periode=XPeriode and periode_penggajian=XPeriode_Penggajian
    And segmen=XSegmen;
  insert into trans_periode_jamsostek(periode,periode_penggajian,segmen,nik,nama,id,nama_program,gaji_dasar,maksimal_gaji_dasar,bpjs_kesehatan,
    bagian_perusahaan,bagian_karyawan,lastupdate,user_id)

    select a.periode,a.periode_penggajian,a.segmen,a.nik,a.nama,c.id,c.nama_program,(a.base+a.tunjangan_anak+a.tunjangan_kendaraan) as base,c.maksimal_dasar,d.bpjs_kesehatan,c.bagian_perusahaan,c.bagian_karyawan,sysdate(),'SYS'
    from trans_periode_gaji_karyawan e join master_jamsostek_komponen_dasar b on e.kode_gaji=b.kode_gaji 
    join trans_periode_pph21 a join master_karyawan d on a.nik=d.nik  
    join master_jamsostek c
    Where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian And a.segmen=XSegmen
    Group by a.periode,a.periode_penggajian,a.segmen,a.nik,c.id,c.nama_program,c.maksimal_dasar,
    c.bagian_perusahaan,c.bagian_karyawan;

    update trans_periode_jamsostek a set 
    a.nilai_perusahaan=a.bagian_perusahaan*(if(a.nama_program='BPJS Ketenagakerjaan' and a.gaji_dasar>a.maksimal_gaji_dasar,
    a.gaji_dasar,if(a.nama_program='BPJSKES' and a.gaji_dasar>a.maksimal_gaji_dasar and a.bpjs_kesehatan='Ya',
    a.maksimal_gaji_dasar,if(a.nama_program='BPJSKES' and a.bpjs_kesehatan='Tidak',0,if(a.nama_program='JPN' and a.gaji_dasar>a.maksimal_gaji_dasar,a.maksimal_gaji_dasar,a.gaji_dasar))))),

    a.nilai_karyawan=a.bagian_karyawan*(if(a.nama_program='BPJS Ketenagakerjaan' and a.gaji_dasar>a.maksimal_gaji_dasar,a.gaji_dasar,if(a.nama_program='BPJSKES' and a.gaji_dasar>a.maksimal_gaji_dasar and a.bpjs_kesehatan='Ya',a.maksimal_gaji_dasar,if(a.nama_program='BPJSKES' and a.bpjs_kesehatan='Tidak',0,if(a.nama_program='JPN' and a.gaji_dasar>a.maksimal_gaji_dasar,a.maksimal_gaji_dasar,a.gaji_dasar)))))
    where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian And a.segmen=XSegmen;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `hitung_pph21` (IN `XPeriode` TEXT, IN `XPeriode_Penggajian` TEXT, IN `XSegmen` TEXT)  NO SQL
BEGIN
  Delete from trans_periode_pph21 where periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;
  DELETE FROM trans_periode_pph21_tarif where periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;
  Insert into trans_periode_pph21(periode,periode_penggajian,segmen,nik,nama,status_perkawinan,status_kerja,bank,no_rekening,npwp,departemen,jabatan,tanggal_masuk,lastupdate,user_id) select a.periode,a.periode_penggajian,a.segmen,a.nik,a.nama,a.status_perkawinan,a.status_kerja,a.bank,a.no_rekening,a.npwp,a.departemen,a.jabatan,a.tanggal_masuk,sysdate(),'SYS'
    from trans_periode_gaji_karyawan a join master_pph21_komponen b on a.kode_gaji=b.kode_gaji
    join master_gaji c on a.kode_gaji=b.kode_gaji
    where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen
    group by a.periode,a.periode_penggajian,a.segmen,a.nik,a.status_perkawinan,a.status_kerja,a.npwp,a.departemen;
    
    UPDATE trans_periode_pph21 a join (SELECT trans_periode.periode, trans_periode.thr
    FROM trans_periode) b 
    on a.periode=b.periode
    SET periode_thr=b.thr
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21 a join (SELECT vspl.nik, vspl.jumlah_jam AS total_jam, master_gaji_karyawan_periode.periode, master_gaji_karyawan_periode.nilai_gaji, master_gaji_karyawan_periode.kode_gaji
    FROM vspl JOIN master_gaji_karyawan_periode ON vspl.nik = master_gaji_karyawan_periode.nik and vspl.periode=master_gaji_karyawan_periode.periode group by master_gaji_karyawan_periode.nik, master_gaji_karyawan_periode.periode) b 
    on a.periode=b.periode AND a.nik=b.nik
    SET over_time_index=b.total_jam
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;
    
    UPDATE trans_periode_pph21 a join (SELECT nik,periode,count(status_aktual) as kehadiran FROM master_mesin_clear 
    WHERE status_aktual = 'Hadir' group by periode, nik) b
    on a.nik=b.nik and a.periode=b.periode SET a.kehadiran=b.kehadiran
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (SELECT nik,periode,count(status) as ketidakhadiran FROM master_absen_sakit_cuti 
    WHERE status = 'Tanpa Keterangan' group by periode, nik) b
    on a.nik=b.nik and a.periode=b.periode SET a.ketidakhadiran=b.ketidakhadiran
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21 a join (SELECT nik,periode,nilai_gaji
    FROM master_gaji_karyawan_periode where kode_gaji=101) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.potongan_ketidakhadiran=if(a.jabatan='STAFF',((a.ketidakhadiran/21)*b.nilai_gaji),((a.ketidakhadiran/25)*b.nilai_gaji))
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21 a join (select nik, periode, dari_tanggal, sampai_tanggal, sum(datediff(sampai_tanggal, dari_tanggal)) as jumlah
    from master_absen_sakit_cuti
    where status ='Sakit' group by nik,periode) b
    on a.nik=b.nik and a.periode=b.periode SET sakit=b.jumlah
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select nik, periode, dari_tanggal, sampai_tanggal, datediff(sampai_tanggal, dari_tanggal) as jumlah
    from master_absen_sakit_cuti
    where status ='Cuti' group by nik,periode) b
    on a.nik=b.nik and a.periode=b.periode SET a.cuti=b.jumlah
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (SELECT nik, periode, Count(keterangan) AS terlambat
    FROM master_mesin_clear where keterangan='Terlambat' group by nik,periode) b
    on a.nik=b.nik and a.periode=b.periode SET a.terlambat=b.terlambat
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 set kct=(ketidakhadiran+sakit+terlambat)
    WHERE periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;
    
    UPDATE trans_periode_pph21 a join (select trans_periode.periode, trans_periode_pph21.nik, trans_periode_pph21.nama, trans_periode_pph21.tanggal_masuk, datediff(trans_periode.selesai, trans_periode_pph21.tanggal_masuk) as lama_kerja
    from trans_periode join trans_periode_pph21 on trans_periode.periode=trans_periode_pph21.periode) b 
    on a.periode=b.periode and a.nik=b.nik
    SET a.lama_kerja=b.lama_kerja
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select jenis, periode, periode_penggajian, segmen, nik, nilai as base
    from trans_periode_gaji_karyawan
    where kode_gaji='101'
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.base=if(a.lama_kerja='0',b.base,if(a.lama_kerja > 20,b.base,((a.lama_kerja*8)/173)*b.base))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan 
    where kode_gaji='301'
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_transport=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select jenis, periode, periode_penggajian, segmen, nik, nilai as tunjangan_kendaraan
    from trans_periode_gaji_karyawan
    where kode_gaji='302'
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_kendaraan=b.tunjangan_kendaraan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji='303') b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_makan=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (SELECT nik,periode,nilai_gaji
    FROM master_gaji_karyawan_periode where kode_gaji=304) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_anak=b.nilai_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=305) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.sales_incentive=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode,nik,nilai 
    from trans_periode_gaji_karyawan 
    where kode_gaji='308') b on a.periode=b.periode 
    AND a.nik=b.nik set a.bonus=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=402) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.adjustment_plus=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode,periode_penggajian,segmen,nik,    
    (0.3/100)*(base+tunjangan_anak+tunjangan_kendaraan) as jkm_perusahaan
    from trans_periode_pph21
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jkm_perusahaan=b.jkm_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode,periode_penggajian,segmen,nik,
    (0.89/100)*(base+tunjangan_anak+tunjangan_kendaraan) as jkk_perusahaan
    from trans_periode_pph21
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jkk_perusahaan=b.jkk_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode,periode_penggajian,segmen,nik,
    (3.70/100)*(base+tunjangan_anak+tunjangan_kendaraan) as jht_perusahaan
    from trans_periode_pph21
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jht_perusahaan=b.jht_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21) b on a.periode=b.periode AND a.nik=b.nik
    set a.jkn_perusahaan=if(b.base+b.tunjangan_anak+b.tunjangan_kendaraan >= 8000000,0.04*(8000000),0.04*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21) b on a.periode=b.periode AND a.nik=b.nik
    set a.jpn_perusahaan=if(b.base+b.tunjangan_anak+b.tunjangan_kendaraan >= 7703500,0.02*(7703500),0.02*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (SELECT vspl.nik, vspl.jumlah_jam AS total_jam, master_gaji_karyawan_periode.periode, master_gaji_karyawan_periode.nilai_gaji, master_gaji_karyawan_periode.kode_gaji, master_tunjangan_anak.nilai
    FROM (master_karyawan INNER JOIN (vspl INNER JOIN master_gaji_karyawan_periode ON vspl.nik = master_gaji_karyawan_periode.nik) ON master_karyawan.nik = master_gaji_karyawan_periode.nik) INNER JOIN master_tunjangan_anak ON master_karyawan.status_perkawinan = master_tunjangan_anak.status_perkawinan
    GROUP BY master_gaji_karyawan_periode.periode, master_gaji_karyawan_periode.nik, master_tunjangan_anak.nilai) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.over_time=((a.base+a.tunjangan_kendaraan+a.tunjangan_anak+a.jkm_perusahaan+a.jkk_perusahaan+a.jht_perusahaan+a.jkn_perusahaan+a.jpn_perusahaan)/173)*a.over_time_index
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21 a SET a.penghasilan_kotor=a.base + a.over_time + a.tunjangan_transport + a.tunjangan_kendaraan + a.tunjangan_makan + a.tunjangan_anak + a.sales_incentive + a.bonus + a.adjustment_plus + a.jkm_perusahaan + a.jkk_perusahaan + a.jht_perusahaan + a.jkn_perusahaan + a.jpn_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode,periode_penggajian,segmen,nik,
    (2/100)*(base+tunjangan_anak+tunjangan_kendaraan) as jht_karyawan
    from trans_periode_pph21
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jht_karyawan=b.jht_karyawan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21) b on a.periode=b.periode AND a.nik=b.nik
    set a.jpn_karyawan=if(b.base+b.tunjangan_kendaraan+b.tunjangan_anak >= 7703500,0.01*(7703500),0.01*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21) b on a.periode=b.periode AND a.nik=b.nik
    set a.bpjs_kesehatan_karyawan=if(b.base+b.tunjangan_kendaraan+b.tunjangan_anak >= 8000000,0.01*(8000000),0.01*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21) b on a.periode=b.periode AND a.nik=b.nik
    set a.biaya_jabatan=if(b.base+b.tunjangan_kendaraan+b.tunjangan_anak >= 6000000,500000,0.05*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a set a.total_pengurang=a.jht_karyawan + a.jpn_karyawan + a.biaya_jabatan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a set a.netto=a.penghasilan_kotor - a.total_pengurang
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a set a.netto_setahun=a.netto*12
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join master_pph21_ptkp b on a.status_perkawinan=b.status_perkawinan
    SET a.ptkp_gaji=b.nilai_ptkp
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a set a.penghasilan_kena_pajak=a.netto_setahun - a.ptkp_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a set a.tunjangan_jamsostek=a.jkm_perusahaan + a.jkk_perusahaan + a.jht_perusahaan + a.jkn_perusahaan + a.jpn_perusahaan 
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=403) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.adjustment_minus=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21 a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=401) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.potongan_koperasi=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    INSERT into trans_periode_pph21_tarif
    select a.periode,a.periode_penggajian,a.segmen,'gaji',a.nik,b.kode_tarif,
    if(a.penghasilan_kena_pajak<b.batas_bawah,0,if(a.penghasilan_kena_pajak>=b.batas_atas,b.batas_atas-(b.batas_bawah-1),a.penghasilan_kena_pajak-b.batas_bawah-1)),b.tarif,
    if(a.penghasilan_kena_pajak<b.batas_bawah,0,if(a.penghasilan_kena_pajak>=b.batas_atas,b.batas_atas-(b.batas_bawah-1),a.penghasilan_kena_pajak-b.batas_bawah-1))*b.tarif,
    sysdate(),'SYS' from trans_periode_pph21 a,master_pph21_tarif b
    where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join
    (select periode,periode_penggajian,segmen,nik,sum(nilai_pph21) as pph21_gaji from trans_periode_pph21_tarif
    where jenis='gaji' group by periode,periode_penggajian,segmen,nik) b
    ON a.periode=b.periode AND a.periode_penggajian=b.periode_penggajian AND a.segmen=b.segmen
    AND a.nik=b.nik SET a.pph21_gaji_setahun=b.pph21_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a set a.pph21_gaji_sebulan=a.pph21_gaji_setahun/12
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a set a.tambahan_non_npwp=if(a.npwp='' or a.npwp='-' or a.npwp='0',a.pph21_gaji_sebulan*0.2,0)
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a join (select nik,pph21_metode from master_karyawan) b on a.nik=b.nik set a.metode_pph21=b.pph21_metode
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21 a set a.pph21_nett=a.pph21_gaji_sebulan + a.tambahan_non_npwp
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21 a set a.thp=if(a.metode_pph21='NET',(a.penghasilan_kotor + a.pph21_gaji_sebulan) - (a.pph21_gaji_sebulan + a.tambahan_non_npwp + a.tunjangan_jamsostek + a.jht_karyawan + a.jpn_karyawan + a.bpjs_kesehatan_karyawan + a.potongan_koperasi + a.adjustment_minus),a.penghasilan_kotor - (a.pph21_gaji_sebulan + a.tambahan_non_npwp + a.tunjangan_jamsostek + a.jht_karyawan + a.jpn_karyawan + a.bpjs_kesehatan_karyawan + a.potongan_koperasi + a.adjustment_minus)) 
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `hitung_pph21_bonus` (IN `XPeriode` TEXT, IN `XPeriode_Penggajian` TEXT, IN `XSegmen` TEXT)  NO SQL
BEGIN
  Delete from trans_periode_pph21_bonus where periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;
  DELETE FROM trans_periode_pph21_tarif_bonus where periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;
  Insert into trans_periode_pph21_bonus(periode,periode_penggajian,segmen,nik,nama,status_perkawinan,status_kerja,bank,no_rekening,npwp,departemen,jabatan,tanggal_masuk,lastupdate,user_id) select a.periode,a.periode_penggajian,a.segmen,a.nik,a.nama,a.status_perkawinan,a.status_kerja,a.bank,a.no_rekening,a.npwp,a.departemen,a.jabatan,a.tanggal_masuk,sysdate(),'SYS'
    from trans_periode_gaji_karyawan a join master_pph21_komponen b on a.kode_gaji=b.kode_gaji
    join master_gaji c on a.kode_gaji=b.kode_gaji
    where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen
    group by a.periode,a.periode_penggajian,a.segmen,a.nik,a.status_perkawinan,a.status_kerja,a.npwp,a.departemen;

    UPDATE trans_periode_pph21_bonus a join (SELECT vspl.nik, vspl.jumlah_jam AS total_jam, master_gaji_karyawan_periode.periode, master_gaji_karyawan_periode.nilai_gaji, master_gaji_karyawan_periode.kode_gaji
    FROM vspl JOIN master_gaji_karyawan_periode ON vspl.nik = master_gaji_karyawan_periode.nik and vspl.periode=master_gaji_karyawan_periode.periode group by master_gaji_karyawan_periode.nik, master_gaji_karyawan_periode.periode) b 
    on a.periode=b.periode AND a.nik=b.nik
    SET a.over_time_index=b.total_jam
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21_bonus a join (SELECT vspl.nik, vspl.jumlah_jam AS total_jam, master_gaji_karyawan_periode.periode, master_gaji_karyawan_periode.nilai_gaji, master_gaji_karyawan_periode.kode_gaji, master_tunjangan_anak.nilai
    FROM (master_karyawan INNER JOIN (vspl INNER JOIN master_gaji_karyawan_periode ON vspl.nik = master_gaji_karyawan_periode.nik) ON master_karyawan.nik = master_gaji_karyawan_periode.nik) INNER JOIN master_tunjangan_anak ON master_karyawan.status_perkawinan = master_tunjangan_anak.status_perkawinan
    GROUP BY master_gaji_karyawan_periode.periode, master_gaji_karyawan_periode.nik, master_tunjangan_anak.nilai) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.over_time=((b.nilai_gaji+b.nilai)/173)*a.over_time_index
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;
    
    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,count(status_aktual) as kehadiran FROM master_mesin_clear 
    WHERE status_aktual = 'Hadir' group by periode, nik) b
    on a.nik=b.nik and a.periode=b.periode SET a.kehadiran=b.kehadiran
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,count(status) as ketidakhadiran FROM master_absen_sakit_cuti 
    WHERE status = 'Tanpa Keterangan' group by periode, nik) b
    on a.nik=b.nik and a.periode=b.periode SET a.ketidakhadiran=b.ketidakhadiran
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,nilai_gaji
    FROM master_gaji_karyawan_periode where kode_gaji=101) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.potongan_ketidakhadiran=if(a.jabatan='STAFF',((a.ketidakhadiran/21)*b.nilai_gaji),((a.ketidakhadiran/25)*b.nilai_gaji))
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21_bonus a join (select nik, periode, dari_tanggal, sampai_tanggal, sum(datediff(sampai_tanggal, dari_tanggal)) as jumlah
    from master_absen_sakit_cuti
    where status ='Sakit' group by nik,periode) b
    on a.nik=b.nik and a.periode=b.periode SET sakit=b.jumlah
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select nik, periode, dari_tanggal, sampai_tanggal, datediff(sampai_tanggal, dari_tanggal) as jumlah
    from master_absen_sakit_cuti
    where status ='Cuti' group by nik,periode) b
    on a.nik=b.nik and a.periode=b.periode SET a.cuti=b.jumlah
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (SELECT nik, periode, Count(keterangan) AS terlambat
    FROM master_mesin_clear where keterangan='Terlambat' group by nik,periode) b
    on a.nik=b.nik and a.periode=b.periode SET a.terlambat=b.terlambat
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus set kct=(ketidakhadiran+sakit+terlambat)
    WHERE periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select jenis, periode, periode_penggajian, segmen, nik, nilai as base
    from trans_periode_gaji_karyawan
    where kode_gaji='101'
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.base=b.base
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_bonus a join (SELECT trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.nilai
    FROM trans_periode_gaji_karyawan where kode_gaji='307') b 
    on a.nik=b.nik and a.periode=b.periode
    SET a.bonus_tahunan=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan 
    where kode_gaji='301'
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_transport=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select jenis, periode, periode_penggajian, segmen, nik, nilai as tunjangan_kendaraan
    from trans_periode_gaji_karyawan
    where kode_gaji='302'
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_kendaraan=b.tunjangan_kendaraan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji='303') b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_makan=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,nilai_gaji
    FROM master_gaji_karyawan_periode where kode_gaji=304) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_anak=b.nilai_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=305) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.sales_incentive=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select periode,nik,nilai 
    from trans_periode_gaji_karyawan 
    where kode_gaji='308') b on a.periode=b.periode 
    AND a.nik=b.nik set a.bonus=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=402) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.adjustment_plus=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select jenis,periode,periode_penggajian,segmen,nik,    
    (0.3/100)*sum(nilai) as jkm_perusahaan
    from trans_periode_gaji_karyawan
    where kode_gaji in ('101','302','304')
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jkm_perusahaan=b.jkm_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select jenis,periode,periode_penggajian,segmen,nik,
    (0.89/100)*sum(nilai) as jkk_perusahaan
    from trans_periode_gaji_karyawan
    where kode_gaji in ('101','302','304')
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jkk_perusahaan=b.jkk_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select jenis,periode,periode_penggajian,segmen,nik,
    (3.70/100)*sum(nilai) as jht_perusahaan
    from trans_periode_gaji_karyawan
    where kode_gaji in ('101','302','304')
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jht_perusahaan=b.jht_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_bonus) b on a.periode=b.periode AND a.nik=b.nik
    set a.jkn_perusahaan=if(b.base+b.tunjangan_kendaraan >= 8000000,0.04*(8000000),0.04*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_bonus) b on a.periode=b.periode AND a.nik=b.nik
    set a.jpn_perusahaan=if(b.base+b.tunjangan_kendaraan >= 7703500,0.02*(7703500),0.02*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a SET a.penghasilan_kotor=a.base + a.bonus_tahunan + a.over_time + a.tunjangan_transport + a.tunjangan_kendaraan + a.tunjangan_makan + a.tunjangan_anak + a.sales_incentive + a.bonus + a.adjustment_plus + a.jkm_perusahaan + a.jkk_perusahaan + a.jht_perusahaan + a.jkn_perusahaan + a.jpn_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select jenis,periode,periode_penggajian,segmen,nik,
    (2/100)*sum(nilai) as jht_karyawan
    from trans_periode_gaji_karyawan
    where kode_gaji in ('101','302','304')
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jht_karyawan=b.jht_karyawan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_bonus) b on a.periode=b.periode AND a.nik=b.nik
    set a.jpn_karyawan=if(b.base+b.tunjangan_kendaraan >= 7703500,0.01*(7703500),0.01*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_bonus) b on a.periode=b.periode AND a.nik=b.nik
    set a.bpjs_kesehatan_karyawan=if(b.base+b.tunjangan_kendaraan >= 8000000,0.01*(8000000),0.01*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_bonus) b on a.periode=b.periode AND a.nik=b.nik
    set a.biaya_jabatan=if(b.base+b.tunjangan_kendaraan >= 6000000,500000,0.05*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a set a.total_pengurang=a.jht_karyawan + a.jpn_karyawan + a.biaya_jabatan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a set a.netto=a.penghasilan_kotor - a.total_pengurang
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a set a.netto_setahun=a.netto*12
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join master_pph21_ptkp b on a.status_perkawinan=b.status_perkawinan
    SET a.ptkp_gaji=b.nilai_ptkp
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a set a.penghasilan_kena_pajak=a.netto_setahun - a.ptkp_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a set a.tunjangan_jamsostek=a.jkm_perusahaan + a.jkk_perusahaan + a.jht_perusahaan + a.jkn_perusahaan + a.jpn_perusahaan 
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=403) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.adjustment_minus=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_bonus a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=401) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.potongan_koperasi=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    INSERT into trans_periode_pph21_tarif_bonus
    select a.periode,a.periode_penggajian,a.segmen,'gaji',a.nik,b.kode_tarif,
    if(a.penghasilan_kena_pajak<b.batas_bawah,0,if(a.penghasilan_kena_pajak>=b.batas_atas,b.batas_atas-(b.batas_bawah-1),a.penghasilan_kena_pajak-b.batas_bawah-1)),b.tarif,
    if(a.penghasilan_kena_pajak<b.batas_bawah,0,if(a.penghasilan_kena_pajak>=b.batas_atas,b.batas_atas-(b.batas_bawah-1),a.penghasilan_kena_pajak-b.batas_bawah-1))*b.tarif,
    sysdate(),'SYS' from trans_periode_pph21_bonus a,master_pph21_tarif b
    where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join
    (select periode,periode_penggajian,segmen,nik,sum(nilai_pph21) as pph21_gaji from trans_periode_pph21_tarif_bonus
    where jenis='gaji' group by periode,periode_penggajian,segmen,nik) b
    ON a.periode=b.periode AND a.periode_penggajian=b.periode_penggajian AND a.segmen=b.segmen
    AND a.nik=b.nik SET a.pph21_gaji_setahun=b.pph21_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a set a.pph21_gaji_sebulan=a.pph21_gaji_setahun/12
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a set a.tambahan_non_npwp=if(a.npwp='' or a.npwp='-' or a.npwp='0',a.pph21_gaji_sebulan*0.2,0)
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a join (select nik,pph21_metode from master_karyawan) b on a.nik=b.nik set a.metode_pph21=b.pph21_metode
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a set a.pph21_nett=a.pph21_gaji_sebulan + a.tambahan_non_npwp
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_bonus a join (SELECT periode,nik,pph21_nett
    FROM trans_periode_pph21) b 
    on a.periode=b.periode AND a.nik=b.nik
    set a.pph21_gaji=b.pph21_nett
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_bonus a set a.pph21_bonus=a.pph21_nett - a.pph21_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_bonus a set a.thp=if(a.metode_pph21='NET',a.bonus_tahunan,a.bonus_tahunan-a.pph21_bonus) 
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `hitung_pph21_clear` (IN `XPeriode` TEXT, IN `XPeriode_Penggajian` TEXT, IN `XSegmen` TEXT)  BEGIN
  Delete from trans_periode_pph21_clear where periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;
  Insert into trans_periode_pph21_clear(periode,periode_penggajian,segmen,nik,nama,departemen,lastupdate,user_id) select a.periode,a.periode_penggajian,a.segmen,a.nik,a.nama,a.departemen,sysdate(),'SYS'
    from trans_periode_gaji_karyawan a join master_pph21_komponen b on a.kode_gaji=b.kode_gaji
    join master_gaji c on a.kode_gaji=b.kode_gaji
    where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen
    group by a.periode,a.periode_penggajian,a.segmen,a.nik,a.status_perkawinan,a.status_kerja,a.npwp,a.departemen;

	UPDATE trans_periode_pph21_clear a join (SELECT master_gaji_karyawan_periode.periode,master_gaji_karyawan_periode.nik,master_gaji_karyawan_periode.kode_gaji,
 	master_gaji_karyawan_periode.nilai_gaji 
    FROM master_gaji_karyawan_periode JOIN trans_periode_gaji_karyawan ON master_gaji_karyawan_periode.nik = trans_periode_gaji_karyawan.nik and 
	trans_periode_gaji_karyawan.periode=master_gaji_karyawan_periode.periode 
	where master_gaji_karyawan_periode.kode_gaji='101'
	group by master_gaji_karyawan_periode.nik, master_gaji_karyawan_periode.periode) b 
    on a.periode=b.periode AND a.nik=b.nik
    SET base=b.nilai_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_pph21.periode,trans_periode_pph21.nik,trans_periode_pph21.over_time
    FROM trans_periode_pph21 
    group by trans_periode_pph21.periode, trans_periode_pph21.nik) b 
    on a.periode=b.periode AND a.nik=b.nik
    SET a.over_time=b.over_time
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_pph21.periode,trans_periode_pph21.nik,trans_periode_pph21.insentive_kehadiran
	FROM trans_periode_pph21 
group by trans_periode_pph21.periode, trans_periode_pph21.nik) b 
    on a.periode=b.periode AND a.nik=b.nik
    SET a.insentive_kehadiran=b.insentive_kehadiran
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_gaji_karyawan.periode,trans_periode_gaji_karyawan.nik,
	trans_periode_gaji_karyawan.kode_gaji,trans_periode_gaji_karyawan.nilai
	FROM trans_periode_gaji_karyawan where trans_periode_gaji_karyawan.kode_gaji='301'
	group by trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.kode_gaji) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.tunjangan_transport=b.nilai
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_gaji_karyawan.periode,trans_periode_gaji_karyawan.nik,
	trans_periode_gaji_karyawan.kode_gaji,trans_periode_gaji_karyawan.nilai
	FROM trans_periode_gaji_karyawan where trans_periode_gaji_karyawan.kode_gaji='303'
	group by trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.kode_gaji) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.tunjangan_makan=b.nilai
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_gaji_karyawan.periode,trans_periode_gaji_karyawan.nik,
	trans_periode_gaji_karyawan.kode_gaji,trans_periode_gaji_karyawan.nilai
	FROM trans_periode_gaji_karyawan where trans_periode_gaji_karyawan.kode_gaji='302'
	group by trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.kode_gaji) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.tunjangan_kendaraan=b.nilai
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_gaji_karyawan.periode,trans_periode_gaji_karyawan.nik,
	trans_periode_gaji_karyawan.kode_gaji,trans_periode_gaji_karyawan.nilai
	FROM trans_periode_gaji_karyawan where trans_periode_gaji_karyawan.kode_gaji='304'
	group by trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.kode_gaji) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.tunjangan_anak=b.nilai
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
        
    UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_gaji_karyawan.periode,trans_periode_gaji_karyawan.nik,
	trans_periode_gaji_karyawan.kode_gaji,trans_periode_gaji_karyawan.nilai
	FROM trans_periode_gaji_karyawan where trans_periode_gaji_karyawan.kode_gaji='309'
	group by trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.kode_gaji) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.thr=b.nilai
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_gaji_karyawan.periode,trans_periode_gaji_karyawan.nik,
	trans_periode_gaji_karyawan.kode_gaji,trans_periode_gaji_karyawan.nilai
	FROM trans_periode_gaji_karyawan where trans_periode_gaji_karyawan.kode_gaji='305'
	group by trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.kode_gaji) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.sales_insentive=b.nilai
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

        UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_gaji_karyawan.periode,trans_periode_gaji_karyawan.nik,
    trans_periode_gaji_karyawan.kode_gaji,trans_periode_gaji_karyawan.nilai
    FROM trans_periode_gaji_karyawan where trans_periode_gaji_karyawan.kode_gaji='402'
    group by trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.kode_gaji) b 
        on a.periode=b.periode AND a.nik=b.nik
        SET a.adjustment_plus=b.nilai
        WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_pph21.periode,trans_periode_pph21.nik,
	trans_periode_pph21.metode_pph21
	FROM trans_periode_pph21
	group by trans_periode_pph21.periode, trans_periode_pph21.nik) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.metode_pph21=b.metode_pph21
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_pph21.periode,trans_periode_pph21.nik,trans_periode_pph21.metode_pph21,
	trans_periode_pph21.pph21_nett
	FROM trans_periode_pph21
	group by trans_periode_pph21.periode, trans_periode_pph21.nik) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.tunjangan_pph21=if(b.metode_pph21 = 'GROSS',0,if(b.metode_pph21='NET',b.pph21_nett,b.pph21_nett))
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_pph21.periode,trans_periode_pph21.nik,trans_periode_pph21.pph21_nett
	FROM trans_periode_pph21
	group by trans_periode_pph21.periode, trans_periode_pph21.nik) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.potongan_pph21=b.pph21_nett
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_jamsostek.periode,trans_periode_jamsostek.nik,trans_periode_jamsostek.id,trans_periode_jamsostek.nilai_perusahaan
	FROM trans_periode_jamsostek where trans_periode_jamsostek.id='3'
	group by trans_periode_jamsostek.periode, trans_periode_jamsostek.nik,trans_periode_jamsostek.id) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.bpjs_ketenagakerjaan_perusahaan=b.nilai_perusahaan
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
        
        UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_jamsostek.periode,trans_periode_jamsostek.nik,trans_periode_jamsostek.id,trans_periode_jamsostek.nilai_perusahaan
	FROM trans_periode_jamsostek where trans_periode_jamsostek.id='2'
	group by trans_periode_jamsostek.periode, trans_periode_jamsostek.nik,trans_periode_jamsostek.id) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.bpjs_kesehatan_perusahaan=b.nilai_perusahaan
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_jamsostek.periode,trans_periode_jamsostek.nik,trans_periode_jamsostek.id,trans_periode_jamsostek.nilai_perusahaan
	FROM trans_periode_jamsostek where trans_periode_jamsostek.id='1'
	group by trans_periode_jamsostek.periode, trans_periode_jamsostek.nik,trans_periode_jamsostek.id) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.jpn_perusahaan=b.nilai_perusahaan
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;


	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_jamsostek.periode,trans_periode_jamsostek.nik,trans_periode_jamsostek.id,trans_periode_jamsostek.nilai_karyawan
	FROM trans_periode_jamsostek where trans_periode_jamsostek.id='3'
	group by trans_periode_jamsostek.periode, trans_periode_jamsostek.nik,trans_periode_jamsostek.id) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.bpjs_ketenagakerjaan_karyawan=b.nilai_karyawan
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_jamsostek.periode,trans_periode_jamsostek.nik,trans_periode_jamsostek.id,trans_periode_jamsostek.nilai_karyawan
	FROM trans_periode_jamsostek where trans_periode_jamsostek.id='2'
	group by trans_periode_jamsostek.periode, trans_periode_jamsostek.nik,trans_periode_jamsostek.id) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.bpjs_kesehatan_karyawan=b.nilai_karyawan
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_jamsostek.periode,trans_periode_jamsostek.nik,trans_periode_jamsostek.id,trans_periode_jamsostek.nilai_karyawan
	FROM trans_periode_jamsostek where trans_periode_jamsostek.id='1'
	group by trans_periode_jamsostek.periode, trans_periode_jamsostek.nik,trans_periode_jamsostek.id) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.jpn_karyawan=b.nilai_karyawan
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_pph21.periode,trans_periode_pph21.nik,trans_periode_pph21.tunjangan_jamsostek
    FROM trans_periode_pph21
    group by trans_periode_pph21.periode, trans_periode_pph21.nik) b 
        on a.periode=b.periode AND a.nik=b.nik
        SET a.potongan_bpjs=b.tunjangan_jamsostek
        WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_pph21.periode,trans_periode_pph21.nik,trans_periode_pph21.pinjaman_perusahaan
    FROM trans_periode_pph21
    group by trans_periode_pph21.periode, trans_periode_pph21.nik) b 
        on a.periode=b.periode AND a.nik=b.nik
        SET a.potongan_koperasi=b.pinjaman_perusahaan
        WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
        
    UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_pph21.periode,trans_periode_pph21.nik,trans_periode_pph21.potongan_ketidakhadiran
    FROM trans_periode_pph21
    group by trans_periode_pph21.periode, trans_periode_pph21.nik) b 
        on a.periode=b.periode AND a.nik=b.nik
        SET a.potongan_mangkir=b.potongan_ketidakhadiran
        WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

        UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_gaji_karyawan.periode,trans_periode_gaji_karyawan.nik,
    trans_periode_gaji_karyawan.kode_gaji,trans_periode_gaji_karyawan.nilai
    FROM trans_periode_gaji_karyawan where trans_periode_gaji_karyawan.kode_gaji='403'
    group by trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.kode_gaji) b 
        on a.periode=b.periode AND a.nik=b.nik
        SET a.adjustment_minus=b.nilai
        WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

	UPDATE trans_periode_pph21_clear a join (SELECT trans_periode_pph21.periode,trans_periode_pph21.nik,trans_periode_pph21.thp
	FROM trans_periode_pph21
	group by trans_periode_pph21.periode, trans_periode_pph21.nik) b 
    	on a.periode=b.periode AND a.nik=b.nik
    	SET a.thp=b.thp
    	WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `hitung_pph21_thr` (IN `XPeriode` TEXT, IN `XPeriode_Penggajian` TEXT, IN `XSegmen` TEXT)  NO SQL
BEGIN
  Delete from trans_periode_pph21_thr where periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;
  DELETE FROM trans_periode_pph21_tarif_thr where periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;
  Insert into trans_periode_pph21_thr(periode,periode_penggajian,segmen,nik,nama,status_perkawinan,status_kerja,bank,no_rekening,npwp,departemen,jabatan,tanggal_masuk,lastupdate,user_id) select a.periode,a.periode_penggajian,a.segmen,a.nik,a.nama,a.status_perkawinan,a.status_kerja,a.bank,a.no_rekening,a.npwp,a.departemen,a.jabatan,a.tanggal_masuk,sysdate(),'SYS'
    from trans_periode_gaji_karyawan a join master_pph21_komponen b on a.kode_gaji=b.kode_gaji
    join master_gaji c on a.kode_gaji=b.kode_gaji
    where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen
    group by a.periode,a.periode_penggajian,a.segmen,a.nik,a.status_perkawinan,a.status_kerja,a.npwp,a.departemen;
    
    UPDATE trans_periode_pph21_thr a join (SELECT trans_periode.periode, trans_periode.thr
    FROM trans_periode) b 
    on a.periode=b.periode
    SET a.periode_thr=b.thr
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21_thr a join (SELECT vspl.nik, vspl.jumlah_jam AS total_jam, master_gaji_karyawan_periode.periode, master_gaji_karyawan_periode.nilai_gaji, master_gaji_karyawan_periode.kode_gaji
    FROM vspl JOIN master_gaji_karyawan_periode ON vspl.nik = master_gaji_karyawan_periode.nik and vspl.periode=master_gaji_karyawan_periode.periode group by master_gaji_karyawan_periode.nik, master_gaji_karyawan_periode.periode) b 
    on a.periode=b.periode AND a.nik=b.nik
    SET a.over_time_index=b.total_jam
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;
    
    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,count(status_aktual) as kehadiran FROM master_mesin_clear 
    WHERE status_aktual = 'Hadir' group by periode, nik) b
    on a.nik=b.nik and a.periode=b.periode SET a.kehadiran=b.kehadiran
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,count(status) as ketidakhadiran FROM master_absen_sakit_cuti 
    WHERE status = 'Tanpa Keterangan' group by periode, nik) b
    on a.nik=b.nik and a.periode=b.periode SET a.ketidakhadiran=b.ketidakhadiran
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,nilai_gaji
    FROM master_gaji_karyawan_periode where kode_gaji=101) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.potongan_ketidakhadiran=if(a.jabatan='STAFF',((a.ketidakhadiran/21)*b.nilai_gaji),((a.ketidakhadiran/25)*b.nilai_gaji))
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21_thr a join (select nik, periode, dari_tanggal, sampai_tanggal, sum(datediff(sampai_tanggal, dari_tanggal)) as jumlah
    from master_absen_sakit_cuti
    where status ='Sakit' group by nik,periode) b
    on a.nik=b.nik and a.periode=b.periode SET sakit=b.jumlah
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select nik, periode, dari_tanggal, sampai_tanggal, datediff(sampai_tanggal, dari_tanggal) as jumlah
    from master_absen_sakit_cuti
    where status ='Cuti' group by nik,periode) b
    on a.nik=b.nik and a.periode=b.periode SET a.cuti=b.jumlah
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (SELECT nik, periode, Count(keterangan) AS terlambat
    FROM master_mesin_clear where keterangan='Terlambat' group by nik,periode) b
    on a.nik=b.nik and a.periode=b.periode SET a.terlambat=b.terlambat
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr set kct=(ketidakhadiran+sakit+terlambat)
    WHERE periode=XPeriode and periode_penggajian=XPeriode_Penggajian and segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select trans_periode.periode, trans_periode_pph21_thr.nik, trans_periode_pph21_thr.nama, trans_periode_pph21_thr.tanggal_masuk, datediff(trans_periode.selesai, trans_periode_pph21_thr.tanggal_masuk) as lama_kerja
    from trans_periode join trans_periode_pph21_thr on trans_periode.periode=trans_periode_pph21_thr.periode) b 
    on a.periode=b.periode and a.nik=b.nik
    SET a.lama_kerja=b.lama_kerja
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select jenis, periode, periode_penggajian, segmen, nik, nilai as base
    from trans_periode_gaji_karyawan
    where kode_gaji='101'
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.base=if(a.lama_kerja='0',b.base,if(a.lama_kerja > 20,b.base,((a.lama_kerja*8)/173)*b.base))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_thr a join (SELECT trans_periode_gaji_karyawan.periode, trans_periode_gaji_karyawan.nik, trans_periode_gaji_karyawan.nilai
    FROM trans_periode_gaji_karyawan where kode_gaji='309') b 
    on a.nik=b.nik and a.periode=b.periode
    SET a.thr=if(b.nilai=0,a.base,b.nilai)
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan 
    where kode_gaji='301'
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_transport=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select jenis, periode, periode_penggajian, segmen, nik, nilai as tunjangan_kendaraan
    from trans_periode_gaji_karyawan
    where kode_gaji='302'
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_kendaraan=b.tunjangan_kendaraan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji='303') b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_makan=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,nilai_gaji
    FROM master_gaji_karyawan_periode where kode_gaji=304) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.tunjangan_anak=b.nilai_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=305) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.sales_incentive=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode,nik,nilai 
    from trans_periode_gaji_karyawan 
    where kode_gaji='308') b on a.periode=b.periode 
    AND a.nik=b.nik set a.bonus=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=402) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.adjustment_plus=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode,periode_penggajian,segmen,nik,    
    (0.3/100)*(base+tunjangan_anak+tunjangan_kendaraan) as jkm_perusahaan
    from trans_periode_pph21_thr
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jkm_perusahaan=b.jkm_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode,periode_penggajian,segmen,nik,
    (0.89/100)*(base+tunjangan_anak+tunjangan_kendaraan) as jkk_perusahaan
    from trans_periode_pph21_thr
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jkk_perusahaan=b.jkk_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode,periode_penggajian,segmen,nik,
    (3.70/100)*(base+tunjangan_anak+tunjangan_kendaraan) as jht_perusahaan
    from trans_periode_pph21_thr
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jht_perusahaan=b.jht_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_thr) b on a.periode=b.periode AND a.nik=b.nik
    set a.jkn_perusahaan=if(b.base+b.tunjangan_anak+b.tunjangan_kendaraan >= 8000000,0.04*(8000000),0.04*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_thr) b on a.periode=b.periode AND a.nik=b.nik
    set a.jpn_perusahaan=if(b.base+b.tunjangan_anak+b.tunjangan_kendaraan >= 7703500,0.02*(7703500),0.02*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (SELECT vspl.nik, vspl.jumlah_jam AS total_jam, master_gaji_karyawan_periode.periode, master_gaji_karyawan_periode.nilai_gaji, master_gaji_karyawan_periode.kode_gaji, master_tunjangan_anak.nilai
    FROM (master_karyawan INNER JOIN (vspl INNER JOIN master_gaji_karyawan_periode ON vspl.nik = master_gaji_karyawan_periode.nik) ON master_karyawan.nik = master_gaji_karyawan_periode.nik) INNER JOIN master_tunjangan_anak ON master_karyawan.status_perkawinan = master_tunjangan_anak.status_perkawinan
    GROUP BY master_gaji_karyawan_periode.periode, master_gaji_karyawan_periode.nik, master_tunjangan_anak.nilai) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.over_time=((a.base+a.tunjangan_kendaraan+a.tunjangan_anak+a.jkm_perusahaan+a.jkk_perusahaan+a.jht_perusahaan+a.jkn_perusahaan+a.jpn_perusahaan)/173)*a.over_time_index
    WHERE a.periode=XPeriode and a.periode_penggajian='BULANAN' and a.segmen=1;

    UPDATE trans_periode_pph21_thr a SET a.penghasilan_kotor=a.base + a.thr + a.over_time + a.tunjangan_transport + a.tunjangan_kendaraan + a.tunjangan_makan + a.tunjangan_anak + a.sales_incentive + a.bonus + a.adjustment_plus + a.jkm_perusahaan + a.jkk_perusahaan + a.jht_perusahaan + a.jkn_perusahaan + a.jpn_perusahaan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode,periode_penggajian,segmen,nik,
    (2/100)*(base+tunjangan_anak+tunjangan_kendaraan) as jht_karyawan
    from trans_periode_pph21_thr
    group by periode,periode_penggajian,segmen,nik) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.jht_karyawan=b.jht_karyawan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_thr) b on a.periode=b.periode AND a.nik=b.nik
    set a.jpn_karyawan=if(b.base+b.tunjangan_kendaraan+b.tunjangan_anak >= 7703500,0.01*(7703500),0.01*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_thr) b on a.periode=b.periode AND a.nik=b.nik
    set a.bpjs_kesehatan_karyawan=if(b.base+b.tunjangan_kendaraan+b.tunjangan_anak >= 8000000,0.01*(8000000),0.01*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select periode, nik, base, tunjangan_kendaraan, tunjangan_anak
    from trans_periode_pph21_thr) b on a.periode=b.periode AND a.nik=b.nik
    set a.biaya_jabatan=if(b.base+b.tunjangan_kendaraan+b.tunjangan_anak >= 6000000,500000,0.05*(b.base+b.tunjangan_kendaraan+b.tunjangan_anak))
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a set a.total_pengurang=a.jht_karyawan + a.jpn_karyawan + a.biaya_jabatan
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a set a.netto=a.penghasilan_kotor - a.total_pengurang
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a set a.netto_setahun=a.netto*12
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join master_pph21_ptkp b on a.status_perkawinan=b.status_perkawinan
    SET a.ptkp_gaji=b.nilai_ptkp
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a set a.penghasilan_kena_pajak=a.netto_setahun - a.ptkp_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a set a.tunjangan_jamsostek=a.jkm_perusahaan + a.jkk_perusahaan + a.jht_perusahaan + a.jkn_perusahaan + a.jpn_perusahaan 
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=403) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.adjustment_minus=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_thr a join (SELECT nik,periode,nilai
    FROM trans_periode_gaji_karyawan where kode_gaji=401) b 
    on a.periode=b.periode AND a.nik=b.nik 
    SET a.potongan_koperasi=b.nilai
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    INSERT into trans_periode_pph21_tarif_thr
    select a.periode,a.periode_penggajian,a.segmen,'gaji',a.nik,b.kode_tarif,
    if(a.penghasilan_kena_pajak<b.batas_bawah,0,if(a.penghasilan_kena_pajak>=b.batas_atas,b.batas_atas-(b.batas_bawah-1),a.penghasilan_kena_pajak-b.batas_bawah-1)),b.tarif,
    if(a.penghasilan_kena_pajak<b.batas_bawah,0,if(a.penghasilan_kena_pajak>=b.batas_atas,b.batas_atas-(b.batas_bawah-1),a.penghasilan_kena_pajak-b.batas_bawah-1))*b.tarif,
    sysdate(),'SYS' from trans_periode_pph21_thr a,master_pph21_tarif b
    where a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join
    (select periode,periode_penggajian,segmen,nik,sum(nilai_pph21) as pph21_gaji from trans_periode_pph21_tarif_thr
    where jenis='gaji' group by periode,periode_penggajian,segmen,nik) b
    ON a.periode=b.periode AND a.periode_penggajian=b.periode_penggajian AND a.segmen=b.segmen
    AND a.nik=b.nik SET a.pph21_gaji_setahun=b.pph21_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a set a.pph21_gaji_sebulan=a.pph21_gaji_setahun/12
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a set a.tambahan_non_npwp=if(a.npwp='' or a.npwp='-' or a.npwp='0',a.pph21_gaji_sebulan*0.2,0)
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a join (select nik,pph21_metode from master_karyawan) b on a.nik=b.nik set a.metode_pph21=b.pph21_metode
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a set a.pph21_nett=a.pph21_gaji_sebulan + a.tambahan_non_npwp
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_thr a join (SELECT periode,nik,pph21_nett
    FROM trans_periode_pph21) b 
    on a.periode=b.periode AND a.nik=b.nik
    set a.pph21_gaji=b.pph21_nett
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    UPDATE trans_periode_pph21_thr a set a.pph21_thr=a.pph21_nett - a.pph21_gaji
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;
    
    UPDATE trans_periode_pph21_thr a set a.thp=if(a.metode_pph21='NET',a.thr,a.thr-a.pph21_thr) 
    WHERE a.periode=XPeriode and a.periode_penggajian=XPeriode_Penggajian and a.segmen=XSegmen;

    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `vlist_gaji_karyawan` ()  BEGIN
SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'sum(if(kode_gaji = ',
      kode_gaji,
      ',nilai_gaji,0)) AS ',
      replace(keterangan, ' ', '')
    )
  ) into @sql from master_gaji;

SET @sql = CONCAT('SELECT a.nik,b.nama,', @sql, ' from master_gaji_karyawan a join master_karyawan b on a.nik=b.nik\r\n    group by a.nik,b.nama');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `vlist_gaji_karyawans` (IN `XPeriode` TEXT)  BEGIN
SET @XPeriode = XPeriode;
SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'sum(if(kode_gaji = ',
      kode_gaji,
      ',nilai_gaji,0)) AS ',
      replace(keterangan, ' ', '')
    )
  ) into @sql from master_gaji;

SET @sql = CONCAT('SELECT a.nik,b.nama,a.periode,', @sql, ' from master_gaji_karyawan a join master_karyawan b on a.nik=b.nik group by a.nik,b.nama,a.periode HAVING a.periode = @XPeriode');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `vlist_gaji_karyawans_periode` (IN `XPeriode` TEXT)  BEGIN
SET @XPeriode = XPeriode;
SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'sum(if(kode_gaji = ',
      kode_gaji,
      ',nilai_gaji,0)) AS ',
      replace(keterangan, ' ', '')
    )
  ) into @sql from master_gaji;

SET @sql = CONCAT('SELECT a.nik,b.nama,a.periode,', @sql, ' from master_gaji_karyawan_periode a join master_karyawan b on a.nik=b.nik group by a.nik,b.nama,a.periode HAVING a.periode = @XPeriode');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `vtrans_periode_gaji_karyawan` ()  BEGIN
SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'sum(if(kode_gaji = ',
      kode_gaji,
      ',nilai,0)) AS ',
      replace(keterangan, ' ', '')
    )
  ) into @sql from master_gaji;

SET @sql = CONCAT('SELECT a.nik,a.periode,b.nama,b.departemen,b.jabatan,', @sql, ' from trans_periode_gaji_karyawan a join master_karyawan b on a.nik=b.nik where a.periode=Periode
    group by a.nik,b.nama,b.departemen,b.jabatan');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `vtrans_periode_gaji_karyawans` (IN `XPeriode` VARCHAR(10))  BEGIN
SET @XPeriode = XPeriode;
SELECT
  GROUP_CONCAT(DISTINCT
    CONCAT(
      'sum(if(kode_gaji = ',
      kode_gaji,
      ',nilai,0)) AS ',
      replace(keterangan, ' ', '')
    )
  ) into @sql from master_gaji;

SET @sql = CONCAT('SELECT a.nik,a.periode,b.nama,b.departemen,b.jabatan,b.tanggal_masuk,', @sql, ' from trans_periode_gaji_karyawan a join master_karyawan b on a.nik=b.nik group by a.periode,a.nik,b.nama,b.departemen,b.jabatan HAVING a.periode = @XPeriode');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `user_data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_agama`
--

CREATE TABLE `content_agama` (
  `agama` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `id_agama` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_agama`
--

INSERT INTO `content_agama` (`agama`, `lastupdate`, `user_id`, `id_agama`) VALUES
('HINDU', '2016-01-07 00:00:00', 'SYS', 2),
('ISLAM', '2016-01-07 00:00:00', 'SYS', 3),
('KONGHUCU', '2016-01-07 00:00:00', 'SYS', 4),
('kristus', '2021-05-21 09:33:31', 'SYS', 6),
('kisten', '2021-05-21 10:30:00', 'SYS', 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_bank`
--

CREATE TABLE `content_bank` (
  `bank` varchar(50) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `id_bank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_bank`
--

INSERT INTO `content_bank` (`bank`, `lastupdate`, `user_id`, `id_bank`) VALUES
('BNI', '2016-01-07 00:00:00', 'SYS', 2),
('BRI', '2016-01-07 00:00:00', 'SYS', 3),
('jatim', '2021-05-18 08:57:37', 'SYS', 4),
('MANDIRI', '2016-01-07 00:00:00', 'SYS', 5),
('papua', '2021-05-18 09:04:59', 'SYS', 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_default`
--

CREATE TABLE `content_default` (
  `nama` varchar(50) NOT NULL,
  `nilai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_default`
--

INSERT INTO `content_default` (`nama`, `nilai`) VALUES
('pph21_karyawan', '0'),
('pph21_perusahaan', '1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_departemen`
--

CREATE TABLE `content_departemen` (
  `departemen` varchar(100) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `id_departemen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_departemen`
--

INSERT INTO `content_departemen` (`departemen`, `lastupdate`, `user_id`, `id_departemen`) VALUES
('admina', '2021-05-21 11:11:10', 'SYS', 1),
('HRGA', '2016-11-20 04:30:05', 'Administrator', 2),
('kekonyolan', '2021-05-18 09:56:32', 'SYS', 3),
('PRODUCTION-ADMIN', '2016-11-20 04:22:26', 'Administrator', 4),
('PRODUCTION-DIRECT', '2016-11-20 04:21:53', 'Administrator', 5),
('SALES', '2016-11-20 04:22:05', 'Administrator', 6),
('SUPPLY-CHAIN', '2016-11-20 04:21:39', 'Administrator', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_grup`
--

CREATE TABLE `content_grup` (
  `grup` varchar(10) NOT NULL,
  `jenis` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `id_grup` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_grup`
--

INSERT INTO `content_grup` (`grup`, `jenis`, `lastupdate`, `user_id`, `id_grup`) VALUES
('A', 'SHIFT', '2016-01-07 00:00:00', 'SYS', 1),
('NS', 'SHIFT', '2021-05-21 12:05:56', 'SYS', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_insentive_kehadiran`
--

CREATE TABLE `content_insentive_kehadiran` (
  `id` int(11) NOT NULL,
  `nilai_insentive_kehadiran` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_insentive_kehadiran`
--

INSERT INTO `content_insentive_kehadiran` (`id`, `nilai_insentive_kehadiran`, `lastupdate`, `user_id`) VALUES
(1, '500000.00', '2016-09-03 05:26:44', 'Administrator');

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_jabatan`
--

CREATE TABLE `content_jabatan` (
  `jabatan` varchar(50) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `id_jabatan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_jabatan`
--

INSERT INTO `content_jabatan` (`jabatan`, `lastupdate`, `user_id`, `id_jabatan`) VALUES
('DIREKTUR', '2016-01-07 00:00:00', 'SYS', 2),
('GENERAL MANAGER', '2016-01-07 00:00:00', 'SYS', 3),
('GROUP LEADER', '2016-01-07 00:00:00', 'SYS', 4),
('LEADER', '2016-01-07 00:00:00', 'SYS', 5),
('MANAGER', '2016-01-07 00:00:00', 'SYS', 6),
('OPERATOR', '2016-01-07 00:00:00', 'SYS', 7),
('PRE-PRESS', '2017-06-15 03:45:31', 'Administrator', 8),
('PRODUKSI', '2016-09-03 00:00:00', 'SYS', 9),
('SALES', '2016-09-20 07:30:45', 'Administrator', 10),
('STAFF', '2016-01-07 00:00:00', 'SYS', 11),
('SUPERVISOR', '2016-01-07 00:00:00', 'SYS', 12);

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_jenis_gaji`
--

CREATE TABLE `content_jenis_gaji` (
  `jenis` varchar(50) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_jenis_gaji`
--

INSERT INTO `content_jenis_gaji` (`jenis`, `lastupdate`, `user_id`) VALUES
('Adjustment Min', '2016-01-16 00:00:00', 'SYS'),
('Adjustment Plus', '2016-01-16 00:00:00', 'SYS'),
('Cicilan Pinjaman', '2016-01-16 00:00:00', 'SYS'),
('Gaji Pokok', '2016-01-16 00:00:00', 'SYS'),
('Lembur', '2016-01-16 00:00:00', 'SYS'),
('Potongan', '2016-01-16 00:00:00', 'SYS'),
('Potongan Jamsostek', '2016-01-16 00:00:00', 'SYS'),
('Potongan Pph21', '2016-01-16 00:00:00', 'SYS'),
('Tunjangan Tetap', '2016-01-16 00:00:00', 'SYS'),
('Tunjangan Tidak Tetap', '2016-01-16 00:00:00', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_mata_uang`
--

CREATE TABLE `content_mata_uang` (
  `mata_uang` varchar(10) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `id_mata_uang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_mata_uang`
--

INSERT INTO `content_mata_uang` (`mata_uang`, `lastupdate`, `user_id`, `id_mata_uang`) VALUES
('idra', '2021-05-21 12:39:14', 'SYS', 2),
('USD', '2016-01-07 00:00:00', 'SYS', 3),
('YEN', '2016-01-07 00:00:00', 'SYS', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_periode_penggajian`
--

CREATE TABLE `content_periode_penggajian` (
  `periode_penggajian` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_periode_penggajian`
--

INSERT INTO `content_periode_penggajian` (`periode_penggajian`, `lastupdate`, `user_id`) VALUES
('BULANAN', '2016-01-07 00:00:00', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_pph21_metode`
--

CREATE TABLE `content_pph21_metode` (
  `pph21_metode` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_pph21_metode`
--

INSERT INTO `content_pph21_metode` (`pph21_metode`, `lastupdate`, `user_id`) VALUES
('GROSS', '2016-09-03 00:00:00', ''),
('NET', '2016-09-03 00:00:00', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_status_aktual`
--

CREATE TABLE `content_status_aktual` (
  `status_aktual` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `id_status_aktual` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_status_aktual`
--

INSERT INTO `content_status_aktual` (`status_aktual`, `lastupdate`, `user_id`, `id_status_aktual`) VALUES
('Ijin', '2021-05-21 02:05:32', 'SYS', 4),
('Lembur', '2016-01-07 00:00:00', 'sys', 5),
('nikah', '2021-05-18 10:40:48', 'SYS', 6),
('SAKIT', '2020-04-01 04:41:40', 'SYS', 7),
('Tanpa Keterangan', '2016-01-07 00:00:00', 'sys', 999);

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_status_karyawan`
--

CREATE TABLE `content_status_karyawan` (
  `status` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `id_status_karyawan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_status_karyawan`
--

INSERT INTO `content_status_karyawan` (`status`, `lastupdate`, `user_id`, `id_status_karyawan`) VALUES
('menetapa', '2021-05-21 02:19:56', 'SYS', 2),
('TETAP', '2016-01-07 00:00:00', 'SYS', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `content_status_perkawinan`
--

CREATE TABLE `content_status_perkawinan` (
  `status_perkawinan` varchar(5) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `id_status_perkawinan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `content_status_perkawinan`
--

INSERT INTO `content_status_perkawinan` (`status_perkawinan`, `lastupdate`, `user_id`, `id_status_perkawinan`) VALUES
('KOP', '2021-05-21 02:27:41', 'SYS', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_absen_grup_shift`
--

CREATE TABLE `master_absen_grup_shift` (
  `grup` varchar(10) NOT NULL,
  `shift` varchar(10) NOT NULL,
  `nama_shift` varchar(20) NOT NULL,
  `masuk` time NOT NULL,
  `pulang` time NOT NULL,
  `kode_hari_masuk` varchar(20) NOT NULL,
  `kode_hari_pulang` varchar(20) NOT NULL,
  `masuk_valid_awal` time NOT NULL,
  `masuk_valid_akhir` time NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_absen_grup_shift`
--

INSERT INTO `master_absen_grup_shift` (`grup`, `shift`, `nama_shift`, `masuk`, `pulang`, `kode_hari_masuk`, `kode_hari_pulang`, `masuk_valid_awal`, `masuk_valid_akhir`, `lastupdate`, `user_id`) VALUES
('A', '1', 'shift-1', '07:00:00', '15:00:00', 'SAMEDAY', 'SAMEDAY', '05:00:00', '07:00:59', '2021-05-18 03:14:14', 'SYS'),
('A', '2', 'shift-2', '15:00:00', '23:00:00', 'SAMEDAY', 'SAMEDAY', '13:00:00', '15:00:59', '2017-06-03 08:46:18', NULL),
('A', '3', 'shift-3', '23:00:00', '07:00:00', 'SAMEDAY', 'NEXTDAY', '21:00:00', '23:00:59', '2017-06-03 08:46:37', NULL),
('NS', '1', 'non-shift', '07:00:00', '17:00:00', 'SAMEDAY', 'SAMEDAY', '05:30:00', '07:00:00', '2020-03-31 04:18:37', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_absen_hari_besar`
--

CREATE TABLE `master_absen_hari_besar` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `user_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_absen_jadwal_karyawan`
--

CREATE TABLE `master_absen_jadwal_karyawan` (
  `nik` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `grup` varchar(10) NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `shift` varchar(10) NOT NULL,
  `status_jadwal` varchar(15) NOT NULL,
  `jadwal_masuk` datetime NOT NULL,
  `jadwal_pulang` datetime NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_absen_sakit_cuti`
--

CREATE TABLE `master_absen_sakit_cuti` (
  `no` int(11) NOT NULL,
  `nik` varchar(30) NOT NULL,
  `periode` varchar(10) NOT NULL,
  `dari_tanggal` date NOT NULL,
  `sampai_tanggal` date NOT NULL,
  `id_status_aktual` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data untuk tabel `master_absen_sakit_cuti`
--

INSERT INTO `master_absen_sakit_cuti` (`no`, `nik`, `periode`, `dari_tanggal`, `sampai_tanggal`, `id_status_aktual`, `status`, `keterangan`, `lastupdate`, `user_id`) VALUES
(3, '123213', '202003', '0000-00-00', '2021-05-05', 4, 'Ijin', 'asdad', '2021-05-24 09:34:51', 'SYS'),
(12, '123456789', '202003', '2021-05-13', '2021-05-05', 999, 'Tanpa Keterangan', 'a', '2021-05-24 09:32:30', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_gaji`
--

CREATE TABLE `master_gaji` (
  `kode_gaji` varchar(20) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `periode_hitung` varchar(20) NOT NULL,
  `rumus` varchar(500) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_gaji`
--

INSERT INTO `master_gaji` (`kode_gaji`, `keterangan`, `jenis`, `periode_hitung`, `rumus`, `lastupdate`, `user_id`) VALUES
('101', 'Gaji Pokok', 'Gaji Pokok', 'bulan', 'master_gaji_karyawan_periode.nilai_gaji', '2017-03-21 09:38:54', NULL),
('12321', 'asdsa', 'Adjustment Min', 'bulan', 'sad', '2021-05-22 07:20:38', 'SYS'),
('301', 'Tunjangan Transport', 'Tunjangan Tidak Tetap', 'hari', 'if(master_mesin_clear.status_aktual=\'Hadir\',master_gaji_karyawan_periode.nilai_gaji,0)', '2016-09-27 09:20:22', NULL),
('302', 'Tunjangan Kendaraan', 'Tunjangan Tetap', 'bulan', 'master_gaji_karyawan_periode.nilai_gaji', '2020-03-26 04:54:01', NULL),
('303', 'Tunjangan Makan', 'Tunjangan Tidak Tetap', 'hari', 'if(master_mesin_clear.status_aktual=\'Hadir\',master_gaji_karyawan_periode.nilai_gaji,0)', '2016-09-27 09:20:34', NULL),
('304', 'Dependence Allowance', 'Tunjangan Tetap', 'bulan', 'master_gaji_karyawan_periode.nilai_gaji', '2020-03-26 04:54:10', NULL),
('305', 'Sales Incentive', 'Tunjangan Tidak Tetap', 'bulan', 'master_gaji_karyawan_periode.nilai_gaji', '2016-09-22 03:51:31', NULL),
('307', 'Bonus Tahunan', 'Tunjangan Tidak Tetap', 'tahun', 'master_gaji_karyawan_periode.nilai_gaji', '2020-03-26 04:18:03', NULL),
('308', 'Bonus', 'Tunjangan Tidak Tetap', 'bulan', 'master_gaji_karyawan_periode.nilai_gaji', '2020-04-01 04:49:07', NULL),
('309', 'THR', 'Tunjangan Tidak Tetap', 'bulan', 'master_gaji_karyawan_periode.nilai_gaji', '2016-09-22 03:51:43', NULL),
('401', 'Potongan Koperasi', 'Cicilan Pinjaman', 'bulan', 'master_gaji_karyawan_periode.nilai_gaji', '2020-04-01 04:49:44', 'SYS'),
('402', 'Adjustment Plus', 'Adjustment Plus', 'bulan', 'master_gaji_karyawan_periode.nilai_gaji', '2017-02-28 05:27:35', NULL),
('403', 'Adjusment Minus', 'Adjusment Min', 'bulan', 'master_gaji_karyawan_periode.nilai_gaji', '2020-03-26 04:54:52', NULL),
('456', 'Sakit', 'Adjustment Min', 'bulan', 'asd', '2021-05-22 07:10:26', 'SYS'),
('4567', 'aaaaaaaaaaa', 'Cicilan Pinjaman', 'bulan', 'asderda', '2021-05-22 07:21:51', 'SYS'),
('708', 'Tiket Surabaya', 'Gaji Pokok', 'bulan', 'a', '2021-05-22 06:38:20', 'SYS'),
('78', 'cfgfvygy', 'Adjustment Plus', 'bulan', '98', '2021-05-22 07:25:37', 'SYS'),
('900', 'Nganuuuu', 'Adjustment Plus', 'bulan', 'q', '2021-05-22 07:15:25', 'SYS'),
('970', 'Sesuatu', 'Adjustment Min', 'hari', 'aaaaaaaa', '2021-05-22 07:19:17', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_gaji_karyawan`
--

CREATE TABLE `master_gaji_karyawan` (
  `nik` varchar(20) NOT NULL,
  `kode_gaji` varchar(20) NOT NULL,
  `nilai_gaji` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_gaji_karyawan`
--

INSERT INTO `master_gaji_karyawan` (`nik`, `kode_gaji`, `nilai_gaji`, `lastupdate`, `user_id`) VALUES
('123', '101', '0.00', '2021-05-18 12:15:13', NULL),
('123', '301', '0.00', '2021-05-18 12:15:13', NULL),
('123', '302', '0.00', '2021-05-18 12:15:13', NULL),
('123', '303', '0.00', '2021-05-18 12:15:13', NULL),
('123', '304', '0.00', '2021-05-18 12:15:13', NULL),
('123', '305', '0.00', '2021-05-18 12:15:13', NULL),
('123', '307', '20.00', '2021-05-18 12:15:13', NULL),
('123', '308', '2.00', '2021-05-18 12:15:13', NULL),
('123', '309', '2.00', '2021-05-18 12:15:14', NULL),
('123', '401', '200000.00', '2021-05-18 12:15:14', NULL),
('123', '402', '2.00', '2021-05-18 12:15:14', NULL),
('123', '403', '4.00', '2021-05-18 12:15:14', NULL),
('123213', '101', '600000.00', '2021-05-20 01:44:17', NULL),
('33333', '101', '0.00', '2021-05-18 12:20:25', NULL),
('33333', '301', '0.00', '2021-05-18 12:20:25', NULL),
('33333', '302', '0.00', '2021-05-18 12:20:25', NULL),
('33333', '303', '0.00', '2021-05-18 12:20:25', NULL),
('33333', '304', '0.00', '2021-05-18 12:20:25', NULL),
('33333', '305', '0.00', '2021-05-18 12:20:26', NULL),
('33333', '307', '2000.00', '2021-05-18 12:20:26', NULL),
('33333', '308', '3000000.00', '2021-05-18 12:20:26', NULL),
('33333', '309', '20000.00', '2021-05-18 12:20:26', NULL),
('33333', '401', '200000.00', '2021-05-18 12:20:26', NULL),
('33333', '402', '50000.00', '2021-05-18 12:20:26', NULL),
('33333', '403', '1000.00', '2021-05-18 12:20:26', NULL),
('345', '101', '0.00', '2021-05-18 12:16:17', NULL),
('345', '301', '300000.00', '2021-05-18 12:16:17', NULL),
('345', '302', '0.00', '2021-05-18 12:16:17', NULL),
('345', '303', '0.00', '2021-05-18 12:16:17', NULL),
('345', '304', '0.00', '2021-05-18 12:16:17', NULL),
('345', '305', '0.00', '2021-05-18 12:16:17', NULL),
('345', '307', '2000.00', '2021-05-18 12:16:17', NULL),
('345', '308', '3000000.00', '2021-05-18 12:16:17', NULL),
('345', '309', '20000.00', '2021-05-18 12:16:18', NULL),
('345', '401', '200000.00', '2021-05-18 12:16:18', NULL),
('345', '402', '0.00', '2021-05-18 12:16:18', NULL),
('345', '403', '0.00', '2021-05-18 12:16:18', NULL),
('43211', '101', '500.00', '2021-05-18 12:22:51', NULL),
('43211', '301', '300000.00', '2021-05-18 12:22:51', NULL),
('43211', '302', '0.00', '2021-05-18 12:22:51', NULL),
('43211', '303', '0.00', '2021-05-18 12:22:51', NULL),
('43211', '304', '0.00', '2021-05-18 12:22:51', NULL),
('43211', '305', '0.00', '2021-05-18 12:22:51', NULL),
('43211', '307', '2000.00', '2021-05-18 12:22:51', NULL),
('43211', '308', '3000000.00', '2021-05-18 12:22:52', NULL),
('43211', '309', '20000.00', '2021-05-18 12:22:52', NULL),
('43211', '401', '200000.00', '2021-05-18 12:22:52', NULL),
('43211', '402', '0.00', '2021-05-18 12:22:52', NULL),
('43211', '403', '1000.00', '2021-05-18 12:22:52', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_gaji_karyawan_periode`
--

CREATE TABLE `master_gaji_karyawan_periode` (
  `nik` varchar(20) NOT NULL,
  `kode_gaji` varchar(20) NOT NULL,
  `periode` varchar(10) NOT NULL,
  `nilai_gaji` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_gaji_karyawan_periode`
--

INSERT INTO `master_gaji_karyawan_periode` (`nik`, `kode_gaji`, `periode`, `nilai_gaji`, `lastupdate`, `user_id`) VALUES
('123213', '101', '202003', '5000.00', '2021-05-20 02:30:09', 'SYS'),
('321111', '101', '202003', '2000000.00', '2021-05-24 02:23:14', NULL),
('321111', '12321', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '301', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '302', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '303', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '304', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '305', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '307', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '308', '202003', '3000000.00', '2021-05-24 02:23:14', NULL),
('321111', '309', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '401', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '402', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '403', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '456', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '4567', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '708', '202003', '0.00', '2021-05-24 02:23:14', NULL),
('321111', '78', '202003', '0.00', '2021-05-24 02:23:15', NULL),
('321111', '900', '202003', '0.00', '2021-05-24 02:23:15', NULL),
('321111', '970', '202003', '0.00', '2021-05-24 02:23:15', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_jamsostek`
--

CREATE TABLE `master_jamsostek` (
  `id` int(11) NOT NULL,
  `nama_program` varchar(50) NOT NULL,
  `bagian_perusahaan` decimal(10,5) NOT NULL,
  `bagian_karyawan` decimal(10,5) NOT NULL,
  `maksimal_dasar` decimal(14,2) NOT NULL,
  `kode_gaji_potongan` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_jamsostek`
--

INSERT INTO `master_jamsostek` (`id`, `nama_program`, `bagian_perusahaan`, `bagian_karyawan`, `maksimal_dasar`, `kode_gaji_potongan`, `lastupdate`, `user_id`) VALUES
(1, 'BPJS Ketenagakerjaan', '0.04890', '0.02000', '0.00', '403', '2020-04-01 04:40:00', 'SYS'),
(2, 'BPJSKES', '0.04000', '0.01000', '8000000.00', '405', '2020-04-01 04:39:57', 'SYS'),
(3, 'JPN', '0.02000', '0.01000', '7703500.00', '403', '2020-04-01 04:39:53', 'SYS'),
(5, 'Kemanusiaan', '900.00000', '0.00000', '9.00', '303', '2021-05-20 01:55:01', 'SYS'),
(6, 'sada', '99999.99999', '0.00000', '0.00', '401', '2021-05-20 01:56:22', 'SYS'),
(7, 'ser', '99999.99999', '0.00000', '0.00', '101', '2021-05-20 02:18:59', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_jamsostek_komponen_dasar`
--

CREATE TABLE `master_jamsostek_komponen_dasar` (
  `kode_gaji` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_jamsostek_komponen_dasar`
--

INSERT INTO `master_jamsostek_komponen_dasar` (`kode_gaji`, `lastupdate`, `user_id`) VALUES
('101', '2016-01-07 00:00:00', 'SYS'),
('102', '2016-01-07 00:00:00', 'SYS'),
('201', '2016-01-07 00:00:00', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_karyawan`
--

CREATE TABLE `master_karyawan` (
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jenis_kelamin` varchar(10) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `agama` varchar(20) DEFAULT NULL,
  `status_perkawinan` varchar(5) DEFAULT NULL,
  `alamat` varchar(200) DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `departemen` varchar(100) DEFAULT NULL,
  `grup` varchar(10) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `tanggal_masuk` date NOT NULL,
  `akhir_kontrak` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `npwp` varchar(100) DEFAULT NULL,
  `bank` varchar(50) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `pemilik_rekening` varchar(100) DEFAULT NULL,
  `periode_penggajian` varchar(20) DEFAULT NULL,
  `mata_uang` varchar(10) DEFAULT NULL,
  `pph21_metode` varchar(10) NOT NULL,
  `bpjs_kesehatan` varchar(10) NOT NULL,
  `enroll` varchar(30) NOT NULL,
  `aktif` varchar(10) NOT NULL,
  `lastupdate` datetime DEFAULT NULL,
  `user_id` varchar(20) DEFAULT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_karyawan`
--

INSERT INTO `master_karyawan` (`nik`, `nama`, `jenis_kelamin`, `tanggal_lahir`, `agama`, `status_perkawinan`, `alamat`, `telepon`, `email`, `departemen`, `grup`, `jabatan`, `tanggal_masuk`, `akhir_kontrak`, `status`, `npwp`, `bank`, `no_rekening`, `pemilik_rekening`, `periode_penggajian`, `mata_uang`, `pph21_metode`, `bpjs_kesehatan`, `enroll`, `aktif`, `lastupdate`, `user_id`, `foto`) VALUES
('123213', 'tes', 'Perempuan', '0000-00-00', 'BUDHA', 'K0', 'asdad', '12313', 'a@mail.com', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'BULANAN', 'IDR', 'GROSS', 'Ya', '123', 'Active', '2021-05-20 04:39:22', 'SYS', 'karyawan-123213-20210523124754.jpg'),
('321111', 'Anto', 'Laki-laki', '2021-05-18', 'KONGHUCU', 'KOP', 'asdad', '13213123', 'adsdsad@gmail.com', 'kekonyolan', 'NS', 'OPERATOR', '2021-05-12', '2021-05-25', 'TETAP', '1231313', 'BRI', '13213', 'Anto', 'BULANAN', 'USD', 'GROSS', 'Ya', '1231', 'Active', '2021-05-24 10:08:54', 'SYS', 'user-image-default.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_mesin`
--

CREATE TABLE `master_mesin` (
  `no` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `enroll` varchar(30) NOT NULL,
  `periode` varchar(10) NOT NULL,
  `status_aktual` varchar(20) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `waktu` datetime NOT NULL,
  `jam` time NOT NULL,
  `kondisi` varchar(10) NOT NULL,
  `shift` varchar(10) NOT NULL,
  `kondisi_baru` varchar(20) NOT NULL,
  `status` varchar(10) NOT NULL,
  `operasi` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_mesin`
--

INSERT INTO `master_mesin` (`no`, `tanggal`, `enroll`, `periode`, `status_aktual`, `keterangan`, `waktu`, `jam`, `kondisi`, `shift`, `kondisi_baru`, `status`, `operasi`) VALUES
(344, '2020-03-09', '321', '202003', 'Hadir', '', '2020-03-09 06:30:00', '06:30:00', 'CIn', '', 'a_1', 'OK', 'b_1'),
(345, '2020-03-09', '123', '202003', 'Hadir', '', '2020-03-09 16:03:00', '16:03:00', 'COut', '', 'a_2', 'OK', 'b_2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_mesin_clear`
--

CREATE TABLE `master_mesin_clear` (
  `enroll` varchar(30) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `periode` varchar(10) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `status_perkawinan` varchar(5) NOT NULL,
  `departemen` varchar(100) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `status_kerja` varchar(20) NOT NULL,
  `shift` varchar(15) NOT NULL,
  `status_aktual` varchar(20) NOT NULL,
  `keterangan` varchar(30) NOT NULL,
  `jam_masuk` datetime NOT NULL,
  `jam_pulang` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_mesin_clear`
--

INSERT INTO `master_mesin_clear` (`enroll`, `tanggal`, `jam`, `periode`, `nik`, `nama`, `status_perkawinan`, `departemen`, `jabatan`, `status_kerja`, `shift`, `status_aktual`, `keterangan`, `jam_masuk`, `jam_pulang`) VALUES
('321', '2020-03-09', '06:30:00', '202003', '1231312', 'qwasdasd', 'K3', 'HRGA', 'DIREKTUR', 'KONTRAK', 'shift-1', 'Hadir', '', '2020-03-09 06:30:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_overtime`
--

CREATE TABLE `master_overtime` (
  `jam` decimal(14,1) NOT NULL,
  `index_hari_kerja` decimal(14,1) NOT NULL,
  `index_hari_libur` decimal(14,1) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_overtime`
--

INSERT INTO `master_overtime` (`jam`, `index_hari_kerja`, `index_hari_libur`, `lastupdate`, `user_id`) VALUES
('1.0', '1.5', '2.0', '2020-04-01 04:40:13', 'SYS'),
('2.0', '3.5', '4.0', '2020-04-01 04:47:07', 'SYS'),
('8.0', '15.5', '17.0', '2020-04-01 04:40:08', 'SYS'),
('8.5', '16.5', '18.5', '2016-11-24 08:42:06', 'Administrator'),
('9.0', '17.5', '36.0', '2016-09-28 01:42:42', 'Administrator'),
('12.0', '1200.0', '1.0', '2021-05-18 02:09:01', 'SYS'),
('32.0', '1.0', '1.0', '2021-05-20 11:30:37', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_periode_penggajian`
--

CREATE TABLE `master_periode_penggajian` (
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `mulai` int(10) UNSIGNED NOT NULL,
  `selesai` int(10) UNSIGNED NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_periode_penggajian`
--

INSERT INTO `master_periode_penggajian` (`periode_penggajian`, `segmen`, `mulai`, `selesai`, `lastupdate`, `user_id`) VALUES
('BULANAN', 1, 23, 22, '2016-01-16 00:00:00', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_periode_penggajian_komponen`
--

CREATE TABLE `master_periode_penggajian_komponen` (
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `kode_gaji` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_periode_penggajian_komponen`
--

INSERT INTO `master_periode_penggajian_komponen` (`periode_penggajian`, `segmen`, `kode_gaji`, `lastupdate`, `user_id`) VALUES
('BULANAN', 1, '0989', '2021-05-22 07:27:34', 'SYS'),
('BULANAN', 1, '301', '2016-09-02 04:45:38', 'SYS'),
('BULANAN', 1, '456', '2021-05-22 07:10:26', 'SYS'),
('BULANAN', 1, '4567', '2021-05-22 07:21:51', 'SYS'),
('BULANAN', 1, '708', '2021-05-22 06:38:20', 'SYS'),
('BULANAN', 1, '78', '2021-05-22 07:25:37', 'SYS'),
('BULANAN', 1, '900', '2021-05-22 07:15:25', 'SYS'),
('BULANAN', 1, '970', '2021-05-22 07:19:17', 'SYS'),
('BULANAN', 2, '101', '2021-05-21 01:40:46', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_perusahaan`
--

CREATE TABLE `master_perusahaan` (
  `id` int(11) NOT NULL,
  `nama_perusahaan` varchar(300) NOT NULL,
  `alamat_perusahaan` text NOT NULL,
  `logo_perusahaan` varchar(100) NOT NULL,
  `status` enum('aktif','tidak_aktif') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_perusahaan`
--

INSERT INTO `master_perusahaan` (`id`, `nama_perusahaan`, `alamat_perusahaan`, `logo_perusahaan`, `status`) VALUES
(2, 'Nama Perusahaan', 'Jl. Perusahaan', 'logo.png', 'aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_pph21_komponen`
--

CREATE TABLE `master_pph21_komponen` (
  `kode_gaji` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_pph21_komponen`
--

INSERT INTO `master_pph21_komponen` (`kode_gaji`, `lastupdate`, `user_id`) VALUES
('101', '2014-11-11 13:14:26', 'manager'),
('302', '2020-03-26 00:00:00', 'manager'),
('304', '2020-03-26 00:00:00', 'manager');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_pph21_metode`
--

CREATE TABLE `master_pph21_metode` (
  `periode` varchar(10) NOT NULL,
  `pph21_metode` varchar(20) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_pph21_metode`
--

INSERT INTO `master_pph21_metode` (`periode`, `pph21_metode`, `lastupdate`, `user_id`) VALUES
('201608', 'GROSS-UP', '2016-09-09 07:59:01', 'Administrator'),
('201609', 'GROSS-UP', '2016-09-22 04:28:59', 'Administrator'),
('201610', 'GROSS-UP', '2016-10-31 03:10:49', 'Administrator');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_pph21_ptkp`
--

CREATE TABLE `master_pph21_ptkp` (
  `status_perkawinan` varchar(5) NOT NULL,
  `nilai_ptkp` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_pph21_ptkp`
--

INSERT INTO `master_pph21_ptkp` (`status_perkawinan`, `nilai_ptkp`, `lastupdate`, `user_id`) VALUES
('K0', '58500000.00', '2015-11-12 14:16:29', 'FIN'),
('K1', '63000000.00', '2015-11-12 14:16:55', 'FIN'),
('K2', '67500000.00', '2015-11-12 14:17:05', 'FIN'),
('K3', '72000000.00', '2015-11-12 14:17:23', 'FIN'),
('TK0', '54000000.00', '2015-11-12 14:17:34', 'FIN'),
('TK1', '58500000.00', '2015-11-12 14:17:46', 'FIN'),
('TK2', '63000000.00', '2015-11-12 14:18:03', 'FIN'),
('TK3', '67500000.00', '2015-11-12 14:18:12', 'FIN');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_pph21_tarif`
--

CREATE TABLE `master_pph21_tarif` (
  `kode_tarif` int(10) UNSIGNED NOT NULL,
  `batas_bawah` decimal(14,2) NOT NULL,
  `batas_atas` decimal(14,2) NOT NULL,
  `tarif` decimal(10,5) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_pph21_tarif`
--

INSERT INTO `master_pph21_tarif` (`kode_tarif`, `batas_bawah`, `batas_atas`, `tarif`, `lastupdate`, `user_id`) VALUES
(1, '0.00', '0.00', '0.00000', '2021-05-20 12:05:02', 'SYS'),
(2, '50000001.00', '250000000.00', '0.15000', '2020-04-01 04:52:06', 'SYS'),
(3, '250000001.00', '500000000.00', '0.25000', '2014-11-11 00:00:00', 'manager'),
(4, '500000001.00', '9999999999.00', '0.30000', '2014-11-11 00:00:00', 'manager'),
(5, '0.00', '90000.00', '0.00000', '2021-05-20 02:25:01', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_shift`
--

CREATE TABLE `master_shift` (
  `shift` varchar(10) NOT NULL,
  `masuk` time NOT NULL,
  `pulang` time NOT NULL,
  `masuk_valid_awal` time NOT NULL,
  `masuk_valid_akhir` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_total_gaji`
--

CREATE TABLE `master_total_gaji` (
  `id_total_gaji` int(11) NOT NULL,
  `periode` varchar(100) NOT NULL,
  `total` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `master_total_gaji`
--

INSERT INTO `master_total_gaji` (`id_total_gaji`, `periode`, `total`) VALUES
(0, '202003', 5005000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `master_tunjangan_anak`
--

CREATE TABLE `master_tunjangan_anak` (
  `status_perkawinan` varchar(5) NOT NULL,
  `nilai` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `master_tunjangan_anak`
--

INSERT INTO `master_tunjangan_anak` (`status_perkawinan`, `nilai`, `lastupdate`, `user_id`) VALUES
('K0', '0.00', '2016-09-23 01:25:42', 'Administrator'),
('K1', '25000.00', '2016-09-03 00:00:00', 'SYS'),
('K2', '35000.00', '2016-09-03 00:00:00', 'SYS'),
('K3', '50000.00', '2016-09-03 00:00:00', 'SYS'),
('K90', '3000.00', '2021-05-18 01:07:16', 'SYS'),
('KAWIN', '900000.00', '2021-05-20 01:49:07', 'SYS'),
('TK0', '0.00', '2017-01-26 08:09:26', 'Administrator'),
('TK1', '25000.00', '2017-01-26 08:09:41', 'Administrator'),
('TK2', '35000.00', '2017-01-26 08:09:54', 'Administrator'),
('TK3', '50000.00', '2017-01-26 08:10:06', 'Administrator');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(20) NOT NULL,
  `u_name` varchar(255) DEFAULT NULL,
  `pass_word` varchar(255) DEFAULT NULL,
  `text_pass` varchar(10) DEFAULT NULL,
  `aktif` enum('Y','N') DEFAULT NULL,
  `level` varchar(10) DEFAULT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `foto` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `u_name`, `pass_word`, `text_pass`, `aktif`, `level`, `lastupdate`, `user_id`, `foto`) VALUES
(14, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'Y', 'SYS', '0000-00-00 00:00:00', '', 'user-admin-20210525062626.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_cicilan`
--

CREATE TABLE `trans_cicilan` (
  `no` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `cicilan` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_periode`
--

CREATE TABLE `trans_periode` (
  `periode` varchar(10) NOT NULL,
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `mulai` date NOT NULL,
  `selesai` date NOT NULL,
  `thr` varchar(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `id_periode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_periode`
--

INSERT INTO `trans_periode` (`periode`, `periode_penggajian`, `segmen`, `mulai`, `selesai`, `thr`, `status`, `lastupdate`, `user_id`, `id_periode`) VALUES
('202003', 'BULANAN', 1, '2020-02-16', '2020-03-15', 'Tidak', 'OPEN', '2020-04-08 06:43:57', 'SYS', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_periode_gaji_karyawan`
--

CREATE TABLE `trans_periode_gaji_karyawan` (
  `periode` varchar(10) NOT NULL,
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `status_perkawinan` varchar(5) NOT NULL,
  `departemen` varchar(100) NOT NULL,
  `grup` varchar(10) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `status_kerja` varchar(20) NOT NULL,
  `npwp` varchar(100) DEFAULT NULL,
  `bank` varchar(50) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `pemilik_rekening` varchar(100) DEFAULT NULL,
  `mata_uang` varchar(10) DEFAULT NULL,
  `kode_gaji` varchar(20) NOT NULL,
  `keterangan_gaji` varchar(100) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `nilai` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_periode_gaji_karyawan`
--

INSERT INTO `trans_periode_gaji_karyawan` (`periode`, `periode_penggajian`, `segmen`, `nik`, `nama`, `status_perkawinan`, `departemen`, `grup`, `jabatan`, `tanggal_masuk`, `status_kerja`, `npwp`, `bank`, `no_rekening`, `pemilik_rekening`, `mata_uang`, `kode_gaji`, `keterangan_gaji`, `jenis`, `nilai`, `lastupdate`, `user_id`) VALUES
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '101', 'Gaji Pokok', 'Gaji Pokok', '5000.00', '2021-05-20 14:45:59', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '302', 'Tunjangan Kendaraan', 'Tunjangan Tetap', '0.00', '2021-05-20 14:45:59', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '304', 'Dependence Allowance', 'Tunjangan Tetap', '0.00', '2021-05-20 14:45:59', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '305', 'Sales Incentive', 'Tunjangan Tidak Tetap', '0.00', '2021-05-20 14:45:59', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '307', 'Bonus Tahunan', 'Tunjangan Tidak Tetap', '0.00', '2021-05-20 14:45:59', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '308', 'Bonus', 'Tunjangan Tidak Tetap', '0.00', '2021-05-20 14:45:59', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '309', 'THR', 'Tunjangan Tidak Tetap', '0.00', '2021-05-20 14:45:59', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '401', 'Potongan Koperasi', 'Cicilan Pinjaman', '0.00', '2021-05-20 14:45:59', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '402', 'Adjustment Plus', 'Adjustment Plus', '0.00', '2021-05-20 14:45:59', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'HRGA', 'NS', 'ASS. MANAGER', '0000-00-00', 'KONTRAK', '1231313', 'BRI', '13213', 'tes', 'IDR', '403', 'Adjusment Minus', 'Adjusment Min', '0.00', '2021-05-20 14:45:59', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_periode_jamsostek`
--

CREATE TABLE `trans_periode_jamsostek` (
  `periode` varchar(10) NOT NULL,
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `id` int(11) NOT NULL,
  `nama_program` varchar(50) NOT NULL,
  `gaji_dasar` decimal(14,2) NOT NULL,
  `maksimal_gaji_dasar` decimal(14,2) NOT NULL,
  `bagian_perusahaan` decimal(10,5) NOT NULL,
  `bagian_karyawan` decimal(10,5) NOT NULL,
  `bpjs_kesehatan` varchar(10) NOT NULL,
  `nilai_perusahaan` decimal(14,2) NOT NULL,
  `nilai_karyawan` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_periode_jamsostek`
--

INSERT INTO `trans_periode_jamsostek` (`periode`, `periode_penggajian`, `segmen`, `nik`, `nama`, `id`, `nama_program`, `gaji_dasar`, `maksimal_gaji_dasar`, `bagian_perusahaan`, `bagian_karyawan`, `bpjs_kesehatan`, `nilai_perusahaan`, `nilai_karyawan`, `lastupdate`, `user_id`) VALUES
('202003', 'BULANAN', 1, '123213', 'tes', 1, 'BPJS Ketenagakerjaan', '5000.00', '0.00', '0.04890', '0.02000', 'Ya', '244.50', '100.00', '2021-05-20 14:46:02', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 2, 'BPJSKES', '5000.00', '8000000.00', '0.04000', '0.01000', 'Ya', '200.00', '50.00', '2021-05-20 14:46:02', 'SYS'),
('202003', 'BULANAN', 1, '123213', 'tes', 3, 'JPN', '5000.00', '7703500.00', '0.02000', '0.01000', 'Ya', '100.00', '50.00', '2021-05-20 14:46:02', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_periode_pph21`
--

CREATE TABLE `trans_periode_pph21` (
  `periode` varchar(10) NOT NULL,
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `status_perkawinan` varchar(5) NOT NULL,
  `status_kerja` varchar(20) NOT NULL,
  `bank` varchar(50) NOT NULL,
  `no_rekening` varchar(100) NOT NULL,
  `npwp` varchar(50) NOT NULL,
  `departemen` varchar(50) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `periode_thr` varchar(10) NOT NULL,
  `over_time_index` decimal(16,2) NOT NULL,
  `kehadiran` decimal(14,0) NOT NULL,
  `ketidakhadiran` decimal(14,0) NOT NULL,
  `potongan_ketidakhadiran` decimal(14,2) NOT NULL,
  `sakit` decimal(14,0) NOT NULL,
  `cuti` decimal(14,0) NOT NULL,
  `terlambat` decimal(14,0) NOT NULL,
  `kct` decimal(14,0) NOT NULL,
  `lama_kerja` decimal(16,0) NOT NULL,
  `base` decimal(16,2) NOT NULL,
  `tunjangan_transport` decimal(16,2) NOT NULL,
  `tunjangan_kendaraan` decimal(16,2) NOT NULL,
  `tunjangan_makan` decimal(16,2) NOT NULL,
  `tunjangan_anak` decimal(14,2) NOT NULL,
  `sales_incentive` decimal(16,2) NOT NULL,
  `bonus` decimal(14,2) DEFAULT 0.00,
  `adjustment_plus` decimal(16,2) NOT NULL,
  `jkm_perusahaan` decimal(16,2) NOT NULL,
  `jkk_perusahaan` decimal(16,2) NOT NULL,
  `jht_perusahaan` decimal(16,2) NOT NULL,
  `jkn_perusahaan` decimal(16,2) NOT NULL,
  `jpn_perusahaan` decimal(16,2) NOT NULL,
  `over_time` decimal(14,2) NOT NULL,
  `penghasilan_kotor` decimal(16,2) NOT NULL,
  `jht_karyawan` decimal(16,2) NOT NULL,
  `jpn_karyawan` decimal(16,2) NOT NULL,
  `bpjs_kesehatan_karyawan` decimal(16,2) NOT NULL,
  `biaya_jabatan` decimal(16,2) NOT NULL,
  `total_pengurang` decimal(16,2) NOT NULL,
  `netto` decimal(16,2) NOT NULL,
  `netto_setahun` decimal(16,2) NOT NULL,
  `ptkp_gaji` decimal(14,2) DEFAULT 0.00,
  `penghasilan_kena_pajak` decimal(16,2) NOT NULL,
  `tunjangan_jamsostek` decimal(14,2) DEFAULT 0.00,
  `adjustment_minus` decimal(16,2) NOT NULL,
  `potongan_koperasi` decimal(16,2) NOT NULL,
  `pph21_gaji_setahun` decimal(14,2) DEFAULT 0.00,
  `pph21_gaji_sebulan` decimal(14,2) DEFAULT NULL,
  `tambahan_non_npwp` decimal(14,2) DEFAULT 0.00,
  `metode_pph21` varchar(20) NOT NULL,
  `pph21_nett` decimal(14,2) DEFAULT 0.00,
  `thp` decimal(16,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_periode_pph21`
--

INSERT INTO `trans_periode_pph21` (`periode`, `periode_penggajian`, `segmen`, `nik`, `nama`, `status_perkawinan`, `status_kerja`, `bank`, `no_rekening`, `npwp`, `departemen`, `jabatan`, `tanggal_masuk`, `periode_thr`, `over_time_index`, `kehadiran`, `ketidakhadiran`, `potongan_ketidakhadiran`, `sakit`, `cuti`, `terlambat`, `kct`, `lama_kerja`, `base`, `tunjangan_transport`, `tunjangan_kendaraan`, `tunjangan_makan`, `tunjangan_anak`, `sales_incentive`, `bonus`, `adjustment_plus`, `jkm_perusahaan`, `jkk_perusahaan`, `jht_perusahaan`, `jkn_perusahaan`, `jpn_perusahaan`, `over_time`, `penghasilan_kotor`, `jht_karyawan`, `jpn_karyawan`, `bpjs_kesehatan_karyawan`, `biaya_jabatan`, `total_pengurang`, `netto`, `netto_setahun`, `ptkp_gaji`, `penghasilan_kena_pajak`, `tunjangan_jamsostek`, `adjustment_minus`, `potongan_koperasi`, `pph21_gaji_setahun`, `pph21_gaji_sebulan`, `tambahan_non_npwp`, `metode_pph21`, `pph21_nett`, `thp`, `lastupdate`, `user_id`) VALUES
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'KONTRAK', 'BRI', '13213', '1231313', 'HRGA', 'ASS. MANAGER', '0000-00-00', 'Tidak', '0.00', '0', '0', '0.00', '0', '0', '0', '0', '0', '5000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '15.00', '44.50', '185.00', '200.00', '100.00', '0.00', '5544.50', '100.00', '50.00', '50.00', '250.00', '400.00', '5144.50', '61734.00', '58500000.00', '-58438266.00', '544.50', '0.00', '0.00', '0.00', '0.00', '0.00', 'GROSS', '0.00', '4800.00', '2021-05-20 14:46:00', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_periode_pph21_bonus`
--

CREATE TABLE `trans_periode_pph21_bonus` (
  `periode` varchar(10) NOT NULL,
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `status_perkawinan` varchar(5) NOT NULL,
  `status_kerja` varchar(20) NOT NULL,
  `bank` varchar(50) NOT NULL,
  `no_rekening` varchar(100) NOT NULL,
  `npwp` varchar(50) NOT NULL,
  `departemen` varchar(50) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `over_time_index` decimal(16,2) NOT NULL,
  `over_time` decimal(14,2) NOT NULL,
  `kehadiran` decimal(14,0) NOT NULL,
  `ketidakhadiran` decimal(14,0) NOT NULL,
  `potongan_ketidakhadiran` decimal(14,2) NOT NULL,
  `sakit` decimal(14,0) NOT NULL,
  `cuti` decimal(14,0) NOT NULL,
  `terlambat` decimal(14,0) NOT NULL,
  `kct` decimal(14,0) NOT NULL,
  `base` decimal(16,2) NOT NULL,
  `bonus_tahunan` decimal(16,2) NOT NULL,
  `tunjangan_transport` decimal(16,2) NOT NULL,
  `tunjangan_kendaraan` decimal(16,2) NOT NULL,
  `tunjangan_makan` decimal(16,2) NOT NULL,
  `tunjangan_anak` decimal(14,2) NOT NULL,
  `sales_incentive` decimal(16,2) NOT NULL,
  `bonus` decimal(14,2) DEFAULT 0.00,
  `adjustment_plus` decimal(16,2) NOT NULL,
  `jkm_perusahaan` decimal(16,2) NOT NULL,
  `jkk_perusahaan` decimal(16,2) NOT NULL,
  `jht_perusahaan` decimal(16,2) NOT NULL,
  `jkn_perusahaan` decimal(16,2) NOT NULL,
  `jpn_perusahaan` decimal(16,2) NOT NULL,
  `penghasilan_kotor` decimal(16,2) NOT NULL,
  `jht_karyawan` decimal(16,2) NOT NULL,
  `jpn_karyawan` decimal(16,2) NOT NULL,
  `bpjs_kesehatan_karyawan` decimal(16,2) NOT NULL,
  `biaya_jabatan` decimal(16,2) NOT NULL,
  `total_pengurang` decimal(16,2) NOT NULL,
  `netto` decimal(16,2) NOT NULL,
  `netto_setahun` decimal(16,2) NOT NULL,
  `ptkp_gaji` decimal(14,2) DEFAULT 0.00,
  `penghasilan_kena_pajak` decimal(16,2) NOT NULL,
  `tunjangan_jamsostek` decimal(14,2) DEFAULT 0.00,
  `adjustment_minus` decimal(16,2) NOT NULL,
  `potongan_koperasi` decimal(16,2) NOT NULL,
  `pph21_gaji_setahun` decimal(14,2) DEFAULT 0.00,
  `pph21_gaji_sebulan` decimal(14,2) DEFAULT NULL,
  `tambahan_non_npwp` decimal(14,2) DEFAULT 0.00,
  `metode_pph21` varchar(20) NOT NULL,
  `pph21_nett` decimal(14,2) DEFAULT 0.00,
  `pph21_gaji` decimal(16,2) NOT NULL,
  `pph21_bonus` decimal(16,2) NOT NULL,
  `thp` decimal(16,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_periode_pph21_bonus`
--

INSERT INTO `trans_periode_pph21_bonus` (`periode`, `periode_penggajian`, `segmen`, `nik`, `nama`, `status_perkawinan`, `status_kerja`, `bank`, `no_rekening`, `npwp`, `departemen`, `jabatan`, `tanggal_masuk`, `over_time_index`, `over_time`, `kehadiran`, `ketidakhadiran`, `potongan_ketidakhadiran`, `sakit`, `cuti`, `terlambat`, `kct`, `base`, `bonus_tahunan`, `tunjangan_transport`, `tunjangan_kendaraan`, `tunjangan_makan`, `tunjangan_anak`, `sales_incentive`, `bonus`, `adjustment_plus`, `jkm_perusahaan`, `jkk_perusahaan`, `jht_perusahaan`, `jkn_perusahaan`, `jpn_perusahaan`, `penghasilan_kotor`, `jht_karyawan`, `jpn_karyawan`, `bpjs_kesehatan_karyawan`, `biaya_jabatan`, `total_pengurang`, `netto`, `netto_setahun`, `ptkp_gaji`, `penghasilan_kena_pajak`, `tunjangan_jamsostek`, `adjustment_minus`, `potongan_koperasi`, `pph21_gaji_setahun`, `pph21_gaji_sebulan`, `tambahan_non_npwp`, `metode_pph21`, `pph21_nett`, `pph21_gaji`, `pph21_bonus`, `thp`, `lastupdate`, `user_id`) VALUES
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'KONTRAK', 'BRI', '13213', '1231313', 'HRGA', 'ASS. MANAGER', '0000-00-00', '0.00', '0.00', '0', '0', '0.00', '0', '0', '0', '0', '5000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '15.00', '44.50', '185.00', '200.00', '100.00', '5544.50', '100.00', '50.00', '50.00', '250.00', '400.00', '5144.50', '61734.00', '58500000.00', '-58438266.00', '544.50', '0.00', '0.00', '0.00', '0.00', '0.00', 'GROSS', '0.00', '0.00', '0.00', '0.00', '2021-05-20 14:46:01', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_periode_pph21_tarif`
--

CREATE TABLE `trans_periode_pph21_tarif` (
  `periode` varchar(10) NOT NULL,
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `kode_tarif` int(10) UNSIGNED NOT NULL,
  `nilai_gaji` decimal(14,2) NOT NULL,
  `tarif` decimal(10,5) NOT NULL,
  `nilai_pph21` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_periode_pph21_tarif`
--

INSERT INTO `trans_periode_pph21_tarif` (`periode`, `periode_penggajian`, `segmen`, `jenis`, `nik`, `kode_tarif`, `nilai_gaji`, `tarif`, `nilai_pph21`, `lastupdate`, `user_id`) VALUES
('202003', 'BULANAN', 1, 'gaji', '123213', 1, '0.00', '0.05000', '0.00', '2021-05-20 14:46:00', 'SYS'),
('202003', 'BULANAN', 1, 'gaji', '123213', 2, '0.00', '0.15000', '0.00', '2021-05-20 14:46:00', 'SYS'),
('202003', 'BULANAN', 1, 'gaji', '123213', 3, '0.00', '0.25000', '0.00', '2021-05-20 14:46:00', 'SYS'),
('202003', 'BULANAN', 1, 'gaji', '123213', 4, '0.00', '0.30000', '0.00', '2021-05-20 14:46:00', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_periode_pph21_tarif_bonus`
--

CREATE TABLE `trans_periode_pph21_tarif_bonus` (
  `periode` varchar(10) NOT NULL,
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `kode_tarif` int(10) UNSIGNED NOT NULL,
  `nilai_gaji` decimal(14,2) NOT NULL,
  `tarif` decimal(10,5) NOT NULL,
  `nilai_pph21` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_periode_pph21_tarif_bonus`
--

INSERT INTO `trans_periode_pph21_tarif_bonus` (`periode`, `periode_penggajian`, `segmen`, `jenis`, `nik`, `kode_tarif`, `nilai_gaji`, `tarif`, `nilai_pph21`, `lastupdate`, `user_id`) VALUES
('202003', 'BULANAN', 1, 'gaji', '123213', 1, '0.00', '0.05000', '0.00', '2021-05-20 14:46:01', 'SYS'),
('202003', 'BULANAN', 1, 'gaji', '123213', 2, '0.00', '0.15000', '0.00', '2021-05-20 14:46:01', 'SYS'),
('202003', 'BULANAN', 1, 'gaji', '123213', 3, '0.00', '0.25000', '0.00', '2021-05-20 14:46:01', 'SYS'),
('202003', 'BULANAN', 1, 'gaji', '123213', 4, '0.00', '0.30000', '0.00', '2021-05-20 14:46:01', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_periode_pph21_tarif_thr`
--

CREATE TABLE `trans_periode_pph21_tarif_thr` (
  `periode` varchar(10) NOT NULL,
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `kode_tarif` int(10) UNSIGNED NOT NULL,
  `nilai_gaji` decimal(14,2) NOT NULL,
  `tarif` decimal(10,5) NOT NULL,
  `nilai_pph21` decimal(14,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_periode_pph21_tarif_thr`
--

INSERT INTO `trans_periode_pph21_tarif_thr` (`periode`, `periode_penggajian`, `segmen`, `jenis`, `nik`, `kode_tarif`, `nilai_gaji`, `tarif`, `nilai_pph21`, `lastupdate`, `user_id`) VALUES
('202003', 'BULANAN', 1, 'gaji', '123213', 1, '0.00', '0.05000', '0.00', '2021-05-20 14:46:01', 'SYS'),
('202003', 'BULANAN', 1, 'gaji', '123213', 2, '0.00', '0.15000', '0.00', '2021-05-20 14:46:01', 'SYS'),
('202003', 'BULANAN', 1, 'gaji', '123213', 3, '0.00', '0.25000', '0.00', '2021-05-20 14:46:01', 'SYS'),
('202003', 'BULANAN', 1, 'gaji', '123213', 4, '0.00', '0.30000', '0.00', '2021-05-20 14:46:01', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_periode_pph21_thr`
--

CREATE TABLE `trans_periode_pph21_thr` (
  `periode` varchar(10) NOT NULL,
  `periode_penggajian` varchar(10) NOT NULL,
  `segmen` int(10) UNSIGNED NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `status_perkawinan` varchar(5) NOT NULL,
  `status_kerja` varchar(20) NOT NULL,
  `bank` varchar(50) NOT NULL,
  `no_rekening` varchar(100) NOT NULL,
  `npwp` varchar(50) NOT NULL,
  `departemen` varchar(50) NOT NULL,
  `jabatan` varchar(50) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `periode_thr` varchar(10) NOT NULL,
  `over_time_index` decimal(16,2) NOT NULL,
  `kehadiran` decimal(14,0) NOT NULL,
  `ketidakhadiran` decimal(14,0) NOT NULL,
  `potongan_ketidakhadiran` decimal(14,2) NOT NULL,
  `sakit` decimal(14,0) NOT NULL,
  `cuti` decimal(14,0) NOT NULL,
  `terlambat` decimal(14,0) NOT NULL,
  `kct` decimal(14,0) NOT NULL,
  `lama_kerja` decimal(16,0) NOT NULL,
  `base` decimal(16,2) NOT NULL,
  `thr` decimal(16,2) NOT NULL,
  `tunjangan_transport` decimal(16,2) NOT NULL,
  `tunjangan_kendaraan` decimal(16,2) NOT NULL,
  `tunjangan_makan` decimal(16,2) NOT NULL,
  `tunjangan_anak` decimal(14,2) NOT NULL,
  `sales_incentive` decimal(16,2) NOT NULL,
  `bonus` decimal(14,2) DEFAULT 0.00,
  `adjustment_plus` decimal(16,2) NOT NULL,
  `jkm_perusahaan` decimal(16,2) NOT NULL,
  `jkk_perusahaan` decimal(16,2) NOT NULL,
  `jht_perusahaan` decimal(16,2) NOT NULL,
  `jkn_perusahaan` decimal(16,2) NOT NULL,
  `jpn_perusahaan` decimal(16,2) NOT NULL,
  `over_time` decimal(14,2) NOT NULL,
  `penghasilan_kotor` decimal(16,2) NOT NULL,
  `jht_karyawan` decimal(16,2) NOT NULL,
  `jpn_karyawan` decimal(16,2) NOT NULL,
  `bpjs_kesehatan_karyawan` decimal(16,2) NOT NULL,
  `biaya_jabatan` decimal(16,2) NOT NULL,
  `total_pengurang` decimal(16,2) NOT NULL,
  `netto` decimal(16,2) NOT NULL,
  `netto_setahun` decimal(16,2) NOT NULL,
  `ptkp_gaji` decimal(14,2) DEFAULT 0.00,
  `penghasilan_kena_pajak` decimal(16,2) NOT NULL,
  `tunjangan_jamsostek` decimal(14,2) DEFAULT 0.00,
  `adjustment_minus` decimal(16,2) NOT NULL,
  `potongan_koperasi` decimal(16,2) NOT NULL,
  `pph21_gaji_setahun` decimal(14,2) DEFAULT 0.00,
  `pph21_gaji_sebulan` decimal(14,2) DEFAULT NULL,
  `tambahan_non_npwp` decimal(14,2) DEFAULT 0.00,
  `metode_pph21` varchar(20) NOT NULL,
  `pph21_nett` decimal(14,2) DEFAULT 0.00,
  `pph21_gaji` decimal(16,2) NOT NULL,
  `pph21_thr` decimal(16,2) NOT NULL,
  `thp` decimal(16,2) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_periode_pph21_thr`
--

INSERT INTO `trans_periode_pph21_thr` (`periode`, `periode_penggajian`, `segmen`, `nik`, `nama`, `status_perkawinan`, `status_kerja`, `bank`, `no_rekening`, `npwp`, `departemen`, `jabatan`, `tanggal_masuk`, `periode_thr`, `over_time_index`, `kehadiran`, `ketidakhadiran`, `potongan_ketidakhadiran`, `sakit`, `cuti`, `terlambat`, `kct`, `lama_kerja`, `base`, `thr`, `tunjangan_transport`, `tunjangan_kendaraan`, `tunjangan_makan`, `tunjangan_anak`, `sales_incentive`, `bonus`, `adjustment_plus`, `jkm_perusahaan`, `jkk_perusahaan`, `jht_perusahaan`, `jkn_perusahaan`, `jpn_perusahaan`, `over_time`, `penghasilan_kotor`, `jht_karyawan`, `jpn_karyawan`, `bpjs_kesehatan_karyawan`, `biaya_jabatan`, `total_pengurang`, `netto`, `netto_setahun`, `ptkp_gaji`, `penghasilan_kena_pajak`, `tunjangan_jamsostek`, `adjustment_minus`, `potongan_koperasi`, `pph21_gaji_setahun`, `pph21_gaji_sebulan`, `tambahan_non_npwp`, `metode_pph21`, `pph21_nett`, `pph21_gaji`, `pph21_thr`, `thp`, `lastupdate`, `user_id`) VALUES
('202003', 'BULANAN', 1, '123213', 'tes', 'K0', 'KONTRAK', 'BRI', '13213', '1231313', 'HRGA', 'ASS. MANAGER', '0000-00-00', 'Tidak', '0.00', '0', '0', '0.00', '0', '0', '0', '0', '0', '5000.00', '5000.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '0.00', '15.00', '44.50', '185.00', '200.00', '100.00', '0.00', '10544.50', '100.00', '50.00', '50.00', '250.00', '400.00', '10144.50', '121734.00', '58500000.00', '-58378266.00', '544.50', '0.00', '0.00', '0.00', '0.00', '0.00', 'GROSS', '0.00', '0.00', '0.00', '5000.00', '2021-05-20 14:46:00', 'SYS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `trans_spl`
--

CREATE TABLE `trans_spl` (
  `id_spl` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `periode` varchar(10) NOT NULL,
  `mulai` time NOT NULL,
  `selesai` time NOT NULL,
  `jumlah_jam` double NOT NULL,
  `hari` varchar(10) NOT NULL,
  `index_jumlah` decimal(14,2) NOT NULL,
  `keterangan` varchar(100) NOT NULL,
  `lastupdate` datetime NOT NULL,
  `user_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `trans_spl`
--

INSERT INTO `trans_spl` (`id_spl`, `nik`, `tanggal`, `periode`, `mulai`, `selesai`, `jumlah_jam`, `hari`, `index_jumlah`, `keterangan`, `lastupdate`, `user_id`) VALUES
(9, '1231312', '1900-10-30', '202003', '12:12:00', '12:12:00', 2, 'Libur', '4.00', 'sasad', '2021-05-20 12:23:02', 'SYS');

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vabsen_mesin`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vabsen_mesin` (
`enroll` varchar(30)
,`nik` varchar(20)
,`nama` varchar(100)
,`tanggal` date
,`periode` varchar(10)
,`status_aktual` varchar(20)
,`keterangan` varchar(100)
,`status_perkawinan` varchar(5)
,`departemen` varchar(100)
,`grup` varchar(10)
,`jabatan` varchar(50)
,`tanggal_masuk` date
,`status_kerja` varchar(20)
,`CIn` datetime
,`COut` datetime
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vabsen_periodes`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vabsen_periodes` (
`periode` varchar(10)
,`periode_penggajian` varchar(10)
,`segmen` int(10) unsigned
,`nik` varchar(20)
,`nama` varchar(100)
,`status_perkawinan` varchar(5)
,`departemen` varchar(100)
,`grup` varchar(10)
,`status_kerja` varchar(20)
,`jabatan` varchar(50)
,`tanggal_masuk` date
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vabsen_sakit_cuti`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vabsen_sakit_cuti` (
`no` int(11)
,`periode` varchar(10)
,`nik` varchar(30)
,`nama` varchar(100)
,`dari_tanggal` date
,`sampai_tanggal` date
,`id_status_aktual` int(11)
,`status` varchar(20)
,`keterangan` varchar(100)
,`lastupdate` datetime
,`user_id` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vbonus`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vbonus` (
`periode` varchar(10)
,`nik` varchar(20)
,`nama` varchar(100)
,`bank` varchar(50)
,`no_rekening` varchar(100)
,`bonus_tahunan` decimal(16,2)
,`metode_pph21` varchar(20)
,`pph21_bonus` decimal(16,2)
,`thp` decimal(16,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vpph21_clear_new`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vpph21_clear_new` (
`periode` varchar(10)
,`periode_penggajian` varchar(10)
,`segmen` int(10) unsigned
,`nik` varchar(20)
,`nama` varchar(100)
,`base` decimal(16,2)
,`over_time` decimal(14,2)
,`tunjangan_transport` decimal(16,2)
,`tunjangan_makan` decimal(16,2)
,`tunjangan_kendaraan` decimal(16,2)
,`tunjangan_anak` decimal(14,2)
,`sales_insentive` decimal(16,2)
,`adjustment_plus` decimal(16,2)
,`bpjs_ketenagakerjaan_perusahaan` decimal(18,2)
,`bpjs_kesehatan_perusahaan` decimal(16,2)
,`jpn_perusahaan` decimal(16,2)
,`bpjs_ketenagakerjaan_karyawan` decimal(16,2)
,`bpjs_kesehatan_karyawan` decimal(16,2)
,`jpn_karyawan` decimal(16,2)
,`potongan_bpjs` decimal(14,2)
,`potongan_koperasi` decimal(16,2)
,`potongan_mangkir` decimal(14,2)
,`adjustment_minus` decimal(16,2)
,`metode_pph21` varchar(20)
,`pph21_gaji` decimal(14,2)
,`tunjangan_pph21` decimal(14,2)
,`potongan_pph21` decimal(14,2)
,`thp` decimal(16,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vspl`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vspl` (
`nik` varchar(20)
,`periode` varchar(10)
,`jumlah_jam` decimal(36,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vthr`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vthr` (
`periode` varchar(10)
,`nik` varchar(20)
,`nama` varchar(100)
,`bank` varchar(50)
,`no_rekening` varchar(100)
,`thr` decimal(16,2)
,`metode_pph21` varchar(20)
,`pph21_thr` decimal(16,2)
,`thp` decimal(16,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vtotal_pph21_old`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vtotal_pph21_old` (
`periode` varchar(10)
,`periode_penggajian` varchar(10)
,`segmen` int(10) unsigned
,`nik` varchar(20)
,`nama` varchar(100)
,`pph21_gaji_sebulan` decimal(14,2)
,`tambahan_non_npwp` decimal(14,2)
,`pph21_nett` decimal(14,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vtotal_salary`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vtotal_salary` (
`periode` varchar(10)
,`periode_penggajian` varchar(10)
,`segmen` int(10) unsigned
,`nik` varchar(20)
,`nama` varchar(100)
,`bank` varchar(50)
,`no_rekening` varchar(50)
,`thp` decimal(16,2)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_rekap_absen`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_rekap_absen` (
`enroll` varchar(30)
,`tanggal` date
,`periode` varchar(10)
,`status_aktual` varchar(20)
,`keterangan` varchar(100)
,`nik` varchar(20)
,`nama` varchar(100)
,`waktu` datetime
,`kondisi` varchar(10)
,`shift` varchar(10)
);

-- --------------------------------------------------------

--
-- Struktur dari tabel `xday`
--

CREATE TABLE `xday` (
  `ina` varchar(20) NOT NULL,
  `eng` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `xday`
--

INSERT INTO `xday` (`ina`, `eng`) VALUES
('Jumat', 'Friday'),
('Kamis', 'Thursday'),
('Minggu', 'Sunday'),
('Rabu', 'Wednesday'),
('Sabtu', 'Saturday'),
('Selasa', 'Tuesday'),
('Senin', 'Monday');

-- --------------------------------------------------------

--
-- Struktur untuk view `vabsen_mesin`
--
DROP TABLE IF EXISTS `vabsen_mesin`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vabsen_mesin`  AS SELECT `a`.`enroll` AS `enroll`, `b`.`nik` AS `nik`, `b`.`nama` AS `nama`, `a`.`tanggal` AS `tanggal`, `a`.`periode` AS `periode`, `a`.`status_aktual` AS `status_aktual`, `a`.`keterangan` AS `keterangan`, `b`.`status_perkawinan` AS `status_perkawinan`, `b`.`departemen` AS `departemen`, `b`.`grup` AS `grup`, `b`.`jabatan` AS `jabatan`, `b`.`tanggal_masuk` AS `tanggal_masuk`, `b`.`status` AS `status_kerja`, if(`a`.`kondisi` = 'CIn',`a`.`waktu`,NULL) AS `CIn`, if(`a`.`kondisi` = 'COut',`a`.`waktu`,NULL) AS `COut` FROM (`master_mesin` `a` join `master_karyawan` `b` on(`a`.`enroll` = `b`.`enroll`)) GROUP BY `a`.`enroll`, `a`.`tanggal`, `a`.`periode` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vabsen_periodes`
--
DROP TABLE IF EXISTS `vabsen_periodes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vabsen_periodes`  AS SELECT `trans_periode`.`periode` AS `periode`, `trans_periode`.`periode_penggajian` AS `periode_penggajian`, `trans_periode`.`segmen` AS `segmen`, `vabsen_mesin`.`nik` AS `nik`, `vabsen_mesin`.`nama` AS `nama`, `vabsen_mesin`.`status_perkawinan` AS `status_perkawinan`, `vabsen_mesin`.`departemen` AS `departemen`, `vabsen_mesin`.`grup` AS `grup`, `vabsen_mesin`.`status_kerja` AS `status_kerja`, `vabsen_mesin`.`jabatan` AS `jabatan`, `vabsen_mesin`.`tanggal_masuk` AS `tanggal_masuk` FROM (`vabsen_mesin` join `trans_periode` on(`vabsen_mesin`.`tanggal` >= `trans_periode`.`mulai` and `vabsen_mesin`.`tanggal` <= `trans_periode`.`selesai`)) GROUP BY `trans_periode`.`periode`, `trans_periode`.`periode_penggajian`, `trans_periode`.`segmen`, `vabsen_mesin`.`nik`, `vabsen_mesin`.`nama`, `vabsen_mesin`.`status_perkawinan`, `vabsen_mesin`.`departemen`, `vabsen_mesin`.`grup`, `vabsen_mesin`.`jabatan` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vabsen_sakit_cuti`
--
DROP TABLE IF EXISTS `vabsen_sakit_cuti`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vabsen_sakit_cuti`  AS SELECT `master_absen_sakit_cuti`.`no` AS `no`, `master_absen_sakit_cuti`.`periode` AS `periode`, `master_absen_sakit_cuti`.`nik` AS `nik`, `master_karyawan`.`nama` AS `nama`, `master_absen_sakit_cuti`.`dari_tanggal` AS `dari_tanggal`, `master_absen_sakit_cuti`.`sampai_tanggal` AS `sampai_tanggal`, `master_absen_sakit_cuti`.`id_status_aktual` AS `id_status_aktual`, `master_absen_sakit_cuti`.`status` AS `status`, `master_absen_sakit_cuti`.`keterangan` AS `keterangan`, `master_absen_sakit_cuti`.`lastupdate` AS `lastupdate`, `master_absen_sakit_cuti`.`user_id` AS `user_id` FROM (`master_absen_sakit_cuti` join `master_karyawan` on(`master_absen_sakit_cuti`.`nik` = `master_karyawan`.`nik`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vbonus`
--
DROP TABLE IF EXISTS `vbonus`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vbonus`  AS SELECT `trans_periode_pph21_bonus`.`periode` AS `periode`, `trans_periode_pph21_bonus`.`nik` AS `nik`, `trans_periode_pph21_bonus`.`nama` AS `nama`, `trans_periode_pph21_bonus`.`bank` AS `bank`, `trans_periode_pph21_bonus`.`no_rekening` AS `no_rekening`, `trans_periode_pph21_bonus`.`bonus_tahunan` AS `bonus_tahunan`, `trans_periode_pph21_bonus`.`metode_pph21` AS `metode_pph21`, `trans_periode_pph21_bonus`.`pph21_bonus` AS `pph21_bonus`, `trans_periode_pph21_bonus`.`thp` AS `thp` FROM `trans_periode_pph21_bonus` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vpph21_clear_new`
--
DROP TABLE IF EXISTS `vpph21_clear_new`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vpph21_clear_new`  AS SELECT `trans_periode_pph21`.`periode` AS `periode`, `trans_periode_pph21`.`periode_penggajian` AS `periode_penggajian`, `trans_periode_pph21`.`segmen` AS `segmen`, `trans_periode_pph21`.`nik` AS `nik`, `master_karyawan`.`nama` AS `nama`, `trans_periode_pph21`.`base` AS `base`, `trans_periode_pph21`.`over_time` AS `over_time`, `trans_periode_pph21`.`tunjangan_transport` AS `tunjangan_transport`, `trans_periode_pph21`.`tunjangan_makan` AS `tunjangan_makan`, `trans_periode_pph21`.`tunjangan_kendaraan` AS `tunjangan_kendaraan`, `trans_periode_pph21`.`tunjangan_anak` AS `tunjangan_anak`, `trans_periode_pph21`.`sales_incentive` AS `sales_insentive`, `trans_periode_pph21`.`adjustment_plus` AS `adjustment_plus`, `trans_periode_pph21`.`jkm_perusahaan`+ `trans_periode_pph21`.`jkk_perusahaan` + `trans_periode_pph21`.`jht_perusahaan` AS `bpjs_ketenagakerjaan_perusahaan`, `trans_periode_pph21`.`jkn_perusahaan` AS `bpjs_kesehatan_perusahaan`, `trans_periode_pph21`.`jpn_perusahaan` AS `jpn_perusahaan`, `trans_periode_pph21`.`jht_karyawan` AS `bpjs_ketenagakerjaan_karyawan`, `trans_periode_pph21`.`bpjs_kesehatan_karyawan` AS `bpjs_kesehatan_karyawan`, `trans_periode_pph21`.`jpn_karyawan` AS `jpn_karyawan`, `trans_periode_pph21`.`tunjangan_jamsostek` AS `potongan_bpjs`, `trans_periode_pph21`.`potongan_koperasi` AS `potongan_koperasi`, `trans_periode_pph21`.`potongan_ketidakhadiran` AS `potongan_mangkir`, `trans_periode_pph21`.`adjustment_minus` AS `adjustment_minus`, `trans_periode_pph21`.`metode_pph21` AS `metode_pph21`, `trans_periode_pph21`.`pph21_gaji_sebulan` AS `pph21_gaji`, if(`trans_periode_pph21`.`metode_pph21` = 'NET',`trans_periode_pph21`.`pph21_gaji_sebulan`,0) AS `tunjangan_pph21`, `trans_periode_pph21`.`pph21_gaji_sebulan` AS `potongan_pph21`, `trans_periode_pph21`.`thp` AS `thp` FROM (`trans_periode_pph21` join `master_karyawan` on(`trans_periode_pph21`.`nik` = `master_karyawan`.`nik`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vspl`
--
DROP TABLE IF EXISTS `vspl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vspl`  AS SELECT `trans_spl`.`nik` AS `nik`, `trans_spl`.`periode` AS `periode`, sum(`trans_spl`.`index_jumlah`) AS `jumlah_jam` FROM `trans_spl` GROUP BY `trans_spl`.`nik`, `trans_spl`.`periode` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vthr`
--
DROP TABLE IF EXISTS `vthr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vthr`  AS SELECT `trans_periode_pph21_thr`.`periode` AS `periode`, `trans_periode_pph21_thr`.`nik` AS `nik`, `trans_periode_pph21_thr`.`nama` AS `nama`, `trans_periode_pph21_thr`.`bank` AS `bank`, `trans_periode_pph21_thr`.`no_rekening` AS `no_rekening`, `trans_periode_pph21_thr`.`thr` AS `thr`, `trans_periode_pph21_thr`.`metode_pph21` AS `metode_pph21`, `trans_periode_pph21_thr`.`pph21_thr` AS `pph21_thr`, `trans_periode_pph21_thr`.`thp` AS `thp` FROM `trans_periode_pph21_thr` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vtotal_pph21_old`
--
DROP TABLE IF EXISTS `vtotal_pph21_old`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotal_pph21_old`  AS SELECT `trans_periode_pph21`.`periode` AS `periode`, `trans_periode_pph21`.`periode_penggajian` AS `periode_penggajian`, `trans_periode_pph21`.`segmen` AS `segmen`, `trans_periode_pph21`.`nik` AS `nik`, `master_karyawan`.`nama` AS `nama`, `trans_periode_pph21`.`pph21_gaji_sebulan` AS `pph21_gaji_sebulan`, `trans_periode_pph21`.`tambahan_non_npwp` AS `tambahan_non_npwp`, `trans_periode_pph21`.`pph21_nett` AS `pph21_nett` FROM (`trans_periode_pph21` join `master_karyawan` on(`trans_periode_pph21`.`nik` = `master_karyawan`.`nik`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vtotal_salary`
--
DROP TABLE IF EXISTS `vtotal_salary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vtotal_salary`  AS SELECT `trans_periode_pph21`.`periode` AS `periode`, `trans_periode_pph21`.`periode_penggajian` AS `periode_penggajian`, `trans_periode_pph21`.`segmen` AS `segmen`, `trans_periode_pph21`.`nik` AS `nik`, `master_karyawan`.`nama` AS `nama`, `master_karyawan`.`bank` AS `bank`, `master_karyawan`.`no_rekening` AS `no_rekening`, `trans_periode_pph21`.`thp` AS `thp` FROM (`master_karyawan` join `trans_periode_pph21` on(`master_karyawan`.`nik` = `trans_periode_pph21`.`nik`)) GROUP BY `trans_periode_pph21`.`periode`, `trans_periode_pph21`.`periode_penggajian`, `trans_periode_pph21`.`segmen`, `trans_periode_pph21`.`nik` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_rekap_absen`
--
DROP TABLE IF EXISTS `v_rekap_absen`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_rekap_absen`  AS SELECT `master_mesin`.`enroll` AS `enroll`, `master_mesin`.`tanggal` AS `tanggal`, `master_mesin`.`periode` AS `periode`, `master_mesin`.`status_aktual` AS `status_aktual`, `master_mesin`.`keterangan` AS `keterangan`, `master_karyawan`.`nik` AS `nik`, `master_karyawan`.`nama` AS `nama`, `master_mesin`.`waktu` AS `waktu`, `master_mesin`.`kondisi` AS `kondisi`, `master_mesin`.`shift` AS `shift` FROM (`master_mesin` join `master_karyawan` on(`master_mesin`.`enroll` = `master_karyawan`.`enroll`)) ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `last_activity_idx` (`last_activity`);

--
-- Indeks untuk tabel `content_agama`
--
ALTER TABLE `content_agama`
  ADD PRIMARY KEY (`id_agama`);

--
-- Indeks untuk tabel `content_bank`
--
ALTER TABLE `content_bank`
  ADD PRIMARY KEY (`id_bank`);

--
-- Indeks untuk tabel `content_default`
--
ALTER TABLE `content_default`
  ADD PRIMARY KEY (`nama`);

--
-- Indeks untuk tabel `content_departemen`
--
ALTER TABLE `content_departemen`
  ADD PRIMARY KEY (`id_departemen`) USING BTREE;

--
-- Indeks untuk tabel `content_grup`
--
ALTER TABLE `content_grup`
  ADD PRIMARY KEY (`id_grup`) USING BTREE;

--
-- Indeks untuk tabel `content_insentive_kehadiran`
--
ALTER TABLE `content_insentive_kehadiran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `content_jabatan`
--
ALTER TABLE `content_jabatan`
  ADD PRIMARY KEY (`id_jabatan`) USING BTREE;

--
-- Indeks untuk tabel `content_jenis_gaji`
--
ALTER TABLE `content_jenis_gaji`
  ADD PRIMARY KEY (`jenis`);

--
-- Indeks untuk tabel `content_mata_uang`
--
ALTER TABLE `content_mata_uang`
  ADD PRIMARY KEY (`id_mata_uang`) USING BTREE;

--
-- Indeks untuk tabel `content_periode_penggajian`
--
ALTER TABLE `content_periode_penggajian`
  ADD PRIMARY KEY (`periode_penggajian`);

--
-- Indeks untuk tabel `content_pph21_metode`
--
ALTER TABLE `content_pph21_metode`
  ADD PRIMARY KEY (`pph21_metode`);

--
-- Indeks untuk tabel `content_status_aktual`
--
ALTER TABLE `content_status_aktual`
  ADD PRIMARY KEY (`id_status_aktual`) USING BTREE;

--
-- Indeks untuk tabel `content_status_karyawan`
--
ALTER TABLE `content_status_karyawan`
  ADD PRIMARY KEY (`id_status_karyawan`) USING BTREE;

--
-- Indeks untuk tabel `content_status_perkawinan`
--
ALTER TABLE `content_status_perkawinan`
  ADD PRIMARY KEY (`id_status_perkawinan`) USING BTREE;

--
-- Indeks untuk tabel `master_absen_grup_shift`
--
ALTER TABLE `master_absen_grup_shift`
  ADD PRIMARY KEY (`grup`,`shift`);

--
-- Indeks untuk tabel `master_absen_hari_besar`
--
ALTER TABLE `master_absen_hari_besar`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `master_absen_jadwal_karyawan`
--
ALTER TABLE `master_absen_jadwal_karyawan`
  ADD PRIMARY KEY (`nik`,`tanggal`) USING BTREE;

--
-- Indeks untuk tabel `master_absen_sakit_cuti`
--
ALTER TABLE `master_absen_sakit_cuti`
  ADD PRIMARY KEY (`no`,`nik`,`periode`);

--
-- Indeks untuk tabel `master_gaji`
--
ALTER TABLE `master_gaji`
  ADD PRIMARY KEY (`kode_gaji`);

--
-- Indeks untuk tabel `master_gaji_karyawan`
--
ALTER TABLE `master_gaji_karyawan`
  ADD PRIMARY KEY (`nik`,`kode_gaji`);

--
-- Indeks untuk tabel `master_gaji_karyawan_periode`
--
ALTER TABLE `master_gaji_karyawan_periode`
  ADD PRIMARY KEY (`nik`,`kode_gaji`,`periode`);

--
-- Indeks untuk tabel `master_jamsostek`
--
ALTER TABLE `master_jamsostek`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `master_jamsostek_komponen_dasar`
--
ALTER TABLE `master_jamsostek_komponen_dasar`
  ADD PRIMARY KEY (`kode_gaji`);

--
-- Indeks untuk tabel `master_karyawan`
--
ALTER TABLE `master_karyawan`
  ADD PRIMARY KEY (`nik`);

--
-- Indeks untuk tabel `master_mesin`
--
ALTER TABLE `master_mesin`
  ADD PRIMARY KEY (`no`,`enroll`,`waktu`);

--
-- Indeks untuk tabel `master_mesin_clear`
--
ALTER TABLE `master_mesin_clear`
  ADD PRIMARY KEY (`enroll`,`tanggal`,`nik`);

--
-- Indeks untuk tabel `master_overtime`
--
ALTER TABLE `master_overtime`
  ADD PRIMARY KEY (`jam`);

--
-- Indeks untuk tabel `master_periode_penggajian`
--
ALTER TABLE `master_periode_penggajian`
  ADD PRIMARY KEY (`periode_penggajian`,`segmen`);

--
-- Indeks untuk tabel `master_periode_penggajian_komponen`
--
ALTER TABLE `master_periode_penggajian_komponen`
  ADD PRIMARY KEY (`periode_penggajian`,`segmen`,`kode_gaji`);

--
-- Indeks untuk tabel `master_perusahaan`
--
ALTER TABLE `master_perusahaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `master_pph21_komponen`
--
ALTER TABLE `master_pph21_komponen`
  ADD PRIMARY KEY (`kode_gaji`);

--
-- Indeks untuk tabel `master_pph21_metode`
--
ALTER TABLE `master_pph21_metode`
  ADD PRIMARY KEY (`periode`);

--
-- Indeks untuk tabel `master_pph21_ptkp`
--
ALTER TABLE `master_pph21_ptkp`
  ADD PRIMARY KEY (`status_perkawinan`);

--
-- Indeks untuk tabel `master_pph21_tarif`
--
ALTER TABLE `master_pph21_tarif`
  ADD PRIMARY KEY (`kode_tarif`);

--
-- Indeks untuk tabel `master_shift`
--
ALTER TABLE `master_shift`
  ADD PRIMARY KEY (`shift`);

--
-- Indeks untuk tabel `master_total_gaji`
--
ALTER TABLE `master_total_gaji`
  ADD PRIMARY KEY (`id_total_gaji`);

--
-- Indeks untuk tabel `master_tunjangan_anak`
--
ALTER TABLE `master_tunjangan_anak`
  ADD PRIMARY KEY (`status_perkawinan`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `trans_cicilan`
--
ALTER TABLE `trans_cicilan`
  ADD PRIMARY KEY (`no`);

--
-- Indeks untuk tabel `trans_periode`
--
ALTER TABLE `trans_periode`
  ADD PRIMARY KEY (`id_periode`);

--
-- Indeks untuk tabel `trans_periode_gaji_karyawan`
--
ALTER TABLE `trans_periode_gaji_karyawan`
  ADD PRIMARY KEY (`periode`,`periode_penggajian`,`segmen`,`nik`,`kode_gaji`) USING BTREE;

--
-- Indeks untuk tabel `trans_periode_jamsostek`
--
ALTER TABLE `trans_periode_jamsostek`
  ADD PRIMARY KEY (`periode`,`periode_penggajian`,`segmen`,`nik`,`nama_program`) USING BTREE;

--
-- Indeks untuk tabel `trans_periode_pph21`
--
ALTER TABLE `trans_periode_pph21`
  ADD PRIMARY KEY (`periode`,`segmen`,`nik`,`periode_penggajian`) USING BTREE;

--
-- Indeks untuk tabel `trans_periode_pph21_bonus`
--
ALTER TABLE `trans_periode_pph21_bonus`
  ADD PRIMARY KEY (`periode`,`segmen`,`nik`,`periode_penggajian`) USING BTREE;

--
-- Indeks untuk tabel `trans_periode_pph21_tarif`
--
ALTER TABLE `trans_periode_pph21_tarif`
  ADD PRIMARY KEY (`periode`,`periode_penggajian`,`segmen`,`nik`,`kode_tarif`,`jenis`) USING BTREE;

--
-- Indeks untuk tabel `trans_periode_pph21_tarif_bonus`
--
ALTER TABLE `trans_periode_pph21_tarif_bonus`
  ADD PRIMARY KEY (`periode`,`periode_penggajian`,`segmen`,`nik`,`kode_tarif`,`jenis`) USING BTREE;

--
-- Indeks untuk tabel `trans_periode_pph21_tarif_thr`
--
ALTER TABLE `trans_periode_pph21_tarif_thr`
  ADD PRIMARY KEY (`periode`,`periode_penggajian`,`segmen`,`nik`,`kode_tarif`,`jenis`) USING BTREE;

--
-- Indeks untuk tabel `trans_periode_pph21_thr`
--
ALTER TABLE `trans_periode_pph21_thr`
  ADD PRIMARY KEY (`periode`,`segmen`,`nik`,`periode_penggajian`) USING BTREE;

--
-- Indeks untuk tabel `trans_spl`
--
ALTER TABLE `trans_spl`
  ADD PRIMARY KEY (`id_spl`);

--
-- Indeks untuk tabel `xday`
--
ALTER TABLE `xday`
  ADD PRIMARY KEY (`ina`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `content_agama`
--
ALTER TABLE `content_agama`
  MODIFY `id_agama` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `content_bank`
--
ALTER TABLE `content_bank`
  MODIFY `id_bank` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `content_departemen`
--
ALTER TABLE `content_departemen`
  MODIFY `id_departemen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `content_grup`
--
ALTER TABLE `content_grup`
  MODIFY `id_grup` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `content_insentive_kehadiran`
--
ALTER TABLE `content_insentive_kehadiran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `content_jabatan`
--
ALTER TABLE `content_jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `content_mata_uang`
--
ALTER TABLE `content_mata_uang`
  MODIFY `id_mata_uang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `content_status_aktual`
--
ALTER TABLE `content_status_aktual`
  MODIFY `id_status_aktual` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT untuk tabel `content_status_karyawan`
--
ALTER TABLE `content_status_karyawan`
  MODIFY `id_status_karyawan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `content_status_perkawinan`
--
ALTER TABLE `content_status_perkawinan`
  MODIFY `id_status_perkawinan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `master_absen_hari_besar`
--
ALTER TABLE `master_absen_hari_besar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `master_absen_sakit_cuti`
--
ALTER TABLE `master_absen_sakit_cuti`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `master_jamsostek`
--
ALTER TABLE `master_jamsostek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `master_mesin`
--
ALTER TABLE `master_mesin`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=346;

--
-- AUTO_INCREMENT untuk tabel `master_perusahaan`
--
ALTER TABLE `master_perusahaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `master_pph21_tarif`
--
ALTER TABLE `master_pph21_tarif`
  MODIFY `kode_tarif` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `trans_cicilan`
--
ALTER TABLE `trans_cicilan`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `trans_periode`
--
ALTER TABLE `trans_periode`
  MODIFY `id_periode` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `trans_spl`
--
ALTER TABLE `trans_spl`
  MODIFY `id_spl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
