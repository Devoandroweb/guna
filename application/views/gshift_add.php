<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Absen Shift Information</h4>
                <hr>
                <?php echo form_open('cgshift/create');?>
                <div class="container">
                    <div class="row">
                        <div class="col">
                                <div class="form-group">
                                    <label for="grup">Grup</label>
                                    <input maxlength="20" name="grup" required="required" type="text" class="form-control  input-smm" id="grup" placeholder="Grup" />
                                </div>
                                <div class="form-group">
                                    <label for="shift">Shift</label>
                                    <input maxlength="20" name="shift" required="required" type="text" class="form-control  input-smm" id="shift" placeholder="Shift" />
                                </div>
                                <div class="form-group">
                                    <label for="nama_shift">Nama Shift</label>
                                    <input maxlength="25" name="nama_shift" required="required" type="text" class="form-control  input-smm" id="nama_shift" placeholder="Nama Shift" />
                                </div>
                                <div class="form-group">
                                    <label for="masuk">Masuk</label>
                                    <input maxlength="20" name="masuk" type="text" required="required" class="form-control  input-smm" id="masuk" placeholder="Masuk" />
                                </div>
                                <div class="form-group">
                                    <label for="pulang">Pulang</label>
                                    <input maxlength="20" name="pulang" type="text" required="required" class="form-control  input-smm" id="pulang" placeholder="Pulang" />
                                </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                    <label for="kode_hari_masuk">Kode Hari Masuk</label>
                                    <select required="required" id="kode_hari_masuk" name="kode_hari_masuk" class="form-control h45">
                                        <option></option>
                                        <option>SAMEDAY</option>
                                        <option>NEXTDAY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_hari_pulang">Kode Hari Pulang</label>
                                    <select required="required" id="kode_hari_pulang" name="kode_hari_pulang" class="form-control h45 input-smm">
                                        <option></option>
                                        <option>SAMEDAY</option>
                                        <option>NEXTDAY</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="masuk_valid_awal">Masuk Valid Awal</label>
                                    <input required="required" maxlength="20" name="masuk_valid_awal" type="text" required="required" class="form-control  input-smm" id="masuk_valid_awal" placeholder="Masuk Valid Awal" />
                                </div>
                                <div class="form-group">
                                    <label for="masuk_valid_akhir">Masuk Valid Akhir</label>
                                    <input required="required" maxlength="20" name="masuk_valid_akhir" type="text" required="required" class="form-control  input-smm" id="masuk_valid_akhir" placeholder="Masuk Valid Akhir" />
                                </div>
                        </div>
                    </div>
                    <!-- <div class="rows col-md-12 wells">
                        <div class="row">
                            <div class="col-md-3 btn-sm">&nbsp;</div>
                            <div class="rows col-md-3 wellsy btn-sm">
                                <div class="form-group">
                                    <label for="grup">Grup</label>
                                    <input maxlength="20" name="grup" required="required" type="text" class="form-control col-md-4 input-smm" id="grup" placeholder="Grup" />
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
                                <div class="form-group">
                                    <label for="masuk_valid_awal">Masuk Valid Awal</label>
                                    <input required="required" maxlength="20" name="masuk_valid_awal" type="text" required="required" class="form-control col-md-4 input-smm" id="masuk_valid_awal" placeholder="Masuk Valid Awal" />
                                </div>
                                <div class="form-group">
                                    <label for="masuk_valid_akhir">Masuk Valid Akhir</label>
                                    <input required="required" maxlength="20" name="masuk_valid_akhir" type="text" required="required" class="form-control col-md-4 input-smm" id="masuk_valid_akhir" placeholder="Masuk Valid Akhir" />
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col">
                            <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cgshift" class="btn btn-default text-muted">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
        </div>
    </div>
<?php include "footer.php" ?>
</body>
</html>