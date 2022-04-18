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
            <li><a href="/master_angkatan"><i class="fa fa-circle-o"></i> Master Angkatan</a></li>
            <li><a href="/master_bom"><i class="fa fa-circle-o"></i> Master BOM (Bill of Makul)</a></li>
            <li><a href="/master_jam"><i class="fa fa-circle-o"></i> Master Jam</a></li>
            <li><a href="/master_makul"><i class="fa fa-circle-o"></i> Master Matakuliah</a></li>
            <li><a href="/master_menitsks"><i class="fa fa-circle-o"></i> Master Menit SKS</a></li>
            <li><a href="/master_menitujian"><i class="fa fa-circle-o"></i> Master Menit Ujian</a></li>
            <li><a href="/master_nilai_angkahuruf"><i class="fa fa-circle-o"></i> Master Nilai Angka-Huruf</a></li>
            <li><a href="/master_prodi"><i class="fa fa-circle-o"></i> Master Program Studi</a></li>
            <li><a href="/master_ruangan"><i class="fa fa-circle-o"></i> Master Ruang Kelas</a></li>
            <li><a href="/master_kodeprausta"><i class="fa fa-circle-o"></i> Master Kode PraUSTA</a></li>
            <li><a href="/master_edom"><i class="fa fa-circle-o"></i> Master EDOM</a></li>
            <li><a href="/master_kategoriprausta"><i class="fa fa-circle-o"></i> Master Kategori PraUSTA</a></li>
            <li><a href="/master_penilaianprausta"><i class="fa fa-circle-o"></i> Master Penilaian PraUSTA</a></li>
            <li><a href="/master_kategorikuisioner"><i class="fa fa-circle-o"></i> Master Kategori Kuisioner</a></li>
            <li><a href="/master_aspekkuisioner"><i class="fa fa-circle-o"></i> Master Aspek Kuisioner</a></li>
            <li><a href="/master_kuisioner"><i class="fa fa-circle-o"></i> Master Kuisioner</a></li>
        </ul>
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
            <li><a href="{{ url('nilai_prausta_admin') }}"><i class="fa fa-circle-o"></i> Data Nilai PraUSTA</a></li>
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
            <li><a href="{{ url('rekap_perkuliahan') }}"><i class="fa fa-circle-o"></i> Rekap Perkuliahan</a>
            </li>
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
            <li><a href="{{ url('data_prausta') }}"><i class="fa fa-circle-o"></i> Data Mahasiswa PraUSTA</a>
            </li>
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
    <li class="treeview">
        <a href="#">
            <i class="fa fa-th"></i> <span>Master Microsoft</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('user_microsoft') }}"><i class="fa fa-circle-o"></i>Data Akun Microsoft</a></li>
        </ul>
    </li>
</ul>
