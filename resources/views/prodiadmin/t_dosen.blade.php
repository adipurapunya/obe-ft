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
                <h5 class="m-0">Data Dosen </h5>

            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <th style="width: 3%">No.</th>
                        <th style="width: 7%">NIDN</th>
                        <th style="width: 9%">NIP</th>
                        <th>Nama Dosen</th>
                        <th>Jurusan</th>
                        <th><center>Aksi</center></th>
                    </thead>
                    <tbody>
                        @foreach($data as $ds)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ds->nidn }}</td>
                                <td>{{ $ds->nip }}</td>
                                <td>{{ $ds->nama_dosen }}</td>
                                <td><center>{{ $ds->nama_prodi }}</center></td>
                                <td>
                                    <center><a href="{{ url('prodiadmin/e_dosen/'.Crypt::encryptString($ds->id), [])}}" class="btn btn-sm btn-warning">Edit</a>
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
                window.location.href = "{{ url('superadmin/h_dosen') }}/" + id;
            }
        });
    }
</script>

<style>
        /* Memastikan dropdown "Show entries" tidak bertumpuk */
    .dataTables_length select {
        width: 80px !important;  /* Lebar dropdown */
        padding: 6px;  /* Memberikan jarak agar tampilan lebih rapi */
        border-radius: 5px;
        text-align: center;
        appearance: auto;
        -moz-appearance: none;
        -webkit-appearance: none;
    }

    /* Memastikan tabel fleksibel */
    .dataTables_wrapper .dataTables_length select {
        display: inline-block !important;
        min-width: 80px;
        max-width: 100px;
        padding: 6px;
    }

</style>

  @endsection


