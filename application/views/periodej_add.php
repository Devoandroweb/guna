
    <?php echo form_open('cperiodej/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Periode Jamsostek Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right">
                        <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cperiodej" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
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
                        <label for="nama_program">Nama Program</label>
                        <input maxlength="20" name="nama_program" required="required" type="text" class="form-control col-md-4 input-smm" id="nama_program" placeholder="Nama Program" />
                    </div>
                    <div class="form-group">
                        <label for="gaji_dasar">Gaji Dasar</label>
                        <input maxlength="20" name="gaji_dasar" required="required" type="text" class="form-control col-md-4 input-smm" id="gaji_dasar" placeholder="Gaji Dasar" />
                    </div>
                </div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="maksimal_gaji_dasar">Maksimal Gaji Dasar</label>
                        <input maxlength="20" name="maksimal_gaji_dasar" required="required" type="text" class="form-control col-md-4 input-smm" id="maksimal_gaji_dasar" placeholder="Maksimal Gaji Dasar" />
                    </div>
                    <div class="form-group">
                        <label for="bagian_perusahaan">Bagian Perusahaan</label>
                        <input maxlength="20" name="bagian_perusahaan" required="required" type="text" class="form-control col-md-4 input-smm" id="bagian_perusahaan" placeholder="Bagian Perusahaan" />
                    </div>
                    <div class="form-group">
                        <label for="bagian_karyawan">Bagian Karyawan</label>
                        <input maxlength="20" name="bagian_karyawan" required="required" type="text" class="form-control col-md-4 input-smm" id="bagian_karyawan" placeholder="Bagian Karyawan" />
                    </div>
                    <div class="form-group">
                        <label for="nilai_perusahaan">Nilai Perusahaan</label>
                        <input maxlength="20" name="nilai_perusahaan" required="required" type="text" class="form-control col-md-4 input-smm" id="nilai_perusahaan" placeholder="Nilai Perusahaan" />
                    </div>
                    <div class="form-group">
                        <label for="nilai_karyawan">Nilai Karyawan</label>
                        <input maxlength="20" name="nilai_karyawan" required="required" type="text" class="form-control col-md-4 input-smm" id="nilai_karyawan" placeholder="Nilai Karyawan" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>