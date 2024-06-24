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
                <form role="form" method="post" action="{{ url('dosen/s_mkscpmk/'.$matkul_id) }}">
                    @csrf

                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div id="form-container">
                            <div class="row form-group-template">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="kode_cpmk">Kode CPMK</label>
                                        <select class="form-control kode_cpmk" name="kode_cpmk[]">
                                            <option value="">Pilih Kode CPMK</option>
                                            @foreach($mscpmk as $mkcp)
                                                <option value="{{ $mkcp->kode_cpmk }}">{{ $mkcp->kode_cpmk }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label for="desk_cpmk">Deskripsi CPMK</label>
                                        <textarea name="desk_cpmk[]" class="form-control desk_cpmk" style="height: 70px; resize: vertical;" readonly></textarea>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="kode_subcpmk">Kode Sub CPMK</label>
                                        <input type="text" class="form-control kode_subcpmk" name="kode_subcpmk[]" readonly>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>Deskripsi Sub CPMK</strong>
                                        <textarea name="desk_scpmk[]" class="form-control" style="height: 100px; resize: vertical;"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12 text-right">
                                    <div class="form-group">
                                        <button type="button" class="btn btn-danger remove-subcpmk mb-3">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <div class="form-group">
                                    <button type="button" class="btn btn-info mr-1" id="add-subcpmk">+ Sub CPMK</button>
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
        margin-top: 5px;
        margin-right: 5px;
    }
</style>

<script>
    $(document).ready(function() {
        var matkul_id = {{ $matkul_id }};
        var existingSubCPMKs = @json($existingSubCPMKs);

        function getSubCPMKCount(kode_cpmk) {
            return existingSubCPMKs.filter(function(item) {
                return item.kode_cpmk === kode_cpmk;
            }).length;
        }


        $('#add-subcpmk').click(function() {
            var newForm = $('.form-group-template:first').clone();
            $('#form-container').append(newForm);
            resetForm(newForm);
            reindexSubCPMK();
        });


        function resetForm(form) {
            form.find('select').val('');
            form.find('textarea').val('');
            form.find('input').val('');
            form.find('.remove-subcpmk').show();
        }


        $('#form-container').on('click', '.remove-subcpmk', function() {
            $(this).closest('.form-group-template').remove();
            reindexSubCPMK();
        });


        function reindexSubCPMK() {
            var subCPMKCounter = {};

            $('#form-container .form-group-template').each(function(index) {
                var container = $(this);
                var kode_cpmk = container.find('.kode_cpmk').val();

                if (kode_cpmk) {
                    if (!subCPMKCounter[kode_cpmk]) {
                        subCPMKCounter[kode_cpmk] = getSubCPMKCount(kode_cpmk) + 1;
                    } else {
                        subCPMKCounter[kode_cpmk]++;
                    }
                    var kode_subcpmk = kode_cpmk + '.' + subCPMKCounter[kode_cpmk];
                    container.find('.kode_subcpmk').val(kode_subcpmk);
                } else {
                    container.find('.kode_subcpmk').val('');
                }
            });
        }


        $('#form-container').on('change', '.kode_cpmk', function() {
            var kode_cpmk = $(this).val();
            var container = $(this).closest('.form-group-template');

            if (kode_cpmk) {
                $.ajax({
                    url: '/get-desk-cpmk/' + matkul_id + '/' + kode_cpmk,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        container.find('.desk_cpmk').val(data.desk_cpmk);
                        reindexSubCPMK();
                    }
                });
            } else {
                container.find('.desk_cpmk').val('');
                container.find('.kode_subcpmk').val('');
            }
        });


        $('.form-group-template:first .remove-subcpmk').hide();
    });
</script>

@endsection

