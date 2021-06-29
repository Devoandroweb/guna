<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Periode Penggajian Information</h4>
                <hr>

                <?php echo form_open('cperiode/create');?>
                <div class="container">
                    <div class="rows col-md-12 wells m-0 p-0">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="periode">Periode</label>
                                    <input name="periode" required="required" type="text" class="form-control input-smm" placeholder="Periode" />
                                </div>
                                <div class="form-group">
                                    <label for="periode_penggajian">Periode Penggajian</label>
                                    <input maxlength="25" value="BULANAN" readonly name="periode_penggajian" type="text" class="form-control input-smm" placeholder="Periode Penggajian" />
                                </div>
                                <div class="form-group">
                                    <label for="segmen">Segmen</label>
                                    <input maxlength="25" value="1" readonly name="segmen" required="required" type="text" class="form-control input-smm" placeholder="Segmen" />
                                </div>
                                <div class="form-group">
                                    <label for="mulai">Mulai</label>
                                    <input type="text" required="required" class="form-control input-smm" name="mulai" id="tgl" placeholder="Mulai" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="selesai">Selesai</label>
                                    <input type="text" required="required" class="form-control input-smm" name="selesai" id="tgla" placeholder="Selesai" />
                                </div>
                                <div class="form-group">
                                    <label for="thr">THR</label>
                                    <select required="required" id="thr" name="thr" class="form-control input-smm h45">
                                        <option></option>
                                        <option value="Ya">Ya</option>
                                        <option value="Tidak">Tidak</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select required="required" id="status" name="status" class="form-control input-smm h45">
                                        <option></option>
                                        <option value="OPEN">OPEN</option>
                                        <option value="CLOSE">CLOSE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                        <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cperiode" class="btn btn-default text-muted">Cancel</a>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>