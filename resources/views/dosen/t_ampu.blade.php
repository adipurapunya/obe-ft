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
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              {{-- <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li> --}}
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="m-0">Daftar Mata Kuliah Diampu</h5>
            </tr>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('/dosen/t_ampu') }}">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <div class="form-group">
                                <label for="semester_id"><strong>Semester</strong></label>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <select id="semester_select" name="semester_id" class="form-control">
                                    @foreach ($smtr as $sm)
                                    <option value="{{ encrypt($sm->id) }}" @if(isset($encrypted_semester_id) && $encrypted_semester_id == encrypt($sm->id)) selected @endif>{{ $sm->keterangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <div class="form-group">

                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <button type="submit" class="btn" style="float:left; background-color: rgb(95, 158, 160); color: white;">Tampilkan</button>
                            </div>
                        </div>
                    </div>
                </form><br><br>
                @if (isset($mata_kuliah) && count($mata_kuliah) > 0)
                <table style="width: 100%">
                    <tr>
                        <td style="width:12%"><b>Nama</b></td>
                        <td style="width: 3%"></td>
                        @foreach($mata_kuliah->unique('name') as $mk)
                            <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $mk->nama_dosen }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td style="width:8%"><b>NIDN</b></td>
                        <td style="width: 3%"></td>
                        @foreach($mata_kuliah->unique('nidn') as $mk)
                            <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $mk->nidn }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Program Studi</b></td>
                        <td style="width: 3%"></td>
                        @foreach($mata_kuliah->unique('nama_prodi') as $mk)
                            <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $mk->nama_prodi }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td style="width:8%"><b>Semester</b></td>
                        <td style="width: 3%"></td>
                        @foreach($mata_kuliah->unique('keterangan') as $mk)
                            <td style="width: 70%; border-bottom: 1px solid rgb(95, 158, 160); color: rgb(95, 158, 160);"><b>{{ $mk->keterangan }}</td>
                        @endforeach
                    </tr>
                </table><br><br>
                <table id="example1" class="table table-bordered table-striped">
                    <thead style="background: rgb(95, 158, 160); color: white;">
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;" rowspan="2">No.</th>
                            <th colspan="2" style="text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            <th rowspan="2" style="width: 6%; text-align: center; vertical-align: middle;">Kelas</th>
                            <th colspan="3" style="text-align: center; vertical-align: middle;">SKS</th>
                        </tr>
                        <tr>
                            <th><center>Kode</center></th>
                            <th>Nama</th>
                            <th><center>T</center></th>
                            <th><center>P</center></th>
                            <th><center>PL</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mata_kuliah as $mk)
                            <tr>
                                <td><center>{{ $loop->iteration }}<center></td>
                                <td><center>{{ $mk->kode_mk }}</center></td>
                                <td>{{ $mk->nama_mk }}</td>
                                <td><center>{{ $mk->nama_kelas }}</center></td>
                                <td><center>{{ $mk->sks_teo }}</center></td>
                                <td><center>{{ $mk->sks_prak }}</center></td>
                                <td><center>{{ $mk->sks_lap }}</center></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p>Tidak ada data yang tersedia.</p>
                @endif
            </div>
            </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

    {{-- <script>
        var selectedSemester = localStorage.getItem('selected_semester');

        if (selectedSemester) {
            document.getElementById('semester_select').value = selectedSemester;
        }

        document.getElementById('semester_select').addEventListener('change', function() {
            localStorage.setItem('selected_semester', this.value);
        });
    </script> --}}
    <script>
        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan file ini setelah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('superadmin/h_matkul') }}/" + id;
                }
            });
        }
    </script>

  @endsection
