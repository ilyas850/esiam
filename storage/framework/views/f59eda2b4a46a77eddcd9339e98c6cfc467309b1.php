<style>
    /* Enhancement styles tanpa merubah struktur CSS yang ada */
    .enhanced-animations {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Profile enhancements */
    .profile-user-img {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 3px solid #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .profile-user-img:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }
    
    /* Box enhancements */
    .box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .box::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #3c8dbc, transparent);
        transition: left 0.5s;
    }
    
    .box:hover::before {
        left: 100%;
    }
    
    .box:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    /* Info box enhancements */
    .info-box {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .info-box::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }
    
    .info-box:hover::after {
        transform: translateX(100%);
    }
    
    .info-box:hover {
        transform: scale(1.02);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .info-box-icon {
        transition: transform 0.3s ease;
    }
    
    .info-box:hover .info-box-icon {
        transform: rotate(5deg) scale(1.1);
    }
    
    /* Button enhancements */
    .btn {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        transition: width 0.3s, height 0.3s, top 0.3s, left 0.3s;
    }
    
    .btn:hover::before {
        width: 300px;
        height: 300px;
        top: -150px;
        left: -150px;
    }
    
    /* Tab enhancements */
    .nav-tabs > li > a {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .nav-tabs > li > a::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: #3c8dbc;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }
    
    .nav-tabs > li.active > a::after,
    .nav-tabs > li > a:hover::after {
        width: 100%;
    }
    
    /* Table enhancements */
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(60, 141, 188, 0.05);
        transform: translateX(5px);
    }
    
    /* Label animations */
    .label {
        animation: subtle-pulse 2s infinite;
    }
    
    @keyframes  subtle-pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    /* List group enhancements */
    .list-group-item {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .list-group-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 3px;
        background: #3c8dbc;
        transform: translateX(-3px);
        transition: transform 0.3s ease;
    }
    
    .list-group-item:hover::before {
        transform: translateX(0);
    }
    
    .list-group-item:hover {
        background-color: rgba(60, 141, 188, 0.05);
        padding-left: 20px;
    }
    
    /* Product list enhancements */
    .products-list .item {
        transition: all 0.3s ease;
        position: relative;
    }
    
    .products-list .item:hover {
        background-color: rgba(60, 141, 188, 0.05);
        transform: translateX(10px);
        border-left: 3px solid #3c8dbc;
        padding-left: 10px;
    }
    
    /* Countdown enhancement */
    #waktumundur, #waktumunduredom {
        background: linear-gradient(135deg, #31266b 0%, #4a3c8a 100%);
        box-shadow: 0 4px 15px rgba(49, 38, 107, 0.3);
        animation: glow 2s ease-in-out infinite alternate;
    }
    
    @keyframes  glow {
        from { box-shadow: 0 4px 15px rgba(49, 38, 107, 0.3); }
        to { box-shadow: 0 6px 20px rgba(49, 38, 107, 0.5), 0 0 30px rgba(254, 197, 3, 0.2); }
    }
    
    .digit, .judul {
        text-shadow: 0 0 10px rgba(255,255,255,0.3);
    }
    
    /* Modal enhancements */
    .modal-content {
        animation: slideDown 0.3s ease;
    }
    
    @keyframes  slideDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Form control enhancements */
    .form-control {
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #3c8dbc;
        box-shadow: 0 0 0 2px rgba(60, 141, 188, 0.2);
        transform: translateY(-2px);
    }
    
    /* Fade in animations */
    .fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
    
    @keyframes  fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .slide-in-left {
        animation: slideInLeft 0.8s ease-out;
    }
    
    @keyframes  slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .slide-in-right {
        animation: slideInRight 0.8s ease-out;
    }
    
    @keyframes  slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    /* Widget user enhancements */
    .widget-user-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
    }
    
    .widget-user-image img {
        transition: all 0.3s ease;
    }
    
    .widget-user-image:hover img {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    }
    
    /* Icon hover effects */
    .fa, .glyphicon, .ion {
        transition: transform 0.3s ease;
    }
    
    .box-header:hover .fa,
    .box-header:hover .glyphicon {
        transform: rotate(360deg);
    }
    
    /* Description block enhancement */
    .description-block {
        transition: all 0.3s ease;
    }
    
    .description-block:hover {
        transform: scale(1.05);
    }
    
    /* Responsive adjustments */
    @media (max-width: 767px) {
        .products-list .item:hover {
            transform: translateX(5px);
        }
        
        .table tbody tr:hover {
            transform: translateX(2px);
        }
    }
    
    /* Loading animation for dynamic content */
    .loading-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }
    
    @keyframes  pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    /* Smooth scroll behavior */
    html {
        scroll-behavior: smooth;
    }
    
    /* Enhanced tooltips */
    [data-toggle="tooltip"] {
        position: relative;
    }
    
    /* Camera icon animation */
    .fa-camera {
        display: inline-block;
        transition: all 0.3s ease;
    }
    
    a:hover .fa-camera {
        transform: scale(1.2) rotate(-10deg);
        color: #3c8dbc;
    }
    
    /* Tab content fade effect */
    .tab-pane {
        animation: fadeIn 0.5s ease;
    }
    
    @keyframes  fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Box tools enhancement */
    .box-tools .btn-box-tool {
        transition: all 0.3s ease;
    }
    
    .box-tools .btn-box-tool:hover {
        transform: rotate(90deg);
        color: #3c8dbc;
    }
    
    /* Profile username animation */
    .profile-username {
        position: relative;
        display: inline-block;
    }
    
    .profile-username::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, #3c8dbc, #17a2b8);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .profile-username:hover::after {
        transform: scaleX(1);
    }
    
    /* Notification bell animation */
    .img-circle[src*="bell"] {
        animation: ring 2s ease-in-out infinite;
    }
    
    @keyframes  ring {
        0%, 100% { transform: rotate(0deg); }
        10%, 30% { transform: rotate(-10deg); }
        20%, 40% { transform: rotate(10deg); }
    }
