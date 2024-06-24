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
                <h3 class="box-title">Tambah Data CPMK</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">

                        <form role="form" method="post" action="{{ url('superadmin/s_cpl')}}">
                            @csrf
                            <div class="box-body">

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                      <strong>Mata Kuliah</strong>
                                        <select name="matkul_id" class="form-control">
                                          <option value=" " selected disabled>Pilih Mata Kuliah</option>
                                          @foreach($matk as $mt)
                                            <option value="{{ $mt->id }}">{{ $mt->nama_mk }}</option>
                                          @endforeach
                                        </select>
                                        @if ($errors->has('matkul_id'))
                                            <span class="text-danger">{{ $errors->first('matkul_id') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                            <strong>Kode CPMK</strong>
                                            <input type="text" name="kode_cpmk" class="form-control">
                                            @if ($errors->has('kode_cpmk'))
                                            <span class="text-danger">{{ $errors->first('kode_cpmk') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                            <strong>Deskripsi CPMK</strong>
                                            <textarea name="desk_cpmk" class="form-control" style="height: 100px; resize: vertical;"></textarea>
                                            @if ($errors->has('desk_cpmk'))
                                            <span class="text-danger">{{ $errors->first('desk_cpmk') }}</span>
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
