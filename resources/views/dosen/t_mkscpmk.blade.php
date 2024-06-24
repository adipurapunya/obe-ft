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
                <h5 class="m-0">Input Sub CPMK</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('/dosen/t_mkscpmk') }}">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <div class="form-group">
                                <label for="semester_id"><strong>Semester</strong></label>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <select id="semester_select" name="semester_id" class="form-control">
                                    @foreach ($smtr as $sm)
                                    <option value="{{ encrypt($sm->id) }}" @if(isset($encrypted_semester_id) && $encrypted_semester_id == encrypt($sm->id)) selected @endif>{{ $sm->keterangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <button type="submit" class="btn" style="float:left; background-color: rgb(95, 158, 160); color: white;">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </form><br><br>

                @if (isset($mkscp) && count($mkscp) > 0)
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
                        @foreach($mkscp as $ms)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $ms->kode_mk }} - {{ $ms->nama_mk }}</td>
                                {{-- <td><center>{{ $mk->nama_kelas }}</center></td> --}}
                                <td>
                                    <center><a href="{{ url('dosen/a_mkscpmk/'.Crypt::encryptString($ms->matkul_id), [])}}" class="btn btn-sm" style="color:white; background-color: #5895bd">+ Input Sub CPMK</a></center>
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
                <h5 class="m-0">Matriks MK & CPMK</h5>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;">No.</th>
                            <th style="width: 20%; text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            <th style="width: 4%; text-align: center; vertical-align: middle;">Kode CPMK</th>
                            <th style="width: 4%; text-align: center; vertical-align: middle;">Kode Sub CPMK</th>
                            <th style="width: 20%; text-align: center; vertical-align: middle;">Deskripsi Sub CPMK</th>
                            <th style="width: 5%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mksucp as $msc)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $msc->kode_mk }} - {{ $msc->nama_mk }}</td>
                                <td><center>{{ $msc->kode_cpmk }}</center></td>
                                <td><center>{{ $msc->kode_scpmk }}</center></td>
                                <td>{{ $msc->desk_scpmk }}</td>
                                <td style="text-align: center">
                                    <a href="{{ url('dosen/e_mkscpmk/'.Crypt::encryptString($msc->mkscpmk_id), [])}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="{{ url('dosen/h_mkscpmk/'.Crypt::encryptString($msc->mkscpmk_id), [])}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
