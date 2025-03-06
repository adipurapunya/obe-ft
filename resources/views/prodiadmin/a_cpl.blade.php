{{-- @extends('layouts.admin.panel')

@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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

                    <form role="form" method="post" action="{{ url('prodiadmin/s_cpl')}}">
                        @csrf

                        <div class="col-md-12">
                            <div class="form-group">
                                <strong>Kurikulum</strong>
                                <select name="kurikulum_id" class="form-control">
                                    <option value=" " selected disabled>Pilih Kurikulum</option>
                                    @foreach($prod as $pr)
                                    <option value="{{ $pr->kurikulum_id }}">{{ $pr->nama_kuri }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('kurikulum_id'))
                                <span class="text-danger">{{ $errors->first('kurikulum_id') }}</span>
                                @endif
                            </div>
                        </div>

                        @foreach($prod as $pr)
                        <input type="hidden" name="prodi_id" class="form-control" value="{{ $pr->prodi_id }}" readonly>
                        @endforeach

                        <div id="cpl_fields" class="col-xs-12 col-sm-12 col-md-12">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>Kode CPL</strong>
                                        <input type="text" name="kode_cpl[]" class="form-control" value="CPL-01" readonly>
                                        @if ($errors->has('kode_cpl'))
                                        <span class="text-danger">{{ $errors->first('kode_cpl') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>Deskripsi CPL</strong>
                                        <textarea name="deskrip_cpl[]" class="form-control" style="height: 100px; resize: vertical;"></textarea>
                                        <button type="button" class="btn btn-warning remove_cpl" style="float: right; margin-top:5px">Hapus</button>
                                        @if ($errors->has('deskrip_cpl'))
                                        <span class="text-danger">{{ $errors->first('deskrip_cpl') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" id="add_cpl" class="btn btn-info">+ CPL</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("add_cpl").addEventListener("click", function() {
            addCPLField();
        });
    });

    function addCPLField() {
        var cplFields = document.getElementById("cpl_fields");
        var existingCPLs = document.querySelectorAll("#cpl_fields .cpl_field");

        var lastCPLNumber = existingCPLs.length > 0 ? parseInt(existingCPLs[existingCPLs.length - 1].querySelector("input[name='kode_cpl[]']").value.replace("CPL-", "")) + 1 : 2;

        var newField = document.createElement("div");
        newField.className = "form-group cpl_field";
        newField.innerHTML = `
            <strong>Kode CPL</strong>
            <input type="text" name="kode_cpl[]" class="form-control" value="CPL-${padNumber(lastCPLNumber, 2)}" readonly>
            <strong>Deskripsi CPL</strong>
            <textarea name="deskrip_cpl[]" class="form-control" style="height: 100px; resize: vertical;"></textarea>
            <button type="button" class="btn btn-warning remove_cpl" style="float: right">Hapus</button>
        `;

        cplFields.appendChild(newField);
        handleCPLRemoval();

        newField.querySelector(".remove_cpl").onclick = function() {
            newField.remove();
            handleCPLRemoval();
        };

        // Tambahkan event listener untuk menambahkan tombol "Hapus" pada sub-CPL yang baru saja ditambahkan
        var removeButton = newField.querySelector(".remove_cpl");
        removeButton.onclick = function() {
            newField.remove();
            handleCPLRemoval();
        };
    }

    function padNumber(number, length) {
        return (number < 10 ? '0' : '') + number;
    }

    function handleCPLRemoval() {
        var cplFields = document.querySelectorAll("#cpl_fields .cpl_field");
        var nextCPLNumber = 2;

        cplFields.forEach(function(field, index) {
            var kodeCPLInput = field.querySelector("input[name='kode_cpl[]']");
            kodeCPLInput.value = "CPL-" + padNumber(nextCPLNumber++, 2);
        });
    }
</script>


@endsection --}}

@extends('layouts.admin.panel')

