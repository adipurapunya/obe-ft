@extends('layouts.admin.panel')

@section('content')

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
                <h5 class="m-0">Pilih Semester</h5>
            </tr>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ url('/dosen/t_perkelas') }}">
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
                                    <option value="">{{ $sm->keterangan }}</option>
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
                 <!-- @if (isset($inilai) && count($inilai) > 0)
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
                       
                    </tbody>
                </table>
                @else
                <p>Tidak ada data yang tersedia.</p>
                @endif
                -->
            </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  
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

</div>

@endsection
