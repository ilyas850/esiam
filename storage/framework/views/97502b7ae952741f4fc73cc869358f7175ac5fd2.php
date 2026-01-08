<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu BAUK</li>
    <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Master Keuangan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('kategori_penangguhan_bauk')); ?>"><i class="fa fa-circle-o"></i>
                    <span>Penangguhan</span></a></li>
            <li><a href="<?php echo e(url('pengajuan_beasiswa_by_mhs')); ?>"><i class="fa fa-circle-o"></i>
                    <span>Beasiswa</span></a></li>
            <li><a href="<?php echo e(url('uang_saku_pkl')); ?>"><i class="fa fa-circle-o"></i>
                    <span>Uang Saku Mhs PKL</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Setting</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('waktu_penangguhan')); ?>"><i class="fa fa-circle-o"></i> <span>Waktu Penangguhan</span></a>
            </li>
            <li><a href="<?php echo e(url('waktu_beasiswa')); ?>"><i class="fa fa-circle-o"></i> <span>Waktu Beasiswa</span></a></li>
            <li><a href="<?php echo e(url('min_biaya')); ?>"><i class="fa fa-circle-o"></i> <span>Minimal Pembayaran</span></a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list"></i> <span>Master Pengajuan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('data_cuti_bauk')); ?>"><i class="fa fa-circle-o"></i> <span>Cuti</span></a>
            </li>
            <li><a href="<?php echo e(url('data_mengundurkan_diri_bauk')); ?>"><i class="fa fa-circle-o"></i> <span>Mengundurkan diri</span></a></li>
            <li><a href="<?php echo e(url('data_pindah_kelas_bauk')); ?>"><i class="fa fa-circle-o"></i> <span>Pindah Kelas</span></a></li>
        </ul>
    </li>
</ul>
<?php /**PATH /var/www/html/resources/views/layouts/side_bauk.blade.php ENDPATH**/ ?>