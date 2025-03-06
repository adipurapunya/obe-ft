@extends('layouts.admin.panel')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tambah Kelas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Tambah Kelas</li>
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
                <h5 class="m-0">Form Tambah Kelas</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url('prodiadmin/s_kelas') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="semester_id">Semester</label>
                                <select id="semester_id" name="semester_id" class="form-control" required>
                                    <option value="" disabled selected>Pilih Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}">{{ $semester->keterangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Mata Kuliah -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="matkul_id">Mata Kuliah</label>
                                <select id="matkul_id" name="matkul_id" class="form-control" required>
                                    <option value="" disabled selected>Pilih Mata Kuliah</option>
                                    @foreach ($matkul as $mk)
                                        <option value="{{ $mk->id }}">{{ $mk->nama_mk }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- No. Urut Kelas -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="urut">No. Urut Kelas [1,2,3,...]</label>
                                <input type="number" id="urut" name="urut" class="form-control @error('urut') is-invalid @enderror"
                                       value="{{ old('urut') }}" required>
                                @error('urut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <strong>Nama Kelas</strong>
                                <select name="nama_kelas" class="form-control">
                                    <option value="" selected disabled>Pilih Nama Kelas</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">c</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                </select>
                                @if ($errors->has('nama_kelas'))
                                    <span class="text-danger">{{ $errors->first('nama_kelas') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Dosen 1 -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dosen_satu">Dosen 1</label>
                                <select id="dosen_satu" name="dosen_satu" class="form-control" required>
                                    <option value="" disabled selected>Pilih Dosen (Wajib)</option>
                                    @foreach ($dosen as $dsn)
                                        <option value="{{ $dsn->id }}">{{ $dsn->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-check">
                                    <input type="radio" id="dosen_satu_input" name="dosen_inputnilai" value="1" class="form-check-input">
                                    <label class="form-check-label" for="dosen_satu_input">Input Nilai</label>
                                </div>
                            </div>
                        </div>

                        <!-- Dosen 2 -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dosen_dua">Dosen 2</label>
                                <select id="dosen_dua" name="dosen_dua" class="form-control">
                                    <option value="">Pilih Dosen 2 (Opsional)</option>
                                    @foreach ($dosen as $dsn)
                                        <option value="{{ $dsn->id }}">{{ $dsn->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-check">
                                    <input type="radio" id="dosen_dua_input" name="dosen_inputnilai" value="2" class="form-check-input">
                                    <label class="form-check-label" for="dosen_dua_input">Input Nilai</label>
                                </div>
                            </div>
                        </div>

                        <!-- Dosen 3 -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dosen_tiga">Dosen 3</label>
                                <select id="dosen_tiga" name="dosen_tiga" class="form-control">
                                    <option value="">Pilih Dosen 3 (Opsional)</option>
                                    @foreach ($dosen as $dsn)
                                        <option value="{{ $dsn->id }}">{{ $dsn->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-check">
                                    <input type="radio" id="dosen_tiga_input" name="dosen_inputnilai" value="3" class="form-check-input">
                                    <label class="form-check-label" for="dosen_tiga_input">Input Nilai</label>
                                </div>
                            </div>
                        </div>

                        <!-- Dosen 4 -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dosen_empat">Dosen 4</label>
                                <select id="dosen_empat" name="dosen_empat" class="form-control">
                                    <option value="">Pilih Dosen 4 (Opsional)</option>
                                    @foreach ($dosen as $dsn)
                                        <option value="{{ $dsn->id }}">{{ $dsn->name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-check">
                                    <input type="radio" id="dosen_empat_input" name="dosen_inputnilai" value="4" class="form-check-input">
                                    <label class="form-check-label" for="dosen_empat_input">Input Nilai</label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <button type="submit" class="btn btn-primary" style="float:right">Simpan</button>
                </form>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>

<script>
        document.addEventListener('DOMContentLoaded', function() {
        var selectElement = document.getElementById('matkul_id');
        selectElement.selectedIndex = 0;
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var selectElement = document.getElementById('dosen_satu');
    selectElement.selectedIndex = 0;
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    var selectElement = document.getElementById('semester_id');
    selectElement.selectedIndex = 0;
});
</script>
<!-- /.content-wrapper -->
@endsection
