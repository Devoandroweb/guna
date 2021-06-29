   <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Status Perkawinan Information</h4>
                    <hr>
                    <?php echo form_open('cstatusperkawinan/save_update');?>
                    <div class="container">
                            
                        <?php foreach($query->result() as $row):?>
                        <div class="row">
                            <div class="col">
                                <input name="id_status_perkawinan" value="<?php echo $row->id_status_perkawinan;?>" type="hidden" class="form-control col-md-4 input-smm" id="id" placeholder="ID" />
                                    <div class="form-group">
                                        <label for="statusperkawinan">Status Perkawinan</label>
                                        <input maxlength="20" name="status_perkawinan" value="<?php echo $row->status_perkawinan;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="statusperkawinan" placeholder="Status Perkawinan" />
                                    </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="row">
                                <div class="col">
                                    <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cstatusperkawinan" class="btn btn-light">Cancel</a>
                                </div>
                            </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
<?php include "footer.php" ?>
</body>
</html>