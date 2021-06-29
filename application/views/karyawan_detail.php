<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail Karyawan</h4>
                <hr>
                <div class="row">
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
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
                				<td class="label">Jenis Kelamin</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->jenis_kelamin;?></td>
                			</tr>
                			<tr>
                				<td class="label">Tanggal Lahir</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->dateToIndo($row->tanggal_lahir);?></td>
                			</tr>
                			<tr>
                				<td class="label">Agama</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->agama;?></td>
                			</tr>
                			<tr>
                				<td class="label">Status Perkawinan</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->status_perkawinan;?></td>
                			</tr>
                            <tr>
                                <td class="label">Alamat</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->alamat;?></td>
                            </tr>
                            <tr>
                                <td class="label">Telepon</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->telepon;?></td>
                            </tr>
                            <tr>
                                <td class="label">Email</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->email;?></td>
                            </tr>
                            <tr>
                                <td class="label">Departemen</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->departemen;?></td>
                            </tr>
                            <tr>
                                <td class="label">Grup</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->grup;?></td>
                            </tr>
                            <tr>
                                <td class="label">Jabatan</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->jabatan;?></td>
                            </tr>
                			<?php endforeach;?>

                		</table>

                	</div>
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                			<tr>
                				<td class="label">Tanggal Masuk</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->dateToIndo($row->tanggal_masuk);?></td>
                			</tr>
                			<tr>
                				<td class="label">Akhir Kontrak</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->dateToIndo($row->akhir_kontrak);?></td>
                			</tr>
                			<tr>
                				<td class="label">Status</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->status;?></td>
                			</tr>
                			<tr>
                				<td class="label">NPWP</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->npwp;?></td>
                			</tr>
                			<tr>
                				<td class="label">Bank</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->bank;?></td>
                			</tr>
                			<tr>
                				<td class="label">No. Rekening</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->no_rekening;?></td>
                			</tr>
                            <tr>
                                <td class="label">Pemilik Rekening</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->pemilik_rekening;?></td>
                            </tr>
                            <tr>
                                <td class="label">Periode Penggajian</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->periode_penggajian;?></td>
                            </tr>
                            <tr>
                                <td class="label">Mata Uang</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->pph21_metode;?></td>
                            </tr>
                            <tr>
                                <td class="label">BPJS Kesehatan</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->bpjs_kesehatan;?></td>
                            </tr>
                            <tr>
                                <td class="label">Enroll</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->enroll;?></td>
                            </tr>
                            <tr>
                                <td class="label">Aktif</td>
                                <td>:</td>
                                <td class="value"><?php echo $row->aktif;?></td>
                            </tr>
                			<?php endforeach;?>

                		</table>

                	</div>
                </div>
                <hr>
                <div class="row mt-4">
                	<div class="col">
                		<a href="<?= site_url() ?>ckaryawan" class="btn btn-primary">Kembali</a>
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