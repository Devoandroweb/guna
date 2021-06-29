<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Bank Information</h4>
                <hr>
                <?php echo form_open('cbank/save_update');?>
                <div class="">
                    <?php foreach($query->result() as $row):?>
                    <div class="row">
                        <div class="col">
                            <input name="id_bank" value="<?php echo $row->id_bank;?>" type="hidden" class="form-control col-md-4 input-smm" id="id_bank" placeholder="id_bank" />
                            <div class="form-group">
                                <label for="bank">Bank</label>
                                <input maxlength="20" name="bank" value="<?php echo $row->bank;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="bank" placeholder="Bank" />
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                    <div class="row">
                            <div class="col">
                                <input class="btn-lgs btn btn-primary" type="submit" value="Save">
                                <a href="<?=base_URL()?>cbank" class="btn-lgs btn btn-light">Cancel</a>
                            </div>
                        </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>