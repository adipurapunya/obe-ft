@extends('layouts.admin.panel')

@section('content')

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
                <h3 class="box-title">Input Data RPS</h3>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <form role="form" method="post" action="{{ url('dosen/s_rps/'.$matkul_id)}}" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="isi_berita">Deskripsi Singkat</label>
                                            <textarea name="desk_singkat" class="form-control ckeditor" id="editor1" rows="3"></textarea>
                                            @if ($errors->has('desk_singkat'))
                                                <span class="text-danger">{{ $errors->first('desk_singkat') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="materi">Materi</label>
                                            <textarea name="materi" class="form-control ckeditor" id="editor2" rows="3"></textarea>
                                            @if ($errors->has('materi'))
                                                <span class="text-danger">{{ $errors->first('materi') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pustaka">Pustaka</label>
                                            <textarea name="pustaka" class="form-control ckeditor" id="editor3" rows="3"></textarea>
                                            @if ($errors->has('pustaka'))
                                                <span class="text-danger">{{ $errors->first('pustaka') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mk_syarat">Mata Kuliah Syarat</label>
                                            <textarea name="mk_syarat" class="form-control ckeditor" id="editor4" rows="3"></textarea>
                                            @if ($errors->has('mk_syarat'))
                                                <span class="text-danger">{{ $errors->first('mk_syarat') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="keterangan">Keterangan</label>
                                            <textarea name="keterangan" class="form-control ckeditor" id="editor5" rows="3"></textarea>
                                            @if ($errors->has('keterangan'))
                                                <span class="text-danger">{{ $errors->first('keterangan') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lampiran">Lampiran</label>
                                            <textarea name="lampiran" class="form-control ckeditor" id="editor6" rows="3"></textarea>
                                            @if ($errors->has('lampiran'))
                                                <span class="text-danger">{{ $errors->first('lampiran') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div id="form-container">

                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="float:right; margin-left:10px">Simpan</button>
                                <button type="button" id="add-form" class="btn btn-success" style="float:right">Tambah Isi</button>
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

<!-- Template for new form section -->
<div id="form-template" style="display: none;">
    <div class="form-section">
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <strong>Minggu Ke</strong>
                    <select name="pekan_ke[]" class="form-control">
                        <option value=" " selected disabled>Pilih Pekan Ke ...</option>
                        @for ($i = 1; $i <= 17; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <strong>Kemampuan Akhir Tiap Tahapan Belajar (Sub CPMK)</strong>
                    <select name="indikator[]" class="form-control">
                        <option value=" " selected disabled>Pilih Sub CPMK</option>
                        @foreach($mkscp->unique('kode_scpmk') as $ms)
                            <option value="{{ $ms->mkscpmk_id }}">{{ $ms->kode_scpmk }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('indikator'))
                        <span class="text-danger">{{ $errors->first('indikator') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="kriteria">Kriteria dan Teknik</label>
                    <textarea name="kriteria[]" class="form-control ckeditor" rows="3"></textarea>
                    @if ($errors->has('kriteria'))
                        <span class="text-danger">{{ $errors->first('kriteria') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="luring">Bentuk Pembelajaran [Luring]</label>
                    <textarea name="luring[]" class="form-control ckeditor" rows="3"></textarea>
                    @if ($errors->has('luring'))
                        <span class="text-danger">{{ $errors->first('luring') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="daring">Bentuk Pembelajaran [Daring]</label>
                    <textarea name="daring[]" class="form-control ckeditor" rows="3"></textarea>
                    @if ($errors->has('daring'))
                        <span class="text-danger">{{ $errors->first('daring') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="mat_pustaka">Materi Pembelajaran [Pustaka]</label>
                    <textarea name="mat_pustaka[]" class="form-control ckeditor" rows="3"></textarea>
                    @if ($errors->has('mat_pustaka'))
                        <span class="text-danger">{{ $errors->first('mat_pustaka') }}</span>
                    @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label for="bobot_nil">Bobot Penilaian</label>
                    <input type="text" name="bobot_nil[]" class="form-control" rows="3"/>
                    @if ($errors->has('bobot_nil'))
                        <span class="text-danger">{{ $errors->first('bobot_nil') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="button" class="btn btn-danger remove-form">Hapus</button>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
<script>
    function initializeCKEditors() {
        const textareas = document.querySelectorAll('.ckeditor');
        textareas.forEach(textarea => {
            if (!textarea.classList.contains('ck-initialized')) {
                ClassicEditor.create(textarea, {
                    ckfinder: {
                        uploadUrl: '{{ route('ckeditor.upload') . '?_token=' . csrf_token() }}',
                    }
                }).then(editor => {
                    console.log('Editor was initialized', editor);
                }).catch(error => {
                    console.error('Error initializing CKEditor:', error);
                });
                textarea.classList.add('ck-initialized');
            }
        });
    }

    function setupRemoveFormListeners() {
        const removeButtons = document.querySelectorAll('.remove-form');
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const formSection = this.closest('.form-section');
                formSection.remove();
            });
        });
    }

    document.getElementById('add-form').addEventListener('click', function() {
        var container = document.getElementById('form-container');
        var template = document.getElementById('form-template').innerHTML;
        container.insertAdjacentHTML('beforeend', template);
        initializeCKEditors();
        setupRemoveFormListeners();
    });

    setupRemoveFormListeners();

    initializeCKEditors();
</script>

@endsection
