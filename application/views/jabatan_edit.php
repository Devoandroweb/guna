<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Jabatan Information</h4>
                    <hr>
                    <?php echo form_open('cjabatan/save_update');?>
                    <div class="container">
                            
                        <?php foreach($query->result() as $row):?>
                        <div class="row">
                            <div class="col">
                                <input name="id_jabatan" value="<?php echo $row->id_jabatan;?>" type="hidden" class="form-control col-md-4 input-smm" id="id_jabatan" placeholder="ID" />
                                    <div class="form-group">
                                        <label for="jabatan">Jabatan</label>
                                        <input maxlength="20" name="jabatan" value="<?php echo $row->jabatan;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="jabatan" placeholder="Jabatan" />
                                    </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="row">
                                <div class="col">
                                    <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cjabatan" class="btn btn-light text-muted">Cancel</a>
                                </div>
                            </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
<?php include "footer.php" ?>
</body>
</html>