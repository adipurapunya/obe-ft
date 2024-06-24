@extends('layouts.admin.panel')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0"></div>
        </div>
    </div>

    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Tambah Data Pejabat Pengesah</h3>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <form role="form" method="post" action="{{ url('prodiadmin/s_pengesah')}}">
                        @csrf
                        <div class="box-body">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Pilih Dosen</strong>
                                    <select name="dosen_id" id="dosen_id" class="form-control">
                                        <option value="">Pilih Dosen</option>
                                        @foreach($dosens as $dosen)
                                            <option value="{{ $dosen->dosen_id }}">{{ $dosen->nama_dosen }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>NIDN</strong>
                                    <input type="text" name="nidn" id="nidn" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>NIP</strong>
                                    <input type="text" name="nip" id="nip" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Jabatan</strong>
                                        <select name="jabatan" class="form-control">
                                            <option value=" " selected disabled>Pilih Jabatan</option>
                                            <option value="Ketua Jurusan">Ketua Jurusan</option>
                                            <option value="Koordinator Kelompok Keahlian">Koordinator Kelompok Keahlian</option>
                                        </select>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info" style="float:right; color:white">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dosen_id').change(function() {
            var dosen_id = $(this).val();
            if (dosen_id) {
                $.ajax({
                    url: '/getDosenData/' + dosen_id,
                    type: 'GET',
                    success: function(response) {

                        $('#nidn').val(response.nidn);
                        $('#nip').val(response.nip);
                    }
                });
            } else {
                $('#nidn').val('');
                $('#nip').val('');
            }
        });
    });
</script>

@endsection
