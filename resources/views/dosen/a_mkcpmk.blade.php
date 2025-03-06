@extends('layouts.admin.panel')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Form Input Matriks Mata Kuliah dan CPMK</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Form Input Matriks Mata Kuliah dan CPMK</h5>
            </div>

            <div class="card-body">
                <form role="form" method="post" action="{{ url('dosen/s_mkcpmk/'.$matkulId) }}">
                    @csrf

                    <input type="hidden" name="matkul_id" value="{{ $matkulId }}" readonly>
                    <input type="hidden" name="kelas_id[]" value="{{ $kelasId }}" readonly>

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div id="form-container">
                            <div class="row form-group-template">
                                <input type="hidden" class="kode_mk" value="{{ $mcpmk->first()->kode_mk }}" readonly>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="kode_subcpl">Kode Subcpl</label>
                                        <select name="subcpl_id[]" class="form-control kode_subcpl">
                                            <option value="">-- Pilih Kode SubCPL --</option>
                                            @foreach($mcpmk->unique('subcpl_id') as $subcpl)
                                                <option value="{{ $subcpl->subcpl_id }}">{{ $subcpl->kode_subcpl }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="desk_subcpl">Deskripsi Subcpl</label>
                                        <textarea name="desk_subcpl[]" class="form-control desk_subcpl" style="height: 70px; resize: vertical;" readonly></textarea>
                                    </div>
                                </div>


                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="kode_cpmk">Kode CPMK</label>
                                        <input type="text" name="kode_cpmk[]" class="form-control kode_cpmk" readonly>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>Deskripsi CPMK</strong>
                                        <textarea name="desk_cpmk[]" class="form-control" style="height: 100px; resize: vertical;"></textarea>
                                        @if ($errors->has('desk_cpmk'))
                                            <span class="text-danger">{{ $errors->first('desk_cpmk') }}</span>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger remove-cpmk mb-3" style="float: right">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button type="button" class="btn btn-info mr-1" id="add-cpmk">+ CPMK</button>
                                    <button type="submit" class="btn btn-success" style="float: right">Simpan</button>
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
    .remove_cpmk {
        float: right;
        margin-top: 5px;
        margin-right: 5px;
    }
</style>

<script>
    $(document).ready(function() {
        function updateCpmkCodes() {
            var subcplUsage = {};
            $('#form-container .form-group-template').each(function(index, element) {
                var $row = $(element);
                var kode_subcpl = $row.find('.kode_subcpl option:selected').text();
                var kode_mk = $row.find('.kode_mk').val();

                if (kode_subcpl && kode_mk) {
                    if (!subcplUsage[kode_subcpl]) {
                        subcplUsage[kode_subcpl] = 0;
                    }
                    subcplUsage[kode_subcpl]++;
                    var kode_subcpl_angka = kode_subcpl.split('-')[1];
                    var newKodeCpmk = kode_subcpl_angka + '.' + kode_mk + '-' + subcplUsage[kode_subcpl];
                    $row.find('.kode_cpmk').val(newKodeCpmk);
                }
            });
        }

        function initFormEvents() {
            $('.kode_subcpl').off('change').on('change', function() {
                var $parentRow = $(this).closest('.row');
                var kode_subcpl = $(this).val();

                if (kode_subcpl) {
                    $.ajax({
                        url: '/subcpls/details/' + kode_subcpl,
                        type: 'GET',
                        success: function(data) {
                            if (data && data.desk_subcpl) {
                                $parentRow.find('.desk_subcpl').val(data.desk_subcpl);
                                updateCpmkCodes();
                            } else {
                                $parentRow.find('.desk_subcpl').val('');
                                $parentRow.find('.kode_cpmk').val('');
                            }
                        },
                        error: function(xhr, status, error) {
                            $parentRow.find('.desk_subcpl').val('');
                            $parentRow.find('.kode_cpmk').val('');
                        }
                    });
                } else {
                    $parentRow.find('.desk_subcpl').val('');
                    $parentRow.find('.kode_cpmk').val('');
                }
            });

            $('.remove-cpmk').off('click').on('click', function() {
                $(this).closest('.row').remove();
                updateCpmkCodes();
            });
        }

        $('#add-cpmk').on('click', function() {
            var $newForm = $('.form-group-template:first').clone();
            $newForm.find('input, textarea').val('');
            $newForm.find('.kode_mk').val($('.form-group-template:first').find('.kode_mk').val());
            $newForm.find('.kode_cpmk').val('');
            $newForm.find('input[name="kelas_id[]"]').val('{{ $kelasId }}');
            $('#form-container').append($newForm);
            initFormEvents();
            updateCpmkCodes();
        });

        initFormEvents();
        updateCpmkCodes();
    });
</script>

@endsection
