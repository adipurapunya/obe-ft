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
                <h3 class="box-title">Master Data Mahasiswa</h3>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <!-- Form Input Manual -->

                        <form method="POST" action="{{ url('prodiadmin/s_importMahasiswa') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">

                            <h4>Import Data Mahasiswa</h4>
                            <hr>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <label for="file"><strong>Pilih File Excel</strong></label>
                                        <input type="file" name="file_mhs" class="form-control" required>
                                        @if ($errors->has('file'))
                                            <span class="text-danger">{{ $errors->first('file') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <a href="{{ url('prodiadmin/downloadTemplateMahasiswa') }}" class="btn btn-primary" style="float:left">
                                            Download Template
                                        </a>
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success" style="float:right">Import</button>
                                    </div>
                                </div>
                        </form><br><br><br>

                            <form method="POST" action="{{ url('prodiadmin/s_mahasiswa') }}" enctype="multipart/form-data">
                            @csrf
                            <h4>Input Manual</h4>
                            <hr>
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
                                  <strong>Jenis Kelamin</strong>
                                    <select name="jenis_kelamin" class="form-control">
                                        <option value=" " selected disabled>Pilih Jenis Kelamin</option>
                                        <option value="L">Laki-Laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="prodi_id" value="{{ $prodiId }}">
                                </div>
                            </div>

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

    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Data Mahasiswa </h5>

            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <th style="width: ">No.</th>
                        <th style="width: ">NIM</th>
                        <th style="width: ">Nama</th>
                        <th>Angkatan</th>
                        <th><center>Aksi</center></th>
                    </thead>
                    <tbody>
                       
                    @foreach($mahasiswas as $mhs)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->nama_mahasiswa }}</td>
                                <td>{{ $mhs->angkatan }}</td>
                                <td>
                                    <center>
                                    <a href="{{ url('prodiadmin/editMahasiswa', Crypt::encryptString($mhs->id)) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ Crypt::encryptString($mhs->id) }}')">Hapus</button>
                                    </center>
                                </td>

                            </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<style>
        /* Memastikan dropdown "Show entries" tidak bertumpuk */
    .dataTables_length select {
        width: 80px !important;  /* Lebar dropdown */
        padding: 6px;  /* Memberikan jarak agar tampilan lebih rapi */
        border-radius: 5px;
        text-align: center;
        appearance: auto;
        -moz-appearance: none;
        -webkit-appearance: none;
    }

    /* Memastikan tabel fleksibel */
    .dataTables_wrapper .dataTables_length select {
        display: inline-block !important;
        min-width: 80px;
        max-width: 100px;
        padding: 6px;
    }

</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(encryptedId) {
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data mahasiswa akan dihapus secara permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, Hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "/prodiadmin/hapusMahasiswa/" + encodeURIComponent(encryptedId);
            }
        });
    }
</script>

@endsection
