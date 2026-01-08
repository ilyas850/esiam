<ul class="sidebar-menu" data-widget="tree">
    <li class="header">MENU MAHASISWA</li>
    <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Pedoman & SOP</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('pedoman_akademik')); ?>"><i class="fa fa-circle-o"></i> <span>Pedoman</span></a></li>
            <li><a href="<?php echo e(url('sop')); ?>"><i class="fa fa-circle-o"></i> <span>S.O.P</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Dosen</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('dosbing')); ?>"><i class="fa fa-circle-o"></i> <span>Dosen Pembimbing</span></a></li>
            <li><a href="<?php echo e(url('dosen_mip')); ?>"><i class="fa fa-circle-o"></i> <span>Dosen MIP</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Perkuliahan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li class="treeview">
                <a href="#"><i class="fa fa-circle-o"></i> KRS & KHS
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo e(url('krs')); ?>"><i class="fa fa-circle-o"></i>KRS</a></li>
                    <li><a href="<?php echo e(url('khs')); ?>"><i class="fa fa-circle-o"></i>KHS</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Lihat Jadwal</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo e(url('jadwal')); ?>"><i class="fa fa-circle-o"></i> <span>Jadwal Kuliah</span></a></li>
                    <li><a href="<?php echo e(url('jdl_uts')); ?>"><i class="fa fa-circle-o"></i> <span>Jadwal UTS</span></a></li>
                    <li><a href="<?php echo e(url('jdl_uas')); ?>"><i class="fa fa-circle-o"></i> <span>Jadwal UAS</span></a></li>
                </ul>
            </li>
            <li><a href="<?php echo e(url('absen_ujian_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Absen Ujian</span></a>
            <li><a href="<?php echo e(url('soal_ujian_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Soal Ujian</span></a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-circle-o"></i> <span>Keuangan</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="<?php echo e(url('record_biaya')); ?>"><i class="fa fa-circle-o"></i> <span>Record Biaya
                                Kuliah</span></a>
                    </li>
                    <li><a href="<?php echo e(url('data_biaya')); ?>"><i class="fa fa-circle-o"></i> <span>Data Biaya
                                Kuliah</span></a>
                    </li>
                </ul>
            </li>
            <li><a href="<?php echo e(url('history_perkuliahan')); ?>"><i class="fa fa-circle-o"></i> <span>History
                        Perkuliahan</span></a>
            </li>
            <li><a href="<?php echo e(url('bim_perwalian')); ?>"><i class="fa fa-circle-o"></i> <span>Bimbingan
                        Perwalian</span></a>
            </li>
        </ul>
    </li>

    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>PraUSTA - D3</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('seminar_prakerin')); ?>"><i class="fa fa-circle-o"></i> <span>Seminar
                        PKL</span></a></li>
            <li><a href="<?php echo e(url('seminar_proposal')); ?>"><i class="fa fa-circle-o"></i> <span>Seminar
                        Proposal</span></a></li>
            <li><a href="<?php echo e(url('sidang_ta')); ?>"><i class="fa fa-circle-o"></i> <span>Sidang TA</span></a></li>

        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Magang & Skripsi - D4</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('magang_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Magang 1</span></a></li>
            <li><a href="<?php echo e(url('magang2_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Magang 2</span></a></li>
            <li><a href="<?php echo e(url('sempro_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Seminar
                        Proposal</span></a></li>
            <li><a href="<?php echo e(url('skripsi_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Skripsi</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Wisuda</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('yudisium')); ?>"><i class="fa fa-circle-o"></i> <span>Pendaftaran Yudisium</span></a>
            </li>
            <li><a href="<?php echo e(url('wisuda')); ?>"><i class="fa fa-circle-o"></i> <span>Pendaftaran Wisuda</span></a>
            </li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Pengajuan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('penangguhan_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Penangguhan</span></a>
            </li>
            <li><a href="<?php echo e(url('beasiswa_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Beasiswa</span></a>
            </li>
            <li><a href="<?php echo e(url('cuti_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Cuti</span></a>
            </li>
            <li><a href="<?php echo e(url('mengundurkan_diri_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Mengundurkan
                        Diri</span></a>
            </li>
            <li><a href="<?php echo e(url('perpindahan_kelas_mhs')); ?>"><i class="fa fa-circle-o"></i> <span>Pindah
                        Kelas</span></a>
            </li>
        </ul>
    </li>
    <li><a href="<?php echo e(url('kuisioner')); ?>"><i class="fa fa-pencil-square-o"></i> <span>Kuisioner</span></a>
    </li>
    
    <li><a href="<?php echo e(url('upload_sertifikat')); ?>"><i class="fa fa-file"></i> <span>Upload Sertifikat</span></a>
    </li>
    <li><a href="<?php echo e(url('pengalaman_kerja')); ?>"><i class="fa fa-file"></i> <span>Pengalaman Kerja</span></a>
    </li>

    <li><a href="<?php echo e(url('kritiksaran_mhs')); ?>"><i class="fa fa-file"></i> <span>Kritik & Saran</span></a>
    </li>
    

</ul>
<?php /**PATH /var/www/html/resources/views/layouts/side_mhs.blade.php ENDPATH**/ ?>