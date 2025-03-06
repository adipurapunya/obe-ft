@extends('layouts.admin.panel')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1 class="m-0">Form Input Taget/Penilaian CPMK untuk {{$dataMK->kode_mk}} - {{$dataMK->nama_mk}}</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
            </div>

            <div class="card-body">
                <form role="form" method="post" action="{{ url('dosen/s_tarcpmk/'.Crypt::encryptString($matkul_id)) }}">
                    @csrf
                    @foreach($kelas as $kls)
                        <input type="hidden" name="kelas_id[]" value="{{ $kls->kelas_id }}">
                    @endforeach
                        <input type="hidden" name="semester_id" value="{{ $dataMK->semester_id }}">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div id="form-container">
                            <div class="row form-group-template">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="kode_cpmk">Kode CPMK</label>
                                        <select class="form-control kode_cpmk" name="kode_cpmk[]">
                                            <option value="">Pilih Kode CPMK</option>
                                            @foreach($mkcp as $mkcp)
                                                <option value="{{ $mkcp->kode_cpmk }}">{{ $mkcp->kode_cpmk }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="desk_cpmk">Deskripsi CPMK</label>
                                        <textarea name="desk_cpmk[]" class="form-control desk_cpmk" style="height: 70px; resize: vertical;" readonly></textarea>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="jenilai_id">Jenis Penilaian</label>
                                        <select name="kompnilai_id[]" class="form-control">
                                            <option value=" " selected disabled>Pilih Jenis Penilaian</option>
                                            @foreach($jenilai as $jn)
                                              <option value="{{ $jn->id }}">{{ $jn->jen_penilaian }} - {{$jn->label}}</option>
                                            @endforeach
                                            </select>
                                            @if ($errors->has('kompnilai_id'))
                                              <span class="text-danger">{{ $errors->first('kompnilai_id') }}</span>
                                            @endif
                                    </div>
                                </div>

                                <div class="col-md-12 text-right remove-button-container">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger remove-subcpmk mb-1">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button type="button" class="btn btn-info mr-1" id="add-subcpmk">+ Jenis Penilaian</button>
                                    <button type="submit" class="btn btn-success">Simpan</button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<style>
    .remove_subcpmk {
        float: right;
        margin-top: 1px;
        margin-right: 5px;
    }
</style>

<script>
    $(document).ready(function() {
        function updateCpmkDescription($element) {
            var kodeCpmk = $element.val();
            var $row = $element.closest('.row');

            if (kodeCpmk) {
                $.ajax({
                    url: '/cpmk/description/' + kodeCpmk,
                    type: 'GET',
                    success: function(data) {
                        if (data && data.desk_cpmk) {
                            $row.find('.desk_cpmk').val(data.desk_cpmk);
                        } else {
                            $row.find('.desk_cpmk').val('');
                        }
                    },
                    error: function(xhr, status, error) {
                        $row.find('.desk_cpmk').val('');
                    }
                });
            } else {
                $row.find('.desk_cpmk').val('');
            }
        }

        function initFormEvents() {
            $('.kode_cpmk').off('change').on('change', function() {
                updateCpmkDescription($(this));
            });

            $('.remove-subcpmk').off('click').on('click', function() {
                $(this).closest('.row').remove();
            });
        }

        $('#add-subcpmk').on('click', function() {
            var $newForm = $('.form-group-template:first').clone();
            $newForm.find('input, textarea, select').val('');
            $newForm.find('.remove-button-container').remove();
            $newForm.append('<div class="col-md-12 text-right remove-button-container"><div class="form-group"><button type="button" class="btn btn-danger remove-subcpmk mb-3">Hapus</button></div></div>');
            $('#form-container').append($newForm);
            initFormEvents();
        });

        $('.form-group-template:first .remove-subcpmk').closest('.remove-button-container').remove();
        initFormEvents();
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Maaf Data Mahasiswa KOSONG',
                text: '{{ session("warning") }}'
            });
        @endif
    });
</script>




@endsection

