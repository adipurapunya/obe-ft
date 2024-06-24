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
                <h3 class="box-title">Edit Data Mahasiswa</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        @foreach ($objek as $mh)
                            <form action="/superadmin/u_mahasiswa/{{$mh->id}}" method="POST">
                        @endforeach
                        @csrf
                        @method('put')
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>NIM</strong>
                                        @foreach($objek as $mh)
                                            <input type="text" name="nim" value="{{ $mh->nim}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Nama Mahasiswa</strong>
                                        @foreach($objek as $mh)
                                            <input type="text" name="nama_mahasiswa" value="{{ $mh->nama_mahasiswa}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Tahun Penerimaan</strong>
                                        @foreach($objek as $mh)
                                            <input type="text" name="angkatan" value="{{ $mh->angkatan}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Semester Penerimaan</strong>
                                            @foreach($objek as $sm)
                                            <select name="smt_angkatan" class="form-control">
                                                <option value= {{$sm->smt_angkatan}}>{{$sm->smt_angkatan}}</option>
                                                <option value="Ganjil">Ganjil</option>
                                                <option value="Genap">Genap</option>
                                            </select>
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
