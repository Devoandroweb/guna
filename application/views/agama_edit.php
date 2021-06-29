
 
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Agama Information</h4>
                    <hr>
                    <?php echo form_open('cagama/save_update');?>
                    <div class="">
                            
                        <?php foreach($query->result() as $row):?>
                        <div class="row">
                            <div class="col">
                                <input name="id_agama" value="<?php echo $row->id_agama;?>" type="hidden" class="form-control col-md-4 input-smm" id="id" placeholder="id_agama" />
                                <div class="form-group">
                                    <label for="agama">Agama</label>
                                    <input maxlength="20" name="agama" value="<?php echo $row->agama;?>" required="required" type="text" class="form-control col-md-4 input-smm" id="agama" placeholder="Agama" />
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>
                        <div class="row">
                                <div class="col">
                                    <input class="btn btn-primary" type="submit" value="Save">
                                    <a href="<?=base_URL()?>cagama" class="btn btn-light">Cancel</a>
                                </div>
                            </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
<?php include "footer.php" ?>
</body>
</html>