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
                <form role="form" method="post" action="{{ url('dosen/s_inpnilai')}}">
                @csrf
                <h5><b>Komponen Nilai</b></h5><br>
                @foreach($inpnil as $ip)
                    <input type="hidden" name="rubnilai_id" value="{{ $ip->rubnilai_id }}">
                @endforeach
                <table style="width: 100%">
                    <tr>
                        <td style="width:12%"><b>Mata Kuliah</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $inpnil->first()->nama_mk }}</b></td>
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
                </table><br><br>
                <table class="table table-bordered table-striped">
                    <thead style="color:white; background-color: #5895bd; text-align:center">
                        <th style="width: fit-content">No.</th>
                        <th style="width: fit-content">NIM</th>
                        <th style="width: 30%">Nama</th>
                        @foreach($inpnil->unique('jen_penilaian') as $in)
                            @php
                                $uniqueCpmk = $inpnil->where('jen_penilaian', $in->jen_penilaian)->pluck('cpmk_id')->unique();
                            @endphp
                            @foreach($uniqueCpmk as $cpmkId)
                                <th style="width: 8%;">{{ $in->jen_penilaian }} - {{ $in->kode_cpmk }}</th>
                            @endforeach
                        @endforeach
                        <th style="width: fit-content">Absolut</th>
                        <th style="width: fit-content">Relatif</th>
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
                                                    <input type="text" name="nilai[{{ $mahasiswa->id }}][{{ $nilai->kompnilai_id }}]" class="form-control" required>
                                                @endif
                                            </div>
                                        </td>
                                    @endforeach
                                @endforeach
                                <td></td>
                                <td></td>
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

@endsection
