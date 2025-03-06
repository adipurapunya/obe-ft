@extends('layouts.admin.panel')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Edit Data Matriks MK Sub CPMK</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        <!-- Tidak perlu foreach karena $objek hanya satu item -->
                        <form action="/dosen/u_mkscpmk/{{$objek->id}}" method="POST">
                        @csrf
                        @method('put')

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Mata Kuliah</strong>
                                <input type="text" value="{{ $objek->matkul->nama_mk }}" class="form-control" readonly>
                                <input type="hidden" name="matkul_id" value="{{ $objek->matkul->id }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Kode Sub CPMK</strong>
                                <input type="text" name="kode_cpmk" value="{{ $objek->kode_scpmk }}" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Deskripsi Sub CPMK</strong>
                                <textarea name="desk_scpmk" class="form-control" rows="4">{{ $objek->desk_scpmk }}</textarea>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="float:right">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content-wrapper -->



@endsection
