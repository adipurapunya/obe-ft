@extends('layouts.admin.panel')

@section('content')

<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Daftar Peserta Kelas</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Daftar Mahasiswa</h5>
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
                </table><br>

                <table id="example1" class="table table-bordered table-striped">
                    <thead style="color:white; background-color: #5895bd; text-align:center">
                        <tr>
                            <th style="width:2%">No.</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($mahasiswas_kelas as $mhs)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->nama_mahasiswa }}</td>
                                <td>
                                    <center><a href="{{ url('', [])}}" class="btn btn-sm btn-warning">Hapus</a>
                                </td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>
</div>

<style>
        /* Memastikan dropdown "Show entries" tidak bertumpuk */
    .dataTables_length select {
        width: 80px !important;  /* Lebar dropdown */
        padding: 6px;  /* Memberikan jarak agar tampilan lebih rapi */
        border-radius: 5px;
        text-align: center;
        appearance: auto;
        -moz-appearance: none;
        -webkit-appearance: none;
    }

    /* Memastikan tabel fleksibel */
    .dataTables_wrapper .dataTables_length select {
        display: inline-block !important;
        min-width: 80px;
        max-width: 100px;
        padding: 6px;
    }

</style>

@endsection
