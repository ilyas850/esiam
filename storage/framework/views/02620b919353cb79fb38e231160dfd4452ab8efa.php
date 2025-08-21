<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        /* Consistent with List Page Styles */
        .detail-grid {
            padding: 20px 15px;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        /* Page Header - Same as List */
        .page-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            color: white;
            text-align: center;
        }
        
        .page-header-custom::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }
        
        @keyframes  rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .page-header-custom h1 {
            font-size: 36px;
            font-weight: 700;
            margin: 0;
            position: relative;
            z-index: 1;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .page-header-custom p {
            font-size: 18px;
            margin-top: 10px;
            position: relative;
            z-index: 1;
            opacity: 0.9;
        }
        
        /* Breadcrumb */
        .breadcrumb-simple {
            background: white;
            padding: 15px 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            font-size: 14px;
            border: 1px solid #e9ecef;
            display: inline-block;
        }
        
        .breadcrumb-simple a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }
        
        .breadcrumb-simple a:hover {
            color: #764ba2;
        }
        
        .breadcrumb-simple .separator {
            color: #cbd5e0;
            margin: 0 10px;
        }
        
        .breadcrumb-simple .current {
            color: #2c3e50;
            font-weight: 700;
        }
        
        /* Main Detail Card - Consistent with info-card */
        .detail-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            position: relative;
            border: 1px solid #e9ecef;
            animation: fadeInUp 0.6s ease;
        }
        
        .detail-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #3c8dbc);
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
        
        /* Header Section - Same style as card-header-section */
        .detail-header {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        /* Date Section - Identical to list page */
        .date-section {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 15px;
        }
        
        .date-item {
            display: flex;
            align-items: center;
        }
        
        .date-item i {
            margin-right: 5px;
            color: #adb5bd;
        }
        
        /* Title */
        .detail-title {
            font-size: 28px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
            line-height: 1.4;
            position: relative;
            padding-bottom: 15px;
        }
        
        .detail-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100px;
            height: 3px;
            background: linear-gradient(90deg, #3c8dbc, transparent);
        }
        
        /* Content Body */
        .detail-content {
            padding: 25px;
        }
        
        /* File Section - Same as list page */
        .file-section {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .file-section:hover {
            background: #f1f3f5;
            border-color: #667eea;
        }
        
        .file-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .file-label i {
            color: #667eea;
        }
        
        /* File Badge - Consistent with list */
        .file-badge {
            display: inline-flex;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 18px;
            border-radius: 30px;
            font-weight: 500;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 3px 10px rgba(118,75,162,0.2);
        }
        
        .file-badge:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 5px 20px rgba(118,75,162,0.3);
            color: white;
            text-decoration: none;
        }
        
        .file-badge i {
            margin-right: 8px;
            font-size: 16px;
        }
        
        /* No File - Same as list */
        .no-file-placeholder {
            display: inline-flex;
            align-items: center;
            padding: 10px 18px;
            background: #e9ecef;
            border-radius: 30px;
            color: #6c757d;
            font-size: 13px;
        }
        
        .no-file-placeholder i {
            margin-right: 8px;
            font-size: 16px;
            color: #adb5bd;
        }
        
        /* Description Section */
        .description-section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .section-title::before {
            content: '';
            width: 4px;
            height: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        
        .description-content {
            color: #495057;
            font-size: 15px;
            line-height: 1.8;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border-left: 3px solid transparent;
            border-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-image-slice: 1;
        }
        
        /* Info Grid */
        .info-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 25px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            border: 1px solid #e9ecef;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0 auto 10px;
            font-size: 18px;
        }
        
        .stat-value {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        /* Action Section */
        .action-section {
            padding: 20px 25px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        /* Buttons - Same style as list page */
        .btn-custom {
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 13px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            position: relative;
            overflow: hidden;
        }
        
        .btn-back {
            background: white;
            color: #495057;
            border: 1px solid #e9ecef;
        }
        
        .btn-back:hover {
            background: #f8f9fa;
            border-color: #667eea;
            color: #667eea;
            text-decoration: none;
            transform: translateX(-3px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3c8dbc 0%, #17a2b8 100%);
            color: white;
            border: none;
            box-shadow: 0 3px 10px rgba(60,141,188,0.3);
        }
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-primary:hover::before {
            left: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(60,141,188,0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-primary i {
            transition: transform 0.3s ease;
        }
        
        .btn-primary:hover i {
            transform: translateX(3px);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .page-header-custom h1 {
                font-size: 28px;
            }
            
            .detail-title {
                font-size: 22px;
            }
            
            .date-section {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .info-stats {
                grid-template-columns: 1fr;
            }
            
            .action-section {
                flex-direction: column;
            }
            
            .btn-custom {
                width: 100%;
                justify-content: center;
            }
        }
        
        /* Print Styles */
        @media  print {
            .breadcrumb-simple,
            .action-section,
            .page-header-custom {
                display: none;
            }
            
            .detail-grid {
                background: white;
                padding: 0;
            }
            
            .detail-card {
                box-shadow: none;
                border: none;
            }
        }
        
        /* Loading animation */
        .fade-in {
            animation: fadeIn 0.6s ease;
        }
        
        @keyframes  fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    
    <section class="content detail-grid">
        <!-- Page Header - Same as List Page -->
        <div class="page-header-custom fade-in">
            <h1><i class="fa fa-file-text-o"></i> Detail Informasi</h1>
            <p>Baca informasi lengkap dan unduh lampiran</p>
        </div>
        
        <div class="container-fluid">
            <!-- Breadcrumb -->
            <div class="breadcrumb-simple">
                <a href="/home"><i class="fa fa-home"></i> Home</a>
                <span class="separator">/</span>
                <a href="/lihat_semua">Pusat Informasi</a>
                <span class="separator">/</span>
                <span class="current">Detail</span>
            </div>
            
            <!-- Main Detail Card -->
            <div class="detail-card">
                <!-- Header -->
                <div class="detail-header">
                    <!-- Date Section - Same as list -->
                    <div class="date-section">
                        <div class="date-item">
                            <i class="fa fa-calendar"></i>
                            <?php echo e(date('d M Y', strtotime($info->created_at))); ?>

                        </div>
                        <div class="date-item">
                            <i class="fa fa-clock-o"></i>
                            <?php echo e(date('H:i', strtotime($info->created_at))); ?> WIB
                        </div>
                        <div class="date-item">
                            <i class="fa fa-history"></i>
                            <?php echo e($info->created_at->diffForHumans()); ?>

                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="detail-title"><?php echo e($info->judul); ?></h1>
                </div>
                
                <!-- Content -->
                <div class="detail-content">
                    <!-- File Section - Same style as list -->
                    <div class="file-section">
                        <div class="file-label">
                            <i class="fa fa-paperclip"></i>
                            Lampiran File
                        </div>
                        <?php if($info->file != null): ?>
                            <a href="<?php echo e(asset('/data_file/' . $info->file)); ?>" target="_blank" class="file-badge">
                                <i class="fa fa-file-pdf-o"></i>
                                <span><?php echo e(Str::limit($info->file, 40)); ?></span>
                            </a>
                        <?php else: ?>
                            <div class="no-file-placeholder">
                                <i class="fa fa-info-circle"></i>
                                <span>Tidak ada lampiran</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Description -->
                    <div class="description-section">
                        <h3 class="section-title">Deskripsi</h3>
                        <div class="description-content">
                            <?php echo nl2br(e($info->deskripsi)); ?>

                        </div>
                    </div>
                    
                    <!-- Info Stats -->
                    <?php if(isset($info->kategori) || isset($info->status) || isset($info->updated_at)): ?>
                    <div class="info-stats">
                        <?php if(isset($info->kategori)): ?>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa fa-bookmark"></i>
                            </div>
                            <div class="stat-value"><?php echo e($info->kategori); ?></div>
                            <div class="stat-label">Kategori</div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if(isset($info->status)): ?>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div class="stat-value"><?php echo e($info->status); ?></div>
                            <div class="stat-label">Status</div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa fa-calendar-check-o"></i>
                            </div>
                            <div class="stat-value"><?php echo e($info->created_at->format('d M Y')); ?></div>
                            <div class="stat-label">Dipublikasikan</div>
                        </div>
                        
                        <?php if(isset($info->updated_at) && $info->updated_at != $info->created_at): ?>
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fa fa-refresh"></i>
                            </div>
                            <div class="stat-value"><?php echo e($info->updated_at->format('d M Y')); ?></div>
                            <div class="stat-label">Update Terakhir</div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-section">
                    <a href="/lihat_semua" class="btn-custom btn-back">
                        <i class="fa fa-arrow-left"></i>
                        Kembali
                    </a>
                   
                </div>
            </div>
        </div>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth entrance animations
            const elements = document.querySelectorAll('.detail-card, .breadcrumb-simple');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/lihatinfo.blade.php ENDPATH**/ ?>