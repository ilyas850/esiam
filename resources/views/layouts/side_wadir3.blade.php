<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu WADIR 3</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Mahasiswa</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/master_mhs_aktif_wadir3"><i class="fa fa-circle-o"></i> Mahasiswa Aktif</a></li>
            <li><a href="{{ url('master_sertifikat_mhs_wadir3') }}"><i class="fa fa-circle-o"></i>Sertifikat
                    Mahasiswa</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Kritik & Saran</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('data_kritiksaran') }}"><i class="fa fa-circle-o"></i> <span>Kritik &
                        Saran</span></a></li>
        </ul>
    </li>
</ul>
