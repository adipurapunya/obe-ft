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
                <h5 class="m-0">Daftar Kurikulum<a href="{{url('superadmin/a_kurikulum')}}"><div class="btn btn-small btn-primary text-center" style="float:right"> Tambah Data</div></a></h5>
            </tr>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <th style="width: 3%">No.</th>
                        <th>Nama Prodi</th>
                        <th>Kode</th>
                        <th>Nama Kurikulum</th>
                        <th>Deskripsi</th>
                        <th>Tahun</th>
                        <th>Nomor SK</th>
                        <th>File SK</th>
                        <th style="width:9%">Aksi</th>
                    </thead>
                    <tbody>
                        @foreach($kuri as $kr)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $kr->nama_prodi }}</td>
                                <td>{{ $kr->kode_kuri }}</td>
                                <td>{{ $kr->nama_kuri }}</td>
                                <td>{{ $kr->deskripsi }}</td>
                                <td>{{ $kr->tahun_kuri }}</td>
                                <td>{{ $kr->sk_kuri }}</td>
                                <td><a href="{{ asset('SK-Kurikulum')}}/{{ $kr->filesk }}" target="_blank" /> {{ $kr->filesk }}</td>
                                <td>
                                    <a href="{{ url('superadmin/e_kurikulum/'.Crypt::encryptString($kr->id), [])}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="javascript:void(0);" onclick="konfirmasiHapus('{{ Crypt::encryptString($kr->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
                window.location.href = "{{ url('superadmin/h_kurikulum') }}/" + id;
            }
        });
    }
</script>

  @endsection
