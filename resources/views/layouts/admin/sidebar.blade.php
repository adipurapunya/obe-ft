<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    {{-- <a href="index3.html" class="brand-link">
      <img src="lte/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a> --}}

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset ('lte/dist/img/logo.png') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->name}}</a>
        </div>
      </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
            <a href="/" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                {{-- <i class="right fas fa-angle-left"></i> --}}
              </p>
            </a>
          </li>
          @if(Auth::user()->role->id == 1)
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Master Data
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
              <li class="nav-item">
                <a href="/superadmin/t_listuser" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master User</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/superadmin/t_prodi" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master Prodi</p>
                </a>
              </li>
	       <li class="nav-item">
                <a href="/superadmin/t_adminprodi" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master Admin Prodi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/superadmin/t_dosen" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master Dosen</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/superadmin/t_mahasiswa" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master Mahasiswa</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/superadmin/t_kurikulum" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master Kurikulum</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/superadmin/t_semester" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master Semester</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/superadmin/t_matkul" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master Mata Kuliah</p>
                </a>
              </li>
            </ul>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Official
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
              <li class="nav-item">
                <a href="/superadmin/t_pengesah" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pejabat Pengesah</p>
                </a>
              </li>
            </ul>
            {{-- <ul class="nav nav-treeview" style="padding-left: 25px;">
                <li class="nav-item">
                  <a href="/superadmin/t_koorkk" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Koord. Klp Keahlian</p>
                  </a>
                </li>
              </ul>
          </li> --}}

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-edit"></i>
              <p>
                Capaian Pmbelajaran
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
              <li class="nav-item">
                <a href="/superadmin/t_cpl" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>CPL</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/superadmin/t_subcpl" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sub CPL</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>
                CPMK
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
              <li class="nav-item">
                <a href="/superadmin/t_mkcpmk" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>MK CPMK</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/superadmin/t_mkscpmk" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>MK Sub CPMK</p>
                </a>
              </li>
            </ul>
          </li>
        @endif

        @if(Auth::user()->role->id == 4)
        <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-edit"></i>
              <p>
                Capaian Pmbelajaran
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
              <li class="nav-item">
                <a href="/dosen/t_cpl" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>CPL</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/dosen/t_subcpl" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sub CPL (PI)</p>
                </a>
              </li>
            </ul>
          </li>

        <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="nav-icon fas fa-list"></i>
            <p>
                Mata Kuliah
                <i class="fas fa-angle-left right"></i>
            </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
                <li class="nav-item">
                    <a href="/dosen/t_ampu" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>MK Diampu</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/dosen/t_mkcpmk" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>MK & CPMK</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/dosen/t_mkscpmk" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>MK & Sub CPMK</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/dosen/t_rps" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>RPS</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/dosen/t_tarcpmk" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Target CPMK</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dosen/t_inpnilai" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Input Nilai</p>
                    </a>
                </li>
            </ul>
            <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>
                    Report
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview" style="padding-left: 25px;">
                  <li class="nav-item">
                    <a href="/dosen/t_perkelas" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Per Kelas</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="/dosen/t_permatkul" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Per Mata Kuliah</p>
                    </a>
                  </li>
                </ul>
        @endif

        @if(Auth::user()->role->id == 3)
        <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-database"></i>
              <p>
                Master Data
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
            <li class="nav-item">
                <a href="/prodiadmin/t_dosen" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Master Dosen</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/prodiadmin/t_kurikulum" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master Kurikulum</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
              <li class="nav-item">
                <a href="/prodiadmin/t_matkul" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Master Mata Kuliah</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
                <li class="nav-item">
                  <a href="/prodiadmin/a_mahasiswa" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Master Mahasiswa</p>
                  </a>
                </li>
            </ul>
           <ul class="nav nav-treeview" style="padding-left: 25px;">
                <li class="nav-item">
                  <a href="/prodiadmin/t_kelas" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Master Kelas</p>
                  </a>
                </li>
            </ul>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
                <li class="nav-item">
                  <a href="/prodiadmin/t_mhskelas" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Master Mhs-Kelas</p>
                  </a>
                </li>
            </ul>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class=" nav-icon fas fa-user"></i>
              <p>
                Capaian Pmbelajaran
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
              <li class="nav-item">
                <a href="/prodiadmin/t_cpl" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>CPL</p>
                </a>
              </li>
            </ul>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
                <li class="nav-item">
                  <a href="/prodiadmin/t_subcpl" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Sub CPL (PI)</p>
                  </a>
                </li>
              </ul>
              <ul class="nav nav-treeview" style="padding-left: 25px;">
                <li class="nav-item">
                  <a href="/prodiadmin/t_mksubcpl" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>MK & Sub CPL</p>
                  </a>
                </li>
              </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Official
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
              <li class="nav-item">
                <a href="/prodiadmin/t_pengesah" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Pejabat Pengesah</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-coffee fa-2x"></i>
              
              <p>
                Laporan
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="padding-left: 25px;">
              <li class="nav-item">
                <a href="/prodiadmin/t_laporan_cpmk" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>CPL/SCPL Mahasiswa</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/prodiadmin/t_laporan_cpl_subcpl_prodi" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>CPL/SCPL Prodi</p>
                </a>
              </li>
            </ul>
          </li>



          @endif

    </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
