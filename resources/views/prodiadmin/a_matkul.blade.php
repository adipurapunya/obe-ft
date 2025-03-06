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
                <h3 class="box-title">Tambah Data Mata Kuliah</h3>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-body">

                    <!-- Tempatkan pesan alert hanya di atas form -->
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Formulir untuk input data mata kuliah -->
                    <form role="form" method="post" action="{{ url('prodiadmin/s_matkul') }}">
                        @csrf
                        <div class="box-body">

                            <!-- Input untuk prodi_id (hidden) -->
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    @foreach($kuri as $kr)
                                    <input type="hidden" name="prodi_id" value="{{ $kr->prodi_id }}" class="form-control">
                                    @endforeach
                                </div>
                            </div>

                            <!-- Input untuk kurikulum -->
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

                            <!-- Input untuk semester -->
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Semester</strong>
                                    <select name="semester_id" class="form-control">
                                        <option value=" " selected disabled>Pilih Semester</option>
                                        @foreach($semes as $sm)
                                            <option value="{{ $sm->id }}">{{ $sm->nama_smtr }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('semester_id'))
                                        <span class="text-danger">{{ $errors->first('semester_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Input untuk Kode Mata Kuliah -->
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Kode Mata Kuliah</strong>
                                    <input type="text" name="kode_mk" class="form-control">
                                    @if ($errors->has('kode_mk'))
                                        <span class="text-danger">{{ $errors->first('kode_mk') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Input untuk Nama Mata Kuliah -->
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Nama Mata Kuliah</strong>
                                    <input type="text" name="nama_mk" class="form-control">
                                    @if ($errors->has('nama_mk'))
                                        <span class="text-danger">{{ $errors->first('nama_mk') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Input untuk SKS Kurikulum, SKS Teori, SKS Praktek, dan SKS Lapangan -->
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>SKS</strong>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input type="text" name="sks_kuri" class="form-control" placeholder="Kurikulum">
                                            @if ($errors->has('sks_kuri'))
                                                <span class="text-danger">{{ $errors->first('sks_kuri') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="sks_teo" class="form-control" placeholder="Teori">
                                            @if ($errors->has('sks_teo'))
                                                <span class="text-danger">{{ $errors->first('sks_teo') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="sks_prak" class="form-control" placeholder="Praktek">
                                            @if ($errors->has('sks_prak'))
                                                <span class="text-danger">{{ $errors->first('sks_prak') }}</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="sks_lap" class="form-control" placeholder="Praktek Lapangan">
                                            @if ($errors->has('sks_lap'))
                                                <span class="text-danger">{{ $errors->first('sks_lap') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Input untuk status -->
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Status</strong>
                                    <select name="status" class="form-control">
                                        <option value="" selected disabled>Pilih Status Mata Kuliah</option>
                                        <option value="Wajib">Wajib</option>
                                        <option value="Pilihan">Pilihan</option>
                                    </select>
                                    @if ($errors->has('status'))
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Input untuk rumpun mata kuliah -->
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Rumpun Mata Kuliah</strong>
                                    <select name="rumpun_mk" class="form-control">
                                        <option value="" selected disabled>Pilih Rumpun Mata Kuliah</option>
                                        <option value="MK Program Studi">MK Program Studi</option>
                                        <option value="MK Fakultas">MK Fakultas</option>
                                        <option value="MK Universitas">MK Universitas</option>
                                        <option value="MK Basic Science">MK Basic Science</option>
                                    </select>
                                    @if ($errors->has('rumpun_mk'))
                                        <span class="text-danger">{{ $errors->first('rumpun_mk') }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Tombol Simpan -->
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="float:right">Simpan</button>
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
