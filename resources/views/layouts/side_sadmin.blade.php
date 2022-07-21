<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Super Admin</li>
    <li>
        <a href="{{ url('home') }}">
            <i class="fa fa-dashboard"></i> <span>Halaman Utama</span>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-th"></i> <span>Master Akademik</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/master_kurikulum_standar"><i class="fa fa-circle-o"></i> Kurikulum Standar</a></li>
            <li><a href="/master_angkatan"><i class="fa fa-circle-o"></i> Angkatan</a></li>
            <li><a href="/master_bom"><i class="fa fa-circle-o"></i> BOM (Bill of Makul)</a></li>
            <li><a href="/master_jam"><i class="fa fa-circle-o"></i> Jam</a></li>
            <li><a href="/master_makul"><i class="fa fa-circle-o"></i> Matakuliah</a></li>
            <li><a href="/master_menitsks"><i class="fa fa-circle-o"></i> Menit SKS</a></li>
            <li><a href="/master_menitujian"><i class="fa fa-circle-o"></i> Menit Ujian</a></li>
            <li><a href="/master_nilai_angkahuruf"><i class="fa fa-circle-o"></i> Nilai Angka-Huruf</a></li>
            <li><a href="/master_prodi"><i class="fa fa-circle-o"></i> Program Studi</a></li>
            <li><a href="/master_ruangan"><i class="fa fa-circle-o"></i> Ruang Kelas</a></li>
            <li><a href="/info"><i class="fa fa-circle-o"></i> Informasi</a></li>
            <li><a href="/pdm_aka"><i class="fa fa-circle-o"></i> Pedoman</a></li>
            <li><a href="/visimisi"><i class="fa fa-circle-o"></i> Visi Misi</a></li>
            <li><a href="/skpi"><i class="fa fa-circle-o"></i> SKPI</a></li>
            <li><a href="/master_yudisium"><i class="fa fa-circle-o"></i> Yudisium</a></li>
            <li><a href="/master_wisuda"><i class="fa fa-circle-o"></i> Wisuda</a></li>
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
            <li><a href="/show_mhs"><i class="fa fa-circle-o"></i> Mahasiswa Aktif</a></li>
            <li><a href="/data_nilai"><i class="fa fa-circle-o"></i> Nilai Mahasiswa</a></li>
            <li><a href="/data_ipk"><i class="fa fa-circle-o"></i> IPK Mahasiswa</a></li>
            <li><a href="/data_ktm"><i class="fa fa-circle-o"></i> KTM Mahasiswa</a></li>
            <li><a href="/data_foto"><i class="fa fa-circle-o"></i> Foto Mahasiswa</a></li>
            <li><a href="{{ url('mhs_ta') }}"><i class="fa fa-circle-o"></i>Mahasiswa Tugas Akhir</a></li>
            <li><a href="{{ url('kartu_ujian_mhs') }}"><i class="fa fa-circle-o"></i>Kartu Ujian Mahasiswa</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-th"></i> <span>Master Struktural</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/pembimbing"><i class="fa fa-circle-o"></i> Pembimbing Akademik</a></li>
            <li><a href="/kaprodi"><i class="fa fa-circle-o"></i> KAPRODI</a></li>
            <li><a href="/wadir"><i class="fa fa-circle-o"></i> WADIR</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-th"></i> <span>Master Pengguna</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/show_user"><i class="fa fa-circle-o"></i> User Mahasiswa</a></li>
            <li><a href="/data_admin"><i class="fa fa-circle-o"></i> User Dosen Dalam</a></li>
            <li><a href="/data_dosen_luar"><i class="fa fa-circle-o"></i> User Dosen Luar</a></li>
            <li><a href="/data_admin_prodi"><i class="fa fa-circle-o"></i> User Admin Prodi</a></li>
            <li><a href="{{ url('user_microsoft') }}"><i class="fa fa-circle-o"></i>User Microsoft</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-th"></i> <span>Master KRS & KHS</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('approve_krs') }}"><i class="fa fa-circle-o"></i> Data Approve KRS</a></li>
            <li><a href="{{ url('data_krs') }}"><i class="fa fa-circle-o"></i> Data KRS</a></li>
            <li><a href="{{ url('summary_krs') }}"><i class="fa fa-circle-o"></i> Data Rekap KRS</a></li>
            <li><a href="{{ url('nilai_khs') }}"><i class="fa fa-circle-o"></i> Data Nilai KHS</a></li>
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
            <li><a href="{{ url('rekap_perkuliahan') }}"><i class="fa fa-circle-o"></i> Rekap Perkuliahan</a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-th"></i> <span>Master EDOM</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/master_edom"><i class="fa fa-circle-o"></i>Report EDOM</a></li>
            <li><a href="/edom"><i class="fa fa-circle-o"></i> Setting EDOM</a></li>
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
            {{-- <li><a href="{{ url('soal_uts') }}"><i class="fa fa-circle-o"></i> Soal UTS</a></li>
            <li><a href="{{ url('soal_uas') }}"><i class="fa fa-circle-o"></i> Soal UAS</a></li> --}}
            <li><a href="{{ url('soal_uts_uas') }}"><i class="fa fa-circle-o"></i> Soal UTS dan UAS</a></li>
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
            <li><a href="{{ url('transkrip_nilai') }}"><i class="fa fa-circle-o"></i> Transkrip Nilai
                    Sementara</a></li>
            <li><a href="{{ url('transkrip_nilai_final') }}"><i class="fa fa-circle-o"></i> Transkrip Nilai
                    Final</a></li>
            <li><a href="{{ url('nilai_mhs') }}"><i class="fa fa-circle-o"></i> Rekap Nilai Mahasiswa</a></li>
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
            <li><a href="/master_kodeprausta"><i class="fa fa-circle-o"></i> Kode PraUSTA</a></li>
            <li><a href="/master_kategoriprausta"><i class="fa fa-circle-o"></i> Kategori PraUSTA</a></li>
            <li><a href="/master_penilaianprausta"><i class="fa fa-circle-o"></i> Penilaian PraUSTA</a></li>
            <li><a href="{{ url('data_prausta') }}"><i class="fa fa-circle-o"></i> Mahasiswa PraUSTA</a>
            </li>
            <li><a href="/master_prakerin"><i class="fa fa-circle-o"></i> Data PKL</a></li>
            <li><a href="/master_sempro"><i class="fa fa-circle-o"></i> Data SEMPRO</a></li>
            <li><a href="/master_ta"><i class="fa fa-circle-o"></i> Data TA</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-th"></i> <span>Master Kuisioner</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/master_kategorikuisioner"><i class="fa fa-circle-o"></i>Kategori Kuisioner</a></li>
            <li><a href="/master_aspekkuisioner"><i class="fa fa-circle-o"></i>Aspek Kuisioner</a></li>
            <li><a href="/master_kuisioner"><i class="fa fa-circle-o"></i>Kuisioner</a></li>
            <li><a href="/report_kuisioner"><i class="fa fa-circle-o"></i>Report Kuisioner</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-th"></i> <span>Export Data</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/export_data_akm"><i class="fa fa-circle-o"></i>Data AKM</a></li>

        </ul>
    </li>
</ul>
