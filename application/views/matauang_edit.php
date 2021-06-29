<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Mata Uang Information</h4>
                    <hr>
                    <?php echo form_open('cmatauang/save_update');?>
                    <div class="">
                            
                        <?php foreach($query->result() as $row):?>
                        <div class="row">
                            <div class="col">
                                <input name="id_mata_uang" value="<?php echo $row->id_mata_uang;?>" type="hidden" class="form-control col-md-4 input-smm" id="id_mata_uang" placeholder="ID" />
                                    <div class="form-group">
                                        <label for="matauang">Mata Uang</label>
                                        <input maxlength="20" name="mata_uang" value="<?php echo $row->mata_uang;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="mata_uang" placeholder="Mata Uang" />
                                    </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="row">
                                <div class="col">
                                    <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cmatauang" class="btn btn-light text-muted">Cancel</a>
                                </div>
                            </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
<?php include "footer.php" ?>
</body>
</html>