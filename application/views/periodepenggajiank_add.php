<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Periode Penggajian Komponen Information</h4>
                <hr>
                <?php echo form_open('cperiodepenggajiank/create');?>
                <!-- <div class="container">
                        <div class="rows col-md-11 wells btn-info">
                            <div class="rows">
                                <div class="col-xs-12 col-sm-6 col-md-8"><strong>Periode Penggajian Komponen Information</strong></div>
                                <div class="col-xs-6 col-md-4" align="right">
                                    <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cperiodepenggajiank" class="btn-lgs btn btn-default">Cancel</a></div>
                            </div>
                        </div>
                    
                    <div class="rows col-md-11 wells">
                        <div class="row">
                            <div class="rows col-md-3 wellsy btn-sm">
                                <div class="form-group">
                                    <label for="periode_penggajian">Periode Penggajian</label>
                                    <input maxlength="20" name="periode_penggajian" required="required" type="text" class="form-control col-md-4 input-smm" id="periode_penggajian" placeholder="Periode Penggajian" />
                                </div>
                                <div class="form-group">
                                    <label for="segmen">Segmen</label>
                                    <input maxlength="2" name="segmen" required="required" type="text" class="form-control col-md-4 input-smm" id="segmen" placeholder="Segmen" />
                                </div>
                                <div class="form-group">
                                    <label for="kode_gaji">Kode Gaji</label>
                                    <select required="required" id="grup" name="kode_gaji" class="form-control col-md-4 input-smm">
                                        <option></option>
                                        <?php foreach($query->result() as $row):?>
                                        <option><?php echo $row->kode_gaji?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="periode_penggajian">Periode Penggajian</label>
                            <input maxlength="20" name="periode_penggajian" required="required" type="text" class="form-control col-md-4 input-smm" id="periode_penggajian" placeholder="Periode Penggajian" />
                        </div>
                        <div class="form-group">
                            <label for="segmen">Segmen</label>
                            <input maxlength="2" name="segmen" required="required" type="text" class="form-control col-md-4 input-smm" id="segmen" placeholder="Segmen" />
                        </div>
                        <div class="form-group">
                            <label for="kode_gaji">Kode Gaji</label>
                            <select required="required" id="grup" name="kode_gaji" class="form-control col-md-4 input-smm">
                                <option></option>
                                <?php foreach($query->result() as $row):?>
                                <option><?php echo $row->kode_gaji?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <input class="btn btn-primary" type="submit" value="Save">
                        <a href="<?=base_URL()?>cperiodepenggajiank" class="btn btn-default text-muted">Cancel</a></div>
                    </div>
                </div>
                <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>