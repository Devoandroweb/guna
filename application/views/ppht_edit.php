<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">PPH 21 Tarif Information</h4>
                <hr>
                <?php echo form_open('cppht/update');?>

                <div class="container">
                    
                    <?php foreach($query->result() as $row):?>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                    <label for="kode_gaji">Kode Tarif</label>
                                    <input value="<?php echo $row->kode_tarif;?>" disabled="disabled" type="text" class="form-control input-smm" id="kode_tarif" />
                                    <input name="kode_tarif" value="<?php echo $row->kode_tarif;?>" type="hidden" class="form-control input-smm" id="kode_tarif" />
                                </div>
                                <div class="form-group">
                                    <label for="batas_atas">Batas Atas</label>
                                    <input value="<?php echo $row->batas_atas;?>" required="required"  name="batas_atas" type="text" class="form-control input-smm" id="batas_atas" placeholder="Batas Atas" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="batas_atas">Batas Bawah</label>
                                    <input value="<?php echo $row->batas_bawah;?>" required="required" name="batas_bawah" type="text" class="form-control input-smm" id="batas_bawah" placeholder="Batas Bawah" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="tarif">Tarif</label>
                                    <input value="<?php echo $row->tarif;?>" required="required" name="tarif" type="text" class="form-control input-smm" id="tarif" placeholder="Tarif" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                
                        </div>
                    </div>
                    <?php endforeach;?>
                    <div class="row">
                        <div class="col">
                            <input class="btn-lgs btn btn-primary" type="submit" value="Save">
                            <a href="<?=base_URL()?>cppht" class="btn-lgs btn btn-default text-muted">Cancel</a>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>