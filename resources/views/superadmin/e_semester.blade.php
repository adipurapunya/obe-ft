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
                <h3 class="box-title">Edit Data Semester</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        @foreach ($objek as $sm)
                            <form action="/superadmin/u_semester/{{$sm->id}}" method="POST">
                        @endforeach
                        @csrf
                        @method('put')
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Nama Semester</strong>
                                        @foreach($objek as $sm)
                                            <input type="text" name="nama_smtr" value="{{ $sm->nama_smtr}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Semester</strong>
                                            @foreach($objek as $sm)
                                            <select name="semester" class="form-control">
                                                <option value= {{$sm->semester}}>{{$sm->semester}}</option>
                                                <option value="Ganjil">Ganjil</option>
                                                <option value="Genap">Genap</option>
                                            </select>
                                            @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Status</strong>
                                            @foreach($objek as $sm)
                                            <select name="status" class="form-control">
                                                <option value= {{$sm->status}}>{{$sm->status}}</option>
                                                <option value="Aktif">Aktif</option>
                                                <option value="Tidak Aktif">Tidak Aktif</option>
                                            </select>
                                            @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Tahun</strong>
                                        @foreach($objek as $sm)
                                            <input type="text" name="tahun" value="{{ $sm->tahun}}" class="form-control">
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Keterangan</strong>
                                        @foreach($objek as $sm)
                                            <input type="text" name="keterangan" value="{{ $sm->keterangan}}" class="form-control">
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
