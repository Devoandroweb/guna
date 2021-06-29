   <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Status Aktual Information</h4>
                    <hr>
                    <?php echo form_open('cstatusaktual/save_update');?>
                    <div class="container">
                            
                        <?php foreach($query->result() as $row):?>
                        <div class="row">
                            <div class="col">
                                <input name="id_status_aktual" value="<?php echo $row->id_status_aktual;?>" type="hidden" class="form-control col-md-4 input-smm" id="id_status_aktual" placeholder="ID" />
                                <div class="form-group">
                                    <label for="statusaktual">Status Aktual</label>
                                    <input maxlength="20" name="status_aktual" value="<?php echo $row->status_aktual;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="status_aktual" placeholder="Status Aktual" />
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="row">
                                <div class="col">
                                    <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cstatusaktual" class="btn btn-light text-muted">Cancel</a>
                                </div>
                            </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
<?php include "footer.php" ?>
</body>
</html>