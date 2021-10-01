@if (Auth::user()->role ==1)
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Super Admin</li>
    <li>
      <a href="{{ url('home') }}">
        <i class="fa fa-dashboard"></i> <span>Halaman Utama</span>
      </a>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-th"></i> <span>Master</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="/show_mhs"><i class="fa fa-circle-o"></i> Data Mahasiswa Aktif</a></li>
        <li><a href="/pembimbing"><i class="fa fa-circle-o"></i> Data Dosen Pembimbing</a></li>
        <li><a href="/kaprodi"><i class="fa fa-circle-o"></i> Data KAPRODI</a></li>
        <li><a href="/wadir"><i class="fa fa-circle-o"></i> Data WADIR</a></li>
        <li><a href="/data_nilai"><i class="fa fa-circle-o"></i> Data Nilai Mahasiswa</a></li>
        <li><a href="/data_ipk"><i class="fa fa-circle-o"></i> Data IPK Mahasiswa Aktif</a></li>
        <li><a href="/edom"><i class="fa fa-circle-o"></i> Setting EDOM</a></li>
        <li><a href="/data_edom"><i class="fa fa-circle-o"></i> Data EDOM</a></li>
        <li><a href="/data_foto"><i class="fa fa-circle-o"></i> Data Foto</a></li>
        <li><a href="/show_user"><i class="fa fa-circle-o"></i> Data User Mahasiswa</a></li>
        <li><a href="/data_admin"><i class="fa fa-circle-o"></i> Data User Dosen</a></li>
        <li><a href="/data_admin_prodi"><i class="fa fa-circle-o"></i> Data User Admin Prodi</a></li>
        <li><a href="/data_dosen_luar"><i class="fa fa-circle-o"></i> Data User Dosen Luar</a></li>
        <li><a href="/data_ktm"><i class="fa fa-circle-o"></i> Data KTM Mahasiswa</a></li>
        <li><a href="/info"><i class="fa fa-circle-o"></i> Informasi</a></li>
        <li><a href="/pdm_aka"><i class="fa fa-circle-o"></i> Pedoman</a></li>
        <li><a href="/visimisi"><i class="fa fa-circle-o"></i> Visi Misi</a></li>
      </ul>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-th"></i> <span>Master KRS</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('approve_krs') }}"><i class="fa fa-circle-o"></i> Data Approve KRS</a></li>
        <li><a href="{{ url('data_krs') }}"><i class="fa fa-circle-o"></i> Data KRS</a></li>
      </ul>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-th"></i> <span>Master KHS</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('nilai_khs') }}"><i class="fa fa-circle-o"></i> Data Nilai KHS</a></li>
        <li><a href="{{ url('nilai_prausta') }}"><i class="fa fa-circle-o"></i> Data Nilai PraUSTA</a></li>
      </ul>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-th"></i> <span>Master Nilai</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('no_transkrip') }}"><i class="fa fa-circle-o"></i> Nomor Transkrip Nilai</a></li>
        <li><a href="{{ url('transkrip_nilai') }}"><i class="fa fa-circle-o"></i> Transkrip Nilai Sementara</a></li>
        <li><a href="{{ url('nilai_mhs') }}"><i class="fa fa-circle-o"></i> Rekap Nilai Mahasiswa</a></li>
      </ul>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-th"></i> <span>Master Soal</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('soal_uts') }}"><i class="fa fa-circle-o"></i> Soal UTS</a></li>
        <li><a href="{{ url('soal_uas') }}"><i class="fa fa-circle-o"></i> Soal UAS</a></li>
      </ul>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-th"></i> <span>Master Perkuliahan</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('rekap_perkuliahan') }}"><i class="fa fa-circle-o"></i> Rekap Perkuliahan</a></li>
      </ul>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-th"></i> <span>Master PraUSTA</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('data_prausta') }}"><i class="fa fa-circle-o"></i> Data Mahasiswa PraUSTA</a></li>
      </ul>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-th"></i> <span>Master Mahasiswa</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('mhs_ta') }}"><i class="fa fa-circle-o"></i>Mahasiswa Tugas Akhir</a></li>
      </ul>
    </li>
  </ul>
@elseif (Auth::user()->role ==2)
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Dosen</li>
    <li>
      <a href="{{ url('home') }}">
        <i class="fa fa-dashboard"></i> <span>Halaman Utama</span>
      </a>
    </li>
    <li><a href="{{ url('mhs_bim') }}"><i class="fa  fa-users"></i> <span>Mahasiswa Bimbingan</span></a></li>
    <li><a href="{{ url('val_krs') }}"><i class="fa fa-check-square"></i> <span>Validasi KRS</span></a></li>
    <li><a href="{{ url('makul_diampu_dsn') }}"><i class="fa  fa-users"></i> <span>Matakuliah diampu</span></a></li>
    <li><a href="{{ url('history_makul_dsn') }}"><i class="fa  fa-list"></i> <span>History Matakuliah diampu</span></a></li>
  </ul>
