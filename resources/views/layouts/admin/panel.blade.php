<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
    <title>Admin | Dashboard</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ url ('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ url ('lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ url ('lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url ('lte/plugins/jqvmap/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ url ('lte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ url ('lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ url ('lte/plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ url ('lte/plugins/summernote/summernote-bs4.min.css') }}">

    <script src="{{ url ('lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .ck-editor__editable_inline {
            min-height: 300px;
        }
        </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
        integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
        crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin="">
    </script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">


  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{ asset ('lte/dist/img/logo.png') }}" alt="bpbd" height="200" width="200">
  </div>

    @include('layouts.admin.header')
    @include('layouts.admin.sidebar')
    @yield('content')
    @include('layouts.admin.footer')

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<script src="{{ url ('lte/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ url ('lte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="{{ url ('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url ('lte/plugins/chart.js/Chart.min.js') }}"></script>
<script src="{{ url ('lte/plugins/sparklines/sparkline.js') }}"></script>
<script src="{{ url ('lte/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ url ('lte/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<script src="{{ url ('lte/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<script src="{{ url ('lte/plugins/moment/moment.min.js') }}"></script>
<script src="{{ url ('lte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ url ('lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ url ('lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
<script src="{{ url ('lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<script src="{{ url ('lte/dist/js/adminlte.js') }}"></script>
<script src="{{ url ('lte/dist/js/demo.js') }}"></script>
<script src="{{ url ('lte/dist/js/pages/dashboard.js') }}"></script>


<script src="{{ url ('lte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url ('lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url ('lte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url ('lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ url ('lte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url ('lte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ url ('lte/plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url ('lte/plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url ('lte/plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url ('lte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ url ('lte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ url ('lte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

<link rel="stylesheet" href="{{ url ('lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">

{{-- hightchart --}}
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "ordering": true,
        "responsive": true,
        "paging": true,
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
  </script>

<script>
$(function () {
  $("#example3").DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "ordering": true,
    "responsive": true,
    "paging": true,
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});
</script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

{{-- @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif --}}





</body>
</html>
