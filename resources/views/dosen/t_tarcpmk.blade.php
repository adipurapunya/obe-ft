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
                <h5 class="m-0">Input Komponen Penilaian</h5>
            </tr>
            </div>
            <div class="card-body">
                {{-- @if (isset($mata_) && count($mkscp) > 0) --}}
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;">No.</th>
                            <th style="width: 40%; text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            {{-- <th style="width: 4%; text-align: center; vertical-align: middle;">Kelas</th> --}}
                            <th style="width: 6%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mata_kuliah->unique('nama_mk') as $ms)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $ms->kode_mk }} - {{ $ms->nama_mk }}</td>
                                {{-- <td><center>{{ $mk->nama_kelas }}</center></td> --}}
                                <td>
                                    <center><a href="{{ url('dosen/a_tarcpmk/'.Crypt::encryptString($ms->matkul_id), [])}}" class="btn btn-sm" style="color:white; background-color: #5895bd">+ Input Sub CPMK</a></center>
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
                <h5 class="m-0">Komponen Penilaian Mata Kuliah</h5>
            </tr>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;">No.</th>
                            <th style="width: 15%; text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            <th style="width: 4%; text-align: center; vertical-align: middle;">Kode CPMK</th>
                            <th style="width: 15%; text-align: center; vertical-align: middle;">Komponen Penilaian</th>
                            <th style="width: 5%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                            $displayedCourses = [];
                            $lastCourse = null;
                        @endphp

                        @foreach($tarcpmk as $tr)
                            <tr>
                                <td>
                                    @if (!in_array($tr->kode_mk, $displayedCourses))
                                        <center>{{ $counter }}</center>
                                        @php
                                            $counter++;
                                            $displayedCourses[] = $tr->kode_mk;
                                        @endphp
                                    @endif
                                </td>
                                <td>
                                    @if ($tr->nama_mk != $lastCourse)
                                        {{ $tr->kode_mk }} - {{ $tr->nama_mk }}
                                        @php
                                            $lastCourse = $tr->nama_mk;
                                        @endphp
                                    @endif
                                </td>
                                <td><center>{{ $tr->kode_cpmk }}</center></td>
                                <td>{{ $tr->jen_penilaian }}</td>
                                <td style="text-align: center">
                                    <a href="{{ url('dosen/e_mkscpmk/'.Crypt::encryptString($tr->rubni_id), [])}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="{{ url('dosen/h_mkscpmk/'.Crypt::encryptString($tr->rubni_id), [])}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
                    window.location.href = "{{ url('superadmin/h_matkul') }}/" + id;
                }
            });
        }
    </script>

  @endsection
