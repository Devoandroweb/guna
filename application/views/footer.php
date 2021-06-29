        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021 <a href="https://www.aptikma.co.id/" class="text-info" target="_blank">Aptikma</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Aplikasi Absensi dan Penggajian V 2.7 <i class="mdi mdi-heart text-danger"></i></span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div> 		
<script type="text/javascript">
  <?= $this->session->flashdata('alert_img'); ?>
  $(document).ready(function () {
      $('#menu_minimze').click(function () {
          $('body').toggleClass('sidebar-icon-only');
      });
  })
</script>