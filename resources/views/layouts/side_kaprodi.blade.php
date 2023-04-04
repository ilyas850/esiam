<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Kaprodi</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Pedoman & SOP</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('pedoman_akademik_dsn_kprd') }}"><i class="fa fa-circle-o"></i> <span>Pedoman
                        Umum</span></a></li>
            <li><a href="{{ url('pedoman_khusus_dsn_kprd') }}"><i class="fa fa-circle-o"></i> <span>Pedoman
                        Khusus</span></a></li>
            <li><a href="{{ url('sop_dsn_kprd') }}"><i class="fa fa-circle-o"></i> <span>S.O.P</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Mahasiswa</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('mhs_aktif') }}"><i class="fa fa-circle-o"></i> <span>Mahasiswa Aktif</span></a>
            </li>
            <li><a href="{{ url('mhs_bim_kprd') }}"><i class="fa fa-circle-o"></i> <span>Mahasiswa
                        Bimbingan</span></a></li>
            <li><a href="{{ url('data_ipk_kprd') }}"><i class="fa fa-circle-o"></i> IPK Mahasiswa Aktif</a>
            </li>
            <li><a href="{{ url('nilai_mhs_kprd') }}"><i class="fa fa-circle-o"></i> Rekap Nilai Mahasiswa</a>
            </li>

            <li><a href="{{ url('record_pembayaran_mahasiswa_kprd') }}"><i class="fa fa-circle-o"></i>Pembayaran
                    Mahasiswa</a></li>
            <li><a href="{{ url('krs_mahasiswa_kprd') }}"><i class="fa fa-circle-o"></i>KRS
                    Mahasiswa</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Validasi</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('val_krs_kprd') }}"><i class="fa fa-circle-o"></i> <span>Validasi KRS</span></a>

            <li><a href="{{ url('val_kurikulum_kprd') }}"><i class="fa fa-circle-o"></i> <span>Validasi
                        Kurikulum</span></a>
            </li>
            <li><a href="{{ url('val_sertifikat_kprd') }}"><i class="fa fa-circle-o"></i> <span>Validasi
                        Sertifikat</span></a>
            </li>
            <li><a href="{{ url('val_soal_uts_kprd') }}"><i class="fa fa-circle-o"></i>Validasi Soal UTS</a></li>
            <li><a href="{{ url('val_soal_uas_kprd') }}"><i class="fa fa-circle-o"></i>Validasi Soal UAS</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Perkuliahan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('rekap_perkuliahan_kprd') }}"><i class="fa fa-circle-o"></i> Rekap
                    Perkuliahan</a></li>
            <li><a href="{{ url('makul_diampu_kprd') }}"><i class="fa fa-circle-o"></i> <span>Matakuliah
                        diampu</span></a></li>
            <li><a href="{{ url('history_makul_kprd') }}"><i class="fa fa-circle-o"></i> <span>History Matakuliah
                        diampu</span></a></li>
            <li><a href="{{ url('total_mhs_matkul') }}"><i class="fa fa-circle-o"></i> <span>Total Mahasiswa Per
                        Matkul</span></a></li>
            <li><a href="{{ url('master_yudisium_kprd') }}"><i class="fa fa-circle-o"></i> Yudisium</a></li>
            <li><a href="{{ url('master_wisuda_kprd') }}"><i class="fa fa-circle-o"></i> Wisuda</a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('jadwal_prausta_kprd') }}"><i class="fa fa-circle-o"></i> Jadwal PraUSTA</a></li>
            <li class="treeview">
                <a href="#"><i class="fa fa-circle-o"></i> Pembimbing
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('pembimbing_pkl_kprd') }}"><i class="fa fa-circle-o"></i> PKL</a></li>
                    <li><a href="{{ url('pembimbing_sempro_kprd') }}"><i class="fa fa-circle-o"></i> SEMPRO</a>
                    </li>
                    <li><a href="{{ url('pembimbing_ta_kprd') }}"><i class="fa fa-circle-o"></i> TA</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Penguji</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('penguji_pkl_kprd') }}"><i class="fa fa-circle-o"></i> PKL</a></li>
                    <li><a href="{{ url('penguji_sempro_kprd') }}"><i class="fa fa-circle-o"></i> SEMPRO</a>
                    </li>
                    <li><a href="{{ url('penguji_ta_kprd') }}"><i class="fa fa-circle-o"></i> TA</a></li>
                </ul>
            </li>

            {{-- <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Jadwal</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('jadwal_seminar_prakerin_kprd') }}"><i class="fa fa-circle-o"></i> Seminar
                            Prakerin</a></li>
                    <li><a href="{{ url('jadwal_seminar_proposal_kprd') }}"><i class="fa fa-circle-o"></i> Seminar
                            Proposal</a></li>
                    <li><a href="{{ url('jadwal_sidang_ta_kprd') }}"><i class="fa fa-circle-o"></i> Sidang TA</a>
                    </li>
                </ul>
            </li> --}}
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Monitoring</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('bimbingan_prakerin') }}"><i class="fa fa-circle-o"></i> Bimbingan
                            Prakerin</a></li>
                    <li><a href="{{ url('bimbingan_sempro') }}"><i class="fa fa-circle-o"></i> Bimbingan SEMPRO</a>
                    </li>
                    <li><a href="{{ url('bimbingan_ta') }}"><i class="fa fa-circle-o"></i> Bimbingan TA</a></li>
                    <li><a href="{{ url('nilai_prakerin_kaprodi') }}"><i class="fa fa-circle-o"></i> <span>
                                Nilai Prakerin</span></a></li>
                    <li><a href="{{ url('nilai_sempro_kaprodi') }}"><i class="fa fa-circle-o"></i> <span>
                                Nilai SEMPRO</span></a></li>
                    <li><a href="{{ url('nilai_ta_kaprodi') }}"><i class="fa fa-circle-o"></i> <span>
                                Nilai TA</span></a></li>
                </ul>
            </li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Penangguhan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('penangguhan_mhs_dsn_kprd') }}"><i class="fa fa-circle-o"></i> <span>Mahasiswa
                        Bimbingan</span></a></li>
            <li><a href="{{ url('penangguhan_mhs_prodi') }}"><i class="fa fa-circle-o"></i> <span>Mahasiswa
                        Prodi</span></a></li>
        </ul>
    </li>
    <li>
        <a href="{{ url('data_pengajuan_keringanan_absen_kprd') }}">
            <i class="fa fa-list"></i> <span>Pengajuan Absen</span>
        </a>
    </li>
</ul>
