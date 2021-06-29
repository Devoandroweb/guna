 
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Agama Information</h4>
                    <hr>
                    <?php echo form_open('cdepartemen/save_update');?>
                    <div class="container">
                            
                        <?php foreach($query->result() as $row):?>
                        <div class="row">
                            <div class="col">
                                <input name="id_departemen" value="<?php echo $row->id_departemen;?>" type="hidden" class="form-control col-md-4 input-smm" id="id_departemen" placeholder="ID" />
                                <div class="form-group">
                                    <label for="departemen">Departemen</label>
                                    <input maxlength="20" name="departemen" value="<?php echo $row->departemen;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="departemen" placeholder="Departemen" />
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">
                                <a href="<?=base_URL()?>cdepartemen" class="btn btn-light text-muted">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
<?php include "footer.php" ?>
</body>
</html>