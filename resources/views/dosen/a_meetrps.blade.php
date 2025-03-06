@extends('layouts.admin.panel')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
            </div>
        </div>
    </div>

    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Input Data RPS</h3>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <form role="form" method="post" action="{{ url('dosen/s_meetrps', ['matkul_id' => $matkul_id]) }}" enctype="multipart/form-data" id="rps-form">
                        @csrf
                        {{-- <input type="hidden" name="genkrs_id" value="{{ $genkrs_id }}"> --}}

                        <div class="box-body">
                            <div id="form-container">
                                <!-- Initial Form Section -->
                                <div class="row form-section">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Minggu Ke</strong>
                                            <select name="pekan_ke[]" class="form-control">
                                                <option value=" " selected disabled>Pilih Pekan Ke ...</option>
                                                @for ($i = 1; $i <= 18; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <strong>Kemampuan Akhir Tiap Tahapan Belajar (Sub CPMK)</strong>
                                            <select name="mksubcpmk_id[]" class="form-control">
                                                <option value="" selected disabled>Pilih Sub CPMK</option>
                                                @foreach ($mkscp->unique('kode_scpmk') as $ms)
                                                    <option value="{{ $ms->mksubcpmk_id }}">{{ $ms->kode_scpmk }}</option>
                                                @endforeach
                                            </select>
                                            @error('mksubcpmk_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="indikator">Indikator</label>
                                            <textarea name="indikator[]" class="form-control ckeditor" rows="3"></textarea>
                                            @error('indikator')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kritek">Kriteria dan Teknik</label>
                                            <textarea name="kritek[]" class="form-control ckeditor" rows="3"></textarea>
                                            @error('kritek')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="luring">Bentuk Pembelajaran [Luring]</label>
                                            <textarea name="luring[]" class="form-control ckeditor" rows="3"></textarea>
                                            @error('luring')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="daring">Bentuk Pembelajaran [Daring]</label>
                                            <textarea name="daring[]" class="form-control ckeditor" rows="3"></textarea>
                                            @error('daring')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="mat_pustaka">Materi Pembelajaran [Pustaka]</label>
                                            <textarea name="mat_pustaka[]" class="form-control ckeditor" rows="3"></textarea>
                                            @error('mat_pustaka')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="bobot_nil">Bobot Penilaian</label>
                                            <input type="text" name="bobot_nil[]" class="form-control"/>
                                            @error('bobot_nil')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-danger remove-form">Hapus</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" style="float:right; margin-left:10px">Simpan</button>
                                    <button type="button" id="add-form" class="btn btn-success" style="float:right">Tambah Isi</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Template for Additional Form Sections -->
<template id="form-template">
    <div class="row form-section"><p>
        <div class="col-md-6">
            <div class="form-group">
                <strong>Minggu Ke</strong>
                <select name="pekan_ke[]" class="form-control">
                    <option value=" " selected disabled>Pilih Pekan Ke ...</option>
                    @for ($i = 1; $i <= 18; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <strong>Kemampuan Akhir Tiap Tahapan Belajar (Sub CPMK)</strong>
                <select name="mksubcpmk_id[]" class="form-control">
                    <option value="" selected disabled>Pilih Sub CPMK</option>
                    @foreach ($mkscp->unique('kode_scpmk') as $ms)
                        <option value="{{ $ms->mksubcpmk_id }}">{{ $ms->kode_scpmk }}</option>
                    @endforeach
                </select>
                @error('mksubcpmk_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="indikator">Indikator</label>
                <textarea name="indikator[]" class="form-control ckeditor" rows="3"></textarea>
                @error('indikator')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="kritek">Kriteria dan Teknik</label>
                <textarea name="kritek[]" class="form-control ckeditor" rows="3"></textarea>
                @error('kritek')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="luring">Bentuk Pembelajaran [Luring]</label>
                <textarea name="luring[]" class="form-control ckeditor" rows="3"></textarea>
                @error('luring')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="daring">Bentuk Pembelajaran [Daring]</label>
                <textarea name="daring[]" class="form-control ckeditor" rows="3"></textarea>
                @error('daring')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="mat_pustaka">Materi Pembelajaran [Pustaka]</label>
                <textarea name="mat_pustaka[]" class="form-control ckeditor" rows="3"></textarea>
                @error('mat_pustaka')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="bobot_nil">Bobot Penilaian</label>
                <input type="text" name="bobot_nil[]" class="form-control"/>
                @error('bobot_nil')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <button type="button" class="btn btn-danger remove-form">Hapus</button>
        </div>
    </div>
</template>

<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function initializeCKEditors() {
            document.querySelectorAll('.ckeditor').forEach((textarea) => {
                if (!textarea.editorInstance) {
                    ClassicEditor.create(textarea)
                        .then(editor => {
                            textarea.editorInstance = editor;
                        })
                        .catch(error => {
                            console.error('Error initializing CKEditor:', error);
                        });
                }
            });
        }

        initializeCKEditors();

        document.getElementById('add-form').addEventListener('click', function() {
            const formContainer = document.getElementById('form-container');
            const template = document.getElementById('form-template').innerHTML;
            formContainer.insertAdjacentHTML('beforeend', template);

            initializeCKEditors();
        });

        document.getElementById('form-container').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-form')) {
                const formSection = event.target.closest('.form-section');
                const textareas = formSection.querySelectorAll('.ckeditor');
                textareas.forEach(textarea => {
                    if (textarea.editorInstance) {
                        textarea.editorInstance.destroy();
                    }
                });
                formSection.remove();
            }
        });
    });
</script>

@endsection
