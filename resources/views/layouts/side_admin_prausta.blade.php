<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu PraUSTA</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Data</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('nilai_prausta') }}"><i class="fa fa-circle-o"></i> <span>Nilai
                        PraUSTA</span></a></li>
            <li><a href="{{ url('data_prakerin') }}"><i class="fa fa-circle-o"></i> <span>Data
                        Prakerin</span></a></li>
            <li><a href="{{ url('data_sempro') }}"><i class="fa fa-circle-o"></i> <span>Data
                        Seminar Proposal</span></a></li>
            <li><a href="{{ url('data_ta') }}"><i class="fa fa-circle-o"></i> <span>Data
                        Tugas Akhir</span></a></li>
        </ul>
    </li>
</ul>
