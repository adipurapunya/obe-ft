@extends('layouts.admin.panel')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Nilai Mahasiswa</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Daftar Nilai</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead style="color:white; background-color: #5895bd; text-align:center">
                        <tr>
                            <th>No.</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            @foreach($jen_penilaian as $penilaian)
                                <th>{{ $penilaian }}</th>
                            @endforeach
                            <th>Nilai Rata-Rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grouped_nilai as $index => $mahasiswa)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td style="text-align: center">{{ $mahasiswa['nim'] }}</td>
                                <td>{{ $mahasiswa['nama_mahasiswa'] }}</td>
                                @foreach($jen_penilaian as $penilaian)
                                    <td style="text-align: center">
                                        @if(isset($mahasiswa['nilai'][$penilaian]))
                                            {{ $mahasiswa['nilai'][$penilaian] }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                @endforeach
                                <td style="text-align: center">
                                    @if(is_float($mahasiswa['nilai_rata']))
                                        {{ number_format($mahasiswa['nilai_rata'], 2) }}
                                    @else
                                        {{ intval($mahasiswa['nilai_rata']) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

@endsection
