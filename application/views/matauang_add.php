<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Grup Information</h4>
                <hr>

                    <?php echo form_open('cmatauang/create');?>
                    <!-- <div class="container">
                            <div class="rows col-md-11 wells btn-info">
                                <div class="rows">
                                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Mata Uang Information</strong></div>
                                    <div class="col-xs-6 col-md-4" align="right">
                                        <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cmatauang" class="btn-lgs btn btn-default">Cancel</a></div>
                                </div>
                            </div>
                        
                        <div class="rows col-md-11 wells">
                            <div class="row">
                                <div class="rows col-md-3 wellsy btn-sm">
                                    <div class="form-group">
                                        <label for="matauang">Mata Uang</label>
                                        <input maxlength="20" name="matauang" required="required" type="text" class="form-control col-md-4 input-smm" id="matauang" placeholder="Mata Uang" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="matauang">Mata Uang</label>
                                <input maxlength="20" name="matauang" required="required" type="text" class="form-control col-md-4 input-smm" id="matauang" placeholder="Mata Uang" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <input class="btn-lgs btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cmatauang" class="btn-lgs btn btn-default text-dark">Cancel</a></div>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>