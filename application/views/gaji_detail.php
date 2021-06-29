<div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                <h4 class="card-title">Detail Gaji</h4>
                <hr>
                <div class="row">
                	<div class="col">

                		<table>
                			<?php foreach($query->result() as $row):?>
                			<tr>
                				<td class="label">Kode Gaji</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->kode_gaji;?></td>
                			</tr>
                			<tr>
                				<td class="label">Jenis</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->jenis;?></td>
                			</tr>
                			<tr>
                				<td class="label">Periode Hitung</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->periode_hitung;?></td>
                			</tr>
                			<tr>
                				<td class="label">Rumus</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->rumus;?></td>
                			</tr>
                			<tr>
                				<td class="label">Last Update</td>
                				<td>:</td>
                				<td class="value"><?php echo $this->fungsi->dateToIndo($row->lastupdate);?></td>
                			</tr>
                			<tr>
                				<td class="label">Keterangan</td>
                				<td>:</td>
                				<td class="value"><?php echo $row->keterangan;?></td>
                			</tr>
                			<?php endforeach;?>

                		</table>

                	</div>
                </div>
                <hr>
                <div class="row mt-4">
                	<div class="col">
                		<a href="<?= site_url() ?>cgaji" class="btn btn-primary">Kembali</a>
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