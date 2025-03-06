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
                <h5 class="m-0">Daftar Kelas</h5>
            </div>
            <div class="card-body">

                <form method="GET" action="{{ url('/prodiadmin/t_kelas') }}">
                    <div class="row">
                        <div class="col-xs-6 col-sm-6 col-md-2">
                            <div class="form-group">
                                <label for="semester_id"><strong>Semester</strong></label>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <select id="semester_select" name="semester_id" class="form-control">
                                    <option value="">Pilih Semester</option>
                                    @foreach ($semes as $sm)
                                        <option value="{{ encrypt($sm->id) }}"
                                            @if(isset($encrypted_semester_id) && $encrypted_semester_id == encrypt($sm->id)) selected @endif>
                                            <b>{{ $sm->keterangan }}</b>
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-2 col-sm-2 col-md-2">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Tampilkan</button>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ url('/prodiadmin/a_kelas') }}" class="btn btn-success">Tambah Kelas</a>
                        </div>
                    </div>
                </form><br><br>
                {{-- @if (isset($mata_kuliah) && count($mata_kuliah) > 0) --}}
                <table id="example1" class="table table-bordered table-striped">
                    <thead style="background: rgb(95, 158, 160); color: white;">
                        <tr>
                            <th><center>No.</center></th>
                            <th><center>Matkul</center></th>
                            <th><center>Nama Kelas</center></th>
                            <th><center>Dosen 1</center></th>
                            <th><center>Dosen 2</center></th>
			<th><center>Dosen 3</center></th>
                            <th><center>Semester</center></th>
                            <th><center>Aksi</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelas->unique(function ($item) {
                            return $item->nama_kelas . '-' . $item->nama_mk;
                        }) as $ke)
                            <tr>
                                <td><center>{{ $loop->iteration }}</center></td>
                                <td>{{ $ke->nama_mk }}</td>
                                <td><center>{{ $ke->nama_kelas }}</center></td>
                                <td>{{ $ke->nama_dosen_satu }}</td>
                                <td>{{ $ke->nama_dosen_dua}}</td>
				<td>{{ $ke->nama_dosen_tiga}}</td>
                                <td><center>{{ $ke->nama_smtr }}</center></td>
                                <td>
                                    <center><a href="{{ url('prodiadmin/e_kelas/'.Crypt::encryptString($ke->id), [])}}" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i></a>
                                    <a href="javascript:void(0);" onclick="konfirmasiHapus('{{ Crypt::encryptString($ke->id) }}')" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- @else
                <p>Tidak ada data yang tersedia.</p>
                @endif --}}
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
                window.location.href = "{{ url('prodiadmin/h_kelas') }}/" + id;
            }
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var semesterSelect = document.getElementById('semester_select');
        var selectedSemester = localStorage.getItem('selected_semester');

        if (selectedSemester) {
            semesterSelect.value = selectedSemester;
        }

        semesterSelect.addEventListener('change', function() {
            localStorage.setItem('selected_semester', this.value);
        });
    });
</script>

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
