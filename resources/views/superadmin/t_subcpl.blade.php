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
                <h5 class="m-0">Filter Data Sub CPL</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('filter.subcpl') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="prodi_id">Program Studi</label>
                                <select name="prodi_id" id="prodi_id" class="form-control">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($prodiList as $key => $value)
                                        <option value="{{ $key }}" {{ request('prodi_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mt-4">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Data Sub CPL</h5>
            </div>
            <div class="card-body">
                @if(empty($scp))
                    <div class="alert alert-info">Silahkan memilih Program Studi untuk melihat data Sub CPL</div>
                @else
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <th style="width: 3%">No.</th>
                            <th style="width: 12%">Kurikulum</th>
                            <th style="width: 12%">Kode CPL</th>
                            <th style="width: 12%">Kode Sub CPL</th>
                            <th>Deskripsi</th>
                            <th style="width:9%">Aksi</th>
                        </thead>
                        <tbody>
                            @foreach($scp as $cp)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $cp->nama_kuri }}</td>
                                    <td>{{ $cp->kode_cpl }}</td>
                                    <td>{{ $cp->kode_subcpl }}</td>
                                    <td>{{ $cp->desk_subcpl }}</td>
                                    <td>
                                        <a href="{{ url('prodiadmin/e_subcpl/'.Crypt::encryptString($cp->id)) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0);" onclick="konfirmasiHapus('{{ Crypt::encryptString($cp->id) }}')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
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
                window.location.href = "{{ url('superadmin/h_subcpl') }}/" + id;
            }
        });
    }
</script>

@endsection
