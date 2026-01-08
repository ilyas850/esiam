<ul class="sidebar-menu" data-widget="tree">
    <li class="header">Menu Gugus Mutu</li>
    <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> <span>Halaman Utama</span></a></li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-database"></i> <span>Master Perkuliahan</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('data_bap_gugusmutu')); ?>"><i class="fa fa-circle-o"></i> <span>
                        Data BAP</span></a></li>
        </ul>
    </li>
    <li class="treeview">
    <a href="#">
            <i class="fa fa-database"></i> <span>Master EDOM</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('data_rekapitulasi_edom_gugusmutu')); ?>"><i class="fa fa-circle-o"></i> <span>
                        Rekapitulasi EDOM</span></a></li>
            <li><a href="<?php echo e(url('data_absensi_edom_gugusmutu')); ?>"><i class="fa fa-circle-o"></i> <span>
                        Absensi EDOM</span></a></li>
                        
        </ul>
    </li>
    <li class="treeview">
    <a href="#">
            <i class="fa fa-database"></i> <span>Master Kuisioner</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="<?php echo e(url('data_report_kuisioner_gugusmutu')); ?>"><i class="fa fa-circle-o"></i> <span>
                        Report Kuisioner</span></a></li>
                        
        </ul>
    </li>
</ul>
<?php /**PATH /var/www/html/resources/views/layouts/side_gugus_mutu.blade.php ENDPATH**/ ?>