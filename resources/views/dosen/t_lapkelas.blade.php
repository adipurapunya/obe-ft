@extends('layouts.admin.panel')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{url('lte/plugins/fontawesome-free/css/all.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{url('lte/dist/css/adminlte.min.css')}}">

@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Nilai Mahasiswa</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Grafik Ketercapaian CPMK</h5>
            </div>
            <div class="card-body">

                <table style="width: 100%">
                    <tr>
                        <td style="width:12%"><b>Mata Kuliah</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $Datamatkul->nama_mk }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:12%"><b>Kode Mata Kuliah</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $Datamatkul->kode_mk }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Kelas</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $Datamatkul->nama_kelas }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Semester</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $Datamatkul->semester }}</b></td>
                    </tr>
                </table><br><hr>

                <div class="card-body">
                    <canvas id="pieChart" style="min-height: 300px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                    <div id="cpmk-legend" style="margin-top: 20px;">
                        <!-- Keterangan untuk kode CPMK akan ditambahkan di sini -->
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<script src="{{url('lte/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{url('lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{url('lte/plugins/chart.js/Chart.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('lte/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{url('lte/dist/js/demo.js')}}"></script>

<script>
    $(function () {
    // Data untuk pie chart yang sudah dikirimkan dari controller
    const chartData = @json($chartData); // Mendapatkan data dari controller

    // Pie Chart
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
    var pieData = {
        labels: ['Tercapai', 'Tidak Tercapai'],
        datasets: [{
            data: [chartData.statusTercapai, chartData.statusTidak],
            backgroundColor: ['#28a745', '#dc3545'],
            borderColor: ['#218838', '#c82333'],
            borderWidth: 1
        }]
    };

    var pieOptions = {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(tooltipItem, data) {
                        // Mendapatkan label status (Tercapai/Tidak Tercapai)
                        var label = data.labels[tooltipItem.index];

                        // Mendapatkan kode CPMK berdasarkan index
                        var cpmkCode = tooltipItem.index === 0
                            ? chartData.cpmkCodeTercapai[tooltipItem.index]  // Ambil kode CPMK pertama yang tercapai
                            : chartData.cpmkCodeTidak[tooltipItem.index];   // Ambil kode CPMK pertama yang tidak tercapai

                        // Mendapatkan jumlah status
                        var count = label === 'Tercapai' ? chartData.statusTercapai : chartData.statusTidak;

                        // Menampilkan hasil dalam format label, jumlah status, dan kode CPMK
                        return label + ": " + count + " (Kode CPMK: " + cpmkCode + ")";
                    }
                }
            }
        }
    };

    // Membuat pie chart
    new Chart(pieChartCanvas, {
        type: 'pie',
        data: pieData,
        options: pieOptions
    });

    // Menambahkan legenda untuk kode CPMK di bawah chart
    var legendHTML = '';

    // Tambahkan keterangan untuk status Tercapai
    chartData.cpmkCodeTercapai.forEach(function(cpmkCode) {
        legendHTML += `<div><span style="color: #28a745;">●</span> Tercapai (Kode CPMK: <strong>${cpmkCode}</strong>)</div>`;
    });

    // Tambahkan keterangan untuk status Tidak Tercapai
    chartData.cpmkCodeTidak.forEach(function(cpmkCode) {
        legendHTML += `<div><span style="color: #dc3545;">●</span> Tidak Tercapai (Kode CPMK: <strong>${cpmkCode}</strong>)</div>`;
    });

    // Menambahkan legenda ke div #cpmk-legend
    $('#cpmk-legend').html(legendHTML);
});


</script>

@endsection
