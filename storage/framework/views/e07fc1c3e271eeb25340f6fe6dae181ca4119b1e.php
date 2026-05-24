<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        /* Custom styling for primary box card */
        .custom-payment-box {
            border-radius: 8px !important;
            border-top: 3px solid #3c8dbc !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05) !important;
            background: #fff;
            margin-bottom: 20px;
        }

        .custom-payment-box .box-header {
            border-bottom: 1px solid #f4f4f4;
            padding: 15px 20px;
        }

        .custom-payment-box .box-title {
            font-size: 18px;
            font-weight: 600;
            color: #2d3748;
            margin: 0;
        }

        /* Summary Widget Design */
        .summary-widget {
            background: #ffffff;
            border-radius: 8px;
            border: 1px solid #eef2f5;
            padding: 18px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }

        .summary-widget:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        }

        .summary-widget .widget-icon {
            font-size: 26px;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f6f9;
        }

        .summary-widget.widget-blue .widget-icon {
            background: #eef7ff;
            color: #3c8dbc;
        }

        .summary-widget.widget-green .widget-icon {
            background: #ebfbf2;
            color: #2dca73;
        }

        .summary-widget .widget-info {
            text-align: right;
        }

        .summary-widget .widget-info .widget-label {
            font-size: 11px;
            color: #a0aec0;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .summary-widget .widget-info .widget-value {
            font-size: 18px;
            font-weight: 700;
            color: #2d3748;
        }

        /* Desktop Custom Table Styling */
        .custom-table-container {
            padding: 20px !important;
        }

        .custom-table {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100% !important;
            border: 1px solid #eef2f5 !important;
            border-radius: 8px !important;
            overflow: hidden;
        }

        .custom-table th {
            background: #3c8dbc !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 12px 15px !important;
            border: none !important;
        }

        .custom-table td {
            padding: 14px 15px !important;
            vertical-align: middle !important;
            border-bottom: 1px solid #f0f4f8 !important;
            border-top: none !important;
            color: #4a5568;
            font-size: 13px;
        }

        .custom-table tbody tr {
            transition: background-color 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background-color: #f7fafc !important;
        }

        .custom-table tr:last-child td {
            border-bottom: none !important;
        }

        /* Badge and Elements Styling */
        .badge-kuitansi {
            background: #ebf5ff !important;
            color: #2b6cb0 !important;
            font-family: 'Courier New', Courier, monospace;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 4px;
            border: 1px solid #bee3f8;
            font-size: 12px;
            display: inline-block;
        }

        .badge-item-name {
            font-weight: 600;
            color: #2d3748;
        }

        /* Mobile View - Cards Layout */
        .mobile-card-list {
            display: none;
            padding: 15px !important;
        }

        .mobile-payment-card {
            background: #ffffff;
            border: 1px solid #eef2f5;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.01);
            transition: all 0.2s ease;
        }

        .mobile-payment-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
            border-color: #d2e3f7;
        }

        .mobile-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed #f0f4f8;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .mobile-card-body {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .mobile-info-row {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #718096;
        }

        .mobile-info-row .label {
            color: #a0aec0;
            font-weight: 500;
            padding: 0;
            background: transparent;
        }

        .mobile-info-row .value {
            color: #4a5568;
            font-weight: 600;
        }

        .mobile-card-amount {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #f0f4f8;
        }

        .mobile-amount-val {
            font-size: 15px;
            font-weight: 700;
            color: #2dca73;
        }

        /* Media Queries for Responsiveness */
        @media (max-width: 767px) {
            .desktop-table-view {
                display: none !important;
            }
            .mobile-card-list {
                display: block !important;
            }
            .summary-widget {
                padding: 14px 16px;
            }
            .summary-widget .widget-icon {
                width: 46px;
                height: 46px;
                font-size: 22px;
            }
            .summary-widget .widget-value {
                font-size: 16px;
            }
        }
    </style>

    <section class="content">
        <!-- Row for Widgets -->
        <div class="row">
            <!-- Widget 1: Total Transaksi -->
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="summary-widget widget-blue">
                    <div class="widget-icon">
                        <i class="fa fa-file-text-o"></i>
                    </div>
                    <div class="widget-info">
                        <div class="widget-label">Total Transaksi</div>
                        <div class="widget-value"><?php echo e($kuitansi->count()); ?> Kali</div>
                    </div>
                </div>
            </div>
            
            <!-- Widget 2: Total Pembayaran -->
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="summary-widget widget-green">
                    <div class="widget-icon">
                        <i class="fa fa-wallet"></i>
                    </div>
                    <div class="widget-info">
                        <div class="widget-label">Total Terbayar</div>
                        <div class="widget-value">Rp. <?php echo number_format((float) $totalbayarmhs, 0, ',', '.'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Box Content -->
        <div class="row">
            <div class="col-md-12">
                <div class="box custom-payment-box">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-history text-primary" style="margin-right: 8px;"></i>
                            Riwayat Pembayaran Kuliah
                        </h3>
                    </div>

                    <!-- Desktop View Table -->
                    <div class="box-body custom-table-container desktop-table-view">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px; text-align: center;">No</th>
                                    <th>Item Pembayaran</th>
                                    <th style="text-align: center; width: 220px;">Tanggal Bayar</th>
                                    <th style="text-align: center; width: 220px;">Nomor Kuitansi</th>
                                    <th style="text-align: right; width: 220px;">Nominal Bayar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php $__currentLoopData = $kuitansi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td align="center" style="font-weight: 600; color: #a0aec0;"><?php echo e($no++); ?></td>
                                        <td>
                                            <span class="badge-item-name">
                                                <i class="fa fa-check-circle text-success" style="margin-right: 6px;"></i>
                                                <?php echo e($key->item); ?>

                                            </span>
                                        </td>
                                        <td align="center">
                                            <i class="fa fa-calendar text-muted" style="margin-right: 6px;"></i>
                                            <?php echo e(Carbon\Carbon::parse($key->tanggal)->formatLocalized('%d %B %Y')); ?>

                                        </td>
                                        <td align="center">
                                            <span class="badge-kuitansi">
                                                <i class="fa fa-ticket text-muted" style="margin-right: 4px;"></i>
                                                <?php echo e($key->nokuit); ?>

                                            </span>
                                        </td>
                                        <td align="right" style="font-weight: 700; color: #2d3748;">
                                            Rp. <?php echo number_format((float) $key->bayar, 0, ',', '.'); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr style="background: #f7fafc; font-size: 14px;">
                                    <td align="center" colspan="4" style="font-weight: 700; color: #2d3748; padding: 16px !important;">Total Bayar</td>
                                    <td align="right" style="font-weight: 800; color: #2dca73; padding: 16px !important; font-size: 15px;">
                                        Rp. <?php echo number_format((float) $totalbayarmhs, 0, ',', '.'); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card List View -->
                    <div class="box-body mobile-card-list">
                        <?php if($kuitansi->isEmpty()): ?>
                            <div class="text-center text-muted" style="padding: 40px 15px;">
                                <i class="fa fa-info-circle" style="font-size: 36px; margin-bottom: 10px; color: #cbd5e0;"></i>
                                <p>Belum ada catatan transaksi pembayaran.</p>
                            </div>
                        <?php else: ?>
                            <?php $__currentLoopData = $kuitansi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mobile-payment-card">
                                    <div class="mobile-card-header">
                                        <span class="badge-item-name" style="font-size: 13px;">
                                            <i class="fa fa-check-circle text-success" style="margin-right: 5px;"></i>
                                            <?php echo e($key->item); ?>

                                        </span>
                                        <span class="badge-kuitansi" style="font-size: 10px; padding: 3px 6px;">
                                            #<?php echo e($key->nokuit); ?>

                                        </span>
                                    </div>
                                    <div class="mobile-card-body">
                                        <div class="mobile-info-row">
                                            <span class="label">
                                                <i class="fa fa-calendar" style="margin-right: 4px;"></i> Tanggal Bayar
                                            </span>
                                            <span class="value">
                                                <?php echo e(Carbon\Carbon::parse($key->tanggal)->formatLocalized('%d %B %Y')); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <div class="mobile-card-amount">
                                        <span style="font-size: 11px; font-weight: 700; color: #a0aec0; text-transform: uppercase; letter-spacing: 0.5px;">Nominal</span>
                                        <span class="mobile-amount-val">Rp. <?php echo number_format((float) $key->bayar, 0, ',', '.'); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <!-- Total Widget for Mobile -->
                            <div class="mobile-payment-card" style="background: #ebfbf2; border-color: #c3e6cb; margin-top: 15px; box-shadow: none;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 13px; font-weight: 700; color: #1e7e34;">Total Pembayaran</span>
                                    <span style="font-size: 16px; font-weight: 800; color: #2dca73;">Rp. <?php echo number_format((float) $totalbayarmhs, 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/keuangan/record_biaya.blade.php ENDPATH**/ ?>