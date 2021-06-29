


    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail Gaji Karyawan</h4>
                <hr>
                <table>
                    <?php 
                    $q = $this->db->query("SELECT nik FROM master_gaji_karyawan WHERE nik='$nik'");
                    ?>
                    <tr>
        				<td class="label">NIK</td>
        				<td>:</td>
        				<td class="value"><?php echo $q->row('nik');?></td>
        			</tr>
                        

                    <?php 
                    $n = 0;
                    foreach($query->result() as $row):
                    $n++;
                    ?>
                    
                    <?php 
                    $qs = $this->db->query("SELECT * FROM master_gaji where kode_gaji='$row->kode_gaji'")->row();
                    ?>
                	<tr>
        				<td class="label"><?php echo $qs->keterangan;?></td>
        				<td>:</td>
        				<td class="value"><?php echo $this->fungsi->FormatNum($row->nilai_gaji);?></td>
        			</tr>
        			<?php endforeach;?>
                 </table>       
                <!-- <div class="row">
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                			<tr>
                				<td class="label">NIK</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->id_spl;?></td>
                			</tr>
                			<?php endforeach;?>

                		</table>

                	</div>
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                				<tr>
                				<td class="label"><?php echo $row->keterangan;?></td>
                				<td>:</td>
                				<td class="value"><?php echo $row->nilai_gaji;?></td>
                			</tr>
                            <?php endforeach;?>

                		</table>

                	</div>
                </div> -->
                <hr>
                <div class="row mt-4">
                	<div class="col">
                		<a href="<?= site_url() ?>cgajikaryawan" class="btn btn-primary">Kembali</a>
                	</div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        	$(document).ready(function () {
        		$("table tr .label").addClass("font-weight-bold pr-4");
        		$("table tr .value").addClass("pl-2");
        	})
        </script>
<?php include "footer.php" ?>
</body>
</html>