</style>
<div class="row">
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <?php if($foto == null): ?>
                    <img class="profile-user-img img-responsive img-circle" src="<?php echo e(asset('/adminlte/img/default.jpg')); ?>"
                        alt="User profile picture">
                    <center>
                        <a href="/ganti_foto/<?php echo e($mhs->nim); ?>"><span class="fa fa-camera"></span>
                            Ganti foto</a>
                    </center>
                <?php else: ?>
                    <img class="profile-user-img img-responsive img-circle" src="<?php echo e(asset('/foto_mhs/' . $foto)); ?>"
                        alt="User profile picture">
                    <center>
                        <a href="/ganti_foto/<?php echo e($mhs->nim); ?>"><span class="fa fa-camera"></span>
                            Ganti foto</a>
                    </center>
                <?php endif; ?>
                <h3 class="profile-username text-center"><?php echo e($mhs->nama); ?></h3>
                <p class="text-muted text-center">
                    <center>
                        <h4><?php echo e($mhs->nim); ?></h4>
                    </center>
                </p>
                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                        <center>
                            <b><?php echo e($mhs->prodi); ?> - <?php echo e($mhs->konsentrasi); ?></b> <a class="pull-right"> <b></b></a>
                        </center>
                    </li>
                    <li class="list-group-item">
                        <center>
                            <b><?php echo e($mhs->kelas); ?> </b> <a class="pull-right"><b></b></a>
                        </center>
                    </li>
                    <li class="list-group-item">
                        <center>
                            <b><?php echo e($mhs->angkatan); ?></b> <a class="pull-right"></a>
                        </center>
                    </li>
                </ul>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">About Me</h3>
            </div>
            <div class="box-body">
                <strong><i class="fa fa-file-text-o margin-r-5"></i> Microsoft Teams (Username)</strong>
                <p><?php echo e($mhs->username); ?></p>
                <hr>

                <strong><i class="fa fa-file-text-o margin-r-5"></i> Microsoft Teams (Password)</strong>
                <p> <?php echo e($mhs->password); ?> </p>
                <hr>
                <strong><i class="fa fa-barcode margin-r-5"></i> NISN</strong>
                <p class="text-muted"><?php echo e($mhs->nisn); ?>

                    <a class="btn btn-warning btn-xs" data-toggle="modal"
                        data-target="#modalUpdateNisn<?php echo e($mhs->idstudent); ?>" title="klik untuk edit"><i
                            class="fa fa-edit"> </i></a>
                </p>
                <div class="modal fade" id="modalUpdateNisn<?php echo e($mhs->idstudent); ?>" tabindex="-1"
                    aria-labelledby="modalUpdateKaprodi" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update NISN</h5>
                            </div>
                            <div class="modal-body">
                                <form action="/put_nisn/<?php echo e($mhs->idstudent); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('put'); ?>
                                    <div class="form-group">
                                        <label>NISN Mahasiswa</label>
                                        <input class="form-control" type="number" name="nisn"
                                            value="<?php echo e($mhs->nisn); ?>">
                                    </div>
                                    <input type="hidden" name="updated_by" value="<?php echo e(Auth::user()->name); ?>">
                                    <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <strong><i class="fa fa-phone margin-r-5"></i> No. HP</strong>
                <p class="text-muted">
                    <?php if($mhs->hp_baru == null): ?>
                        <?php echo e($mhs->hp); ?>

                    <?php elseif($mhs->hp_baru != null): ?>
                        <?php echo e($mhs->hp_baru); ?>

                    <?php endif; ?>
                </p>
                <hr>
                <strong><i class="fa fa-envelope margin-r-5"></i> E-mail</strong>
                <p class="text-muted">
                    <?php if($mhs->email_baru == null): ?>
                        <?php echo e($mhs->email); ?>

                    <?php elseif($mhs->email_baru != null): ?>
                        <?php echo e($mhs->email_baru); ?>

                    <?php endif; ?>
                </p>
                <hr>
                <strong><i class="fa fa-cc-mastercard"></i> Virtual Account</strong>
                <p class="text-muted"><?php echo e($mhs->virtual_account); ?></p>
                <hr>

                <?php if($mhs->id_mhs == null): ?>
                    <a class="btn btn-success btn-block" href="/update/<?php echo e($mhs->idstudent); ?>"><i
                            class="fa fa-edit"></i> Edit No HP dan E-mail</a>
                <?php elseif($mhs->id_mhs != null): ?>
                    <a class="btn btn-success btn-block" href="/change/<?php echo e($mhs->id); ?>"><i class="fa fa-edit"></i>
                        Edit data No HP dan E-mail</a>
                <?php endif; ?>

            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">Aktivitas</a></li>
                <li><a href="#timeline" data-toggle="tab">Matakuliah Paket</a></li>
                <li><a href="#settings" data-toggle="tab">Matakuliah Mengulang</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="activity">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tahun Akademik</span>
                                    <span class="info-box-number">
                                        <?php echo e($tahun->periode_tahun); ?></span>
                                    <span class="info-box-number"><?php echo e($tipe->periode_tipe); ?></span>
                                    <a href="<?php echo e(asset('/Kalender Akademik/' . $tahun->file)); ?>" target="_blank"
                                        class="small-box-footer">Unduh Kalender Akademik <i
                                            class="fa fa-arrow-circle-right"></i></a>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-green"><i class="fa fa-calendar-check-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Jadwal KRS</span>
                                    <span class="info-box-number">
                                        <?php if($time->status == 0): ?>
                                            Jadwal Belum ada
                                        <?php elseif($time->status == 1): ?>
                                            <?php echo e(date(' d-m-Y', strtotime($time->waktu_awal))); ?> s/d
                                            <?php echo e(date(' d-m-Y', strtotime($time->waktu_akhir))); ?>

                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                    <h3 class="box-title">Waktu Pengisian KRS</h3>
                                </div>
                                <div class="box-body">
                                    <form role="form">
                                        <div id="waktumundur">
                                            <?php if($time->status != 0): ?>
                                                <span id="countdown"></span>
                                            <?php else: ?>
                                                Belum ada info perwalian
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                                <script type='text/javascript'>
                                    //<![CDATA[
                                    var target_date = new Date("<?php echo e($time->waktu_akhir); ?>").getTime();
                                    var days, hours, minutes, seconds;
                                    var countdown = document.getElementById("countdown");
                                    setInterval(function() {
                                        var current_date = new Date().getTime();
                                        var seconds_left = (target_date - current_date) / 1000;
                                        days = parseInt(seconds_left / 86400);
                                        seconds_left = seconds_left % 86400;
                                        hours = parseInt(seconds_left / 3600);
                                        seconds_left = seconds_left % 3600;
                                        minutes = parseInt(seconds_left / 60);
                                        seconds = parseInt(seconds_left % 60);
                                        countdown.innerHTML = days + " <span class=\'digit\'>hari</span> " + hours +
                                            " <span class=\'digit\'>jam</span> " + minutes + " <span class=\'digit\'>menit</span> " + seconds +
                                            " <span class=\'digit\'>detik </span> <br> <span class=\'judul\'>menuju Penutupan Pengisian KRS</span>";
                                    }, 1000);
                                    //]]>
                                </script>

                                <style scoped="" type="text/css">
                                    #waktumundur {

                                        background: #31266b;
                                        color: #fec503;
                                        font-size: 100%;
                                        text-transform: uppercase;
                                        text-align: center;
                                        padding: 20px 0;
                                        font-weight: bold;
                                        border-radius: 5px;
                                        line-height: 1.8em;
                                        font-family: Arial, sans-serif;
                                    }

                                    .digit {
                                        color: white
                                    }

                                    .judul {
                                        color: white
                                    }
                                </style>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <span class="glyphicon glyphicon-info-sign"></span>
                                    <h3 class="box-title">Waktu Pengisian EDOM</h3>
                                </div>
                                <div class="box-body">
                                    <form role="form">
                                        <div id="waktumunduredom">
                                            <?php if($edom->status != 0): ?>
                                                <span id="countdownedom"></span>
                                            <?php else: ?>
                                                Belum ada info pengisian EDOM
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <script type='text/javascript'>
                                //<![CDATA[
                                var target_date_edom = new Date("<?php echo e($edom->waktu_akhir); ?>").getTime();
                                var days_edom, hours_edom, minutes_edom, seconds_edom;
                                var countdownedom = document.getElementById("countdownedom");
                                setInterval(function() {
                                    var current_date_edom = new Date().getTime();
                                    var seconds_left_edom = (target_date_edom - current_date_edom) / 1000;
                                    days_edom = parseInt(seconds_left_edom / 86400);
                                    seconds_left_edom = seconds_left_edom % 86400;
                                    hours_edom = parseInt(seconds_left_edom / 3600);
                                    seconds_left_edom = seconds_left_edom % 3600;
                                    minutes_edom = parseInt(seconds_left_edom / 60);
                                    seconds_edom = parseInt(seconds_left_edom % 60);
                                    countdownedom.innerHTML = days_edom + " <span class=\'digit\'>hari</span> " + hours_edom +
                                        " <span class=\'digit\'>jam</span> " + minutes_edom + " <span class=\'digit\'>menit</span> " +
                                        seconds_edom +
                                        " <span class=\'digit\'>detik  </span> <br> <span class=\'judul\'>menuju Penutupan Pengisian EDOM</span>";
                                }, 1000);
                                //]]>
                            </script>
                            <style scoped="" type="text/css">
                                #waktumunduredom {

                                    background: #31266b;
                                    color: #fec503;
                                    font-size: 100%;
                                    text-transform: uppercase;
                                    text-align: center;
                                    padding: 20px 0;
                                    font-weight: bold;
                                    border-radius: 5px;
                                    line-height: 1.8em;
                                    font-family: Arial, sans-serif;
                                }

                                .digit {
                                    color: white
                                }

                                .judul {
                                    color: white
                                }
                            </style>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Informasi Terbaru</h3>
                                    <div class="box-tools pull-right">
                                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <ul class="products-list product-list-in-box">
                                        <?php $__currentLoopData = $info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="item">
                                                <div class="product-img">
                                                    <?php if($item->file != null): ?>
                                                        <img class="img-circle" src="/images/bell.jpg">
                                                    <?php else: ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="product-info">
                                                    <a href="/lihat/<?php echo e($item->id_informasi); ?>"
                                                        class="product-title"><?php echo e($item->judul); ?>

                                                        <span class="label label-info pull-right">
                                                            <?php echo e(date('l, d F Y', strtotime($item->created_at))); ?><br>
                                                            <?php echo e($item->created_at->diffForHumans()); ?>

                                                        </span></a>
                                                    <span class="product-description">
                                                        <?php echo e($item->deskripsi); ?>

                                                    </span>
                                                </div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                                <div class="box-footer text-center">
                                    <a href="/lihat_semua" class="uppercase">Lihat Semua Informasi</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="timeline">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Paket Matakuliah</h3>
                                </div>
                                <div class="box-body ">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <center>No</center>
                                                </th>
                                                <th>
                                                    <center>Kurikulum</center>
                                                </th>
                                                <th>
                                                    <center>Prodi</center>
                                                </th>
                                                <th>
                                                    <center>Semester</center>
                                                </th>
                                                <th>
                                                    <center>Angkatan</center>
                                                </th>
                                                <th>
                                                    <center>Matakuliah</center>
                                                </th>
                                                <th>
                                                    <center>Status</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td align="center"><?php echo e($no++); ?></td>
                                                    <td align="center"><?php echo e($item->nama_kurikulum); ?></td>
                                                    <td align="center"><?php echo e($item->prodi); ?></td>
                                                    <td align="center"><?php echo e($item->semester); ?></td>
                                                    <td align="center"><?php echo e($item->angkatan); ?></td>
                                                    <td><?php echo e($item->kode); ?> / <?php echo e($item->makul); ?></td>
                                                    <td align="center">
                                                        <?php if($item->id_studentrecord != null): ?>
                                                            <span class="label label-success">diambil</span>
                                                        <?php else: ?>
                                                            <span class="label label-warning">belum</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="tab-pane" id="settings">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title">Matakuliah Wajib Ulang</h3>
                                </div>
                                <div class="box-body ">
                                    <table id="example3" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <center>No</center>
                                                </th>
                                                <th>
                                                    <center>Kurikulum</center>
                                                </th>
                                                <th>
                                                    <center>Prodi</center>
                                                </th>
                                                <th>
                                                    <center>Semester</center>
                                                </th>
                                                <th>
                                                    <center>Angkatan</center>
                                                </th>
                                                <th>
                                                    <center>Matakuliah</center>
                                                </th>
                                                <th>
                                                    <center>Nilai</center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php $__currentLoopData = $data_mengulang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td align="center"><?php echo e($no++); ?></td>
                                                    <td align="center"><?php echo e($item->nama_kurikulum); ?></td>
                                                    <td align="center"><?php echo e($item->prodi); ?></td>
                                                    <td align="center"><?php echo e($item->semester); ?></td>
                                                    <td align="center"><?php echo e($item->angkatan); ?></td>
                                                    <td><?php echo e($item->kode); ?> / <?php echo e($item->makul); ?></td>
                                                    <td align="center">
                                                        <?php echo e($item->nilai_AKHIR); ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add fade-in class to elements as they appear
    const boxes = document.querySelectorAll('.box');
    boxes.forEach((box, index) => {
        setTimeout(() => {
            box.classList.add('fade-in');
        }, index * 100);
    });
    
    // Add hover sound effect (optional)
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Smooth number counting animation for info boxes
    const countUp = (element, target) => {
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current);
            }
        }, 30);
    };
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Add ripple effect to buttons
    $('.btn').on('click', function(e) {
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        this.appendChild(ripple);
        
        const x = e.clientX - e.target.offsetLeft;
        const y = e.clientY - e.target.offsetTop;
        
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
});
</script>
<?php /**PATH /var/www/html/resources/views/layouts/mhs_home.blade.php ENDPATH**/ ?>