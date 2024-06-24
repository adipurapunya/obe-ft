@extends('layouts.admin.panel')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                <h3 class="box-title">Data User</h3>
            </div>
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="m-0"><a href="{{ route('import_user_form') }}"><div class="btn btn-small btn-info text-center" style="float:right"> Import User Excel</div></a></h5>
                    <h5 class="m-0"><a href="{{url('superadmin/create_user')}}"><div class="btn btn-small btn-primary text-center" style="float:right; margin-right: 10px;"> + User Personal</div></a></h5>
                </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 3%">No.</th>
                                    <th style="width: 20%">Nama</th>
                                    <th>Email</th>
                                    <th>Level</th>
                                    <th style="width: 10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $dt)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $dt->name}}</td>
                                    <td>{{ $dt->email}}</td>
                                    <td>
                                        <label class="badge badge-success" style="font-size:15px">{{ $dt->role_name }}</label>
                                    </td>
                                    <td>
                                        <a href="{{ url('superadmin/e_listuser/'.Crypt::encryptString($dt->id), [])}}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="javascript:void(0);" onclick="konfirmasiHapus('{{ Crypt::encryptString($dt->id) }}')" class="btn btn-danger btn-sm">Hapus</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
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
                window.location.href = "{{ url('superadmin/h_user') }}/" + id;
            }
        });
    }
</script>


  @endsection
