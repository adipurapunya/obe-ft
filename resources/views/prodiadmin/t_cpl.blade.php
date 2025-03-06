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
                <h5 class="m-0">
                    Data CPL<p><hr>
                    <form method="GET" action="{{ url('prodiadmin/t_cpl') }}" class="form-inline mb-3" style="float: left">
                        <div class="form-group">
                            <label for="kurikulum" class="mr-2">Pilih Kurikulum:</label>
                            <select name="kurikulum" id="kurikulum" class="form-control mr-2">
                                <option value="">-- Semua Kurikulum --</option>
                                @foreach($kurikulums as $kurikulum)
                                <option value="{{ encrypt($kurikulum->id) }}" {{ request('kurikulum') == encrypt($kurikulum->id) ? 'selected' : '' }}>
                                        {{ $kurikulum->nama_kuri }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                    <a href="{{ url('prodiadmin/a_cpl') }}" class="btn btn-small btn-primary text-center" style="float:right">Tambah Data</a>
                </h5>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%">No.</th>
                            <th style="width: 8%">Prodi</th>
                            <th style="width: 12%">Kurikulum</th>
                            <th style="width: 9%">Kode CPL</th>
                            <th>Deskripsi</th>
                            <th style="width: 9%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cp as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->kopro }}</td>
                                <td>{{ $item->nama_kuri }}</td>
                                <td>{{ $item->kode_cpl }}</td>
                                <td>{{ $item->deskrip_cpl }}</td>
                                <td>
                                    <a href="{{ url('prodiadmin/e_cpl/' . Crypt::encryptString($item->id)) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0);" onclick="konfirmasiHapus('{{ Crypt::encryptString($item->id) }}')" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
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
                window.location.href = "{{ url('prodiadmin/h_cpl') }}/" + id;
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
