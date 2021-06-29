
<div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-md-6 col-lg-3 grid-margin stretch-card">
          <div class="card bg-gradient-primary text-white text-center card-shadow-primary">
            <div class="card-body">
              <h6 class="font-weight-normal">Total Karyawan</h6>
              <h2 class="mb-0"><?= $totalKaryawan ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 grid-margin stretch-card">
          <div class="card bg-gradient-danger text-white text-center card-shadow-danger">
            <div class="card-body">
              <h6 class="font-weight-normal">Total Izin Bulan ini</h6>
              <h2 class="mb-0"><?= $totalIzinBulanIni ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 grid-margin stretch-card">
          <div class="card bg-gradient-warning text-white text-center card-shadow-warning">
            <div class="card-body">
              <h6 class="font-weight-normal">Total Absen Bulan ini</h6>
              <h2 class="mb-0"><?= $totalAbsenBulanIni ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 grid-margin stretch-card">
          <div class="card bg-gradient-info text-white text-center card-shadow-info">
            <div class="card-body">
              <h6 class="font-weight-normal">Total Gaji Bulan ini</h6>
              <h2 class="mb-0"><?= $this->fungsi->convertMoneyToJt($totalGajiBulanIni) ?></h2>
              <!-- <h2 class="mb-0"><?= $totalGajiBulanIni; ?></h2> -->
            </div>
          </div>
        </div>
      </div>
      <div class="row grid-margin">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Izin/Cuti Terbaru</h4>
              <div class="table-responsive mt-2">
                <table class="table mt-3 border-top">
                  <thead>
                    <tr>
                      <th class="font-weight-bold">Periode</th>
                      <th class="font-weight-bold">Nik</th>
                      <th class="font-weight-bold">Nama</th>
                      <th class="font-weight-bold">Dari Tanggal</th>
                      <th class="font-weight-bold">Sampai Tanggal</th>
                      <th class="font-weight-bold">Keterangan</th>
                      <th class="font-weight-bold">Status</th>
                    </tr>
                  </thead>
                  <tbody>

                  	<?php 
                  	$no = 1;
                  	foreach ($izincuti->result() as $key): ?>
                    <tr>
                      <td><?= $key->periode; ?></td>
                      <td><?= $key->nik; ?></td>
                      <td><?= $key->nama; ?></td>
                      <td><?= $key->dari_tanggal; ?></td>
                      <td><?= $key->sampai_tanggal; ?></td>
                      <td><?= $key->keterangan; ?></td>
                      <td>
                      <?php 

                        	$q = "SELECT * from content_status_aktual where id_status_aktual='".$key->id_status_aktual."' LIMIT 1";
      					$resultStatusAktual = $this->db->query($q)->result();


      					$colorBadge = array(
      						1 => 'badge-primary', 
      						2 => 'badge-info', 
      						3 => 'badge-danger', 
      						4 => 'badge-success', 
      					);

      					foreach ($resultStatusAktual as $key) {
      						if ($no > 4) {
      							echo '<div class="badge badge-warning badge-fw">'.$key->status_aktual.'</div>';
      							
      						}else{
      							echo '<div class="badge '.$colorBadge[$no].' badge-fw">'.$key->status_aktual.'</div>';
      						}
      					}
                      ?>
                      </td>
                    </tr>
                	<?php 
                	$no++;
                	endforeach;
                	?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Total Gaji bulanan</h4>
              <p class="card-description"></p>

              <div class="demo-chart">
                <canvas id="dashboard-monthly-analytics"></canvas> 
                <script type="text/javascript">
                  (function($) {
                    'use strict';
                    $(function() {

                      if ($("#dashboard-monthly-analytics").length) {
                        var ctx = document.getElementById('dashboard-monthly-analytics').getContext("2d");
                        var myChart = new Chart(ctx, {
                          type: 'line',
                          data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Arl', 'May', 'Jun', 'Jul', 'Aug','Sept','Okt','Nov','Des'],
                            datasets: [{
                                label: "Ios",
                                borderColor: '#f2a654',
                                backgroundColor: '#f2a654',
                                pointRadius: 0,
                                fill: true,
                                borderWidth: 1,
                                fill: 'origin',
                                data: [
                                  <?= $gajiPerBulan['jan'] ?>, 
                                  <?= $gajiPerBulan['feb'] ?>, 
                                  <?= $gajiPerBulan['mar'] ?>, 
                                  <?= $gajiPerBulan['apr'] ?>, 
                                  <?= $gajiPerBulan['mei'] ?>, 
                                  <?= $gajiPerBulan['jun'] ?>, 
                                  <?= $gajiPerBulan['jul'] ?>, 
                                  <?= $gajiPerBulan['agu'] ?>, 
                                  <?= $gajiPerBulan['sep'] ?>, 
                                  <?= $gajiPerBulan['okt'] ?>, 
                                  <?= $gajiPerBulan['nov'] ?>, 
                                  <?= $gajiPerBulan['des'] ?>,  
                                 
                                ]
                              },
                              // {
                              //   label: "Android",
                              //   borderColor: 'rgba(235, 105, 143, .9)',
                              //   backgroundColor: 'rgba(235, 105, 143, .9)',
                              //   pointRadius: 0,
                              //   fill: true,
                              //   borderWidth: 1,
                              //   fill: 'origin',
                              //   data: [0, 35, 0, 0, 30, 0, 0, 0]
                              // },
                              // {
                              //   label: "Windows",
                              //   borderColor: 'rgba(241, 155, 84, .8)',
                              //   backgroundColor: 'rgba(241, 155, 84, .8)',
                              //   pointRadius: 0,
                              //   fill: true,
                              //   borderWidth: 1,
                              //   fill: 'origin',
                              //   data: [0, 0, 0, 40, 10, 50, 0, 0]
                              // }
                            ]
                          },
                          options: {
                            maintainAspectRatio: false,
                            legend: {
                              display: false,
                              position: "top"
                            },
                            scales: {
                              xAxes: [{
                                ticks: {
                                  display: true,
                                  beginAtZero: true,
                                  fontColor: '#696969'
                                },
                                gridLines: {
                                  display: false,
                                  drawBorder: false,
                                  color: 'transparent',
                                  zeroLineColor: '#eeeeee'
                                }
                              }],
                              yAxes: [{
                                gridLines: {
                                  drawBorder: false,
                                  display: true,
                                  color: '#b8b8b8',
                                },
                                categoryPercentage: 0.5,
                                ticks: {
                                  display: true,
                                  beginAtZero: true,
                                  stepSize: 20000000,
                                  max: 80000000,
                                  fontColor: '#696969'
                                }
                              }]
                            },
                          },
                          elements: {
                            point: {
                              radius: 0
                            }
                          }
                        });
                        document.getElementById('js-legend').innerHTML = myChart.generateLegend();
                      }
                      
                    });
                  })(jQuery);
                </script>                 
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
      	<div class="col">
      		<div class="card">
      			<div class="card-body p-4">
              <h6 class="font-weight-normal">Jalan Pintas</h6>
              <hr>  
      				<div class="row">
      					<div class="col">
      						<a class="btn btn-outline-dark btn-icon-text btn-block" href="<?= base_url() ?>ckaryawan">
                          <i class="mdi mdi-bullseye-arrow btn-icon-prepend mdi-36px text-danger"></i>
                          <span class="d-inline-block text-left">
                            <small class="font-weight-light d-block">Loncat ke Halaman</small>
                            Data Karyawan
                          </span>
                        </a>
      					</div>
      					<div class="col">
      						<a class="btn btn-outline-dark btn-icon-text btn-block" href="<?= base_url() ?>ctpgajikaryawan">
                          <i class="mdi mdi-camera-timer btn-icon-prepend mdi-36px text-danger"></i>
                          <span class="d-inline-block text-left">
                            <small class="font-weight-light d-block">Loncat ke Halaman</small>
                            Bayar Gaji
                          </span>
                        </a>
      					</div>
      					<div class="col">
      						<a class="btn btn-outline-dark btn-icon-text btn-block" href="<?= base_url() ?>covertime">
                          <i class="mdi mdi-arrow-decision-outline btn-icon-prepend mdi-36px text-danger"></i>
                          <span class="d-inline-block text-left">
                            <small class="font-weight-light d-block">Loncat ke Halaman</small>
                            Overtime
                          </span>
                        </a>
      					</div>
      					<div class="col">
      						<a class="btn btn-outline-dark btn-icon-text btn-block" href="<?= base_url() ?>cabsensakitcuti">
                          <i class="mdi mdi-pencil-box-outline btn-icon-prepend mdi-36px text-danger"></i>
                          <span class="d-inline-block text-left">
                            <small class="font-weight-light d-block">Loncat ke Halaman</small>
                            Form Cuti
                          </span>
                        </a>
      					</div>
      					<!-- <div class="col">
      						<a class="btn btn-outline-dark btn-icon-text btn-block" href="<?= base_url() ?>cabsen">
                          <i class="mdi mdi-apple btn-icon-prepend mdi-36px"></i>
                          <span class="d-inline-block text-left">
                            <small class="font-weight-light d-block">Loncat ke Halaman</small>
                            Absensi
                          </span>
                        </a>
      					</div> -->
      				</div>
      			</div>
      		</div>
      	</div>
      </div>
    </div>
    <!-- content-wrapper ends -->
    <!-- partial:partials/_footer.html -->
    <!-- partial -->
    <?php include "footer.php"; ?>
</div>

  </body>
  </html>