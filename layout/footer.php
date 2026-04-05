  </div>  
  <!-- /.content-wrapper -->

  <aside class="control-sidebar control-sidebar-dark">
  </aside>

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block-down">Anything you want</div>
    <strong>CopyRight &copy; 2025 <a href="#">سید ظاهر موسوی</a>.</strong>
  </footer>
</div>

<!-- jQuery -->
<script src="../../assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="../../assets/plugins/datatables/jquery.dataTables.js"></script>
<script src="../../assets/plugins/datatables/dataTables.bootstrap4.js"></script>
<!-- SlimScroll -->
<script src="../../assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../../assets/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../assets/dist/js/demo.js"></script>
<!-- اضافه کردن SweetAlert2 -->
 <script src="../../assets/dist/js/sweetalert2.min.js"></script>

<!-- Select2 -->
<script src="../../assets/plugins/select2/select2.full.min.js"></script>

<script src="../../assets/plugins/chartjs-old/Chart.min.js"></script>
<script src="../../assets/dist/js/pages/dashboard1New.js"></script>




<!--  select2 -->
<script>
$(function () {
    $('.select2').select2({
        width: '100%',        // پر کردن عرض والد
        dir: 'rtl',           // راست به چپ
        language: {
            noResults: function() {
                return "موردی یافت نشد"; // متن فارسی برای همه
            }
        }
    });
});
</script>


<!--  بخش صفحه بندی -->
<script>
$(document).ready(function() {
  $('#example1').DataTable({
    "language": {
      "search": "جستجو:",
      "lengthMenu": "نمایش _MENU_ مورد در هر صفحه",
      "zeroRecords": "موردی یافت نشد",
      "info": "نمایش صفحه _PAGE_ از _PAGES_",
      "infoEmpty": "هیچ داده‌ای موجود نیست",
      "infoFiltered": "(فیلتر شده از مجموع _MAX_ مورد)",
      "paginate": {
        "first": "اول",
        "last": "آخر",
        "next": "بعدی",
        "previous": "قبلی"
      }
    },
    "responsive": true,
    "autoWidth": false,
    "pageLength": 10,
    "order": [], // برای جلوگیری از سورت اولیه
    "columnDefs": [
      { "orderable": false, "targets": 0 } // ستون اول غیرقابل سورت
    ]
  });
});
</script>

</body>
</html>