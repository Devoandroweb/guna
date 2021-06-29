
    <?php echo form_open('cabsengshift/update');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Absen Grup Shift Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right"><input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cabsengshift" class="btn-lgs btn btn-default">Cancel</a></div>
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
                        <label for="shift">Shift</label>
                        <input value="<?php echo $row->shift;?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm" id="shift" placeholder="Shift" />
                        <input name="shift" value="<?php echo $row->shift;?>" type="hidden" class="form-control col-md-4 input-smm" id="shift" placeholder="Shift" />
                    </div>
                    <div class="form-group">
                        <label for="nama_shift">Nama Shift</label>
                        <input maxlength="25" name="nama_shift" value="<?php echo $row->nama_shift;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="nama_shift" placeholder="Nama Shift" />
                    </div>
                    <div class="form-group">
                        <label for="masuk">Masuk</label>
                        <input maxlength="20" name="masuk" value="<?php echo $row->masuk;?>" type="text" required="required" class="form-control col-md-4 input-smm" id="masuk" placeholder="Masuk" />
                    </div>
                    <div class="form-group">
                        <label for="pulang">Pulang</label>
                        <input maxlength="20" name="pulang" value="<?php echo $row->pulang;?>" type="text" required="required" class="form-control col-md-4 input-smm" id="pulang" placeholder="Pulang" />
                    </div>
                </div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="kode_hari_masuk">Kode Hari Masuk</label>
                        <select required="required" id="kode_hari_masuk" name="kode_hari_masuk" class="form-control col-md-4 input-smm">
                            <?php
                            if ($row->kode_hari_masuk == 'SAMEDAY'){
                                echo "
                                <option selected='selected'>SAMEDAY</option>
                                <option>NEXTDAY</option>
                                ";
                            } else {
                                echo "
                                <option>SAMEDAY</option>
                                <option selected='selected'>NEXTDAY</option>
                                ";
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="kode_hari_pulang">Kode Hari Pulang</label>
                        <select required="required" id="kode_hari_pulang" name="kode_hari_pulang" class="form-control col-md-4 input-smm">
                            <?php
                            if ($row->kode_hari_pulang == 'SAMEDAY'){
                                echo "
                                <option selected='selected'>SAMEDAY</option>
                                <option>NEXTDAY</option>
                                ";
                            } else {
                                echo "
                                <option>SAMEDAY</option>
                                <option selected='selected'>NEXTDAY</option>
                                ";
                            }
                            ?>
                        </select>
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