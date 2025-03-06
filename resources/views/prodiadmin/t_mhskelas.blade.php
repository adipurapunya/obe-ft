@extends('layouts.admin.panel')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              {{-- <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li> --}}
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Daftar Kelas</h5>
            </div>
            <div class="card-body">

                <form method="GET" action="{{ url('/prodiadmin/t_mhskelas') }}">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <div class="form-group">
                                <label for="semester_id"><strong>Semester</strong></label>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <select id="semester_select" name="semester_id" class="form-control">
                                    <option value="">Pilih Semester</option>
                                    @foreach ($semes as $sm)
                                        <option value="{{ encrypt($sm->id) }}"
                                            @if(isset($encrypted_semester_id) && $encrypted_semester_id == encrypt($sm->id)) selected @endif>
                                            <b>{{ $sm->keterangan }}</b>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </form><br><br>
                {{-- @if (isset($mata_kuliah) && count($mata_kuliah) > 0) --}}
                <table id="example1" class="table table-bordered table-striped">
                    <thead style="background: rgb(95, 158, 160); color: white;">
                        <tr>
                            <th><center>No.</center></th>
                            <th><center>Matkul</center></th>
                            <th><center>Nama Kelas</center></th>
                            <th><center>Dosen 1</center></th>
                            <th><center>Dosen 2</center></th>
                            <th><center>Semester</center></th>
                            <th style="width: 15%"><center>Aksi</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelas->unique('kode_kelas') as $ke)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $ke->nama_mk }}</td>
                                <td><center>{{ $ke->nama_kelas }}</center></td>
                                <td>{{ $ke->nama_dosen_satu }}</td>
                                <td>{{ $ke->nama_dosen_dua}}</td>
                                <td><center>{{ $ke->nama_smtr }}</center></td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-success"
                                            data-bs-toggle="modal"
                                            data-bs-target="#importExcelModal"
                                            data-kelas-id="{{ $ke->id }}">
                                        Import Peserta Kelas
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table><br><br><hr>

                <h5>Daftar Peserta Kelas</h5>
                <table id="example1" class="table table-bordered table-striped">
                    <thead style="background: rgb(95, 158, 160); color: white;">
                        <tr>
                            <th><center>No.</center></th>
                            <th><center>Matkul</center></th>
                            <th><center>Nama Kelas</center></th>
                            <th><center>Dosen 1</center></th>
                            <th><center>Dosen 2</center></th>
                            <!-- <th><center>Nama Mahasiswa</center></th> -->

                            <th><center>Aksi</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelas->unique('kode_kelas') as $ke)
                            @if(!empty($ke->nim))
                                <tr>
                                    <td><center>{{ $loop->iteration }}</center></td>
                                    <td>{{ $ke->nama_mk }}</td>
                                    <td><center>{{ $ke->nama_kelas }}</center></td>
                                    <td>{{ $ke->nama_dosen_satu }}</td>
                                    <td>{{ $ke->nama_dosen_dua }}</td>
                                    <!--<td>{{ $ke->nama_mahasiswa }}</td> -->
                                    
                                    <td><center><a href="{{ url('prodiadmin/t_peserta_kelas/'.Crypt::encryptString($ke->id)) }}"> Lihat Peserta </a> </center></td>
                                    
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <!-- Modal -->
                    <!-- Modal -->
                    <div class="modal fade" id="importExcelModal" tabindex="-1" aria-labelledby="importExcelModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="" enctype="multipart/form-data" id="importExcelForm">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="importExcelModalLabel">Import Peserta Kelas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="kelas_id" name="kelas_id">
                                        <div class="form-group">
                                            <label for="file_excel">Upload File Excel</label>
                                            <input type="file" id="file_excel" name="file_excel" class="form-control @error('file_excel') is-invalid @enderror" required>
                                            @error('file_excel')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Import</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>


                {{-- @else
                <p>Tidak ada data yang tersedia.</p>
                @endif --}}
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
        document.addEventListener('DOMContentLoaded', function () {
        var importExcelModal = document.getElementById('importExcelModal');
        importExcelModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Tombol yang diklik
            var kelasId = button.getAttribute('data-kelas-id'); // Ambil ID Kelas

            console.log("Kelas ID:", kelasId); // Debugging

            // Update nilai input hidden
            var kelasInput = document.getElementById('kelas_id');
            kelasInput.value = kelasId;

            // Update action form
            var form = document.getElementById('importExcelForm');
            form.action = `/prodiadmin/i_mhskelas/${kelasId}`;
        });
    });

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var importExcelModal = document.getElementById('importExcelModal');
        var cancelButton = importExcelModal.querySelector('[data-bs-dismiss="modal"]');

        cancelButton.addEventListener('click', function () {
            // Redirect ke halaman t_mhskelas
            window.location.href = "/prodiadmin/t_mhskelas";
        });
    });
</script>
<!-- CSS Perbaikan untuk Show Entries -->
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

@endsection
