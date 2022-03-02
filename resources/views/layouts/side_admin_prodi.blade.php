<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Admin Prodi</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Data</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('dospem_pkl') }}"><i class="fa fa-circle-o"></i> <span>Dosen Pembimbing
                        PKL</span></a></li>
            <li><a href="{{ url('dospem_sempro') }}"><i class="fa fa-circle-o"></i> <span>Dosen Pembimbing
                        SEMPRO</span></a></li>
            <li><a href="{{ url('dospem_ta') }}"><i class="fa fa-circle-o"></i> <span>Dosen Pembimbing
                        TA</span></a></li>
        </ul>
    </li>
</ul>
