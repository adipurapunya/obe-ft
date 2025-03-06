@extends('layouts.admin.panel')

@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Nilai dan Nilai Rata-Rata Per CPMK</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-body">

                <!-- Informasi Mata Kuliah -->
                <table style="width: 100%">
                    <tr>
                        <td style="width:12%"><b>Mata Kuliah</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid #5F9EA0; color: #5F9EA0;">
                            <b>{{ $Datamatkul->nama_mk }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:12%"><b>Kode Mata Kuliah</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid #5F9EA0; color: #5F9EA0;">
                            <b>{{ $Datamatkul->kode_mk }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Kelas</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid #5F9EA0; color: #5F9EA0;">
                            <b>{{ $Datamatkul->nama_kelas }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Semester</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid #5F9EA0; color: #5F9EA0;">
                            <b>{{ $Datamatkul->semester }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Prodi</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid #5F9EA0; color: #5F9EA0;">
                            <b>{{ $Datamatkul->jenjang }} - {{ $Datamatkul->nama_prodi }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Fakultas</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid #5F9EA0; color: #5F9EA0;">
                            <b>Fakultas Teknik</b>
                        </td>
                    </tr>
                </table><br>

                <!-- Tabel dengan Scroll Bawah Saja -->
                <div class="table-responsive">
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead style="color:white; background-color: #5895bd; text-align:center">
                            <tr>
                                <th style="width:2%; white-space: nowrap;">No.</th>
                                <th style="white-space: nowrap;">NIM</th>
                                <th style="white-space: nowrap;">Nama Mahasiswa</th>

                                @foreach($groupedNilai->first()['nilai'] as $label => $value)
                                    <th style="white-space: nowrap;">{{ $label }}</th>
                                @endforeach

                                @foreach($averagePerCpmk as $cpmk)
                                    <th style="width:8%; white-space: nowrap;">Rata-Rata ({{ $cpmk['kode_cpmk'] }})</th>
                                    <th style="width:8%; white-space: nowrap;">Status Ketercapaian CPMK-{{ $cpmk['kode_cpmk'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($groupedNilai as $index => $mahasiswa)
                                <tr>
                                    <td style="text-align: center; white-space: nowrap;">{{ $loop->iteration }}</td>
                                    <td style="text-align: center; white-space: nowrap;">{{ $mahasiswa['nim'] }}</td>
                                    <td style="white-space: nowrap;">{{ $mahasiswa['nama_mahasiswa'] }}</td>

                                    @foreach($mahasiswa['nilai'] as $value)
                                        <td style="text-align: center; white-space: nowrap;">{{ $value }}</td>
                                    @endforeach

                                    @foreach($averagePerCpmk as $cpmk)
                                    <td style="text-align: center; white-space: nowrap;">
                                        @if(array_key_exists($mahasiswa['nim'], $cpmk['averages']->toArray()))
                                            {{ number_format($cpmk['averages']->toArray()[$mahasiswa['nim']], 2) }}
                                        @else
                                            0
                                        @endif
                                    </td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        @if(array_key_exists($mahasiswa['nim'], $cpmk['status']->toArray()))
                                            {{ $cpmk['status']->toArray()[$mahasiswa['nim']] }}
                                        @else
                                            Tidak
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- CSS untuk Scroll Bawah -->
<style>
    .table-responsive {
        overflow-x: auto;
        width: 100%;
    }

    .dataTables_wrapper .dataTables_length select {
        width: 70px !important; /* Sesuaikan lebar dropdown */
        padding: 4px;
        border-radius: 5px;
    }

    th, td {
        white-space: nowrap;
    }
</style>



<!-- JavaScript untuk DataTables -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#data-table').DataTable({
            "pageLength": 100,
            "lengthMenu": [[100, 250, 500, -1], [100, 250, 500, "All"]],
            "scrollX": true
        });
    });
</script>

@endsection





























{{--
@extends('layouts.admin.panel')
@section('content')

<div class="content-wrapper">
   
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Nilai dan Nilai Rata-Rata Per CPMK</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
               
            </div>
            <div class="card-body">

                <table style="width: 100%">
                    <tr>
                        <td style="width:12%"><b>Mata Kuliah</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $Datamatkul->nama_mk }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:12%"><b>Kode Mata Kuliah</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $Datamatkul->kode_mk }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Kelas</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $Datamatkul->nama_kelas }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Semester</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $Datamatkul->semester }}</b></td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Prodi</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{$Datamatkul->jenjang}}-{{$Datamatkul->nama_prodi}}</b></td>
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Fakultas</b></td>
                        <td style="width: 3%"></td>
                        <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>Fakultas Teknik</b></td>
                    </tr>
                </table><br>

            <div class="table-responsive" id="top-scroll">
                <div style="width: max-content; height: 20px;"></div> 
            </div>

            <div class="table-responsive" id="bottom-scroll">
                <table id="example1" class="table table-bordered table-striped">
                    <thead style="color:white; background-color: #5895bd; text-align:center">
                        <tr>
                            <th style="width:2%">No.</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>

                            @foreach($groupedNilai->first()['nilai'] as $label => $value)
                                <th>{{ $label}}</th>
                            @endforeach

                            @foreach($averagePerCpmk as $cpmk)
                                <th style="width:8%">Rata-Rata ({{ $cpmk['kode_cpmk'] }})</th>
                                <th style="width:8%">Status Ketercapaian CPMK-{{ $cpmk['kode_cpmk'] }}</th>
                            @endforeach

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedNilai as $index => $mahasiswa)
                            <tr>
                                <td style="text-align: center">{{ $loop->iteration }}</td>
                                <td style="text-align: center">{{ $mahasiswa['nim'] }}</td>
                                <td>{{ $mahasiswa['nama_mahasiswa'] }}</td>

                                @foreach($mahasiswa['nilai'] as $value)
                                    <td style="text-align: center">{{ $value }}</td>
                                @endforeach

                                @foreach($averagePerCpmk as $cpmk)
                                <td style="text-align: center">
                                    @if(array_key_exists($mahasiswa['nim'], $cpmk['averages']->toArray()))
                                        {{ number_format($cpmk['averages']->toArray()[$mahasiswa['nim']], 2) }}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if(array_key_exists($mahasiswa['nim'], $cpmk['status']->toArray()))
                                        {{ $cpmk['status']->toArray()[$mahasiswa['nim']] }}
                                    @else
                                        Tidak
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                        @endforeach
                        
                        <tr>
                            <td colspan="3" style="text-align: center; font-weight: bold;">Jumlah Mahasiswa CPMK Tercapai</td>
                            @foreach($groupedNilai->first()['nilai'] as $label => $value)
                                <td></td>
                            @endforeach
                            @foreach($averagePerCpmk as $cpmk)
                                @php
                                    $totalStatus = $cpmk['status']->count();
                                    $totalTercapai = $cpmk['status']->filter(fn($status) => $status === 'Tercapai')->count();
                                    $percentageTercapai = $totalStatus > 0 ? ($totalTercapai / $totalStatus) * 100 : 0;
                                @endphp
                                <td></td>
                                <td style="text-align: center; font-weight: bold;">
                                    {{ $totalTercapai }} ({{ number_format($percentageTercapai, 2) }}%)
                                </td>
                            @endforeach
                        </tr>

                        
                        <tr>
                            <td colspan="3" style="text-align: center; font-weight: bold;">Jumlah Mahasiswa CPMK Tidak Tercapai</td>
                            @foreach($groupedNilai->first()['nilai'] as $label => $value)
                                <td></td>
                            @endforeach
                            @foreach($averagePerCpmk as $cpmk)
                                @php
                                    $totalTidakTercapai = $cpmk['status']->filter(fn($status) => $status === 'Tidak')->count();
                                    $percentageTidakTercapai = $totalStatus > 0 ? ($totalTidakTercapai / $totalStatus) * 100 : 0;
                                @endphp
                                <td></td>
                                <td style="text-align: center; font-weight: bold;">
                                    {{ $totalTidakTercapai }} ({{ number_format($percentageTidakTercapai, 2) }}%)
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        
            </div>
        </div>
    </section>
</div>
@endsection
--}}

