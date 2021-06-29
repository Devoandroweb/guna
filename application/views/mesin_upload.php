        <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
        <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Absen Aktual</strong></div>
                </div>
            </div>

            <div class="rows col-md-11 wells">
                <div class="row">
                    <div class="col-md-2 btn-sm">&nbsp;</div>
                    <div class="rows col-md-8 btn-sm">
                        <?php if (isset($error)): ?>
                        <div class="alert alert-error"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($this->session->flashdata('success') == TRUE): ?>
                        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label for="x" class="col-sm-3 control-label">Tabel</label>
                            <div class="col-sm-5">
                                <input name="tb1" id="tb1" value="client" type="hidden" />
                                <label for="x" class="col-sm-8 control-label">Master Absen Aktual</label>
                            </div>
                        </div>
                       
                        <div class="form-group">
                            <label for="x" class="col-sm-3 control-label">File (csv)</label>
                            <div class="col-sm-8">
                              <form method="post" action="<?php echo base_url() ?>cmesin/importcsv" enctype="multipart/form-data">
                              <input class="btn-lgs btn btn-default" type="file" name="userfile" required="required" placeholder="Pilih File" accept=".csv"><br /><br />
                              <input name="submit" class="btn-lgs btn btn-default" type="submit" value="Upload">
                              </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>


