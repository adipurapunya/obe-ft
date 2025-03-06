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
                <h3 class="box-title">Edit Data Administrator Prodi</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form action="/superadmin/u_adminprodi/{{ $objek->id }}" method="POST">
                        @csrf
                        @method('put')
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <strong>Kode Prodi</strong>
                                            <input type="text" name="koprodi" value="{{ $objek->koprodi}}" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nama Administrator Prodi</strong>
                                    <select name="user_id" class="form-control">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" @if($user->id == $objek->user_id) selected @endif>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

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
