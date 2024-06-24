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
                <h3 class="box-title">Edit Data CPL</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        @foreach ($objek as $ds)
                            <form action="/prodiadmin/u_cpl/{{$ds->id}}" method="POST">
                        @endforeach
                        @csrf
                        @method('put')

                        <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Kurikulum</strong>
                                        <select name="kurikulum_id" class="form-control">
                                            @foreach($kur as $kr)
                                                <option value="{{ $kr->id }}" @if($kr->id == $objek->first()->kurikulum_id) selected @endif>{{ $kr->nama_kuri }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Kode CPL</strong>
                                        @foreach($objek as $kr)
                                            <input type="text" name="kode_cpl" value="{{ $kr->kode_cpl}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Deskripsi</strong>
                                        @foreach($objek as $kr)
                                            <input type="text" name="deskrip_cpl" value="{{ $kr->deskrip_cpl}}" class="form-control">
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
