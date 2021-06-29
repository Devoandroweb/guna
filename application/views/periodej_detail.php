<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail Periode Jamsostek</h4>
                <hr>
                <div class="row">
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                			<tr>
                				<td class="label">Periode</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->periode;?></td>
                			</tr>
                			<tr>
                				<td class="label">Periode Penggajian</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->periode_penggajian;?></td>
                			</tr>
                			<tr>
                				<td class="label">Segmen</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->segmen;?></td>
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
                				<td class="label">Nama Program</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->nama_program;?></td>
                			</tr>
                            <tr>
                                <td class="label">Gaji Dasar</td>
                                <td>:</td>
                                <td class="value"><?php echo $this->fungsi->FormatNum($row->gaji_dasar);?></td>
                            </tr>
                			<?php endforeach;?>

                		</table>

                	</div>
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                			<tr>
                				<td class="label">Maksimal Gaji Dasar</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->FormatNum($row->maksimal_gaji_dasar);?></td>
                			</tr>
                			<tr>
                				<td class="label">Bagian Perusahaan</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->FormatNum($row->bagian_perusahaan);?></td>
                			</tr>
                			<tr>
                				<td class="label">Bagian Karyawan</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->FormatNum($row->bagian_karyawan);?></td>
                			</tr>
                			<tr>
                				<td class="label">Nilai Perusahaan</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->FormatNum($row->nilai_perusahaan);?></td>
                			</tr>
                			<tr>
                				<td class="label">Nilai Karyawan</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->FormatNum($row->nilai_karyawan);?></td>
                			</tr>
                			<tr>
                				<td class="label">Last Update</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->dateToIndo($row->lastupdate);?></td>
                			</tr>
                            <tr>
                                <td class="label">User ID</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->user_id;?></td>
                            </tr>
                			<?php endforeach;?>

                		</table>

                	</div>
                </div>
                <hr>
                <div class="row mt-4">
                	<div class="col">
                		<a href="<?= site_url() ?>cperiodej" class="btn btn-primary">Kembali</a>
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