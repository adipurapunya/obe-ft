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
                <h3 class="box-title">Edit Data Kurikulum</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form action="/superadmin/u_kurikulum/{{$objek->id}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Jurusan / Prodi</strong>
                                    <select name="prodi_id" class="form-control">
                                        @foreach($prodi as $pr)
                                            <option value="{{ $pr->id }}" @if($pr->id == $objek->prodi_id) selected @endif>{{ $pr->nama_prodi }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Kode Kurikulum</strong>
                                            <input type="text" name="kode_kuri" value="{{ $objek->kode_kuri}}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Nama Kurikulum</strong>
                                            <input type="text" name="nama_kuri" value="{{ $objek->nama_kuri}}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Tahun Kurikulum</strong>
                                            <input type="text" name="tahun_kuri" value="{{ $objek->tahun_kuri}}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Deskripsi</strong>
                                            <input type="text" name="deskripsi" value="{{ $objek->deskripsi}}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>No. SK Kurikulum</strong>
                                            <input type="text" name="sk_kuri" value="{{ $objek->sk_kuri}}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                        <strong>File SK Kurikulum</strong>
                                        <input type="file" name="filesk" class="form-control">
                                        @if ($errors->has('filesk'))
                                        <span class="text-danger">{{ $errors->first('filesk') }}</span>
                                    @endif
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
