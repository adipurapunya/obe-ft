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
                        <div class="container">
                            <h4>Import User Excel</h4>
                            <div style="margin-bottom: 10px;">
                                <a href="{{ route('download_template') }}" class="btn btn-info">Unduh Template Excel</a>
                            </div>
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="box-body">
                                <form action="{{ route('import_user') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="file">Pilih File Excel:</label>
                                        <input type="file" id="file" name="file" class="form-control">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Import</button>
                                </form>
                    </div>
            </div>
        </div>
        </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @endsection



<!-- resources/views/import_user.blade.php -->

