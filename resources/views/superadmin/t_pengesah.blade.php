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
                <h5 class="m-0">Pejabat Pengesah</h5>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <th style="width: 3%">No.</th>
                        <th style="width: 15%">Prodi</th>
                        <th style="width: 9%">NIDN</th>
                        <th style="width: 15%">NIP</th>
                        <th>Nama Dosen</th>
                        <th>Jabatan</th>
                        {{-- <th style="width: 7%">Aksi</th> --}}
                    </thead>
                    <tbody>
                        @foreach($kajur as $ka)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ka->nama_prodi}}</td>
                                <td>{{ $ka->nidn }}</td>
                                <td>{{ $ka->nip }}</td>
                                <td>{{ $ka->nama_dosen }}</td>
                                <td>{{ $ka->jabatan }}</td>
                                {{-- <td>
                                    <a href="{{ url('prodiadmin/e_pengesah/'.Crypt::encryptString($ka->id), [])}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="{{ url('prodiadmin/h_pengesah/'.Crypt::encryptString($ka->id), [])}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td> --}}
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
                window.location.href = "{{ url('superadmin/h_dosen') }}/" + id;
            }
        });
    }
</script>

  @endsection


