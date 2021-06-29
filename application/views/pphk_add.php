<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">PPH 21 Komponen Information</h4>
                <hr>
                <?php echo form_open('cpphk/create');?>
                <div class="container">
                    <div class="row">
                        <div class="col">
                             <div class="form-group">
                                <label for="kode_gaji">Kode Gaji</label>
                                <select required="required" id="kode_gaji" name="kode_gaji" class="form-control input-smm">
                                    <option></option>
                                    <?php foreach($query4->result() as $row):?>
                                    <option><?php echo $row->kode_gaji?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cpphk" class="btn btn-default text-muted">Cancel</a>
                            </div>
                        </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>