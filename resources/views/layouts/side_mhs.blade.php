<ul class="sidebar-menu" data-widget="tree">
    <li class="header">MENU MAHASISWA</li>
    <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>KRS & KHS</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('isi_krs') }}"><i class="fa fa-circle-o"></i> KRS</a></li>
            <li><a href="{{ url('khs') }}"><i class="fa fa-circle-o"></i> KHS</a></li>
            {{-- <li><a href="{{ url('khs_mid') }}"><i class="fa fa-circle-o"></i> KHS Mid-term</a></li>
            <li><a href="{{ url('khs_final') }}"><i class="fa fa-circle-o"></i> KHS Final-term</a></li> --}}
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-calendar"></i> <span>Lihat Jadwal</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('jadwal') }}"><i class="fa fa-circle-o"></i> <span>Jadwal Kuliah</span></a></li>
            <li><a href="{{ url('jdl_uts') }}"><i class="fa fa-circle-o"></i> <span>Jadwal UTS</span></a></li>
            <li><a href="{{ url('jdl_uas') }}"><i class="fa fa-circle-o"></i> <span>Jadwal UAS</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list-alt"></i> <span>Kartu Ujian</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('kartu_uts') }}"><i class="fa fa-circle-o"></i> <span>Kartu UTS</span></a></li>
            <li><a href="{{ url('kartu_uas') }}"><i class="fa fa-circle-o"></i> <span>Kartu UAS</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-calendar-check-o"></i> <span>PraUSTA</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('seminar_prakerin') }}"><i class="fa fa-circle-o"></i> <span>Seminar
                        Prakerin</span></a></li>
            <li><a href="{{ url('seminar_proposal') }}"><i class="fa fa-circle-o"></i> <span>Seminar
                        Proposal</span></a></li>
            <li><a href="{{ url('sidang_ta') }}"><i class="fa fa-circle-o"></i> <span>Sidang TA</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-money"></i> <span>Keuangan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('record_biaya') }}"><i class="fa fa-circle-o"></i> <span>Record Biaya
                        Kuliah</span></a>
            </li>
            <li><a href="{{ url('data_biaya') }}"><i class="fa fa-circle-o"></i> <span>Data Biaya
                        Kuliah</span></a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-edit"></i> <span>Pendaftaran</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{ url('yudisium') }}"><i class="fa fa-circle-o"></i> <span>Yudisium</span></a>
            </li>
            <li><a href="{{ url('wisuda') }}"><i class="fa fa-circle-o"></i> <span>Wisuda</span></a>
            </li>
        </ul>
    </li>
    <li><a href="{{ url('dosbing') }}"><i class="fa fa-user"></i> <span>Dosen Pembimbing</span></a></li>
    <li><a href="{{ url('kuisioner') }}"><i class="fa fa-pencil-square-o"></i> <span>Kuisioner</span></a>
    </li>
    {{-- <li><a href="{{ url('nilai') }}"><i class="fa fa-file-text-o"></i> <span>Lihat Nilai</span></a></li> --}}
    <li><a href="{{ url('upload_sertifikat') }}"><i class="fa fa-file"></i> <span>Upload Sertifikat</span></a>
    </li>

    {{-- <li><a href="{{ url('keuangan') }}"><i class="fa fa-money"></i> <span>Keuangan</span></a></li> --}}
    <li><a href="{{ url('pedoman_akademik') }}"><i class="fa fa-book"></i> <span>Pedoman</span></a></li>
</ul>
