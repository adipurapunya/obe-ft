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
                <h5 class="m-0">Daftar Jurusan / Prodi <a href="{{url('superadmin/a_prodi')}}"><div class="btn btn-small btn-primary text-center" style="float:right"> Tambah Data</div></a></h5>

            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <th style="width: 3%">No.</th>
                        <th style="width: 7%">Kode Jursan</th>
                        <th>Nama Jurusan</th>
                        <th>Inisial</th>
                        <th style="width: 5%">Jenjang</th>
                        <th style="width:11%">Aksi</th>
                    </thead>
                    <tbody>
                        @foreach($prod as $pr)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pr->kopro }}</td>
                                <td>{{ $pr->nama_prodi }}</td>
                                <td>{{ $pr->inisial }}</td>
                                <td>{{ $pr->jenjang }}</td>
                                <td>
                                    <a href="{{ url('superadmin/e_prodi/'.Crypt::encryptString($pr->id), [])}}" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="javascript:void(0);" onclick="konfirmasiHapus('{{ Crypt::encryptString($pr->id) }}')" class="btn btn-danger btn-sm">Hapus</a>
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
                window.location.href = "{{ url('superadmin/h_prodi') }}/" + id;
            }
        });
    }
</script>

  @endsection
