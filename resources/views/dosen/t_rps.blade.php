@extends('layouts.admin.panel')

@section('content')

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
                <h5 class="m-0">Input Komponen RPS</h5>
            </tr>
            </div>
            <div class="card-body">
                @if (isset($mkscp) && count($mkscp) > 0)
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;">No.</th>
                            <th style="width: 40%; text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            {{-- <th style="width: 4%; text-align: center; vertical-align: middle;">Kelas</th> --}}
                            <th style="width: 12%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mkscp->unique('nama_mk') as $ms)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $ms->kode_mk }} - {{ $ms->nama_mk }}</td>
                                {{-- <td><center>{{ $mk->nama_kelas }}</center></td> --}}
                                <td>
                                    {{-- <center><a href="{{ url('dosen/a_rps/'.Crypt::encryptString($ms->matkul_id), [])}}" class="btn btn-sm" style="color:white; background-color: #5895bd">+ Input Data RPS</a></center> --}}
                                    <center><a href="{{ url('dosen/a_genrps/'.Crypt::encryptString($ms->matkul_id), [])}}" class="btn btn-sm" style="color:white; background-color: #5895bd">+ Tabel Umum</a>
                                    <a href="{{ url('dosen/a_meetrps/'.Crypt::encryptString($ms->matkul_id), [])}}" class="btn btn-sm" style="color:white; background-color: #277d63">+ Tabel Pertemuan</a>
                                    <a href="{{ url('dosen/t_createrps/'.Crypt::encryptString($ms->matkul_id), [])}}" class="btn btn-sm" style="color:white; background-color: #062e8a; margin-top:5px;" target="_blank" rel="noopener noreferrer">Cetak RPS</a></center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p>Tidak ada data yang tersedia.</p>
                @endif
            </div>
            </div>
    </section>

    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Komponen RPS Tabel Umum</h5>
            </tr>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;">No.</th>
                            <th style="width: 10%; text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            <th style="width: 20%; text-align: center; vertical-align: middle;">Deskripsi Singkat</th>
                            <th style="width: 3%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rps->unique('nama_mk') as $ms)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $ms->kode_mk }} - {{ $ms->nama_mk }}</td>
                                <td>{!! $ms->desk_singkat !!}</td>
                                <td>
                                    <center><a href="{{ url('dosen/det_genrps/'.Crypt::encryptString($ms->matkul_id), [])}}" class="btn btn-sm btn-primary">Detail</a></center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- @else
                <p>Tidak ada data yang tersedia.</p>
                @endif --}}
            </div>
            </div>
    </section>

    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Komponen RPS Tabel Pertemuan</h5>
            </tr>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;">No.</th>
                            <th style="width: 10%; text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            <th style="width: 20%; text-align: center; vertical-align: middle;">Indikator</th>
                            <th style="width: 3%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rps->unique('nama_mk') as $ms)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $ms->kode_mk }} - {{ $ms->nama_mk }}</td>
                                <td>{!! $ms->indikator !!}</td>
                                <td>
                                    <center><a href="{{ url('dosen/e_genrps/'.Crypt::encryptString($ms->id), [])}}" class="btn btn-sm btn-primary">Detail</a></center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- @else
                <p>Tidak ada data yang tersedia.</p>
                @endif --}}
            </div>
            </div>
    </section>

    <!-- /.content -->
  </div>

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
                    window.location.href = "{{ url('superadmin/h_matkul') }}/" + id;
                }
            });
        }
    </script>

  @endsection
