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
                <h3 class="box-title">Edit Data Dosen</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        @foreach ($objek as $ds)
                            <form action="/superadmin/u_dosen/{{$ds->id}}" method="POST">
                        @endforeach
                        @csrf
                        @method('put')
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>NIDN</strong>
                                        @foreach($objek as $ds)
                                            <input type="text" name="nidn" value="{{ $ds->nidn}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>NIP</strong>
                                        @foreach($objek as $ds)
                                            <input type="text" name="nip" value="{{ $ds->nip}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Jurusan / Prodi</strong>
                                        <select name="prodi_id" class="form-control">
                                            @foreach($prodi as $pr)
                                                <option value="{{ $pr->id }}" @if($pr->id == $data->first()->prodi_id) selected @endif>{{ $pr->nama_prodi }}</option>
                                            @endforeach
                                        </select>
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
