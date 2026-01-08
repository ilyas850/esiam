<style>
    /* Additional BAUK Home specific styles */

    /* Enhanced Widget User */
    .widget-user {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        animation: slideInLeft 0.8s ease-out;
    }

    .widget-user:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
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
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes  rotate {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    .widget-user-username {
        color: white !important;
        font-weight: 600 !important;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 2;
    }

    .widget-user-desc {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 400 !important;
        position: relative;
        z-index: 2;
    }

    .widget-user-image img {
        border: 4px solid white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .widget-user:hover .widget-user-image img {
        transform: scale(1.05);
    }

    /* Enhanced Info Boxes */
    .info-box {
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: white;
        border: none;
        overflow: hidden;
        position: relative;
    }

    .info-box:nth-child(1) {
        animation: slideInRight 0.8s ease-out 0.2s both;
    }

    .info-box:nth-child(2) {
        animation: slideInRight 0.8s ease-out 0.4s both;
    }

    .info-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
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
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
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

    .info-box-content {
        padding: 15px;
    }

    .info-box-text {
        font-weight: 500;
        color: #6c757d;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    .info-box-number {
        font-weight: 700;
        font-size: 18px;
        color: #2c3e50;
    }

    /* Enhanced Primary Box */
    .box-primary {
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        border: none;
        overflow: hidden;
        background: white;
        animation: fadeInUp 0.8s ease-out 0.3s both;
    }

    .box-header {
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
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
        animation: shimmer 2s ease-in-out infinite;
    }

    @keyframes  shimmer {
        0% {
            transform: translateX(-100%);
        }

        100% {
            transform: translateX(100%);
        }
    }

    .box-title {
        color: white !important;
        font-weight: 600;
        text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .btn-box-tool {
        color: rgba(255, 255, 255, 0.8) !important;
        transition: all 0.3s ease;
    }

    .btn-box-tool:hover {
        color: white !important;
        transform: scale(1.1);
    }

    /* Enhanced Product List */
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
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
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

    .label-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border-radius: 20px;
        padding: 5px 12px;
        font-size: 10px;
        font-weight: 500;
        animation: pulse 2s infinite;
    }

    @keyframes  pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }

        100% {
            transform: scale(1);
        }
    }

    .product-description {
        color: #6c757d;
        font-size: 13px;
        line-height: 1.5;
        margin-top: 5px;
    }

    .box-footer {
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        padding: 15px;
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

    .box-footer a:hover {
        color: #0056b3;
        text-decoration: none;
    }

    /* Animations */
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

    /* Responsive enhancements */
    @media (max-width: 768px) {

        .widget-user,
        .info-box,
        .box-primary {
            margin-bottom: 20px;
        }
    }

    /* Custom scrollbar for product list */
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
</style>
<div class="row">
    <div class="col-md-6">
        <div class="box box-widget widget-user">
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username"><?php echo e(Auth::user()->name); ?></h3>
                <h5 class="widget-user-desc">Biro Administrasi Keuangan</h5>
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
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tahun Akademik</span>
                <span class="info-box-number"><?php echo e($tahun->periode_tahun); ?> <?php echo e($tipe->periode_tipe); ?></span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-calendar-check-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Jadwal KRS</span>
                <span class="info-box-number">
                    <?php if($time->status == 0): ?>
                        Jadwal Belum ada
                    <?php elseif($time->status == 1): ?>
                        <?php echo e($time->waktu_awal); ?> s/d <?php echo e($time->waktu_akhir); ?>

                    <?php endif; ?>
                </span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Informasi Terbaru</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
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
                                    <img class="img-circle" src="<?php echo e(asset('/data_file/' . $item->file)); ?>">
                                <?php else: ?>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <a href="/lihat/<?php echo e($item->id_informasi); ?>" class="product-title"><?php echo e($item->judul); ?>

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
</div><?php /**PATH /var/www/html/resources/views/layouts/bauk_home.blade.php ENDPATH**/ ?>