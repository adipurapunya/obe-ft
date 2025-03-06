@extends('layouts.admin.panel')

@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                <h5 class="m-0">Input Nilai</h5>
            </tr>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('/dosen/t_inpnilai') }}">
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
                @if (isset($inilai) && count($inilai) > 0)
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;">No.</th>
                            <th style="width: 20%; text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            <th style="width: 4%; text-align: center; vertical-align: middle;">Kelas</th>
                            <th style="width: 5%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $uniqueNilai = $inilai->map(function($item) {
                                return [
                                    'combined_key' => $item->nama_mk . '-' . $item->nama_kelas,
                                    'original' => $item
                                ];
                            })->unique('combined_key')->pluck('original');
                        @endphp
                        @foreach($uniqueNilai as $ms)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $ms->kode_mk }} - {{ $ms->nama_mk }}</td>
                                <td><center>{{ $ms->nama_kelas }}</center></td>
                                <td>
                                    @if ($ms->dosen_inputnilai == $currentDosenId)
                                        <!-- Tampilkan tombol jika dosen memiliki akses -->
                                        <a href="{{ url('dosen/a_inpnilai/'.Crypt::encryptString($ms->kelas_id)) }}"
                                        class="btn btn-sm"
                                        style="color:white; background-color: #5895bd">+ Input Nilai</a>
                                    @else
                                        <button class="btn btn-sm"
                                                style="color:white; background-color: #d9534f"
                                                onclick="noAccessAlert()">
                                            + Input Nilai
                                        </button>
                                    @endif
                                        <a href="{{ route('dosen.t_nilai', ['kelas_id' => Crypt::encryptString($ms->kelas_id)]) }}"
                                        class="btn btn-sm" style="color:white; background-color: rgb(84, 141, 31)">Lihat Nilai</a>
                                </td>
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

    <script>
        function noAccessAlert() {
            Swal.fire({
                title: 'Akses Ditolak!',
                text: 'Anda tidak memiliki akses untuk menginput nilai, silahkan menghubungi Admin Prodi.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Maaf Data NILAI nya BELUM DIISI',
                text: '{{ session("warning") }}'
            });
        @endif
    });
    </script>


  @endsection
