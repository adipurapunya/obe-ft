@extends('layouts.admin.panel')

@section('content')

<div class="content-wrapper">
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Edit Kelas</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ url('prodiadmin/u_kelas/'.Crypt::encryptString($kelas->id)) }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="semester_id">Semester</label>
                                <select id="semester_id" name="semester_id" class="form-control" required>
                                    <option value="" disabled>Pilih Semester</option>
                                    @foreach ($semesters as $semester)
                                        <option value="{{ $semester->id }}"
                                            {{ $kelas->semester_id == $semester->id ? 'selected' : '' }}>
                                            {{ $semester->keterangan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="matkul_id">Mata Kuliah</label>
                                <select id="matkul_id" name="matkul_id" class="form-control" required>
                                    <option value="" disabled>Pilih Mata Kuliah</option>
                                    @foreach ($matkul as $mk)
                                        <option value="{{ $mk->id }}"
                                            {{ $kelas->matkul_id == $mk->id ? 'selected' : '' }}>
                                            {{ $mk->nama_mk }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="urut">No. Urut Kelas [1,2,3,...]</label>
                                <input type="number" id="urut" name="urut" class="form-control"
                                       value="{{ $kelas->urut }}" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nama_kelas">Nama Kelas</label>
                                <select name="nama_kelas" class="form-control" required>
                                    <option value="" disabled>Pilih Nama Kelas</option>
                                    <option value="A" @if($kelas->nama_kelas == 'A') selected @endif>A</option>
                                    <option value="B" @if($kelas->nama_kelas == 'B') selected @endif>B</option>
                                    <option value="C" @if($kelas->nama_kelas == 'C') selected @endif>C</option>
                                    <option value="D" @if($kelas->nama_kelas == 'D') selected @endif>D</option>
                                    <option value="E" @if($kelas->nama_kelas == 'E') selected @endif>E</option>
                                </select>
                                @if ($errors->has('nama_kelas'))
                                    <span class="text-danger">{{ $errors->first('nama_kelas') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dosen_satu">Dosen 1</label>
                                <select id="dosen_satu" name="dosen_satu" class="form-control" required>
                                    <option value="" disabled>Pilih Dosen (Wajib)</option>
                                    @foreach ($dosen as $dsn)
                                        <option value="{{ $dsn->id }}" {{ $kelas->dosen_satu == $dsn->id ? 'selected' : '' }}>
                                            {{ $dsn->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-check">
                                    <input type="radio" id="dosen_satu_input" name="dosen_inputnilai" value="1"
                                        {{ $kelas->dosen_inputnilai == $kelas->dosen_satu ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dosen_satu_input">Input Nilai</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dosen_dua">Dosen 2</label>
                                <select id="dosen_dua" name="dosen_dua" class="form-control">
                                    <option value="">Pilih Dosen 2 (Opsional)</option>
                                    @foreach ($dosen as $dsn)
                                        <option value="{{ $dsn->id }}"
                                            {{ $kelas->dosen_dua == $dsn->id ? 'selected' : '' }}>
                                            {{ $dsn->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-check">
                                    <input type="radio" id="dosen_dua_input" name="dosen_inputnilai" value="2"
                                        {{ $kelas->dosen_inputnilai == $kelas->dosen_dua ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dosen_dua_input">Input Nilai</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dosen_tiga">Dosen 3</label>
                                <select id="dosen_tiga" name="dosen_tiga" class="form-control">
                                    <option value="">Pilih Dosen 3 (Opsional)</option>
                                    @foreach ($dosen as $dsn)
                                        <option value="{{ $dsn->id }}"
                                            {{ $kelas->dosen_tiga == $dsn->id ? 'selected' : '' }}>
                                            {{ $dsn->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-check">
                                    <input type="radio" id="dosen_tiga_input" name="dosen_inputnilai" value="3"
                                        {{ $kelas->dosen_inputnilai == $kelas->dosen_tiga ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dosen_tiga_input">Input Nilai</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="dosen_empat">Dosen 4</label>
                                <select id="dosen_empat" name="dosen_empat" class="form-control">
                                    <option value="">Pilih Dosen 4 (Opsional)</option>
                                    @foreach ($dosen as $dsn)
                                        <option value="{{ $dsn->id }}"
                                            {{ $kelas->dosen_empat == $dsn->id ? 'selected' : '' }}>
                                            {{ $dsn->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-check">
                                    <input type="radio" id="dosen_empat_input" name="dosen_inputnilai" value="4"
                                        {{ $kelas->dosen_inputnilai == $kelas->dosen_empat ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dosen_empat_input">Input Nilai</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="float:right">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
