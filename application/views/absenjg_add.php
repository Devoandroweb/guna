
    <?php echo form_open('cabsenjg/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
        <div class="rows col-md-11 wells btn-info">
            <div class="rows">
                <div class="col-xs-12 col-sm-6 col-md-8"><strong>Absen Jadwal Grup Information</strong></div>
                <div class="col-xs-6 col-md-4" align="right">
                    <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsenjg" class="btn-lgs btn btn-default">Cancel</a></div>
            </div>
        </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-3 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="grup">Grup</label>
                        <select required="required" id="grup" name="grup" class="form-control col-md-4 input-smm">
                            <option></option>
                            <?php foreach($query->result() as $row):?>
                            <option><?php echo $row->grup?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="text" required="required" class="form-control col-md-4 input-smm" name="tanggal" id="tgl" placeholder="Tanggal" />
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
                        <label for="urutan">Urutan</label>
                        <input maxlength="25" name="urutan" required="required" type="text" class="form-control col-md-4 input-smm" id="urutan" placeholder="Urutan" />
                    </div>
                    <div class="form-group">
                        <label for="status_jadwal">Status Jadwal</label>
                        <select required="required" id="status_jadwal" name="status_jadwal" class="form-control col-md-4 input-smm">
                            <option></option>
                            <option>Masuk</option>
                            <option>Libur</option>
                            <option>Hari Besar</option>
                        </select>
                    </div>
                </div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="jadwal_masuk">Jadwal Masuk</label>
                        <input type="text" required="required" class="form-control col-md-4 input-smm" name="jadwal_masuk" id="tgla" placeholder="Jadwal Masuk" />
                    </div>
                    <div class="form-group">
                        <label for="jadwal_pulang">Jadwal Pulang</label>
                        <input type="text" required="required" class="form-control col-md-4 input-smm" name="jadwal_pulang" id="tgls" placeholder="Jadwal Pulang" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>