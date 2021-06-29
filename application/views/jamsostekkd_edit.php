
    <?php echo form_open('cjamsostekkd/update');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
            <div class="rows col-md-11 wells btn-info">
                <div class="rows">
                    <div class="col-xs-12 col-sm-6 col-md-8"><strong>Jamsostek Komponen Dasar Information</strong></div>
                    <div class="col-xs-6 col-md-4" align="right"><input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cjamsostekkd" class="btn-lgs btn btn-default">Cancel</a></div>
                </div>
            </div>
        <?php foreach($query->result() as $row):?>
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-3 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="id">ID</label>
                        <input value="<?php echo $row->id;?>" disabled="disabled" type="text" class="form-control col-md-4 input-smm" id="id" placeholder="ID" />
                        <input name="id" value="<?php echo $row->id;?>" type="hidden" class="form-control col-md-4 input-smm" id="id" placeholder="ID" />
                    </div>
                    <div class="form-group">
                        <label for="kode_gaji">Kode Gaji</label>
                        <?php
                        $jenis = $this->db->query("SELECT * FROM master_gaji WHERE kode_gaji NOT LIKE '$row->kode_gaji'")->result();
                        ?>
                        <select required="required" id="kode_gaji" name="kode_gaji" class="form-control col-md-4 input-smm">
                            <option selected="selected" value="<?php echo $row->kode_gaji;?>"><?php echo $row->kode_gaji;?></option>
                            <?php
                            foreach ($jenis as $bsd) {
                            echo '<option value="'.$bsd->kode_gaji.'">'.$bsd->kode_gaji.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach;?>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>