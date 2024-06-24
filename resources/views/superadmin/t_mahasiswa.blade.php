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
                <h5 class="m-0">Data Mahasiswa <a href="{{url('superadmin/a_mahasiswa')}}"><div class="btn btn-small btn-primary text-center" style="float:right"> Tambah Data</div></a></h5>

            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <th style="width: 3%">No.</th>
                        <th style="width: 9%">NIM</th>
                        <th>Nama Mahasiswa</th>
                        <th>Tahun Penerimaan</th>
                        <th>Semester Penerimaan</th>
                        <th>Jurusan</th>
                        <th style="width:11%">Aksi</th>
                    </thead>
                    <tbody>
                        @foreach($data as $mhs)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->nama_mahasiswa }}</td>
                                <td>{{ $mhs->angkatan }}</td>
                                <td>{{ $mhs->smt_angkatan }}</td>
                                <td>{{ $mhs->nama_prodi }}</td>
                                <td>
                                    <a href="{{ url('superadmin/e_mahasiswa/'.Crypt::encryptString($mhs->id), [])}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> </a>
                                    <a href="javascript:void(0);" onclick="konfirmasiHapus('{{ Crypt::encryptString($mhs->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
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
                window.location.href = "{{ url('superadmin/h_mahasiswa') }}/" + id;
            }
        });
    }
</script>

  @endsection


