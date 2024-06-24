@extends('layouts.admin.panel')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Form Input Sub CPL</h5>
            </div>

            <div class="card-body">
                <form role="form" method="post" action="{{ url('prodiadmin/s_subcpl')}}">
                    @csrf
                    <div id="subcpl_fields" class="col-xs-12 col-sm-12 col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>CPL</strong>
                                    <select name="cpl_id" class="form-control" onchange="updateCPLInfo(this.value)">
                                        <option value="" selected disabled>Pilih CPL</option>
                                        @foreach($cpel as $cp)
                                            <option value="{{ $cp->cpl_id }}">{{ $cp->kode_cpl }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('cpl_id'))
                                        <span class="text-danger">{{ $errors->first('cpl_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Deskripsi CPL</strong>
                                    <textarea name="cpl_description" class="form-control" id="cpl_description" rows="4" cols="50" readonly></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Kode Sub CPL</strong>
                                    <input type="text" id="sub_cpl_code" name="kode_subcpl[]" class="form-control" readonly>
                                    @if ($errors->has('kode_subcpl'))
                                        <span class="text-danger">{{ $errors->first('kode_subcpl') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Deskripsi Sub CPL</strong>
                                    <textarea name="desk_subcpl[]" class="form-control" style="height: 100px; resize: vertical;"></textarea>
                                    <button type="button" class="btn btn-warning remove_subcpl" style="float: right">Hapus</button>
                                    @if ($errors->has('desk_subcpl'))
                                        <span class="text-danger">{{ $errors->first('desk_subcpl') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-success add_subcpl_field" style="margin-right: 3px;">Tambah Sub CPL</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    var existingSubCPLs = {!! json_encode($existingSubCPLs) !!};

    function generateNextSubCPLNumber(baseCode) {
        var maxExistingNumber = existingSubCPLs[baseCode] || 0;

        var subCPLFields = document.querySelectorAll('input[name^="kode_subcpl"]');
        subCPLFields.forEach(function(field) {
            var fieldValue = field.value.split('.');
            if (fieldValue[0] + '.' === baseCode) {
                var fieldNumber = parseInt(fieldValue[1]);
                if (!isNaN(fieldNumber) && fieldNumber > maxExistingNumber) {
                    maxExistingNumber = fieldNumber;
                }
            }
        });
        return maxExistingNumber + 1;
    }

    function updateCPLInfo(selectedCPLId) {
        var descriptions = {!! json_encode($cpel->pluck('deskrip_cpl', 'cpl_id')) !!};
        var selectedCPLDescription = descriptions[selectedCPLId];
        document.getElementById("cpl_description").value = selectedCPLDescription;

        var cplData = {!! json_encode($cpel->pluck('kode_cpl', 'cpl_id')) !!};
        var cplCode = cplData[selectedCPLId];
        if (cplCode !== undefined) {
            var subCPLBase = "SCPL-" + cplCode.split('-')[1];
            var nextSubCPLNumber = generateNextSubCPLNumber(subCPLBase);

            document.getElementById("sub_cpl_code").value = subCPLBase + "." + nextSubCPLNumber;
        }
    }

        function updateSubCPLNumbers() {
        var deletedCodeElement = document.getElementById("deleted_subcpl_code");
        if (!deletedCodeElement) return;

        var deletedCode = deletedCodeElement.value;
        var subcplFields = document.getElementById("subcpl_fields");
        if (!subcplFields) return;

        var rows = subcplFields.querySelectorAll('.row');
        var isDeleted = false;

        rows.forEach(function(row) {
            var codeInput = row.querySelector('input[name^="kode_subcpl"]');
            if (!codeInput) return;

            var fieldValue = codeInput.value.split('.');
            var baseCode = fieldValue[0];
            var fieldNumber = parseInt(fieldValue[1]);

            if (!isDeleted && deletedCode !== "" && codeInput.value === deletedCode) {
                codeInput.parentNode.parentNode.remove();
                isDeleted = true;
            }

            if (isDeleted && baseCode === deletedCode.split('.')[0]) {
                var newNumber = fieldNumber - 1;
                codeInput.value = baseCode + '.' + newNumber;
            }
        });
    }

        function addSubCPLField() {
            var selectedCPL = document.querySelector('select[name="cpl_id"]').value;
            var cplData = {!! json_encode($cpel->pluck('kode_cpl', 'cpl_id')) !!};
            var cplCode = cplData[selectedCPL];
            if (cplCode !== undefined) {
                var subCPLBase = "SCPL-" + cplCode.split('-')[1];

                var maxExistingNumber = 0;
                var subCPLFields = document.querySelectorAll('input[name^="kode_subcpl"]');
                subCPLFields.forEach(function(field) {
                    var fieldValue = field.value.split('.');
                    if (fieldValue[0] === subCPLBase) {
                        var fieldNumber = parseInt(fieldValue[1]);
                        if (!isNaN(fieldNumber) && fieldNumber > maxExistingNumber) {
                            maxExistingNumber = fieldNumber;
                        }
                    }
                });

                var nextSubCPLNumber = maxExistingNumber + 1;

                var subcplFields = document.getElementById("subcpl_fields");

                var newRow = document.createElement("div");
                newRow.classList.add("row");

                var newCol1 = document.createElement("div");
                newCol1.classList.add("col-md-6");
                var subcplCodeFormGroup = document.createElement("div");
                subcplCodeFormGroup.classList.add("form-group");
                var subcplCodeLabel = document.createElement("strong");
                subcplCodeLabel.textContent = "Kode Sub CPL";
                var subcplCodeInput = document.createElement("input");
                subcplCodeInput.setAttribute("type", "text");
                subcplCodeInput.setAttribute("name", "kode_subcpl[]");
                subcplCodeInput.classList.add("form-control");
                subcplCodeInput.setAttribute("value", subCPLBase + "." + nextSubCPLNumber);
                subcplCodeInput.setAttribute("readonly", true);
                subcplCodeFormGroup.appendChild(subcplCodeLabel);
                subcplCodeFormGroup.appendChild(subcplCodeInput);
                newCol1.appendChild(subcplCodeFormGroup);

                var newCol2 = document.createElement("div");
                newCol2.classList.add("col-md-6");
                var subcplDescFormGroup = document.createElement("div");
                subcplDescFormGroup.classList.add("form-group");
                var subcplDescLabel = document.createElement("strong");
                subcplDescLabel.textContent = "Deskripsi Sub CPL";
                var subcplDescTextarea = document.createElement("textarea");
                subcplDescTextarea.setAttribute("name", "desk_subcpl[]");
                subcplDescTextarea.classList.add("form-control");
                subcplDescTextarea.style.height = "100px";
                subcplDescTextarea.style.resize = "vertical";
                var removeButton = document.createElement("button");
                removeButton.setAttribute("type", "button");
                removeButton.classList.add("btn", "btn-warning", "remove_subcpl");
                removeButton.style.float = "right";
                removeButton.textContent = "Hapus";
                removeButton.onclick = function () {
                    this.closest(".row").remove();
                    updateSubCPLNumbers();
                };
                subcplDescFormGroup.appendChild(subcplDescLabel);
                subcplDescFormGroup.appendChild(subcplDescTextarea);
                subcplDescFormGroup.appendChild(removeButton);
                newCol2.appendChild(subcplDescFormGroup);

                newRow.appendChild(newCol1);
                newRow.appendChild(newCol2);

                subcplFields.appendChild(newRow);
            }
        }


        document.querySelector('.add_subcpl_field').addEventListener('click', function() {
            addSubCPLField();
        });

        var cplDropdown = document.querySelector('select[name="cpl_id"]');
        cplDropdown.addEventListener('change', function() {
            var selectedCPL = this.value;
            updateCPLInfo(selectedCPL);
        });
    });
</script>
@endsection

