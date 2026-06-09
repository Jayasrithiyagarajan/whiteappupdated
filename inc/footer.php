</div>
<!-- Footer -->
  <footer class="footer premium-footer">
         <div class="footer-brand">
            <span class="footer-mark">CIMS</span>
            <span>&copy; 2026</span>
         </div>
         <div class="footer-credit">
            Created by <a href="https://www.burion.in/">Burion</a>
         </div>
      </footer>
      <!-- End Footer -->
   </div>
   <!-- End wrapper -->
  
   <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
   <script src="<?php echo $url; ?>assets/js/jquery.min.js"></script>
   <script src="<?php echo $url; ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script src="<?php echo $url; ?>assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
   <script src="<?php echo $url; ?>assets/js/script.js"></script>
   <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->

   <!-- ======= BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->
   <script src="<?php echo $url; ?>assets/plugins/apex/apexcharts.min.js"></script>
   <script src="<?php echo $url; ?>assets/plugins/apex/custom-apexcharts.js"></script>
   <!-- ======= End BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->

    <!-- ======= BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->
    <script src="<?php echo $url; ?>assets/plugins/datepicker/datepicker.min.js"></script>
   <script src="<?php echo $url; ?>assets/plugins/datepicker/i18n/datepicker.en.js"></script>
   <script src="<?php echo $url; ?>assets/plugins/datepicker/custom-form-datepicker.js"></script>
   <!-- ======= End BEGIN PAGE LEVEL PLUGINS/CUSTOM SCRIPTS ======= -->
<!-- jsPDF library for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<!-- SheetJS library for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script src="<?php echo $url; ?>assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="<?php echo $url; ?>assets/plugins/sweetalert2/sweetalerts.js"></script>

<!-- PWA Service Worker Registration -->
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('<?php echo $url; ?>sw.js')
        .then((registration) => {
          console.log('ServiceWorker registration successful with scope: ', registration.scope);
        }, (err) => {
          console.log('ServiceWorker registration failed: ', err);
        });
    });
  }
</script>

</body>

</html>
