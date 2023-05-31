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
            <li><a href="{{ url('data_krs_wadir1') }}"><i class="fa fa-circle-o"></i> <span>Data KRS</span></a></li>
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
            <li><a href="{{ url('bimbingan_prakerin_wadir') }}"><i class="fa fa-circle-o"></i> Bimbingan Prakerin</a>
            </li>
            <li><a href="{{ url('bimbingan_sempro_wadir') }}"><i class="fa fa-circle-o"></i> Bimbingan SEMPRO</a></li>
            <li><a href="{{ url('bimbingan_ta_wadir') }}"><i class="fa fa-circle-o"></i> Bimbingan TA</a></li>
            <li><a href="{{ url('nilai_prakerin_wadir') }}"><i class="fa fa-circle-o"></i> <span>
                        Nilai Prakerin</span></a></li>
            <li><a href="{{ url('nilai_sempro_wadir') }}"><i class="fa fa-circle-o"></i> <span>
                        Nilai SEMPRO</span></a></li>
            <li><a href="{{ url('nilai_ta_wadir') }}"><i class="fa fa-circle-o"></i> <span>
                        Nilai TA</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Nilai</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('rekap_nilai_mhs_wadir') }}"><i class="fa fa-circle-o"></i> Data Nilai Mahasiswa</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Pembayaran</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('rekap_pembayaran_mhs') }}"><i class="fa fa-circle-o"></i> Data Pembayaran Mahasiswa</a></li>
        </ul>
    </li>
</ul>
