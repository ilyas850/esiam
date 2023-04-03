<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Dosen</li>
    <li>
        <a href="{{ url('home') }}">
            <i class="fa fa-dashboard"></i> <span>Halaman Utama</span>
        </a>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Pedoman & SOP</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('pedoman_akademik_dsn_luar') }}"><i class="fa fa-circle-o"></i> <span>Pedoman
                        Umum</span></a></li>
            <li><a href="{{ url('pedoman_khusus_dsn_luar') }}"><i class="fa fa-circle-o"></i> <span>Pedoman
                        Khusus</span></a></li>
            <li><a href="{{ url('sop_dsn_luar') }}"><i class="fa fa-circle-o"></i> <span>S.O.P</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Pengajaran</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('makul_diampu') }}"><i class="fa fa-circle-o"></i> <span>Matakuliah diampu</span></a>
            </li>
            <li><a href="{{ url('history_makul_dsnlr') }}"><i class="fa fa-circle-o"></i> <span>History MK
                        diampu</span></a></li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('jadwal_prausta_dsn_luar') }}"><i class="fa fa-circle-o"></i> Jadwal PraUSTA</a></li>
            <li><a href="{{ url('pembimbing_pkl_dsnlr') }}"><i class="fa fa-circle-o"></i> Pembimbing PKL</a></li>
            <li><a href="{{ url('pembimbing_sempro_dsnlr') }}"><i class="fa fa-circle-o"></i> Pembimbing SEMPRO</a>
            </li>
            <li><a href="{{ url('pembimbing_ta_dsnlr') }}"><i class="fa fa-circle-o"></i> Pembimbing TA</a></li>
            <li><a href="{{ url('penguji_pkl_dsnlr') }}"><i class="fa fa-circle-o"></i> Penguji PKL</a></li>
            <li><a href="{{ url('penguji_sempro_dsnlr') }}"><i class="fa fa-circle-o"></i> Penguji SEMPRO</a></li>
            <li><a href="{{ url('penguji_ta_dsnlr') }}"><i class="fa fa-circle-o"></i> Penguji TA</a></li>
        </ul>
    </li>
    <li>
        <a href="{{ url('data_pengajuan_keringanan_absen_luar') }}">
            <i class="fa fa-list"></i> <span>Pengajuan Absen</span>
        </a>
    </li>
    {{-- <li class="treeview">
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
    </li> --}}

</ul>
