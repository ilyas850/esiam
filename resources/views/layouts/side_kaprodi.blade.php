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
            <li><a href="{{ url('mhs_aktif') }}"><i class="fa fa-circle-o"></i> <span>Mahasiswa Aktif</span></a>
            </li>
            <li><a href="{{ url('mhs_bim_kprd') }}"><i class="fa fa-circle-o"></i> <span>Mahasiswa
                        Bimbingan</span></a></li>
            <li><a href="{{ url('data_ipk_kprd') }}"><i class="fa fa-circle-o"></i> Data IPK Mahasiswa Aktif</a>
            </li>
            <li><a href="{{ url('nilai_mhs_kprd') }}"><i class="fa fa-circle-o"></i> Rekap Nilai Mahasiswa</a>
            </li>
            <li><a href="{{ url('rekap_perkuliahan_kprd') }}"><i class="fa fa-circle-o"></i> Rekap
                    Perkuliahan</a></li>
            <li><a href="{{ url('soal_uts_kprd') }}"><i class="fa fa-circle-o"></i> Soal UTS</a></li>
            <li><a href="{{ url('soal_uas_kprd') }}"><i class="fa fa-circle-o"></i> Soal UAS</a></li>
        </ul>
    </li>
    <li><a href="{{ url('val_krs_kprd') }}"><i class="fa fa-check-square"></i> <span>Validasi KRS</span></a>
    </li>
    <li><a href="{{ url('makul_diampu_kprd') }}"><i class="fa  fa-users"></i> <span>Matakuliah
                diampu</span></a></li>
    <li><a href="{{ url('history_makul_kprd') }}"><i class="fa  fa-list"></i> <span>History Matakuliah
                diampu</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('pembimbing_pkl_kprd') }}"><i class="fa fa-circle-o"></i> Pembimbing PKL</a></li>
            <li><a href="{{ url('pembimbing_sempro_kprd') }}"><i class="fa fa-circle-o"></i> Pembimbing SEMPRO</a>
            </li>
            <li><a href="{{ url('pembimbing_ta_kprd') }}"><i class="fa fa-circle-o"></i> Pembimbing TA</a></li>
            <li><a href="{{ url('penguji_pkl_kprd') }}"><i class="fa fa-circle-o"></i> Penguji PKL</a></li>
            <li><a href="{{ url('penguji_sempro_kprd') }}"><i class="fa fa-circle-o"></i> Penguji SEMPRO</a></li>
            <li><a href="{{ url('penguji_ta_kprd') }}"><i class="fa fa-circle-o"></i> Penguji TA</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Monitoring PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('bimbingan_prakerin') }}"><i class="fa fa-circle-o"></i> Bimbingan Prakerin</a></li>
            <li><a href="{{ url('bimbingan_sempro') }}"><i class="fa fa-circle-o"></i> Bimbingan SEMPRO</a></li>
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
