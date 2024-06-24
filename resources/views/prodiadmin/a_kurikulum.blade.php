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
                <h3 class="box-title">Tambah Data Kurikulum</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form role="form" method="post" action="{{ url('prodiadmin/s_kurikulum')}}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="prodi_id" value="{{ $data }}">
                            <div class="box-body">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                            <strong>Kode Kurikulum</strong>
                                            <input type="text" name="kode_kuri" class="form-control">
                                            @if ($errors->has('kode_kuri'))
                                            <span class="text-danger">{{ $errors->first('kode_kuri') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                            <strong>Nama Kurikulum</strong>
                                            <input type="text" name="nama_kuri" class="form-control">
                                            @if ($errors->has('nama_kuri'))
                                            <span class="text-danger">{{ $errors->first('nama_kuri') }}</span>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                            <strong>Deskripsi</strong>
                                            <input type="text" name="deskripsi" class="form-control">
                                            @if ($errors->has('deskripsi'))
                                            <span class="text-danger">{{ $errors->first('deskripsi') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                            <strong>Tahun Kurikulum</strong>
                                            <input type="text" name="tahun_kuri" class="form-control">
                                            @if ($errors->has('tahun_kuri'))
                                            <span class="text-danger">{{ $errors->first('tahun_kuri') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                            <strong>Nomor SK Kurikulum</strong>
                                            <input type="text" name="sk_kuri" class="form-control">
                                            @if ($errors->has('sk_kuri'))
                                            <span class="text-danger">{{ $errors->first('sk_kuri') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                            <strong>File SK Kurikulum (Tipe file : PNG, JPG, PDF. Maks : 6 MB)</strong>
                                            <input type="file" name="filesk" class="form-control">
                                            @if ($errors->has('filesk'))
                                            <span class="text-danger">{{ $errors->first('filesk') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-info" style="float:right">Simpan</button>
                                    </div>
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
