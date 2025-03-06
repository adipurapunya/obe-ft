@extends('layouts.admin.panel')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">
                    Data Sub CPL
                    <a href="{{ url('prodiadmin/a_subcpl') }}">
                        <div class="btn btn-small btn-primary text-center" style="float:right"> Tambah Data</div>
                    </a>
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('prodiadmin/t_subcpl') }}" class="form-inline mb-3" style="float: left">
                    <div class="form-group">
                        <label for="kurikulum" class="mr-2">Pilih Kurikulum:</label>
                        <select name="kurikulum" id="kurikulum" class="form-control mr-2">
                            <option value="">-- Semua Kurikulum --</option>
                            @foreach($kurikulums as $kurikulum)
                                <option value="{{ Crypt::encryptString($kurikulum->id) }}" {{ request('kurikulum') == Crypt::encryptString($kurikulum->id) ? 'selected' : '' }}>
                                    {{ $kurikulum->nama_kuri }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <th style="width: 3%">No.</th>
                        <th style="text-align: center;">Kurikulum</th>
                        <th style="text-align: center;" >Kode CPL</th>
                        <th style="text-align: center;">Kode Sub CPL</th>
                        <th style="text-align: center;">Deskripsi</th>
                        <th>Bobot</th>
                        <th>Target Nilai</th>
                        <th style="width:6% text-align: center;">Aksi</th>
                    </thead>
                    <tbody>
                        @foreach($scp as $cp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cp->nama_kuri }}</td>
                                <td style="text-align: center;">{{ $cp->kode_cpl }}</td>
                                <td style="text-align: center;">{{ $cp->kode_subcpl }}</td>
                                <td>{{ $cp->desk_subcpl }}</td>
                                <td><center>{{ $cp->bobot }}</center></td>
                                <td><center>{{ $cp->trgt_nilai }}</center></td>
                                <td>
                                    <a href="{{ url('prodiadmin/e_subcpl/' . Crypt::encryptString($cp->id)) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                    <a href="javascript:void(0);" onclick="konfirmasiHapus('{{ Crypt::encryptString($cp->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

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
                window.location.href = "{{ url('prodiadmin/h_subcpl') }}/" + id;
            }
        });
    }

    $(document).ready(function() {
        // Hancurkan DataTable jika sudah diinisialisasi, lalu inisialisasi ulang
        $.fn.dataTable.ext.errMode = 'none';
        // Inisialisasi ulang DataTables untuk tabel pertama (example1)
        tabel1 = $('#example1').DataTable({
            "pageLength": 100,  // Set default 100 rows
            "lengthMenu": [[25, 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, "All"]],
            "scrollX": true,
            "autoWidth": true,
            "responsive": true,
            "retrieve": true,
        });
    });
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
