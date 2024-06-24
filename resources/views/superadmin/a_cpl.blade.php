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
                <h3 class="box-title">Tambah Data CPL</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">

                        <form role="form" method="post" action="{{ url('superadmin/s_cpl')}}">
                            @csrf
                            <div class="box-body">

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                      <strong>Program Studi</strong>
                                        <select name="prodi_id" class="form-control">
                                          <option value=" " selected disabled>Pilih Program Studi</option>
                                          @foreach($prod as $pr)
                                            <option value="{{ $pr->id }}">{{ $pr->nama_prodi }}</option>
                                          @endforeach
                                        </select>
                                        @if ($errors->has('prodi_id'))
                                            <span class="text-danger">{{ $errors->first('prodi_id') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                      <strong>Kurikulum</strong>
                                        <select name="kurikulum_id" class="form-control">
                                          <option value=" " selected disabled>Pilih Kurikulum</option>
                                          @foreach($kuri as $ku)
                                            <option value="{{ $ku->id }}">{{ $ku->nama_kuri }}</option>
                                          @endforeach
                                        </select>
                                        @if ($errors->has('kurikulum_id'))
                                            <span class="text-danger">{{ $errors->first('kurikulum_id') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                            <strong>Kode CPL</strong>
                                            <input type="text" name="kode_cpl" class="form-control">
                                            @if ($errors->has('kode_cpl'))
                                            <span class="text-danger">{{ $errors->first('kode_cpl') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                            <strong>Deskripsi CPL</strong>
                                            <textarea name="deskrip_cpl" class="form-control" style="height: 100px; resize: vertical;"></textarea>
                                            @if ($errors->has('deskrip_cpl'))
                                            <span class="text-danger">{{ $errors->first('deskrip_cpl') }}</span>
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
