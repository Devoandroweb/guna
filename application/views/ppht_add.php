<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">PPH 21 Tarif Information</h4>
                <hr>
                <?php echo form_open('cppht/create');?>
                <div class="container">

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                    <label for="batas_atas">Batas Atas</label>
                                    <input required="required" name="batas_atas" type="text" class="form-control input-smm" id="batas_atas" placeholder="Batas Atas" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="batas_bawah">Batas Bawah</label>
                                    <input required="required" name="batas_bawah" type="text" class="form-control input-smm" id="batas_bawah" placeholder="Batas Bawah" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                                <div class="form-group">
                                    <label for="tarif">Tarif</label>
                                    <input required="required" name="tarif" type="text" class="form-control input-smm" id="tarif" placeholder="Tarif" data-inputmask="'alias': 'currency','prefix':'','digits': '0', 'allowMinus': 'false','rightAlign': 'false'"/>
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input class="btn btn-primary" type="submit" value="Save">
                            <a href="<?=base_URL()?>cppht" class="btn btn-default text-muted">Cancel</a>
                        </div>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>