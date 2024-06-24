@extends('layouts.admin.panel')

@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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
                <h3 class="box-title">Tambah Data CPL</h3>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-body">

                    <!-- Form utama -->
                    <form role="form" id="main-form" method="post" action="{{ url('prodiadmin/s_mksubcpl') }}">
                        @csrf
                        <input type="hidden" name="matkul_id" value="{{ $matkul_id }}">
                        <div id="subcpl-form-container">
                            <!-- Form pertama -->
                            <div class="subcpl-form">
                                <div class="form-group">
                                    <label for="cpl">Kode CPL:</label>
                                    <select name="cpl[]" class="form-control cpl-select">
                                        <option value="">Pilih Kode CPL</option>
                                        <!-- Populate options dynamically from database -->
                                        @foreach($scp->unique('cpl_id') as $cpl)
                                            <option value="{{ $cpl->id }}">{{ $cpl->kode_cpl }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input tersembunyi untuk id CPL -->
                                <input type="hidden" name="cpl_id[]" value="">

                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 12%">Kode Sub CPL</th>
                                            <th>Deskripsi Sub CPL</th>
                                            <th style="width: 7%">Pilih</th>
                                        </tr>
                                    </thead>
                                    <tbody class="subcpl-details">
                                        <!-- Kode Sub CPL akan otomatis muncul di sini -->
                                    </tbody>
                                </table>
                            </div>
                            <!-- Form pertama selesai -->
                        </div>

                        <hr>

                        <!-- Tombol Simpan di dalam form utama -->
                        <button type="button" id="add-subcpl" class="btn btn-primary">+ Sub CPL</button>
                        <button type="submit" class="btn btn-success">Simpan</button>
                    </form>
                    <!-- Form utama selesai -->

                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        // Tangani klik tombol "+ Sub CPL"
        $('#add-subcpl').click(function() {
            var subcplForm = $('.subcpl-form').first().clone(); // Salin form pertama
            subcplForm.find('select[name="cpl[]"]').val(''); // Kosongkan pilihan kode CPL
            subcplForm.find('.subcpl-details').html(''); // Hapus kode sub CPL yang sebelumnya ditampilkan

            // Tambahkan input tersembunyi untuk id CPL
            subcplForm.find('input[name="cpl_id[]"]').val('');

            $('#subcpl-form-container').append(subcplForm); // Tambahkan form baru ke dalam container form
        });

        // Tangani perubahan pilihan kode CPL pada form
        $(document).on('change', '.cpl-select', function() {
            var cplId = $(this).val();
            var subcplDetails = $(this).closest('.subcpl-form').find('.subcpl-details');
            var cplInput = $(this).closest('.subcpl-form').find('input[name="cpl_id[]"]');
            if (cplId) {
                cplInput.val(cplId); // Set nilai id CPL di input tersembunyi
                $.ajax({
                    url: '{{ route('get.subcpls.by.cpl') }}',
                    type: 'GET',
                    data: {cpl_id: cplId},
                    success: function(response) {
                        var rows = '';
                        $.each(response.subcpls, function(key, value) {
                            rows += '<tr>' +
                                '<td>' + value.kode_subcpl + '</td>' +
                                '<td>' + value.desk_subcpl + '</td>' +
                                // Setiap input checkbox memiliki atribut value dengan nilai cpl_id
                                '<td><input type="checkbox" name="subcpl_checkbox[' + cplId + '][]" value="' + value.id + '" data-cpl-id="' + cplId + '"></td>' +
                                '</tr>';
                        });
                        subcplDetails.html(rows);
                    }
                });
            } else {
                subcplDetails.html(''); // Kosongkan kode subcpl details
            }
        });

        // Tangani saat kotak centang diubah
        $(document).on('change', 'input[type="checkbox"]', function() {
            var cplId = $(this).data('cpl-id'); // Dapatkan nilai cpl_id dari atribut data-cpl-id
            var checked = $(this).prop('checked'); // Periksa apakah kotak centang sedang dicentang atau tidak
            if (checked) {
                // Lakukan sesuatu jika kotak centang dicentang
            } else {
                // Lakukan sesuatu jika kotak centang tidak dicentang
            }
        });
    });
</script>

@endsection
