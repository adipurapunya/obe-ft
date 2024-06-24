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
                        <form role="form" method="post" action="{{ url('superadmin/s_dosen')}}">
                            @csrf
                            <div class="box-body">
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Pilih User</strong>
                                        <select name="user_id" id="user_id" class="form-control">
                                            <option value="">Pilih User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>NIDN</strong>
                                        <input type="text" name="nidn" class="form-control">
                                        @if ($errors->has('nidn'))
                                            <span class="text-danger">{{ $errors->first('nidn') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>NIP</strong>
                                        <input type="text" name="nip" class="form-control" maxlength="18">
                                        @if ($errors->has('nip'))
                                            <span class="text-danger">{{ $errors->first('nip') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <strong>Nama Dosen</strong>
                                        <input type="text" name="nama_dosen" class="form-control" id="nama_dosen" readonly>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#user_id').change(function(){
                var user_id = $(this).val();
                $.ajax({
                    url: '/getUserName/' + user_id,
                    type: 'GET',
                    success: function(response) {
                        $('#nama_dosen').val(response.nama_dosen);
                    }
                });
            });
        });
    </script>


  @endsection
