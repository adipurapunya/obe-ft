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
                <h5 class="m-0">Matriks Mata Kuliah - CPL<a href="{{url('dosen/a_mkcpl')}}"><div class="btn btn-small btn-primary text-center" style="float:right"> Tambah Data</div></a></h5>
            </tr>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;" rowspan="2">No.</th>
                            <th colspan="2"><center>Mata Kuliah</center></th>
                            <th style="text-align: center; vertical-align: middle;" rowspan="2"><center>Capaian Pembelajaran Lulusan</center></th>
                            <th style="width: 8%; text-align: center; vertical-align: middle;" rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th><center>Kode</center></th>
                            <th><center>Nama</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedData as $kode_mk => $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><center>{{ $kode_mk }}</center></td>
                                <td>{{ $data['nama_mk'] }}</td>
                                <td><center>{{ $data['kode_cpl'] }}</center></td>
                                <td>
                                    <a href="{{ url('dosen/e_mkcpl/'.Crypt::encryptString($kode_mk), [])}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="{{ url('dosen/h_mkcpl/'.Crypt::encryptString($kode_mk), [])}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
