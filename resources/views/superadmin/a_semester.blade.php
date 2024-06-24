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
                <h3 class="box-title">Tambah Data Semester</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form role="form" method="post" action="{{ url('superadmin/s_semester')}}">
                            @csrf
                            <div class="box-body">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Nama Semester</strong>
                                        <input type="text" name="nama_smtr" class="form-control">
                                        @if ($errors->has('nama_smtr'))
                                            <span class="text-danger">{{ $errors->first('nama_smtr') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                      <strong> Semester</strong>
                                        <select name="semester" class="form-control">
                                            <option value=" " selected disabled>Pilih Semester</option>
                                            <option value="Ganjil">Ganjil</option>
                                            <option value="Genap">Genap</option>
                                        </select>
                                        @if ($errors->has('semester'))
                                            <span class="text-danger">{{ $errors->first('semester') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                      <strong> Status</strong>
                                        <select name="status" class="form-control">
                                            <option value=" " selected disabled>Pilih Status Semester</option>
                                            <option value="Aktif">Aktif</option>
                                            <option value="Tidak Aktif">Tidak Aktif</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Tahun</strong>
                                        <input type="text" name="tahun" class="form-control">
                                        @if ($errors->has('tahun'))
                                            <span class="text-danger">{{ $errors->first('tahun') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Keterangan</strong>
                                        <input type="text" name="keterangan" class="form-control">
                                        @if ($errors->has('keterangan'))
                                            <span class="text-danger">{{ $errors->first('keterangan') }}</span>
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
