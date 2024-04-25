<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Dosen</li>
    <li>
        <a href="{{ url('home') }}">
            <i class="fa fa-dashboard"></i> <span>Halaman Utama</span>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Pedoman & SOP</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('pedoman_akademik_dsn_dlm') }}"><i class="fa fa-circle-o"></i> <span>Pedoman
                        Umum</span></a></li>
            <li><a href="{{ url('pedoman_khusus_dsn_dlm') }}"><i class="fa fa-circle-o"></i> <span>Pedoman
                        Khusus</span></a></li>
            <li><a href="{{ url('sop_dsn_dlm') }}"><i class="fa fa-circle-o"></i> <span>S.O.P</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Mahasiswa Bimbingan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('mhs_bim') }}"><i class="fa fa-circle-o"></i> <span>Daftar Mahasiswa</span></a></li>
            <li><a href="{{ url('val_krs') }}"><i class="fa fa-circle-o"></i> <span>Validasi KRS</span></a></li>
            <li><a href="{{ url('penangguhan_mhs_dsn') }}"><i class="fa fa-circle-o"></i> <span>Penangguhan </span></a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Pengajaran</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('sk_pengajaran_dsn_dlm') }}"><i class="fa fa-circle-o"></i> <span>SK
                        Pengajaran</span></a>
            </li>
            <li><a href="{{ url('makul_diampu_dsn') }}"><i class="fa fa-circle-o"></i> <span>Matakuliah
                        diampu</span></a>
            </li>
            <li><a href="{{ url('history_makul_dsn') }}"><i class="fa fa-circle-o"></i> <span>History MK
                        diampu</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('jadwal_prausta_dsn_dlm') }}"><i class="fa fa-circle-o"></i> Jadwal PraUSTA</a></li>
            <li><a href="{{ url('pembimbing_pkl') }}"><i class="fa fa-circle-o"></i> Pembimbing PKL</a></li>
            <li><a href="{{ url('pembimbing_sempro') }}"><i class="fa fa-circle-o"></i> Pembimbing SEMPRO</a></li>
            <li><a href="{{ url('pembimbing_ta') }}"><i class="fa fa-circle-o"></i> Pembimbing TA</a></li>
            <li><a href="{{ url('penguji_pkl') }}"><i class="fa fa-circle-o"></i> Penguji PKL</a></li>
            <li><a href="{{ url('penguji_sempro') }}"><i class="fa fa-circle-o"></i> Penguji SEMPRO</a></li>
            <li><a href="{{ url('penguji_ta') }}"><i class="fa fa-circle-o"></i> Penguji TA</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Magang & Skripsi</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('jadwal_magang_dsn_dlm') }}"><i class="fa fa-circle-o"></i> Jadwal Magang & Skripsi</a></li>
            <li><a href="{{ url('pembimbing_magang') }}"><i class="fa fa-circle-o"></i> Pembimbing Magang 1</a></li>
            <li><a href="{{ url('pembimbing_magang2') }}"><i class="fa fa-circle-o"></i> Pembimbing Magang 2</a></li>
            <li><a href="{{ url('pembimbing_sempro_skripsi_dsn_dlm') }}"><i class="fa fa-circle-o"></i>Pembimbing SEMPRO</a></li>
            <li><a href="{{ url('pembimbing_skripsi_dsn_dlm') }}"><i class="fa fa-circle-o"></i>Pembimbing SKRIPSI</a></li>
            <li><a href="{{ url('penguji_magang_dlm') }}"><i class="fa fa-circle-o"></i> Penguji Magang 1</a></li>
            <li><a href="{{ url('penguji_magang2_dlm') }}"><i class="fa fa-circle-o"></i> Penguji Magang 2</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Master Pengajuan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li>
                <a href="{{ url('data_pengajuan_keringanan_absen_dlm') }}">
                    <i class="fa fa-circle-o"></i> <span>Pengajuan Absen</span>
                </a>
            </li>
            <li><a href="{{ url('data_cuti_dsn_pa') }}"><i class="fa fa-circle-o"></i> <span>Cuti</span></a>
            </li>
            <li><a href="{{ url('data_mengundurkan_diri_dsn_pa') }}"><i class="fa fa-circle-o"></i> <span>Mengundurkan
                        diri</span></a></li>
            <li><a href="{{ url('data_pindah_kelas_dsn_pa') }}"><i class="fa fa-circle-o"></i> <span>Pindah
                        Kelas</span></a></li>
        </ul>
    </li>
    {{-- <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Jadwal PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('jadwal_seminar_prakerin_dlm') }}"><i class="fa fa-circle-o"></i> Seminar
    Prakerin</a></li>
    <li><a href="{{ url('jadwal_seminar_proposal_dlm') }}"><i class="fa fa-circle-o"></i> Seminar
            Proposal</a></li>
    <li><a href="{{ url('jadwal_sidang_ta_dlm') }}"><i class="fa fa-circle-o"></i> Sidang TA</a></li>
</ul>
</li> --}}

{{-- <li><a href="{{ url('upload_soal_dsn_dlm') }}"><i class="fa  fa-list"></i> <span>Upload Soal Ujian</span></a>
</li> --}}

</ul>