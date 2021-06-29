<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail Absen Commit</h4>
                <hr>
                <div class="row">
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                			<tr>
                				<td class="label">Enroll</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->enroll;?></td>
                			</tr>
                			<tr>
                				<td class="label">Tanggal</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->dateToIndo($row->tanggal);?></td>
                			</tr>
                			<tr>
                				<td class="label">Periode</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->periode;?></td>
                			</tr>
                			<tr>
                				<td class="label">NIK</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->nik;?></td>
                			</tr>
                			<tr>
                				<td class="label">Nama</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->nama;?></td>
                			</tr>
                			<tr>
                				<td class="label">Status Perkawinan</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->status_perkawinan;?></td>
                			</tr>
                            <tr>
                                <td class="label">Jam Masuk</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->dateToIndo($row->jam_masuk);?></td>
                            </tr>

                			<?php endforeach;?>

                		</table>

                	</div>
                    <div class="col">

                        <table>
                            <?php foreach($query->result() as $row):?>
                            <tr>
                                <td class="label">Departemen</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->departemen;?></td>
                            </tr>
                            <tr>
                                <td class="label">Jabatan</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->jabatan;?></td>
                            </tr>
                            <tr>
                                <td class="label">Status Kerja</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->status_kerja;?></td>
                            </tr>
                            <tr>
                                <td class="label">Sift</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->shift;?></td>
                            </tr>
                            <tr>
                                <td class="label">Status Aktual</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->status_aktual;?></td>
                            </tr>
                            <tr>
                                <td class="label">Keterangan</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->keterangan;?></td>
                            </tr>
                            <tr>
                                <td class="label">Jam Pulang</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->dateToIndo($row->jam_pulang);?></td>
                            </tr>

                            <?php endforeach;?>

                        </table>

                    </div>
                </div>
                <hr>
                <div class="row mt-4">
                	<div class="col">
                		<a href="<?= site_url() ?>ctransct" class="btn btn-primary">Kembali</a>
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