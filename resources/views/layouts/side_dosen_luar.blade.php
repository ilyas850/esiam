<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Dosen</li>
    <li>
        <a href="{{ url('home') }}">
            <i class="fa fa-dashboard"></i> <span>Halaman Utama</span>
        </a>
    </li>
    <li><a href="{{ url('makul_diampu') }}"><i class="fa  fa-users"></i> <span>Matakuliah diampu</span></a>
    </li>
    <li><a href="{{ url('history_makul_dsnlr') }}"><i class="fa  fa-list"></i> <span>History Matakuliah
                diampu</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('pembimbing_pkl_dsnlr') }}"><i class="fa fa-circle-o"></i> Pembimbing PKL</a></li>
            <li><a href="{{ url('pembimbing_sempro_dsnlr') }}"><i class="fa fa-circle-o"></i> Pembimbing SEMPRO</a>
            </li>
            <li><a href="{{ url('pembimbing_ta_dsnlr') }}"><i class="fa fa-circle-o"></i> Pembimbing TA</a></li>
            <li><a href="{{ url('penguji_pkl_dsnlr') }}"><i class="fa fa-circle-o"></i> Penguji PKL</a></li>
            <li><a href="{{ url('penguji_sempro_dsnlr') }}"><i class="fa fa-circle-o"></i> Penguji SEMPRO</a></li>
            <li><a href="{{ url('penguji_ta_dsnlr') }}"><i class="fa fa-circle-o"></i> Penguji TA</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Jadwal PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('jadwal_seminar_prakerin_luar') }}"><i class="fa fa-circle-o"></i> Seminar
                    Prakerin</a></li>
            <li><a href="{{ url('jadwal_seminar_proposal_luar') }}"><i class="fa fa-circle-o"></i> Seminar
                    Proposal</a></li>
            <li><a href="{{ url('jadwal_sidang_ta_luar') }}"><i class="fa fa-circle-o"></i> Sidang TA</a></li>
        </ul>
    </li>
    <li><a href="{{ url('upload_soal_dsn_luar') }}"><i class="fa  fa-list"></i> <span>Upload Soal Ujian</span></a>
    </li>
</ul>
