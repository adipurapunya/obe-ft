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
                <h3 class="box-title">Tambah Data Jurusan</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form role="form" method="post" action="{{ url('superadmin/s_prodi')}}">
                            @csrf
                            <div class="box-body">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Kode Jurusan</strong>
                                        <input type="text" name="kopro" class="form-control">
                                        @if ($errors->has('kopro'))
                                            <span class="text-danger">{{ $errors->first('kopro') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Nama Jurusan</strong>
                                        <input type="text" name="nama_prodi" class="form-control">
                                        @if ($errors->has('nama_prodi'))
                                            <span class="text-danger">{{ $errors->first('nama_prodi') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Inisal</strong>
                                        <input type="text" name="inisial" class="form-control">
                                        @if ($errors->has('inisial'))
                                            <span class="text-danger">{{ $errors->first('inisial') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                      <strong> Jenjang</strong>
                                        <select name="jenjang" class="form-control">
                                          <option value=" " selected disabled>Pilih Jenjang</option>
                                            <option value="S1">S1</option>
                                            <option value="S2">S2</option>
                                            <option value="S3">S3</option>
                                        </select>
                                        @if ($errors->has('jenjang'))
                                            <span class="text-danger">{{ $errors->first('jenjang') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary" style="float:right">Simpan</button>
                                    </div>
                                </div>
                        </form>
                    </div>
            </div>
        </div>
        </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @endsection
