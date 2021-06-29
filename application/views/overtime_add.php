<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Overtime Information</h4>
                <hr>
                <?php echo form_open('covertime/create');?>
                <div class="container">
                    <div class="rows col-md-12 wells m-0 p-0">
                        <div class="row">
                            <div class="rows col-md-4 wellsy btn-sm">
                                <div class="form-group">
                                    <label>Jam</label>
                                    <input maxlength="5" name="jam" required="required" type="text" class="form-control input-smm" placeholder="Jam" />
                                </div>
                            </div>
                            <div class="rows col-md-4 wellsy btn-sm">
                                <div class="form-group">
                                    <label>Index Hari Kerja</label>
                                    <input maxlength="5" name="index_hari_kerja" required="required" type="text" class="form-control input-smm" placeholder="Nilai" />
                                </div>
                            </div>
                            <div class="rows col-md-4 wellsy btn-sm">
                                <div class="form-group">
                                    <label>Index Hari Libur</label>
                                    <input maxlength="5" name="index_hari_libur" required="required" type="text" class="form-control input-smm" placeholder="Index Hari Libur" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rows col-md-12">
                    <div class="rows">
                        <input class="btn btn-primary" type="submit" value="Save">
                        <a href="<?=base_URL()?>covertime" class="btn btn-default text-muted">Cancel</a>
                    </div>
                </div>
        <?php echo form_close(); ?>
        </div>
    </div>
<?php include "footer.php" ?>
</body>
</html>