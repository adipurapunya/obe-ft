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
                <h3 class="box-title">Tambah Data Mahasiswa</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form role="form" method="post" action="{{ url('superadmin/s_mahasiswa')}}">
                            @csrf
                            <div class="box-body">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>NIM</strong>
                                        <input type="text" name="nim" class="form-control">
                                        @if ($errors->has('nim'))
                                            <span class="text-danger">{{ $errors->first('nim') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Nama Mahasiswa</strong>
                                        <input type="text" name="nama_mahasiswa" class="form-control">
                                        @if ($errors->has('nama_mahasiswa'))
                                            <span class="text-danger">{{ $errors->first('nama_mahasiswa') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Angkatan</strong>
                                        <input type="text" name="angkatan" class="form-control">
                                        @if ($errors->has('angkatan'))
                                            <span class="text-danger">{{ $errors->first('angkatan') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                      <strong> Semester di Terima</strong>
                                        <select name="smt_angkatan" class="form-control">
                                            <option value=" " selected disabled>Pilih Semester</option>
                                            <option value="Ganjil">Ganjil</option>
                                            <option value="Genap">Genap</option>
                                        </select>
                                        @if ($errors->has('smt_angkatan'))
                                            <span class="text-danger">{{ $errors->first('smt_angkatan') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                      <strong> Jurusan / Prodi</strong>
                                        <select name="prodi_id" class="form-control">
                                          <option value=" " selected disabled>Pilih Jurusan / Prodi</option>
                                          @foreach($prodi as $pro)
                                            <option value="{{ $pro->id }}">{{ $pro->nama_prodi }}</option>
                                          @endforeach
                                        </select>
                                        @if ($errors->has('prodi_id'))
                                            <span class="text-danger">{{ $errors->first('prodi_id') }}</span>
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
