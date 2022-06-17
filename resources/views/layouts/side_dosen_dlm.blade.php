<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Dosen</li>
    <li>
        <a href="{{ url('home') }}">
            <i class="fa fa-dashboard"></i> <span>Halaman Utama</span>
        </a>
    </li>
    <li><a href="{{ url('mhs_bim') }}"><i class="fa  fa-users"></i> <span>Mahasiswa Bimbingan</span></a></li>
    <li><a href="{{ url('val_krs') }}"><i class="fa fa-check-square"></i> <span>Validasi KRS</span></a></li>
    <li><a href="{{ url('makul_diampu_dsn') }}"><i class="fa  fa-users"></i> <span>Matakuliah diampu</span></a>
    </li>
    <li><a href="{{ url('history_makul_dsn') }}"><i class="fa  fa-list"></i> <span>History Matakuliah
                diampu</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('pembimbing_pkl') }}"><i class="fa fa-circle-o"></i> Pembimbing PKL</a></li>
            <li><a href="{{ url('pembimbing_sempro') }}"><i class="fa fa-circle-o"></i> Pembimbing SEMPRO</a></li>
            <li><a href="{{ url('pembimbing_ta') }}"><i class="fa fa-circle-o"></i> Pembimbing TA</a></li>
            <li><a href="{{ url('penguji_pkl') }}"><i class="fa fa-circle-o"></i> Penguji PKL</a></li>
            <li><a href="{{ url('penguji_sempro') }}"><i class="fa fa-circle-o"></i> Penguji SEMPRO</a></li>
            <li><a href="{{ url('penguji_ta') }}"><i class="fa fa-circle-o"></i> Penguji TA</a></li>
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
            <li><a href="{{ url('jadwal_seminar_prakerin_dlm') }}"><i class="fa fa-circle-o"></i> Seminar
                    Prakerin</a></li>
            <li><a href="{{ url('jadwal_seminar_proposal_dlm') }}"><i class="fa fa-circle-o"></i> Seminar
                    Proposal</a></li>
            <li><a href="{{ url('jadwal_sidang_ta_dlm') }}"><i class="fa fa-circle-o"></i> Sidang TA</a></li>
        </ul>
    </li>
    <li><a href="{{ url('upload_soal_dsn_dlm') }}"><i class="fa  fa-list"></i> <span>Upload Soal Ujian</span></a>
    </li>
</ul>
