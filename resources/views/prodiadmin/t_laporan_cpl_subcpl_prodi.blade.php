@extends('layouts.admin.panel')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Load DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Content Wrapper -->
<div class="content-wrapper">
    
    <div class="content-header">
        <div class="container-fluid">
            <h1 class="m-0 text-center">Laporan Capaian CPMK, CPL, dan SUB CPL Prodi Per semester</h1>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">

            <!-- FORM FILTER -->
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0">Input Parameter</h5>
                </div>
                <div class="card-body">
                    <form id="form-filter">
                        <div class="row">
                            <div class="col-md-4">
                                <label><strong>Pilih Kurikulum</strong></label>
                                <select name="kurikulum_id" class="form-control" id="kurikulum_id">
                                    <option value="" selected disabled>Pilih Kurikulum</option>
                                    @foreach($kurikulums as $kurikulum)
                                        <option value="{{ encrypt($kurikulum->id) }}">{{ $kurikulum->nama_kuri }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><strong>Pilih Semester</strong></label>
                                <select name="semester_id" class="form-control" id="semester_id">
                                    <option value="" selected disabled>Pilih Semester</option>
                                    @foreach($smtr as $semester)
                                        <option value="{{ encrypt($semester->id) }}">{{ $semester->keterangan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-1 d-flex justify-content-end mt-3">
                                <button type="button" id="btn-filter" class="btn btn-primary">Tampilkan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- INFO MAHASISWA -->
            <!--
            <div id="mahasiswaInfo" class="alert alert-info mt-3" style="display: none;">
                <b>NIM:</b> <span id="nimDisplay"></span> | 
                <b>Nama:</b> <span id="namaMahasiswaDisplay"></span>
            </div>
            -->


            <!-- TABEL & GRAFIK -->
            <div class="row">
                <!-- TABEL CAPAIAN PRODI DARI SELURUH MAHASISWA -->
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="m-0">TABEL CAPAIAN PRODI DARI SELURUH MAHASISWA</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="nilai_keseluruhan" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kurikulum</th>
                                        <th>Kode MK</th>
                                        <th>Nama MK</th>
                                        <th>Kelas</th>
                                        <th>Kode CPMK</th>
                                        <th>Kode CPL</th>
                                        <th>Kode Sub CPL</th>
                                        <th>Nilai Mahasiswa</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Semester</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tabel Rata-Rata SubCPL -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="m-0">Nilai Sub-CPL Prodi Per semester</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="rata_subcpl" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode CPL</th>
                                            <th>Kode Sub CPL</th>
                                            <th>Target Sub CPL</th>
                                            <th>Nilai Rata-Rata Sub CPL</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Nilai CPL -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="m-0">Nilai CPL Prodi Per Semester</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="nilai_cpl" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode CPL</th>
                                            <th>Nilai CPL</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GRAFIK SUB CPL -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="m-0"><span id="judulGrafik">Grafik Pencapaian Sub-CPL Prodi</span></h5>
                        </div>
                        <div class="card-body">
                            <canvas id="subcplChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- GRAFIK CPL -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="m-0"><span id="judulGrafikCPL">Grafik Pencapaian CPL Prodi</span></h5>
                        </div>
                        <div class="card-body">
                            <canvas id="cplChart"></canvas>
                        </div>
                    </div>
                </div>

            </div> <!-- End Row -->

        </div> <!-- End Container -->
    </section>

    

    <script>
        $(document).ready(function() {
            let subcplChart, cplChart;;

            $('#btn-filter').click(function() {
                let kurikulumId = $('#kurikulum_id').val();
                //let nim = $('#nim').val();
                let semesterId = $('#semester_id').val();

                if (!kurikulumId || !semesterId) {
                    Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Harap pilih Kurikulum dan semester!' });
                    return;
                }

                // Menampilkan popup loading Swal
                Swal.fire({
                    title: 'Memproses Data...',
                    html: '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>',
                    allowOutsideClick: false,
                    showConfirmButton: false
                });

                $.ajax({
                    url: "{{ url('/prodiadmin/getCapaianCPLProdi') }}",
                    type: "GET",
                    data: { kurikulum_id: kurikulumId, semester_id: semesterId },
                    success: function(response) {
                        //console.log(response);
                        // Tutup Swal Loading setelah data selesai di-load
                        Swal.close();
                        if (response.nilai_keseluruhan.length > 0) {
                            //let semester = response.nilai_keseluruhan[0]?.ID_SEMESTER || 'Tidak Diketahui';
                            //let nama_mhs = response.nilai_keseluruhan[0]?.NAMA_MHS || 'Tidak Diketahui';
                            //let nim = response.nilai_keseluruhan[0]?.nim || 'Tidak Diketahui';
                            
                            //$("#judulGrafik").text(`Grafik Pencapaian Sub-CPL - ${nama_mhs} - ${nim} - (Semester ${semester})`);
                            //$("#judulGrafikCPL").text(`Grafik Pencapaian CPL - ${nama_mhs} - ${nim} - (Semester ${semester})`);

                            loadTable('#nilai_keseluruhan', response.nilai_keseluruhan);
                            loadTable('#rata_subcpl', response.rata_subcpl);
                            loadTable('#nilai_cpl', response.nilai_cpl);

                            updateChart(response.rata_subcpl);
                            updateChartCPL(response.nilai_cpl);
                        } 
                        else {
                            Swal.fire({ icon: 'info', title: 'Data Tidak Ditemukan', text: 'Tidak ada data untuk NIM ini.' });
                            $('#mahasiswaInfo').fadeOut();
                        }
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire({ icon: 'error', title: 'Gagal!', text: 'Terjadi kesalahan saat mengambil data.' });
                        $('#mahasiswaInfo').fadeOut();
                    }
                });
            });

            function loadTable(tableId, data) {
                if ($.fn.DataTable.isDataTable(tableId)) {
                    $(tableId).DataTable().destroy();
                }

                $(tableId).DataTable({
                    data: data,
                    columns: getColumns(tableId),
                    pageLength: 10,
                    responsive: true
                });
            }

            function getColumns(tableId) {
                return {
                    '#nilai_keseluruhan': [
                        { data: null, render: (data, type, row, meta) => meta.row + 1, className: 'text-center' },
                        { data: 'KURIKULUM' }, { data: 'KODE_MK', className: 'text-center' }, { data: 'NAMA_MK' },
                        { data: 'KODE_KELAS', className: 'text-center' }, { data: 'KODE_CPMK', className: 'text-center' }, { data: 'KD_CPL', className: 'text-center' },
                        { data: 'KODE_SUB_CPL', className: 'text-center' }, { data: 'NILAI_MHS', className: 'text-center' }, { data: 'NAMA_MHS', className: 'text-center' },
                        { data: 'ID_SEMESTER', className: 'text-center' }, { data: 'STATUS', className: 'text-center' }
                    ],
                    '#rata_subcpl': [
                        { data: null, render: (data, type, row, meta) => meta.row + 1, className: 'text-center' },
                        { data: 'KODE_CPL', className: 'text-center' },
                        { data: 'KODE_SUB_CPL', className: 'text-center' },
                        { data: 'TARGET_NILAI', className: 'text-center' },
                        { data: 'RATA_RATA_NILAI_SUBCPL', className: 'text-center' }
                    ],
                    '#nilai_cpl': [
                        { data: null, render: (data, type, row, meta) => meta.row + 1, className: 'text-center' },
                        { data: 'KODE_CPL', className: 'text-center' },
                        { data: 'NILAI_CPL', className: 'text-center' }
                    ]
                }[tableId];
            }

            function updateChart(data) {
                if (subcplChart) subcplChart.destroy();
                let ctx = document.getElementById('subcplChart').getContext('2d');

                subcplChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.KODE_SUB_CPL),
                        datasets: [{
                            label: 'Nilai Sub-CPL Prodi Per semester',
                            data: data.map(d => d.RATA_RATA_NILAI_SUBCPL),
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: { y: { beginAtZero: true, max: 100 } }
                    }
                });
            }

            function updateChartCPL(data) {
                if (cplChart) cplChart.destroy();
                let ctx = document.getElementById('cplChart').getContext('2d');

                cplChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.KODE_CPL),
                        datasets: [{
                            label: 'Nilai CPL Prodi Per Semester',
                            data: data.map(d => d.NILAI_CPL),
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: { y: { beginAtZero: true, max: 100 } }
                    }
                });
            }
        });
    </script>

<style>
        /* Memastikan dropdown "Show entries" tidak bertumpuk */
    .dataTables_length select {
        width: 80px !important;  /* Lebar dropdown */
        padding: 6px;  /* Memberikan jarak agar tampilan lebih rapi */
        border-radius: 5px;
        text-align: center;
        appearance: auto;
        -moz-appearance: none;
        -webkit-appearance: none;
    }

    /* Memastikan tabel fleksibel */
    .dataTables_wrapper .dataTables_length select {
        display: inline-block !important;
        min-width: 80px;
        max-width: 100px;
        padding: 6px;
    }

</style>

</div>
@endsection
