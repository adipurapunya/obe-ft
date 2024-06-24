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
                        @foreach ($objek as $ds)
                            <form action="/prodiadmin/u_subcpl/{{$ds->id}}" method="POST">
                        @endforeach
                        @csrf
                        @method('put')

                        <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>CPL</strong>
                                        <select name="cpl_id" class="form-control">
                                            @foreach($cpel as $cp)
                                                <option value="{{ $cp->kode_cpl }}" @if($cp->kode_cpl == $objek->first()->cpl_id) selected @endif>{{ $cp->kode_cpl }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Kode Sub CPL</strong>
                                        @foreach($objek as $cp)
                                            <input type="text" name="kode_subcpl" value="{{ $cp->kode_subcpl}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Deskripsi</strong>
                                        @foreach($objek as $cp)
                                            <input type="text" name="desk_subcpl" value="{{ $cp->desk_subcpl}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="float:right">Update</button>
                                </div>
                            </div>
                    </div>
            </div>
        </div>
        </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection
