<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Yayasan</li>
    <li>
        <a href="{{ url('yayasan_home') }}">
            <i class="fa fa-dashboard"></i> <span>Halaman Utama</span>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-users"></i><span>Data Dosen</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('yayasan/dosen-tetap') }}"><i class="fa fa-circle-o"></i> Dosen Tetap</a></li>
            <li><a href="{{ url('yayasan/dosen-tidak-tetap') }}"><i class="fa fa-circle-o"></i> Dosen Tidak Tetap</a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-graduation-cap"></i><span>Data Mahasiswa</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('yayasan/mahasiswa-aktif') }}"><i class="fa fa-circle-o"></i> Mahasiswa Aktif</a></li>
            <li><a href="{{ url('yayasan/mahasiswa-tidak-aktif') }}"><i class="fa fa-circle-o"></i> Mahasiswa Tidak
                    Aktif</a></li>
        </ul>
    </li>
</ul>