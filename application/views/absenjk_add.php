
    <?php echo form_open('cabsenjk/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
        <div class="rows col-md-11 wells btn-info">
            <div class="rows">
                <div class="col-xs-12 col-sm-6 col-md-8"><strong>Absen Jadwal Karyawan Information</strong></div>
                <div class="col-xs-6 col-md-4" align="right">
                    <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsenjk" class="btn-lgs btn btn-default">Cancel</a></div>
            </div>
        </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-3 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="nik">NIK</label>
                        <input maxlength="20" name="nik" required="required" type="text" class="form-control col-md-4 input-smm" id="nik" placeholder="NIK" />
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="text" required="required" class="form-control col-md-4 input-smm" name="tanggal" id="tgl" placeholder="Tanggal" />
                    </div>
                    <div class="form-group">
                        <label for="shift">Shift</label>
                        <select required="required" id="shift" name="shift" class="form-control col-md-4 input-smm">
                            <option></option>
                            <?php foreach($query->result() as $row):?>
                            <option><?php echo $row->grup?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select required="required" id="jenis" name="jenis" class="form-control col-md-4 input-smm">
                            <option></option>
                            <option>SHIFT</option>
                            <option>NONSHIFT</option>
                            <option>WEEKSHIFT</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="shift">Shift</label>
                        <input maxlength="20" name="shift" required="required" type="text" class="form-control col-md-4 input-smm" id="shift" placeholder="Shift" />
                    </div>
                    <div class="form-group">
                        <label for="nama_shift">Nama Shift</label>
                        <input maxlength="25" name="nama_shift" required="required" type="text" class="form-control col-md-4 input-smm" id="nama_shift" placeholder="Nama Shift" />
                    </div>
                    <div class="form-group">
                        <label for="masuk">Masuk</label>
                        <input maxlength="20" name="masuk" type="text" required="required" class="form-control col-md-4 input-smm" id="masuk" placeholder="Masuk" />
                    </div>
                    <div class="form-group">
                        <label for="pulang">Pulang</label>
                        <input maxlength="20" name="pulang" type="text" required="required" class="form-control col-md-4 input-smm" id="pulang" placeholder="Pulang" />
                    </div>
                </div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="kode_hari_masuk">Kode Hari Masuk</label>
                        <select required="required" id="kode_hari_masuk" name="kode_hari_masuk" class="form-control col-md-4 input-smm">
                            <option></option>
                            <option>SAMEDAY</option>
                            <option>NEXTDAY</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kode_hari_pulang">Kode Hari Pulang</label>
                        <select required="required" id="kode_hari_pulang" name="kode_hari_pulang" class="form-control col-md-4 input-smm">
                            <option></option>
                            <option>SAMEDAY</option>
                            <option>NEXTDAY</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>