@section('content')

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

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

                    <form role="form" method="post" action="{{ url('prodiadmin/s_cpl')}}">
                        @csrf

                        <div class="col-md-12">
                            <div class="form-group">
                                <strong>Kurikulum</strong>
                                <select id="kurikulum_id" name="kurikulum_id" class="form-control">
                                    <option value=" " selected disabled>Pilih Kurikulum</option>
                                    @foreach($prod as $pr)
                                    <option value="{{ $pr->kurikulum_id }}">{{ $pr->nama_kuri }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('kurikulum_id'))
                                <span class="text-danger">{{ $errors->first('kurikulum_id') }}</span>
                                @endif
                            </div>
                        </div>

                        @foreach($prod as $pr)
                        <input type="hidden" name="prodi_id" class="form-control" value="{{ $pr->prodi_id }}" readonly>
                        @endforeach

                        <div id="cpl_fields" class="col-xs-12 col-sm-12 col-md-12">
                            <div class="row cpl_field">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>Kode CPL</strong>
                                        <input type="text" id="kode_cpl" name="kode_cpl[]" class="form-control" value="CPL-01" readonly>
                                        @if ($errors->has('kode_cpl'))
                                        <span class="text-danger">{{ $errors->first('kode_cpl') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <strong>Deskripsi CPL</strong>
                                        <textarea name="deskrip_cpl[]" class="form-control" style="height: 100px; resize: vertical;"></textarea>
                                        <button type="button" class="btn btn-warning remove_cpl" style="float: right; margin-top:5px">Hapus</button>
                                        @if ($errors->has('deskrip_cpl'))
                                        <span class="text-danger">{{ $errors->first('deskrip_cpl') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="button" id="add_cpl" class="btn btn-info">+ CPL</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
    var maxKodeCplPerKurikulum = @json($maxKodeCplPerKurikulum);
    var kurikulumSelect = document.getElementById("kurikulum_id");
    var currentMaxCplNumber = 0;
    var initialField = document.getElementById("kode_cpl");

    kurikulumSelect.addEventListener("change", function() {
        var kurikulumId = this.value;
        currentMaxCplNumber = maxKodeCplPerKurikulum[kurikulumId] || 0;
        generateFirstCplCode(currentMaxCplNumber);
        updateGeneratedCplCodes();
    });

    document.getElementById("add_cpl").addEventListener("click", function() {
        addCPLField();
    });

    function generateFirstCplCode(maxKodeCpl) {
        currentMaxCplNumber = maxKodeCpl;
        initialField.value = "CPL-" + padNumber(currentMaxCplNumber + 1, 2);
    }

    function addCPLField() {
        var cplFields = document.getElementById("cpl_fields");
        var newCplNumber = currentMaxCplNumber + 2; // Start from the next number after initial field

        var newField = document.createElement("div");
        newField.className = "form-group cpl_field";
        newField.innerHTML = `
            <div class="row cpl_field">
                <div class="col-md-12">
                    <div class="form-group">
                        <strong>Kode CPL</strong>
                        <input type="text" name="kode_cpl[]" class="form-control" value="CPL-${padNumber(newCplNumber, 2)}" readonly>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <strong>Deskripsi CPL</strong>
                        <textarea name="deskrip_cpl[]" class="form-control" style="height: 100px; resize: vertical;"></textarea>
                        <button type="button" class="btn btn-warning remove_cpl" style="float: right; margin-top:5px">Hapus</button>
                    </div>
                </div>
            </div>
        `;

        cplFields.appendChild(newField);
        handleCPLRemoval(newField);
        currentMaxCplNumber++;
    }

    function padNumber(number, length) {
        return number.toString().padStart(length, '0');
    }

    function updateGeneratedCplCodes() {
        var cplInputs = document.querySelectorAll("#cpl_fields .cpl_field input[name='kode_cpl[]']");
        cplInputs.forEach(function(input, index) {
            input.value = "CPL-" + padNumber(currentMaxCplNumber + index + 1, 2);
        });
    }

    function handleCPLRemoval(newField) {
        newField.querySelector(".remove_cpl").onclick = function() {
            newField.remove();
            updateCplNumbers();
        };
    }

    function updateCplNumbers() {
        var cplInputs = document.querySelectorAll("#cpl_fields .cpl_field input[name='kode_cpl[]']");
        var existingCPLs = Array.from(cplInputs).map(input => input.value);

        var minNumber = Infinity;
        existingCPLs.forEach(function(value) {
            var number = parseInt(value.split("-")[1]);
            if (number < minNumber) {
                minNumber = number;
            }
        });

        var newCplNumber = minNumber;
        cplInputs.forEach(function(input, index) {
            input.value = "CPL-" + padNumber(newCplNumber++, 2);
        });
    }
});

</script>


@endsection
