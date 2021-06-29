
    <?php echo form_open('cpphm/create');?>
    <p>&nbsp;</p><br /><br />
    <div class="container">
        <div class="rows col-md-11 wells btn-info">
            <div class="rows">
                <div class="col-xs-12 col-sm-6 col-md-8"><strong>PPH 21 Metode Information</strong></div>
                <div class="col-xs-6 col-md-4" align="right">
                    <input class="btn-lgs btn btn-default" type="submit" value="Save">&nbsp;<a href="<?=base_URL()?>cpphm" class="btn-lgs btn btn-default">Cancel</a></div>
            </div>
        </div>
        
        <div class="rows col-md-11 wells">
            <div class="row">
                <div class="col-md-3 btn-sm">&nbsp;</div>
                <div class="rows col-md-3 wellsy btn-sm">
                    <div class="form-group">
                        <label for="periode">Periode</label>
                        <?php
                        $periode = $this->db->query("SELECT * FROM trans_periode")->result();
                        ?>
                        <select required="required" name="periode" class="form-control col-md-4 input-smm">
                            <option></option>
                            <?php
                            foreach ($periode as $bp) {
                                echo '<option value="'.$bp->periode.'">'.$bp->periode.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pph21">PPH21 Metode</label>
                        <?php
                        $pph21 = $this->db->query("SELECT * FROM content_pph21_metode")->result();
                        ?>
                        <select required="required" name="pph21_metode" class="form-control col-md-4 input-smm">
                            <option></option>
                            <?php
                            foreach ($pph21 as $b) {
                                echo '<option value="'.$b->pph21_metode.'">'.$b->pph21_metode.'</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
<?php include "footer.php" ?>
</body>
</html>