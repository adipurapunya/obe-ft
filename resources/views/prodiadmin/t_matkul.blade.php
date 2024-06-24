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
                <h5 class="m-0">Data Mata Kuliah<a href="{{url('prodiadmin/a_matkul')}}"><div class="btn btn-small btn-primary text-center" style="float:right"> Tambah Data</div></a></h5>
            </tr>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;" rowspan="2">No.</th>
                            <th rowspan="2" style="width: 5%; text-align: center; vertical-align: middle;">Program Studi</th>
                            <th rowspan="2" style="width: 5%; text-align: center; vertical-align: middle;">Kurikulum</th>
                            <th colspan="2" style="text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            <th rowspan="2" style="text-align: center; vertical-align: middle;">Sifat</th>
                            <th rowspan="2" style="width: 6%; text-align: center; vertical-align: middle;">Paket Semester</th>
                            <th colspan="3" style="text-align: center; vertical-align: middle;">SKS</th>
                            <th style="width: 8%; text-align: center; vertical-align: middle;" rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th><center>Kode</center></th>
                            <th>Nama</th>
                            <th><center>T</center></th>
                            <th><center>P</center></th>
                            <th><center>PL</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matk as $mt)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><center>{{ $mt->kopro }}</center></td>
                                <td><center>{{ $mt->nama_kuri }}</center></td>
                                <td><center>{{ $mt->kode_mk }}</center></td>
                                <td>{{ $mt->nama_mk }}</td>
                                <td><center>{{ $mt->status }}</center></td>
                                <td><center>{{ $mt->nama_smtr }}</center></td>
                                <td><center>{{ $mt->sks_teo }}</center></td>
                                <td><center>{{ $mt->sks_prak }}</center></td>
                                <td><center>{{ $mt->sks_lap }}</center></td>
                                <td>
                                    <a href="{{ url('superadmin/e_matkul/'.Crypt::encryptString($mt->id), [])}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="{{ url('superadmin/h_matkul/'.Crypt::encryptString($mt->id), [])}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
