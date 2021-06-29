   <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Status Karyawan Information</h4>
                    <hr>
                    <?php echo form_open('cstatuskaryawan/save_update');?>
                    <div class="">
                            
                        <?php foreach($query->result() as $row):?>
                        <div class="row">
                            <div class="col">
                                <input name="id_status_karyawan" value="<?php echo $row->id_status_karyawan;?>" type="hidden" class="form-control col-md-4 input-smm" id="id_status_karyawan" placeholder="ID" />
                                    <div class="form-group">
                                        <label for="statuskaryawan">Status Karyawan</label>
                                        <input maxlength="20" name="status" value="<?php echo $row->status;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="statuskaryawan" placeholder="Status Karyawan" />
                                    </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="row">
                                <div class="col">
                                    <input class="btn btn-primary" type="submit" value="Save">
                                    <a href="<?=base_URL()?>cstatuskaryawan" class="btn btn-light text-muted">Cancel</a>
                                </div>
                            </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
<?php include "footer.php" ?>
</body>
</html>