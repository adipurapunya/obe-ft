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
                <h5 class="m-0">Input Komponen RPS</h5>
            </tr>
            </div>
            <div class="card-body">
                @if (isset($mkscp) && count($mkscp) > 0)
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 3%; text-align: center; vertical-align: middle;">No.</th>
                            <th style="width: 40%; text-align: center; vertical-align: middle;">Mata Kuliah</th>
                            {{-- <th style="width: 4%; text-align: center; vertical-align: middle;">Kelas</th> --}}
                            <th style="width: 6%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mkscp->unique('nama_mk') as $ms)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $ms->kode_mk }} - {{ $ms->nama_mk }}</td>
                                {{-- <td><center>{{ $mk->nama_kelas }}</center></td> --}}
                                <td>
                                    <center><a href="{{ url('dosen/a_rps/'.Crypt::encryptString($ms->matkul_id), [])}}" class="btn btn-sm" style="color:white; background-color: #5895bd">+ Input Data RPS</a></center>
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
