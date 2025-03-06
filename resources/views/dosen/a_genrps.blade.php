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
                <h3 class="box-title">Input Data RPS Mata Kuliah</h3>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <form role="form" method="post" action="{{ url('dosen/s_genrps', ['matkul_id' => $matkul_id]) }}" enctype="multipart/form-data" id="rps-form">
                        @csrf
                        <div class="box-body">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="tgl_susun">Tanggal Penyusunan RPS</label>
                                                <input type="date" name="tgl_susun" class="form-control">
                                                @if ($errors->has('tgl_susun'))
                                                <span class="text-danger">{{ $errors->first('tgl_susun') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kajur_id">Koordinator Bidang Keahlian</label>
                                            <select name="kajur_id" class="form-control">
                                                @foreach ($kajur as $kj)
                                                    <option value="{{ $kj->dosen_id }}">{{ $kj->nama_dosen }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="desk_singkat">Deskripsi Singkat</label>
                                            <textarea name="desk_singkat" class="form-control ckeditor" id="editor1" rows="3"></textarea>
                                            @error('desk_singkat')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="kajian">Materi Kajian</label>
                                            <textarea name="kajian" class="form-control ckeditor" id="editor2" rows="3"></textarea>
                                            @error('kajian')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="pustaka">Pustaka</label>
                                            <textarea name="pustaka" class="form-control ckeditor" id="editor3" rows="3"></textarea>
                                            @error('pustaka')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mk_syarat">Mata Kuliah Syarat</label>
                                            <textarea name="mk_syarat" class="form-control ckeditor" id="editor4" rows="3"></textarea>
                                            @error('mk_syarat')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="keterangan">Keterangan</label>
                                            <textarea name="keterangan" class="form-control ckeditor" id="editor5" rows="3"></textarea>
                                            @error('keterangan')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lampiran">Lampiran</label>
                                            <textarea name="lampiran" class="form-control ckeditor" id="editor6" rows="3"></textarea>
                                            @error('lampiran')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary" style="float:right; margin-left:10px">Simpan</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>


<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>

<script>
    // Initialize CKEditor
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll('.ckeditor').forEach(textarea => {
            ClassicEditor.create(textarea, {
                ckfinder: {
                    uploadUrl: '{{ route('ckeditor.upload') . '?_token=' . csrf_token() }}',
                }
            }).then(editor => {
                console.log('Editor was initialized', editor);
            }).catch(error => {
                console.error('Error initializing CKEditor:', error);
            });
        });
    });
</script>
@endsection
