<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">PPH 21 PTKP Information</h4>
                <hr>
                <?php echo form_open('cpphp/create');?>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="status_perkawinan">Status Perkawinan</label>
                                <select required="required" id="status_perkawinan" name="status_perkawinan" class="form-control input-smm">
                                    <option></option>
                                    <?php foreach($query4->result() as $row):?>
                                    <option><?php echo $row->status_perkawinan?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nilai_ptkp">Nilai PTKP</label>
                                <input name="nilai_ptkp" required="required" type="text" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'" class="form-control input-smm" id="nilai_ptkp" placeholder="Nilai PTKP" />
                            </div>
                            
                        </div>
                    </div>
                     <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">
                                <a href="<?=base_URL()?>cpphp" class="btn btn-default text-muted">Cancel</a>
                            </div>
                        </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>