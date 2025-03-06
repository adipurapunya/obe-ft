
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <strong>Kurikulum</strong>
                                    <select name="kurikulum_id" class="form-control" id="kurikulum_id">
                                        <option value="" selected disabled>Pilih Kurikulum</option>
                                        @foreach($kurikulums as $kurikulum)
                                            <option value="{{ $kurikulum->id }}">{{ $kurikulum->nama_kuri }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('kurikulum_id'))
                                        <span class="text-danger">{{ $errors->first('kurikulum_id') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>CPL</strong>
                                    <select name="cpl_id" class="form-control" id="cpl_id" onchange="updateCPLInfo(this.value)">
                                        <option value="" selected disabled>Pilih CPL</option>
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
                                    @if ($errors->has('desk_subcpl'))
                                        <span class="text-danger">{{ $errors->first('desk_subcpl') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Bobot</strong>
                                    <input type="text" name="bobot[]" class="form-control">
                                    @if ($errors->has('bobot'))
                                        <span class="text-danger">{{ $errors->first('bobot') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <strong>Target Nilai</strong>
                                    <input type="text" name="trgt_nilai[]" class="form-control">
                                    <button type="button" class="btn btn-warning remove_subcpl" style="float: right; margin-top:10px">Hapus</button>
                                    @if ($errors->has('trgt_nilai'))
                                        <span class="text-danger">{{ $errors->first('trgt_nilai') }}</span>
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
        var cpel = {!! json_encode($cpel) !!};
        var existingSubCPLs = {!! json_encode($existingSubCPLs) !!};

        function updateCPLDropdown(kurikulumId) {
            var cplDropdown = document.getElementById('cpl_id');
            cplDropdown.innerHTML = '<option value="" selected disabled>Pilih CPL</option>';

            cpel.forEach(function(cpl) {
                if (cpl.kurikulum_id == kurikulumId) {
                    var option = document.createElement('option');
                    option.value = cpl.cpl_id;
                    option.textContent = cpl.kode_cpl;
                    cplDropdown.appendChild(option);
                }
            });
        }

        function generateNextSubCPLNumber(baseCode, kurikulumId) {
            var maxExistingNumber = 0;

            if (existingSubCPLs[baseCode]) {
                maxExistingNumber = existingSubCPLs[baseCode];
            }

            return maxExistingNumber + 1;
        }

        function updateCPLInfo(selectedCPLId) {
            var descriptions = {!! json_encode($cpel->pluck('deskrip_cpl', 'cpl_id')) !!};
            var selectedCPLDescription = descriptions[selectedCPLId];
            document.getElementById("cpl_description").value = selectedCPLDescription;

            var cplData = {!! json_encode($cpel->pluck('kode_cpl', 'cpl_id')) !!};
            var cplCode = cplData[selectedCPLId];

            if (cplCode !== undefined) {
                var kurikulumId = document.getElementById('kurikulum_id').value;
                var subCPLBase = "SCPL-" + cplCode.split('-')[1];

                var maxIndex = {!! json_encode($existingSubCPLs) !!}[cplCode] || 0;

                var nextSubCPLNumber = maxIndex + 1;

                document.getElementById("sub_cpl_code").value = subCPLBase + "." + nextSubCPLNumber;
            }
        }

        function addSubCPLField() {
            var selectedCPL = document.querySelector('select[name="cpl_id"]').value;
            var cplData = {!! json_encode($cpel->pluck('kode_cpl', 'cpl_id')) !!};
            var cplCode = cplData[selectedCPL];

            if (cplCode !== undefined) {
                var kurikulumId = document.getElementById('kurikulum_id').value;
                var subCPLBase = "SCPL-" + cplCode.split('-')[1];

                var existingSubCPLInputs = document.querySelectorAll('input[name^="kode_subcpl"]');

                var maxNumber = 0;
                existingSubCPLInputs.forEach(function(input) {
                    var fieldValue = input.value.split('.');
                    var fieldBaseCode = fieldValue[0];
                    var fieldNumber = parseInt(fieldValue[1]);

                    if (fieldBaseCode === subCPLBase && fieldNumber > maxNumber) {
                        maxNumber = fieldNumber;
                    }
                });

                var nextSubCPLNumber = maxNumber + 1;

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
                var subcplDescriptionFormGroup = document.createElement("div");
                subcplDescriptionFormGroup.classList.add("form-group");
                var subcplDescriptionLabel = document.createElement("strong");
                subcplDescriptionLabel.textContent = "Deskripsi Sub CPL";
                var subcplDescriptionTextarea = document.createElement("textarea");
                subcplDescriptionTextarea.setAttribute("name", "desk_subcpl[]");
                subcplDescriptionTextarea.classList.add("form-control");
                subcplDescriptionTextarea.style.height = "100px";
                subcplDescriptionTextarea.style.resize = "vertical";
                subcplDescriptionFormGroup.appendChild(subcplDescriptionLabel);
                subcplDescriptionFormGroup.appendChild(subcplDescriptionTextarea);
                newCol2.appendChild(subcplDescriptionFormGroup);

                var newCol3 = document.createElement("div");
                newCol3.classList.add("col-md-6");
                var bobotFormGroup = document.createElement("div");
                bobotFormGroup.classList.add("form-group");
                var bobotLabel = document.createElement("strong");
                bobotLabel.textContent = "Bobot Nilai";
                var bobotInput = document.createElement("input");
                bobotInput.setAttribute("type", "text");
                bobotInput.setAttribute("name", "bobot[]");
                bobotInput.classList.add("form-control");
                bobotFormGroup.appendChild(bobotLabel);
                bobotFormGroup.appendChild(bobotInput);
                newCol3.appendChild(bobotFormGroup);

                var newCol4 = document.createElement("div");
                newCol4.classList.add("col-md-6");
                var targetFormGroup = document.createElement("div");
                targetFormGroup.classList.add("form-group");
                var targetLabel = document.createElement("strong");
                targetLabel.textContent = "Target Nilai";
                var targetInput = document.createElement("input");
                targetInput.setAttribute("type", "number");
                targetInput.setAttribute("name", "trgt_nilai[]");
                targetInput.classList.add("form-control");
                targetFormGroup.appendChild(targetLabel);
                targetFormGroup.appendChild(targetInput);
                newCol4.appendChild(targetFormGroup);

                var removeButton = document.createElement("button");
                removeButton.setAttribute("type", "button");
                removeButton.classList.add("btn", "btn-warning", "remove_subcpl");
                removeButton.style.float = "right";
                removeButton.textContent = "Hapus";
                subcplDescriptionFormGroup.appendChild(removeButton);

                newRow.appendChild(newCol1);
                newRow.appendChild(newCol2);
                newRow.appendChild(newCol3);
                newRow.appendChild(newCol4);

                subcplFields.appendChild(newRow);
            }
        }

        document.querySelector('.add_subcpl_field').addEventListener('click', addSubCPLField);

        document.getElementById('kurikulum_id').addEventListener('change', function() {
            var kurikulumId = this.value;
            updateCPLDropdown(kurikulumId);
        });

        document.getElementById('cpl_id').addEventListener('change', function() {
            updateCPLInfo(this.value);
        });

        document.getElementById('subcpl_fields').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove_subcpl')) {
                e.target.closest('.row').remove();
            }
        });

        var initialKurikulumId = document.getElementById('kurikulum_id').value;
        updateCPLDropdown(initialKurikulumId);
    });
</script>

@endsection
