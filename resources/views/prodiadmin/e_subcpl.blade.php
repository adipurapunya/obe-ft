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
                <h3 class="box-title">Edit Data Sub CPL</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        <!-- Tidak perlu foreach karena $objek hanya satu item -->
                        <form action="/prodiadmin/u_subcpl/{{$objek->id}}" method="POST">
                        @csrf
                        @method('put')

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>CPL</strong>
                                <select class="form-control" disabled>
                                    @foreach($cpel as $cp)
                                        <option value="{{ $cp->id }}" @if($cp->id == $objek->cpl_id) selected @endif>{{ $cp->kode_cpl }}</option>
                                    @endforeach
                                </select>

                                <!-- Input tersembunyi untuk mengirimkan nilai cpl_id -->
                                <input type="hidden" name="cpl_id" value="{{ $objek->cpl_id }}">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Kode Sub CPL</strong>
                                <input type="text" name="kode_subcpl" value="{{ $objek->kode_subcpl }}" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Deskripsi</strong>
                                <input type="text" name="desk_subcpl" value="{{ $objek->desk_subcpl }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Bobot</strong>
                                <input type="text" name="bobot" value="{{ $objek->bobot }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Target Nilai</strong>
                                <input type="text" name="trgt_nilai" value="{{ $objek->trgt_nilai }}" class="form-control">
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
