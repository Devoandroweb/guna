
    <?php echo form_open('cperiodepph21t/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Periode PPH21 Tarif Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right">
                        <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cperiodepph21t" class="btn-lgs btn btn-default">Cancel</a></div>
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
                        <label for="jenis">Jenis</label>
                        <input maxlength="20" name="jenis" required="required" type="text" class="form-control col-md-4 input-smm" id="jenis" placeholder="Jenis" />
                    </div>
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input maxlength="20" name="nik" required="required" type="text" class="form-control col-md-4 input-smm" id="nik" placeholder="NIK" />
                    </div>
                </div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="kode_tarif">Kode Tarif</label>
                        <input maxlength="20" name="kode_tarif" required="required" type="text" class="form-control col-md-4 input-smm" id="kode_tarif" placeholder="Kode Tarif" />
                    </div>
                    <div class="form-group">
                        <label for="nilai_gaji">Nilai Gaji</label>
                        <input maxlength="20" name="nilai_gaji" required="required" type="text" class="form-control col-md-4 input-smm" id="nilai_gaji" placeholder="Nilai Gaji" />
                    </div>
                    <div class="form-group">
                        <label for="tarif">Tarif</label>
                        <input maxlength="20" name="tarif" required="required" type="text" class="form-control col-md-4 input-smm" id="tarif" placeholder="Tarif" />
                    </div>
                    <div class="form-group">
                        <label for="nilai_pph21">Nilai PPH21</label>
                        <input maxlength="20" name="nilai_pph21" required="required" type="text" class="form-control col-md-4 input-smm" id="nilai_pph21" placeholder="Nilai PPH21" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>