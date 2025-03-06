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
                <h3 class="box-title">Tambah User</h3>
            </div>
            <div class="card card-primary card-outline">
                {{-- <div class="card-header">
                    <h5 class="m-0"><a href="{{url('administrator/createuser')}}"><div class="btn btn-small btn-primary text-center"> Tambah User</div></a></h5>
                </div> --}}
                    <div class="card-body">
                        <form role="form" method="post" action="{{ url('superadmin/simpanuser')}}">
                            @csrf
                            <div class="box-body">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>NIP (Username)</strong>
                                    <input type="text" name="nip" class="form-control" id="nip">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                    <strong>Nama User</strong>
                                    <input type="text" name="name" class="form-control" id="name">
                                </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Email</strong>
                                    <input type="email" name="email" class="form-control" id="email">
                                </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Password</strong>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Konfirmasi Password</strong>
                                    <input type="password" name="confirm-password" class="form-control">
                                </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="roles">Level</label>
                                    <select class="form-control" name="role_id">
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->role_name}}</option>
                                        @endforeach
                                </select>
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
