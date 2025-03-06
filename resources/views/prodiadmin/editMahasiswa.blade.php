@extends('layouts.admin.panel')

@section('content')

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0">Edit Mahasiswa</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('updateMahasiswa') }}">
                        @csrf
                        <input type="hidden" name="encryptedId" value="{{ $encryptedId }}">

                        <div class="form-group">
                            <label>NIM</label>
                            <input type="text" name="nim" class="form-control" value="{{ $mahasiswa->nim }}" required>
                        </div>

                        <div class="form-group">
                            <label>Nama Mahasiswa</label>
                            <input type="text" name="nama_mahasiswa" class="form-control" value="{{ $mahasiswa->nama_mahasiswa }}" required>
                        </div>

                        <div class="form-group">
                            <label>Angkatan</label>
                            <input type="number" name="angkatan" class="form-control" value="{{ $mahasiswa->angkatan }}" required>
                        </div>

                        <div class="form-group">
                            <label>Semester Angkatan</label>
                            <select name="smt_angkatan" class="form-control" required>
                                <option value="Ganjil" {{ $mahasiswa->smt_angkatan == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ $mahasiswa->smt_angkatan == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control" required>
                                <option value="L" {{ $mahasiswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ $mahasiswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ url('prodiadmin/a_mahasiswa') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
