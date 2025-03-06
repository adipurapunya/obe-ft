@extends('layouts.admin.panel')

@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Data Mata Kuliah</h5>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%;">No.</th>
                            <th style="width: 12%">Kurikulum</th>
                            <th style="width: 12%">Kode MK</th>
                            <th>Mata Kuliah</th>
                            <th style="width: 8%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($matk as $mt)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mt->nama_kuri }}</td>
                                <td>{{ $mt->kode_mk }}</td>
                                <td>{{ $mt->nama_mk }}</td>
                                <td>
                                    <a href="{{ url('prodiadmin/a_mksubcpl/'.Crypt::encryptString($mt->id), [])}}" class="btn btn-warning btn-sm">+ Sub CPL</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Matriks MK dan Sub CPL</h5>
            </div>
            <div class="card-body">
                <table id="example3" class="table table-bordered table-striped">
                    <thead>
                        <th style="width: 3%">No.</th>
                        <th style="width: 8%">Kode MK</th>
                        <th style="width: 12%">Nama MK</th>
                        <th style="width: 9%">Kode CPL</th>
                        <th style="width: 9%">Kode Sub CPL</th>
                        <th style="width:4%"><center>Aksi</center></th>
                    </thead>
                    <tbody>
                        @php
                            $prevKodeMk = '';
                            $prevNamaMk = '';
                            $prevKodeCpl = '';
                        @endphp
                        @foreach($mkscp as $cp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if ($cp->kode_mk !== $prevKodeMk || $cp->nama_mk !== $prevNamaMk)
                                        {{ $cp->kode_mk }}
                                    @endif
                                </td>
                                <td>
                                    @if ($cp->nama_mk !== $prevNamaMk)
                                        {{ $cp->nama_mk }}
                                    @endif
                                </td>
                                <td>{{ $cp->kode_cpl }}</td>
                                <td>{{ $cp->kode_subcpl }}</td>
                                <td>
                                    <center>
                                        <!--<a href="{{ url('prodiadmin/e_mksubcpl/'.Crypt::encryptString($cp->id), [])}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a> -->
                                        <!--<a href="javascript:void(0);" onclick="konfirmasiHapus('{{ Crypt::encryptString($cp->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>-->
                                        <button class="btn btn-sm btn-danger" onclick="konfirmasiHapus('{{ Crypt::encryptString($cp->id) }}')">Hapus</button>
                                    </center>
                                </td>
                            </tr>
                            @php
                                $prevKodeMk = $cp->kode_mk;
                                $prevNamaMk = $cp->nama_mk;
                                $prevKodeCpl = $cp->kode_cpl;
                            @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- JavaScript DataTables -->
<script>
    
    $(document).ready(function() {
        // Hancurkan DataTable jika sudah diinisialisasi, lalu inisialisasi ulang
        $.fn.dataTable.ext.errMode = 'none';
        // Inisialisasi ulang DataTables untuk tabel pertama (example1)
        tabel1 = $('#example1').DataTable({
            "pageLength": 50,  // Set default 100 rows
            "lengthMenu": [[25, 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, "All"]],
            "scrollX": true,
            "autoWidth": false,
            "responsive": true,
            retrieve: true,
        });
        

        // Memastikan dropdown "Show entries" tidak bertumpuk
        setTimeout(() => {
            document.querySelectorAll('.dataTables_length select').forEach(select => {
                select.style.width = "80px";
            });
        }, 500);
    });

    $(document).ready(function() {
        // Hancurkan DataTable jika sudah diinisialisasi, lalu inisialisasi ulang
        $.fn.dataTable.ext.errMode = 'none';
        // Inisialisasi ulang DataTables untuk tabel pertama (example1)
        tabel3 = $('#example3').DataTable({
            "pageLength": 50,  // Set default 100 rows
            "lengthMenu": [[25, 50, 100, 250, 500, -1], [25, 50, 100, 250, 500, "All"]],
            "scrollX": true,
            "autoWidth": false,
            "responsive": true,
        });
        
        // Memastikan dropdown "Show entries" tidak bertumpuk
        setTimeout(() => {
            document.querySelectorAll('.dataTables_length select').forEach(select => {
                select.style.width = "80px";
            });
        }, 500);

        
    });


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
                window.location.href = "{{ url('prodiadmin/h_mksubcpl') }}/" + id;
            }
        });
    }
</script>

<!-- CSS Perbaikan untuk Show Entries -->
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
