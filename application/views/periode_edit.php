<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Periode Penggajian Information</h4>
                <hr>
                <?php echo form_open('cperiode/update');?>

                <div class="container">
                        
                    <?php foreach($query->result() as $row):?>
                    <div class="rows col-md-12 wells m-0 p-0">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="periode">Periode</label>
                                    <input value="<?php echo $row->periode;?>" disabled="disabled" type="text" class="form-control input-smm" />
                                    <input name="periode" value="<?php echo $row->periode;?>" type="hidden" class="form-control input-smm" />
                                </div>
                                <div class="form-group">
                                    <label for="periode_penggajian">Periode Penggajian</label>
                                    <input value="<?php echo $row->periode_penggajian;?>" disabled="disabled" type="text" class="form-control input-smm" />
                                    <input name="periode_penggajian" value="<?php echo $row->periode_penggajian;?>" type="hidden" class="form-control input-smm" />
                                </div>
                                <div class="form-group">
                                    <label for="segmen">Segmen</label>
                                    <input value="<?php echo $row->segmen;?>" disabled="disabled" type="text" class="form-control input-smm" />
                                    <input name="segmen" value="<?php echo $row->segmen;?>" type="hidden" class="form-control input-smm" />
                                </div>
                                <div class="form-group">
                                    <label for="mulai">Mulai</label>
                                    <input type="text" value="<?php echo $row->mulai;?>" required="required" class="form-control input-smm" name="mulai" id="tgl" placeholder="Mulai" />
                                </div>
                                
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="selesai">Selesai</label>
                                    <input type="text" value="<?php echo $row->selesai;?>" required="required" class="form-control input-smm" name="selesai" id="tgla" placeholder="Selesai" />
                                </div>
                                <div class="form-group">
                                    <label for="thr">THR</label>
                                    <select required="required" id="thr" name="thr" class="form-control input-smm h45">
                                        <option></option>
                                        <?php
                                        if ($row->thr == "Ya"){
                                            echo "
                                            <option value='Ya' selected='selected'>Ya</option>
                                            <option value='Tidak'>Tidak</option>
                                            ";
                                        } else if ($row->thr == "Tidak"){
                                            echo "
                                            <option value='Ya'>Ya</option>
                                            <option value='Tidak' selected='selected'>Tidak</option>
                                            ";
                                        } else {
                                            echo "
                                            <option value='Ya'>Ya</option>
                                            <option value='Tidak'>Tidak</option>
                                            ";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select required="required" id="status" name="status" class="form-control input-smm h45">
                                        <option></option>
                                        <?php
                                        if ($row->status == "OPEN"){
                                            echo "
                                            <option value='OPEN' selected='selected'>OPEN</option>
                                            <option value='CLOSE'>CLOSE</option>
                                            ";
                                        } else if ($row->status == "CLOSE"){
                                            echo "
                                            <option value='OPEN'>OPEN</option>
                                            <option value='CLOSE' selected='selected'>CLOSE</option>
                                            ";
                                        } else {
                                            echo "
                                            <option value='OPEN'>OPEN</option>
                                            <option value='CLOSE'>CLOSE</option>
                                            ";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <input class="btn btn-primary" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cperiode" class="btn btn-default text-muted">Cancel</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach;?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
<?php include "footer.php" ?>
</body>
</html>