@extends('layouts.admin.panel')

@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              {{-- <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li> --}}
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Capaian Pembelajaran Lulusan</h5>
            </tr>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <th style="width: 3%">No.</th>
                        <th style="width: 9%">Kode CPL</th>
                        <th>Deskripsi</th>
                    </thead>
                    <tbody>
                        @foreach($cpl_lengkap as $cp)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $cp->kode_cpl }}</td>
                                <td>{{ $cp->deskrip_cpl }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            </div>
    </section>

    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Form Input Matriks Mata Kuliah dan CPL</h5>
            </tr>
            </div>

            <div class="card-body">
                <form role="form" method="post" action="{{ url('dosen/s_mkcpl')}}">
                    @csrf
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Mata Kuliah</th>
                                @foreach ($cpl as $c)
                                    <th><center>{{ $c->kode_cpl }}</center></th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mata_kuliah as $mk)
                                <tr>
                                    <td>{{ $mk->kode_mk }} - {{ $mk->nama_mk }}</td>
                                    @foreach ($cpl as $c)
                                        <td>
                                            <center><input type="checkbox" name="mkcpl[{{ $mk->id }}][{{ $c->id }}]"></center>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-info" style="float:right">Simpan</button>
                        </div>
                    </div>
                </form>

            </div>
            </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @endsection
