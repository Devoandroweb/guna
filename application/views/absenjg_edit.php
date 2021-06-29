
    <?php echo form_open('cabsenjg/update');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Absen Jadwal Grup Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right"><input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsenjg" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        <?php foreach($query->result() as $row):?>
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-3 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="grup">Grup</label>
                        <input value="<?php echo $row->grup;?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm" id="grup" placeholder="Grup" />
                        <input name="grup" value="<?php echo $row->grup;?>" type="hidden" class="form-control col-md-4 input-smm" id="grup" placeholder="Grup" />
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input value="<?php echo $row->tanggal;?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm" id="tanggal" placeholder="Tanggal" />
                        <input name="tanggal" value="<?php echo $row->tanggal;?>" type="hidden" class="form-control col-md-4 input-smm" id="tanggal" placeholder="Tanggal" />
                    </div>
                    <div class="form-group">
                        <label for="jenis">Jenis</label>
                        <select name="jenis" required="required" class="form-control col-md-4 input-smm">
                            <?php
                            if ($row->jenis == "SHIFT"){
                            ?>
                            <option selected="selected" value="SHIFT">SHIFT</option>
                            <option value="NONSHIFT">NONSHIFT</option>
                            <option value="WEEKSHIFT">WEEKSHIFT</option>
                            <?php
                            } else if ($row->jenis == "NONSHIFT"){
                            ?>
                            <option value="SHIFT">SHIFT</option>
                            <option selected="selected" value="NONSHIFT">NONSHIFT</option>
                            <option value="WEEKSHIFT">WEEKSHIFT</option>
                            <?php
                            } else if ($row->jenis == "WEEKSHIFT"){
                            ?>
                            <option value="SHIFT">SHIFT</option>
                            <option value="NONSHIFT">NONSHIFT</option>
                            <option selected="selected" value="WEEKSHIFT">WEEKSHIFT</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="shift">Shift</label>
                        <input maxlength="25" name="shift" value="<?php echo $row->shift;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="shift" placeholder="Shift" />
                    </div>
                    <div class="form-group">
                        <label for="urutan">Urutan</label>
                        <input maxlength="25" name="urutan" value="<?php echo $row->urutan;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="urutan" placeholder="Urutan" />
                    </div>
                    <div class="form-group">
                        <label for="status_jadwal">Status Jadwal</label>
                        <select name="status_jadwal" required="required" class="form-control col-md-4 input-smm">
                            <?php
                            if ($row->status_jadwal == "Masuk"){
                            ?>
                            <option selected="selected" value="Masuk">Masuk</option>
                            <option value="Libur">Libur</option>
                            <option value="Hari Besar">Hari Besar</option>
                            <?php
                            } else if ($row->status_jadwal == "Libur"){
                            ?>
                            <option value="Masuk">Masuk</option>
                            <option selected="selected" value="Libur">Libur</option>
                            <option value="Hari Besar">Hari Besar</option>
                            <?php
                            } else if ($row->status_jadwal == "Hari Besar"){
                            ?>
                            <option value="Masuk">Masuk</option>
                            <option value="Libur">Libur</option>
                            <option selected="selected" value="Hari Besar">Hari Besar</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="jadwal_masuk">Jadwal Masuk</label>
                        <input type="text" required="required" value="<?php echo $row->jadwal_masuk;?>" class="form-control col-md-4 input-smm" name="jadwal_masuk" id="tgla" placeholder="Jadwal Masuk" />
                    </div>
                    <div class="form-group">
                        <label for="jadwal_pulang">Jadwal Pulang</label>
                        <input type="text" required="required" value="<?php echo $row->jadwal_masuk;?>" class="form-control col-md-4 input-smm" name="jadwal_pulang" id="tgls" placeholder="Jadwal Pulang" />
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>