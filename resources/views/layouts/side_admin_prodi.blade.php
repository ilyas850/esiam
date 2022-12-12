<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Admin Prodi</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Pembimbing</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('dospem_pkl') }}"><i class="fa fa-circle-o"></i> <span>Pembimbing
                        PKL</span></a></li>
            <li><a href="{{ url('dospem_sempro_ta') }}"><i class="fa fa-circle-o"></i> <span>Pembimbing
                        SEMPRO & TA</span></a></li>
            
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Kurikulum</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('setting_standar_kurikulum') }}"><i class="fa fa-circle-o"></i> <span>Setting Standar
                Kurikulum</span></a></li>
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
            <li><a href="{{ url('rekap_nilai_mhs') }}"><i class="fa fa-circle-o"></i> Rekap Nilai Mahasiswa</a></li>
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
            <li><a href="{{ url('jadwal_kuliah_prodi') }}"><i class="fa fa-circle-o"></i> Jadwal Perkuliahan</a></li>
        </ul>
    </li>
</ul>
