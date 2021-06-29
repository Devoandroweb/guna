<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail Absen</h4>
                <hr>
                <div class="row">
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                			<tr>
                				<td class="label">Tanggal</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->dateToIndo($row->tanggal);?></td>
                			</tr>
                			<tr>
                				<td class="label">Enroll</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->enroll;?></td>
                			</tr>
                			<tr>
                				<td class="label">Periode</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->periode;?></td>
                			</tr>
                            <tr>
                                <td class="label">Waktu</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->dateToIndo($row->waktu);?></td>
                            </tr>
                			<tr>
                				<td class="label">Status Aktual</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->status_aktual;?></td>
                			</tr>
                			<?php endforeach;?>

                		</table>

                	</div>
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                			
                			<tr>
                				<td class="label">Kondisi</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->kondisi;?></td>
                			</tr>
                			<tr>
                				<td class="label">Shift</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->shift;?></td>
                			</tr>
                			<tr>
                				<td class="label">Kondisi Baru</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->kondisi_baru;?></td>
                			</tr>
                            <tr>
                                <td class="label">Status</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->status;?></td>
                            </tr>
                            <tr>
                                <td class="label">Operasi</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->operasi;?></td>
                            </tr>
                			<?php endforeach;?>

                		</table>

                	</div>
                </div>
                <hr>
                <div class="row mt-4">
                	<div class="col">
                		<a href="<?= site_url() ?>cabsen" class="btn btn-primary">Kembali</a>
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