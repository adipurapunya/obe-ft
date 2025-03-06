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
                <h3 class="box-title">Edit Data Mata Kuliah</h3>
            </div>
            <div class="card card-primary card-outline">
                    <div class="card-body">
                            <form action="/prodiadmin/u_matkul/{{$objek->id}}" method="POST">
                        @csrf
                        @method('put')

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Kurikulum</strong>
                                    <select name="kurikulum_id" class="form-control">
                                        @foreach($kuri as $kr)
                                            <option value="{{ $kr->id }}" @if($kr->id == $objek->kurikulum_id) selected @endif>{{ $kr->nama_kuri }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Semester</strong>
                                    <select name="semester_id" class="form-control">
                                        @foreach($semes as $sm)
                                            <option value="{{ $sm->id }}" @if($sm->id == $objek->semester_id) selected @endif>{{ $sm->nama_smtr }}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                    <strong>Kode Mata Kuliah</strong>
                                    <input type="text" name="kode_mk" value="{{ $objek->kode_mk}}" class="form-control">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                    <strong>Nama Mata Kuliah</strong>
                                    <input type="text" name="nama_mk" value="{{ $objek->nama_mk}}" class="form-control">
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>SKS</strong>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="sks_kuri" value="{{ $objek->sks_kuri}}" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="sks_teo" value="{{ $objek->sks_teo}}" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="sks_prak" value="{{ $objek->sks_prak}}" class="form-control">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="sks_lap" value="{{ $objek->sks_lap}}" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Status</strong>
                                <select name="status" class="form-control">
                                    <option value="" disabled {{ is_null($objek->status) ? 'selected' : '' }}>Pilih Status Mata Kuliah</option>
                                    <option value="Wajib" {{ $objek->status == 'Wajib' ? 'selected' : '' }}>Wajib</option>
                                    <option value="Pilihan" {{ $objek->status == 'Pilihan' ? 'selected' : '' }}>Pilihan</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Rumpun Mata Kuliah</strong>
                                <select name="rumpun_mk" class="form-control">
                                    <option value="" disabled {{ is_null($objek->rumpun_mk) ? 'selected' : '' }}>Pilih Rumpun Mata Kuliah</option>
                                    <option value="MK Program Studi" {{ $objek->rumpun_mk == 'MK Program Studi' ? 'selected' : '' }}>MK Program Studi</option>
                                    <option value="MK Fakultas" {{ $objek->rumpun_mk == 'MK Fakultas' ? 'selected' : '' }}>MK Fakultas</option>
                                    <option value="MK Universitas" {{ $objek->rumpun_mk == 'MK Universitas' ? 'selected' : '' }}>MK Universitas</option>
				<option value="MK Basic Science" {{ $objek->rumpun_mk == 'MK Basic Science' ? 'selected' : '' }}>MK Basic Science</option>
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
