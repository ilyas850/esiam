<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        /* Enhanced Card Styles */
        .info-grid {
            padding: 20px 15px;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        .info-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #e9ecef;
        }
        
        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #3c8dbc);
            transform: scaleX(0);
            transition: transform 0.4s ease;
            transform-origin: left;
        }
        
        .info-card:hover::before {
            transform: scaleX(1);
        }
        
        .info-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.12);
            border-color: #667eea;
        }
        
        /* Card Header Section */
        .card-header-section {
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            position: relative;
        }
        
        /* File Section */
        .file-section {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        /* File Badge */
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
            max-width: 100%;
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
        
        .file-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 200px;
        }
        
        /* No File Placeholder */
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
        
        /* Date Section */
        .date-section {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #6c757d;
            font-size: 13px;
        }
        
        .date-item {
            display: flex;
            align-items: center;
        }
        
        .date-item i {
            margin-right: 5px;
            color: #adb5bd;
        }
        
        /* Card Body */
        .card-body-content {
            padding: 25px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .card-title {
            font-size: 20px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 15px;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 56px;
            position: relative;
        }
        
        .card-title::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #3c8dbc, transparent);
            transition: width 0.3s ease;
        }
        
        .info-card:hover .card-title::after {
            width: 100%;
        }
        
        /* Description */
        .card-description {
            color: #7f8c8d;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Action Button */
        .btn-read-more {
            background: linear-gradient(135deg, #3c8dbc 0%, #17a2b8 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 13px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: inline-block;
            text-decoration: none;
            margin-top: auto;
        }
        
        .btn-read-more::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-read-more:hover::before {
            left: 100%;
        }
        
        .btn-read-more:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(60,141,188,0.3);
            color: white;
            text-decoration: none;
        }
        
        .btn-read-more i {
            margin-left: 8px;
            transition: transform 0.3s ease;
        }
        
        .btn-read-more:hover i {
            transform: translateX(5px);
        }
        
        /* New Badge */
        .new-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #e74c3c;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            animation: badge-pulse 2s infinite;
            z-index: 10;
        }
        
        @keyframes  badge-pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 10px rgba(231, 76, 60, 0);
            }
        }
        
        /* Page Header */
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
        
        /* Animation on scroll */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            animation: fadeInUp 0.6s ease forwards;
        }
        
        @keyframes  fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .col-sm-6.col-md-4:nth-child(1) .info-card { animation-delay: 0.1s; }
        .col-sm-6.col-md-4:nth-child(2) .info-card { animation-delay: 0.2s; }
        .col-sm-6.col-md-4:nth-child(3) .info-card { animation-delay: 0.3s; }
        .col-sm-6.col-md-4:nth-child(4) .info-card { animation-delay: 0.4s; }
        .col-sm-6.col-md-4:nth-child(5) .info-card { animation-delay: 0.5s; }
        .col-sm-6.col-md-4:nth-child(6) .info-card { animation-delay: 0.6s; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .info-card {
                margin-bottom: 20px;
            }
            
            .page-header-custom h1 {
                font-size: 28px;
            }
            
            .card-title {
                font-size: 18px;
            }
        }
        
        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes  loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    
    <section class="content info-grid">
        <!-- Page Header -->
        <div class="page-header-custom fade-in-up">
            <h1><i class="fa fa-newspaper-o"></i> Pusat Informasi</h1>
            <p>Temukan berbagai informasi dan pengumuman terbaru</p>
        </div>
        
        <div class="row">
            <?php $__currentLoopData = $info; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-sm-6 col-md-4">
                    <div class="info-card fade-in-up">
                        <!-- Check if posted within 7 days -->
                        <?php if(\Carbon\Carbon::parse($item->created_at)->diffInDays(\Carbon\Carbon::now()) <= 7): ?>
                            <span class="new-badge">Baru</span>
                        <?php endif; ?>
                        
                        <!-- Card Header -->
                        <div class="card-header-section">
                            <div class="file-section">
                                <?php if($item->file != null): ?>
                                    <a href="<?php echo e(asset('/data_file/' . $item->file)); ?>" target="_blank" class="file-badge">
                                        <i class="fa fa-file-pdf-o"></i>
                                        <span class="file-text"><?php echo e(Str::limit($item->file, 25)); ?></span>
                                    </a>
                                <?php else: ?>
                                    <div class="no-file-placeholder">
                                        <i class="fa fa-info-circle"></i>
                                        <span>Tidak ada lampiran</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="date-section">
                                <div class="date-item">
                                    <i class="fa fa-calendar"></i>
                                    <?php echo e(date('d M Y', strtotime($item->created_at))); ?>

                                </div>
                                <div class="date-item">
                                    <i class="fa fa-clock-o"></i>
                                    <?php echo e($item->created_at->diffForHumans()); ?>

                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body-content">
                            <h3 class="card-title"><?php echo e($item->judul); ?></h3>
                            
                            <?php if(isset($item->deskripsi)): ?>
                                <p class="card-description"><?php echo e(Str::limit($item->deskripsi, 150)); ?></p>
                            <?php else: ?>
                                <p class="card-description">Klik untuk melihat detail informasi selengkapnya...</p>
                            <?php endif; ?>
                            
                            <a href="/lihat/<?php echo e($item->id_informasi); ?>" class="btn-read-more">
                                Baca Selengkapnya <i class="fa fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        
        <!-- Empty State -->
        <?php if(count($info) == 0): ?>
            <div class="text-center" style="padding: 60px 20px;">
                <i class="fa fa-inbox" style="font-size: 80px; color: #ddd; margin-bottom: 20px;"></i>
                <h3 style="color: #999;">Belum Ada Informasi</h3>
                <p style="color: #bbb;">Informasi terbaru akan muncul di sini</p>
            </div>
        <?php endif; ?>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for fade-in animation
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-in-up');
                    }
                });
            }, {
                threshold: 0.1
            });
            
            // Observe all cards
            document.querySelectorAll('.info-card').forEach(card => {
                observer.observe(card);
            });
            
            // Add hover effect with tilt
            document.querySelectorAll('.info-card').forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    const rotateX = (y - centerY) / 20;
                    const rotateY = (centerX - x) / 20;
                    
                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-10px) scale(1.02)`;
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0) scale(1)';
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/all_info.blade.php ENDPATH**/ ?>