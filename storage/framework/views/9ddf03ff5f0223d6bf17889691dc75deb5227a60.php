<style>
        /* Additional Dosen Home specific styles */
        
        /* Enhanced Nav Tabs */
        .nav-tabs-custom {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            background: white;
            overflow: hidden;
            animation: fadeInUp 0.8s ease-out;
        }

        .nav-tabs-custom .nav-tabs {
            border-bottom: none;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            margin: 0;
            border-radius: 15px 15px 0 0;
        }

        .nav-tabs-custom .nav-tabs > li {
            margin-bottom: 0;
            transform: translateY(0);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-tabs-custom .nav-tabs > li:hover {
            transform: translateY(-2px);
        }

        .nav-tabs-custom .nav-tabs > li > a {
            border-radius: 10px 10px 0 0;
            border: none;
            color: #6c757d;
            font-weight: 600;
            padding: 15px 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-tabs-custom .nav-tabs > li > a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0,123,255,0.1), transparent);
            transition: left 0.5s;
        }

        .nav-tabs-custom .nav-tabs > li > a:hover::before {
            left: 100%;
        }

        .nav-tabs-custom .nav-tabs > li > a:hover {
            background: rgba(0,123,255,0.05);
            border: none;
            color: #007bff;
        }

        .nav-tabs-custom .nav-tabs > li.active > a {
            background: white;
            color: #007bff;
            border: none;
            box-shadow: 0 -3px 10px rgba(0,123,255,0.2);
            position: relative;
        }

        .nav-tabs-custom .nav-tabs > li.active > a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #007bff, #0056b3);
        }

        .tab-content {
            padding: 25px;
            background: white;
            border-radius: 0 0 15px 15px;
        }

        /* Enhanced Widget User with Table */
        .widget-user {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            animation: slideInLeft 0.8s ease-out 0.2s both;
        }

        .widget-user:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .widget-user-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
            position: relative;
            padding: 25px 15px !important;
            overflow: hidden;
        }

        .widget-user-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        .widget-user-username, .widget-user-desc {
            position: relative;
            z-index: 2;
            color: white !important;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .widget-user-image img {
            border: 4px solid white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: transform 0.3s ease;
        }

        .widget-user:hover .widget-user-image img {
            transform: scale(1.05);
        }

        /* Enhanced Table in Widget */
        .widget-user .table {
            margin: 0;
            background: white;
        }

        .widget-user .table th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border: none;
            padding: 12px 15px;
        }

        .widget-user .table td {
            padding: 12px 15px;
            border: none;
            border-bottom: 1px solid #f1f3f4;
            transition: background-color 0.2s ease;
        }

        .widget-user .table tbody tr:hover {
            background: rgba(0,123,255,0.02);
        }

        .widget-user .table tbody tr:hover td {
            color: #007bff;
        }

        /* Enhanced Info Boxes */
        .info-box {
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
            border: none;
            overflow: hidden;
            position: relative;
            margin-bottom: 20px;
        }

        .col-md-6:nth-child(1) .info-box { animation: slideInRight 0.8s ease-out 0.3s both; }
        .col-md-6:nth-child(2) .info-box { animation: slideInRight 0.8s ease-out 0.4s both; }

        .info-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
        }

        .info-box-icon {
            border-radius: 12px 0 25px 0;
            position: relative;
            overflow: hidden;
        }

        .info-box-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .info-box:hover .info-box-icon::before {
            left: 100%;
        }

        .info-box-icon i {
            font-size: 24px;
            transition: transform 0.3s ease;
        }

        .info-box:hover .info-box-icon i {
            transform: scale(1.1) rotate(5deg);
        }

        .small-box-footer {
            background: rgba(0,0,0,0.1);
            color: white !important;
            padding: 8px 15px;
            text-decoration: none;
            font-size: 12px;
            transition: all 0.3s ease;
            display: block;
            margin-top: 10px;
            border-radius: 20px;
        }

        .small-box-footer:hover {
            background: rgba(0,0,0,0.2);
            color: white !important;
            text-decoration: none;
            transform: translateX(5px);
        }

        /* Enhanced Tables */
        .table-bordered {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        .table thead th {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            font-weight: 600;
            text-align: center;
            padding: 15px 10px;
            position: relative;
        }

        .table thead th::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
            animation: shimmer 2s ease-in-out infinite;
        }

        .table tbody td {
            padding: 12px 10px;
            border-color: #f1f3f4;
            transition: all 0.2s ease;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
            transform: scale(1.01);
            box-shadow: 0 3px 10px rgba(0,123,255,0.1);
        }

        .table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .table tbody tr:nth-child(even):hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
        }

        /* Enhanced Labels */
        .label {
            border-radius: 20px;
            padding: 6px 12px;
            font-weight: 500;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: pulse 2s infinite;
        }

        .label-danger {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            box-shadow: 0 3px 10px rgba(220, 53, 69, 0.3);
        }

        .label-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            box-shadow: 0 3px 10px rgba(255, 193, 7, 0.3);
            color: #212529;
        }

        .label-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.3);
        }

        .label-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            box-shadow: 0 3px 10px rgba(23, 162, 184, 0.3);
        }

        /* Enhanced Buttons */
        .btn {
            border-radius: 20px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 11px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transition: width 0.3s, height 0.3s, top 0.3s, left 0.3s;
            z-index: 0;
        }

        .btn:hover::before {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
        }

        .btn > * {
            position: relative;
            z-index: 1;
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border: none;
            box-shadow: 0 3px 10px rgba(23, 162, 184, 0.3);
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(23, 162, 184, 0.4);
        }

        .btn-box-tool {
            transition: all 0.3s ease;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-box-tool:hover {
            background: rgba(255,255,255,0.1);
            transform: scale(1.1);
        }

        /* Box Enhancements */
        .box {
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            border: none;
            overflow: hidden;
            background: white;
            margin-bottom: 25px;
        }

        .box-primary .box-header,
        .box-info .box-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            position: relative;
            overflow: hidden;
        }

        .box-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
            animation: shimmer 2s ease-in-out infinite;
        }

        .box-title {
            color: white !important;
            font-weight: 600;
            text-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .box-body {
            padding: 20px;
        }

        .box-footer {
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            padding: 15px 20px;
        }

        .box-footer a {
            color: #007bff;
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            position: relative;
        }

        .box-footer a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #007bff;
            transition: width 0.3s ease;
        }

        .box-footer a:hover::after {
            width: 100%;
        }

        /* Product List Enhancements */
        .products-list .item {
            padding: 15px;
            border-bottom: 1px solid #f1f3f4;
            transition: all 0.3s ease;
            position: relative;
        }

        .products-list .item:hover {
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
            transform: translateX(5px);
        }

        .product-img img {
            border-radius: 50%;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .products-list .item:hover .product-img img {
            transform: scale(1.05);
        }

        .product-title {
            font-weight: 600;
            color: #2c3e50 !important;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .product-title:hover {
            color: #007bff !important;
            text-decoration: none;
        }

        /* Custom Scrollbar */
        .box-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .box-body::-webkit-scrollbar {
            width: 6px;
        }

        .box-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .box-body::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .box-body::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Animations */
        @keyframes  fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes  slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes  slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes  rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes  shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes  pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-tabs-custom .nav-tabs > li > a {
                padding: 10px 15px;
                font-size: 12px;
            }
            
            .tab-content {
                padding: 15px;
            }
            
            .table-responsive {
                font-size: 12px;
            }
        }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Dashboard</a></li>
                <li><a href="#tab_3" data-toggle="tab">Pelaksanaan Akademik</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="box box-widget widget-user">
                                <div class="widget-user-header bg-aqua-active">
                                    <h3 class="widget-user-username"><?php echo e(Auth::user()->name); ?></h3>
                                    <h5 class="widget-user-desc">Dosen</h5>
                                </div>
                                <div class="widget-user-image">
                                    <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-sm-4 border-right">
                                            <div class="description-block">
                                                <h5 class="description-header"></h5>
                                                <span class="description-text"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 border-right">

                                        </div>
                                        <div class="col-sm-4">
                                            <div class="description-block">
                                                <h5 class="description-header"></h5>
                                                <span class="description-text"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th style="width:30%">Nama</th>
                                            <td style="width:5%">:</td>
                                            <td><?php echo e($dsn->nama); ?>, <?php echo e($dsn->akademik); ?> </td>
                                        </tr>
                                        <tr>
                                            <th>NIK</th>
                                            <td>:</td>
                                            <td><?php echo e(Auth::user()->username); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tempat, tanggal lahir</th>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>Agama</th>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis kelamin</th>
                                            <td>:</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <th>No HP</th>
                                            <td>:</td>
                                            <td><?php echo e($dsn->hp); ?></td>
                                        </tr>
                                        <tr>
                                            <th>E-Mail</th>
                                            <td>:</td>
                                            <td><?php echo e($dsn->email); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Tahun Akademik</span>
                                            <span class="info-box-number"><?php echo e($tahun->periode_tahun); ?><?php echo e($tipe->periode_tipe); ?></span>
                                            <a href="<?php echo e(asset('/Kalender Akademik/' . $tahun->file)); ?>" target="_blank"
                                                class="small-box-footer">Unduh Kalender Akademik <i
                                                    class="fa fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-green"><i
                                                class="fa fa-calendar-check-o"></i></span>
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
                                <div class="col-md-12">
                                    <div class="box box-primary">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Informasi Terbaru</h3>
                                            <div class="box-tools pull-right">
                                                <button type="button" class="btn btn-box-tool"
                                                    data-widget="collapse"><i class="fa fa-minus"></i>
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
                                                            <img class="img-circle" src="/images/bell.jpg"
                                                                alt="user">
                                                        </div>
                                                        <div class="product-info">
                                                            <a href="/lihat/<?php echo e($item->id_informasi); ?>"
                                                                class="product-title"><?php echo e($item->judul); ?>

                                                                <span class="label label-info pull-right">
                                                                    <?php echo e(date('l, d F Y', strtotime($item->created_at))); ?><br>
                                                                    <?php echo e($item->created_at->diffForHumans()); ?>

                                                                </span></a>
                                                            <span
                                                                class="product-description"><?php echo e($item->deskripsi); ?></span>
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
                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ketercapaian Pembelajaran</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                        class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                        class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Matakuliah</th>
                                            <th>SKS</th>
                                            <th>Prodi</th>
                                            <th>Kelas</th>
                                            <th>
                                                <center>Pertemuan</center>
                                            </th>
                                            <th>
                                                <center>Persentase Pembelajaran</center>
                                            </th>
                                            <th>
                                                <center>Persenatse Absensi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php $__currentLoopData = $data_akademik; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($no++); ?></td>
                                                <td><?php echo e($item->makul); ?></td>
                                                <td><?php echo e($item->sks); ?></td>
                                                <td><?php echo e($item->prodi); ?></td>
                                                <td><?php echo e($item->kelas); ?></td>
                                                <td align="center"><?php echo e($item->jml_per); ?> / 16</td>
                                                <td align="center">
                                                    <?php if($item->jml_per <= 7): ?>
                                                        <span class="label label-danger">
                                                            <?php echo e($item->persentase); ?> %</span>
                                                    <?php elseif($item->jml_per < 16): ?>
                                                        <span class="label label-warning">
                                                            <?php echo e($item->persentase); ?> %</span>
                                                    <?php elseif($item->jml_per = 16): ?>
                                                        <span class="label label-success">
                                                            <?php echo e($item->persentase); ?> %</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td align="center">
                                                    <a href="/persentase_absensi_mhs_dsnlr/<?php echo e(Crypt::encryptString($item->id_kurperiode)); ?>"
                                                        class="btn btn-info btn-xs">Cek Absensi</a>
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
<?php /**PATH /var/www/html/resources/views/layouts/dosenluar_home.blade.php ENDPATH**/ ?>