<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Overtime Information</h4>
                <hr>
                <?php echo form_open('covertime/update');?>
                <div class="container">
                        
                    <?php foreach($query->result() as $row):?>
                    <div class="rows col-md-12 wells">
                        <div class="row">
                            <div class="rows col-md-4 wellsy btn-sm">
                                <div class="form-group">
                                    <label>Jam</label>
                                    <input value="<?php echo $row->jam;?>" disabled="disabled" type="text" class="form-control input-smm" placeholder="Jam" />
                                    <input name="jam" value="<?php echo $row->jam;?>" type="hidden" class="form-control input-smm" />
                                </div>
                            </div>
                            <div class="rows col-md-4 wellsy btn-sm">
                                <div class="form-group">
                                    <label>Index Hari Kerja</label>
                                    <input value="<?php echo $row->index_hari_kerja;?>" maxlength="5" name="index_hari_kerja" required="required" type="text" class="form-control input-smm" placeholder="Nilai" />
                                </div>
                            </div>
                            <div class="rows col-md-4 wellsy btn-sm">
                                <div class="form-group">
                                    <label>Index Hari Libur</label>
                                    <input value="<?php echo $row->index_hari_libur;?>" maxlength="5" name="index_hari_libur" required="required" type="text" class="form-control input-smm" placeholder="Index Hari Libur" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>covertime" class="btn btn-default text-muted">Cancel</a>
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