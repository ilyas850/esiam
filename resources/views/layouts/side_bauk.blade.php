<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu BAUK</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Keuangan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('kategori_penangguhan_bauk') }}"><i class="fa fa-circle-o"></i> <span>Penangguhan</span></a></li>
            <li><a href="{{ url('waktu_penangguhan') }}"><i class="fa fa-circle-o"></i> <span>Setting Penangguhan</span></a></li>
        </ul>
    </li>

</ul>
