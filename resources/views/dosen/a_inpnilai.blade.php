@extends('layouts.admin.panel')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                <h5 class="m-0">Form Input Nilai</h5>
            </div>
            <div class="card-body">

                <h5><b>Mata Kuliah</b></h5><br>

                <table style="width: 100%">
                    <tr>
                        <td style="width:12%"><b>Mata Kuliah</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $inpnil->first()->nama_mk }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:12%"><b>Kode Mata Kuliah</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $inpnil->first()->kode_mk }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Kelas</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $kelas->nama_kelas }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Semester</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $kelas->semester->keterangan }}</b></td>
                    </tr>
                </table>

                <!--<a href="" class="btn btn-lg" style="color:white; background-color: #5895bd">Download Template Excel Bos</a> -->

                <table style="width:100%">
                    <tr>
                        <td style="width:8%"><h5><b> Import Nilai Excel</b></h5></td>
                        <td style="width: 7%"></td>
                        <td style="width:70%">
                            <div class="button-container">

                                <form action="{{ route('dosen.uploadTemplateExcel') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="kelas_id" value="{{ Crypt::encryptString($kelas_id) }}">
                                    <a href="{{ route('dosen.downloadTemplateExcel', ['kelas_id' => Crypt::encryptString($kelas_id), 'kode_mk' => Crypt::encryptString($inpnil->first()->kode_mk)]) }}" class="btn btn-sm btn-success" style="color:white; background-color: #5895bd">Export Data Mahasiswa</a><br><br><br>
                                    <div class="form-group">
                                        <label for="file_excel">Upload Template Excel yang Sudah Diisi:</label>
                                        <input type="file" name="file_excel" id="file_excel" class="form-control-file" accept=".xlsx, .xls" required>
                                        <button type="submit" class="btn btn-sm btn-primary" style="margin-top:5px">Upload</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                </table>

                <form role="form" method="post" action="{{ url('dosen/s_inpnilai')}}">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ Crypt::encryptString($kelas_id) }}">
                @foreach($inpnil as $ip)
                    <input type="hidden" name="rubnilai_id[]" value="{{ $ip->rubnilai_id }}">
                @endforeach
                <table class="table table-bordered table-striped">
                    <!--<thead style="color:white; background-color: #5895bd; text-align:center"> -->
                    <thead style="color: white; background-color: #5895bd; text-align: center; vertical-align: middle;">

                        <th style="vertical-align: middle;">No.</th>
                        <th style="vertical-align: middle;">NIM</th>
                        <th style="width: 30%; vertical-align: middle;">NAMA</th>
                        @foreach($inpnil->unique('jen_penilaian') as $in)
                            @php
                                $uniqueCpmk = $inpnil->where('jen_penilaian', $in->jen_penilaian)->pluck('cpmk_id')->unique();
                            @endphp
                            @foreach($uniqueCpmk as $cpmkId)
                                <th style="vertical-align: middle">{{ $in->jen_penilaian }} - {{ $in->label }} - {{ $in->kode_cpmk }}</th>
                            @endforeach
                        @endforeach
                        {{-- <th style="width: fit-content; vertical-align: middle">Absolut</th>
                        <th style="width: fit-content; vertical-align: middle">Relatif</th> --}}
                    </thead>
                    <tbody>
                        @foreach($mahasiswas as $mahasiswa)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td>{{ $mahasiswa->nama_mahasiswa }}</td>
                                @foreach($inpnil->unique('jen_penilaian') as $in)
                                    @php
                                        $uniqueCpmk = $inpnil->where('jen_penilaian', $in->jen_penilaian)->pluck('cpmk_id')->unique();
                                    @endphp
                                    @foreach($uniqueCpmk as $cpmkId)
                                        @php
                                            $nilai = collect($inpnil)->first(function($value) use ($in, $cpmkId) {
                                                return $value->jen_penilaian == $in->jen_penilaian && $value->cpmk_id == $cpmkId;
                                            });
                                        @endphp
                                        <td style="width: fit-content">
                                            <div class="form-group">
                                                @if($nilai)
                                                    <input type="text" style="text-align: center" name="nilai[{{ $mahasiswa->nim }}][{{ $nilai->kompnilai_id }}]" class="form-control" required>
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach
                                @endforeach
                                {{-- <td>NGULANG</td>
                                <td>GAK LULUS</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info" style="float:right; color:white; background-color: #5895bd">Simpan</button>
                    </div>
                </div>
            </div>
            </form>


        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
function konfirmasiHapus(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Anda tidak dapat mengembalikan file ini setelah dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "{{ url('superadmin/h_dosen') }}/" + id;
        }
    });
}
</script>

@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    </script>
@endif

@endsection

<style>
        .container {
            padding-left: 0;
            margin-left: 0;
        }
        .button-container {
            display: flex;
            justify-content: left;
            align-items: center;
            gap: 20px; /* Jarak antara tombol */
            margin-top: 20px; /* Jarak atas */
        }
        .button-container button, .button-container input[type="submit"] {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .download-button {
            background-color: #5895bd; /* Biru */
            color: white;
        }
        .upload-button {
            background-color: #28a745; /* Hijau */
            color: white;
        }
</style>