@elseif (Auth::user()->role == 3)
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">MENU MAHASISWA</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    {{-- <li><a href="{{ url('krs') }}"><i class="fa fa-list-alt"></i> <span>KRS</span></a></li> --}}
    <li><a href="{{ url('isi_krs') }}"><i class="fa fa-list-alt"></i> <span>Input KRS</span></a></li>
    <li><a href="{{ url('isi_edom') }}"><i class="fa fa-pencil-square-o"></i> <span>Input EDOM</span></a></li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-list"></i> <span>KHS</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{url('khs_mid')}}"><i class="fa fa-circle-o"></i> KHS Mid-term</a></li>
        <li><a href="{{ url('khs_final') }}"><i class="fa fa-circle-o"></i> KHS Final-term</a></li>
      </ul>
    </li>
    <li><a href="{{ url('nilai') }}"><i class="fa fa-file-text-o"></i> <span>Lihat Nilai</span></a></li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-calendar"></i> <span>Lihat Jadwal</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('jadwal') }}"><i class="fa fa-circle-o"></i> <span>Jadwal Kuliah</span></a></li>
        <li><a href="{{ url('jdl_uts') }}"><i class="fa fa-circle-o"></i> <span>Jadwal UTS</span></a></li>
        <li><a href="{{ url('jdl_uas') }}"><i class="fa fa-circle-o"></i> <span>Jadwal UAS</span></a></li>
      </ul>
    </li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-calendar-check-o"></i> <span>PraUSTA</span>
        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('seminar_prakerin') }}"><i class="fa fa-circle-o"></i> <span>Seminar Prakerin</span></a></li>
        <li><a href="{{ url('seminar_proposal') }}"><i class="fa fa-circle-o"></i> <span>Seminar Proposal</span></a></li>
        <li><a href="{{ url('sidang_ta') }}"><i class="fa fa-circle-o"></i> <span>Sidang TA</span></a></li>
      </ul>
    </li>
    <li><a href="{{ url('keuangan') }}"><i class="fa fa-money"></i> <span>Keuangan</span></a></li>
    <li><a href="{{ url('pedoman_akademik') }}"><i class="fa fa-file"></i> <span>Pedoman</span></a></li>
  </ul>
@elseif (Auth::user()->role == 5)
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Dosen</li>
    <li>
      <a href="{{ url('home') }}">
        <i class="fa fa-dashboard"></i> <span>Halaman Utama</span>
      </a>
    </li>
    <li><a href="{{ url('makul_diampu') }}"><i class="fa  fa-users"></i> <span>Matakuliah diampu</span></a></li>
    <li><a href="{{ url('history_makul_dsnlr') }}"><i class="fa  fa-list"></i> <span>History Matakuliah diampu</span></a></li>
  </ul>
@elseif (Auth::user()->role == 6)
  <ul class="sidebar-menu" data-widget="tree">
      <li class="header">Menu Kaprodi</li>
      <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-database"></i> <span>Master Data</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('mhs_aktif') }}"><i class="fa fa-circle-o"></i> <span>Mahasiswa Aktif</span></a></li>
          <li><a href="{{ url('mhs_bim_kprd') }}"><i class="fa fa-circle-o"></i> <span>Mahasiswa Bimbingan</span></a></li>
          <li><a href="{{ url('data_ipk_kprd')}}"><i class="fa fa-circle-o"></i> Data IPK Mahasiswa Aktif</a></li>
          <li><a href="{{ url('nilai_mhs_kprd') }}"><i class="fa fa-circle-o"></i> Rekap Nilai Mahasiswa</a></li>
          <li><a href="{{ url('rekap_perkuliahan_kprd') }}"><i class="fa fa-circle-o"></i> Rekap Perkuliahan</a></li>
          <li><a href="{{ url('soal_uts_kprd') }}"><i class="fa fa-circle-o"></i> Soal UTS</a></li>
          <li><a href="{{ url('soal_uas_kprd') }}"><i class="fa fa-circle-o"></i> Soal UAS</a></li>
        </ul>
      </li>
      <li><a href="{{ url('val_krs_kprd') }}"><i class="fa fa-check-square"></i> <span>Validasi KRS</span></a></li>
      <li><a href="{{ url('makul_diampu_kprd') }}"><i class="fa  fa-users"></i> <span>Matakuliah diampu</span></a></li>
      <li><a href="{{ url('history_makul_kprd') }}"><i class="fa  fa-list"></i> <span>History Matakuliah diampu</span></a></li>
    </ul>
@elseif (Auth::user()->role == 7)
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu WADIR 1</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-database"></i> <span>Master Data</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('data_bap') }}"><i class="fa fa-circle-o"></i> <span>Data BAP</span></a></li>

      </ul>
    </li>
  </ul>
@elseif (Auth::user()->role == 11)
  <ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu PraUSTA</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
      <a href="#">
        <i class="fa fa-database"></i> <span>Master Data</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li><a href="{{ url('nilai_prausta') }}"><i class="fa fa-circle-o"></i> <span>Nilai PraUSTA</span></a></li>
        <li><a href="{{ url('data_prakerin') }}"><i class="fa fa-circle-o"></i> <span>Data Prakerin</span></a></li>
      </ul>
    </li>
  </ul>
@endif
