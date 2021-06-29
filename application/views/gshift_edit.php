<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Grup Sift Information</h4>
                <hr>
                <?php echo form_open('cgshift/update');?>
                <div class="container">
                    <?php foreach($query->result() as $row):?>
                    <div class="rows col-md-12 wells">
                        <div class="row">
                            <div class="rows col-md-6 wellsy btn-sm">
                                <div class="form-group">
                                    <label for="grup">Grup</label>
                                    <input value="<?php echo $row->grup;?>" disabled="disabled" type="text" class="form-control input-smm" id="grup" placeholder="Grup" />
                                    <input name="grup" value="<?php echo $row->grup;?>" type="hidden" class="form-control input-smm" id="grup" placeholder="Grup" />
                                </div>
                                <div class="form-group">
                                    <label for="shift">Shift</label>
                                    <input value="<?php echo $row->shift;?>" disabled="disabled" type="text" class="form-control input-smm" id="shift" placeholder="Shift" />
                                    <input name="shift" value="<?php echo $row->shift;?>" type="hidden" class="form-control input-smm" id="shift" placeholder="Shift" />
                                </div>
                                <div class="form-group">
                                    <label for="nama_shift">Nama Shift</label>
                                    <input maxlength="25" name="nama_shift" value="<?php echo $row->nama_shift;?>" required="required" type="text" class="form-control input-smm" id="nama_shift" placeholder="Nama Shift" />
                                </div>
                                <div class="form-group">
                                    <label for="masuk">Masuk</label>
                                    <input maxlength="20" name="masuk" value="<?php echo $row->masuk;?>" type="text" required="required" class="form-control input-smm" id="masuk" placeholder="Masuk" />
                                </div>
                                <div class="form-group">
                                    <label for="pulang">Pulang</label>
                                    <input maxlength="20" name="pulang" value="<?php echo $row->pulang;?>" type="text" required="required" class="form-control input-smm" id="pulang" placeholder="Pulang" />
                                </div>
                            </div>
                            <div class="rows col-md-6 wellsy btn-sm">
                                <div class="form-group">
                                    <label for="kode_hari_masuk">Kode Hari Masuk</label>
                                    <select required="required" id="kode_hari_masuk" name="kode_hari_masuk" class="form-control input-smm">
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
                                
                                <div class="form-group pt-2">
                                    <label for="kode_hari_pulang">Kode Hari Pulang</label>
                                    <select required="required" id="kode_hari_pulang" name="kode_hari_pulang" class="form-control input-smm">
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
                                <div class="form-group pt-2">
                                    <label for="masuk_valid_awal">Masuk Valid Awal</label>
                                    <input maxlength="20" name="masuk_valid_awal" value="<?php echo $row->masuk_valid_awal;?>" type="text" required="required" class="form-control input-smm" id="masuk_valid_awal" placeholder="Masuk Valid Awal" />
                                </div>
                                <div class="form-group">
                                    <label for="masuk_valid_akhir">Masuk Valid Akhir</label>
                                    <input maxlength="20" name="masuk_valid_akhir" value="<?php echo $row->masuk_valid_akhir;?>" type="text" required="required" class="form-control input-smm" id="masuk_valid_akhir" placeholder="Masuk Valid Akhir" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cgshift" class="btn btn-default text-dark">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

<?php include "footer.php" ?>
</body>
</html>