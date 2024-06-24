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
                <h5 class="m-0">Data CPMK<a href="{{url('dosen/a_cpmk')}}"><div class="btn btn-small btn-primary text-center" style="float:right"> Tambah Data</div></a></h5>
            </tr>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;" rowspan="2">No.</th>
                            <th style="width: 8%; text-align: center; vertical-align: middle;" rowspan="2">Prodi</th>
                            <th colspan="2"><center>Mata Kuliah</center></th>
                            <th colspan="2"><center>CPMK</center></th>
                            <th style="width: 7%; text-align: center; vertical-align: middle;" rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th style="width: 10%"><center>Kode</center></th>
                            <th><center>Nama</center></th>
                            <th style="width: 10%"><center>Kode</center></th>
                            <th><center>Deskripsi</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cpmka as $cp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cp->kopro }}</td>
                                <td>{{ $cp->kode_mk }}</td>
                                <td>{{ $cp->nama_mk }}</td>
                                <td>{{ $cp->kode_cpmk }}</td>
                                <td>{{ $cp->deskrip_cpmk }}</td>
                                <td>
                                    <a href="{{ url('dosen/e_cpmk/'.Crypt::encryptString($cp->id), [])}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="{{ url('dosen/h_cpmk/'.Crypt::encryptString($cp->id), [])}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
                window.location.href = "{{ url('superadmin/h_cpl') }}/" + id;
            }
        });
    }
</script>

  @endsection
