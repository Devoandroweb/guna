
    <?php echo form_open('cperiodepph21/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Periode PPH21 Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right">
                        <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cperiodepph21" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-1 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <input maxlength="20" name="periode" required="required" type="text" class="form-control col-md-4 input-smm" id="periode" placeholder="Periode" />
                    </div>
                    <div class="form-group">
                        <label for="periode_penggajian">Periode Penggajian</label>
                        <input maxlength="20" name="periode_penggajian" required="required" type="text" class="form-control col-md-4 input-smm" id="periode_penggajian" placeholder="Periode Penggajian" />
                    </div>
                    <div class="form-group">
                        <label for="segmen">Segmen</label>
                        <input maxlength="20" name="segmen" required="required" type="text" class="form-control col-md-4 input-smm" id="segmen" placeholder="Segmen" />
                    </div>
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input maxlength="20" name="nik" required="required" type="text" class="form-control col-md-4 input-smm" id="nik" placeholder="NIK" />
                    </div>
                    <div class="form-group">
                        <label for="status_perkawinan">Status Perkawinan</label>
                        <input maxlength="20" name="status_perkawinan" required="required" type="text" class="form-control col-md-4 input-smm" id="status_perkawinan" placeholder="Status Perkawinan" />
                    </div>
                    <div class="form-group">
                        <label for="npwp">NPWP</label>
                        <input maxlength="20" name="npwp" required="required" type="text" class="form-control col-md-4 input-smm" id="npwp" placeholder="NPWP" />
                    </div>
                    <div class="form-group">
                        <label for="departemen">Departemen</label>
                        <input maxlength="20" name="departemen" required="required" type="text" class="form-control col-md-4 input-smm" id="departemen" placeholder="Departemen" />
                    </div>
                    <div class="form-group">
                        <label for="gaji_kotor">Gaji Kotor</label>
                        <input maxlength="20" name="gaji_kotor" required="required" type="text" class="form-control col-md-4 input-smm" id="gaji_kotor" placeholder="Gaji Kotor" />
                    </div>
                    <div class="form-group">
                        <label for="tunjangan_jamsostek">Tunjangan Jamsostek</label>
                        <input maxlength="20" name="tunjangan_jamsostek" required="required" type="text" class="form-control col-md-4 input-smm" id="tunjangan_jamsostek" placeholder="Tunjangan Jamsostek" />
                    </div>
                    <div class="form-group">
                        <label for="biaya_jabatan">Biaya Jabatan</label>
                        <input maxlength="20" name="biaya_jabatan" required="required" type="text" class="form-control col-md-4 input-smm" id="biaya_jabatan" placeholder="Biaya Jabatan" />
                    </div>
                    <div class="form-group">
                        <label for="potongan_jamsostek">Potongan Jamsostek</label>
                        <input maxlength="20" name="potongan_jamsostek" required="required" type="text" class="form-control col-md-4 input-smm" id="potongan_jamsostek" placeholder="Potongan Jamsostek" />
                    </div>
                </div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="gaji_netto">Gaji Netto</label>
                        <input maxlength="20" name="gaji_netto" required="required" type="text" class="form-control col-md-4 input-smm" id="gaji_netto" placeholder="Gaji Netto" />
                    </div>
                    <div class="form-group">
                        <label for="gaji_netto_disetahunkan">Gaji Netto Disetahunkan</label>
                        <input maxlength="20" name="gaji_netto_disetahunkan" required="required" type="text" class="form-control col-md-4 input-smm" id="gaji_netto_disetahunkan" placeholder="Gaji Netto Disetahunkan" />
                    </div>
                    <div class="form-group">
                        <label for="ptkp_gaji">PTKP Gaji</label>
                        <input maxlength="20" name="ptkp_gaji" required="required" type="text" class="form-control col-md-4 input-smm" id="ptkp_gaji" placeholder="PTKP Gaji" />
                    </div>
                    <div class="form-group">
                        <label for="pkp_gaji">KP Gaji</label>
                        <input maxlength="20" name="pkp_gaji" required="required" type="text" class="form-control col-md-4 input-smm" id="pkp_gaji" placeholder="PKP Gaji" />
                    </div>
                    <div class="form-group">
                        <label for="pph21_gaji_setahun">PPH21 Gaji Setahun</label>
                        <input maxlength="20" name="pph21_gaji_setahun" required="required" type="text" class="form-control col-md-4 input-smm" id="pph21_gaji_setahun" placeholder="PPH21 Gaji Setahun" />
                    </div>
                    <div class="form-group">
                        <label for="pph21_gaji_sebulan">PPH21 Gaji Sebulan</label>
                        <input maxlength="20" name="pph21_gaji_sebulan" required="required" type="text" class="form-control col-md-4 input-smm" id="pph21_gaji_sebulan" placeholder="PPH21 Gaji Sebulan" />
                    </div>
                    <div class="form-group">
                        <label for="bonus">Bonus</label>
                        <input maxlength="20" name="bonus" required="required" type="text" class="form-control col-md-4 input-smm" id="bonus" placeholder="Bonus" />
                    </div>
                    <div class="form-group">
                        <label for="thr">THR</label>
                        <input maxlength="20" name="thr" required="required" type="text" class="form-control col-md-4 input-smm" id="thr" placeholder="THR" />
                    </div>
                    <div class="form-group">
                        <label for="jumlah_gaji_bonus_thr_setahun">Jumlah Gaji Bonus THR Setahun</label>
                        <input maxlength="20" name="jumlah_gaji_bonus_thr_setahun" required="required" type="text" class="form-control col-md-4 input-smm" id="jumlah_gaji_bonus_thr_setahun" placeholder="Jumlah Gaji Bonus THR Setahun" />
                    </div>
                    <div class="form-group">
                        <label for="tunjangan_jamsostek_setahun">Tunjangan Jamsostek Setahun</label>
                        <input maxlength="20" name="tunjangan_jamsostek_setahun" required="required" type="text" class="form-control col-md-4 input-smm" id="tunjangan_jamsostek_setahun" placeholder="Tunjangan Jamsostek Setahun" />
                    </div>
                    <div class="form-group">
                        <label for="biaya_jabatan_setahun">Biaya Jabatan Setahun</label>
                        <input maxlength="20" name="biaya_jabatan_setahun" required="required" type="text" class="form-control col-md-4 input-smm" id="biaya_jabatan_setahun" placeholder="Biaya Jabatan Setahun" />
                    </div>
                </div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="potongan_jamsostek_setahun">Potongan Jamsostek Setahun</label>
                        <input maxlength="20" name="potongan_jamsostek_setahun" required="required" type="text" class="form-control col-md-4 input-smm" id="potongan_jamsostek_setahun" placeholder="Potongan Jamsostek Setahun" />
                    </div>
                    <div class="form-group">
                        <label for="netto_gaji_bonus_thr_setahun">Netto Gaji Bonus THR Setahun</label>
                        <input maxlength="20" name="netto_gaji_bonus_thr_setahun" required="required" type="text" class="form-control col-md-4 input-smm" id="netto_gaji_bonus_thr_setahun" placeholder="Netto Gaji Bonus THR Setahun" />
                    </div>
                    <div class="form-group">
                        <label for="ptkp_gaji_bonus_thr">PTKP Gaji Bonus THR</label>
                        <input maxlength="20" name="ptkp_gaji_bonus_thr" required="required" type="text" class="form-control col-md-4 input-smm" id="ptkp_gaji_bonus_thr" placeholder="PTKP GajiBonus THR" />
                    </div>
                    <div class="form-group">
                        <label for="pkp_gaji_bonus_thr">PKP Gaji Bonus THR</label>
                        <input maxlength="20" name="pkp_gaji_bonus_thr" required="required" type="text" class="form-control col-md-4 input-smm" id="pkp_gaji_bonus_thr" placeholder="PKP Gaji Bonus THR" />
                    </div>
                    <div class="form-group">
                        <label for="pph21_gaji_bonus_thr">PPH21 Gaji Bonus THR</label>
                        <input maxlength="20" name="pph21_gaji_bonus_thr" required="required" type="text" class="form-control col-md-4 input-smm" id="pph21_gaji_bonus_thr" placeholder="PPH21 Gaji Bonus THR" />
                    </div>
                    <div class="form-group">
                        <label for="pph21_bonus_thr">PPH21 Bonus THR</label>
                        <input maxlength="20" name="pph21_bonus_thr" required="required" type="text" class="form-control col-md-4 input-smm" id="pph21_bonus_thr" placeholder="PPH21 Bonus THR" />
                    </div>
                    <div class="form-group">
                        <label for="pph21_total">PPH21 Total</label>
                        <input maxlength="20" name="pph21_total" required="required" type="text" class="form-control col-md-4 input-smm" id="pph21_total" placeholder="PPH21 Total" />
                    </div>
                    <div class="form-group">
                        <label for="tambahan_non_npwp">Tambahan Non NPWP</label>
                        <input maxlength="20" name="tambahan_non_npwp" required="required" type="text" class="form-control col-md-4 input-smm" id="tambahan_non_npwp" placeholder="Tambahan Non NPWP" />
                    </div>
                    <div class="form-group">
                        <label for="pph21_nett">PPH21 Nett</label>
                        <input maxlength="20" name="pph21_nett" required="required" type="text" class="form-control col-md-4 input-smm" id="pph21_nett" placeholder="PPH21 Nett" />
                    </div>
                    <div class="form-group">
                        <label for="dibayar_perusahaan">Dibayar Perusahaan</label>
                        <input maxlength="20" name="dibayar_perusahaan" required="required" type="text" class="form-control col-md-4 input-smm" id="dibayar_perusahaan" placeholder="Dibayar Perusahaan" />
                    </div>
                    <div class="form-group">
                        <label for="dibayar_karyawan">Dibayar Karyawan</label>
                        <input maxlength="20" name="dibayar_karyawan" required="required" type="text" class="form-control col-md-4 input-smm" id="dibayar_karyawan" placeholder="Dibayar Karyawan" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>