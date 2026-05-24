<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<style>
    /* Styling modern untuk desktop */
    .custom-box {
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border-top: 4px solid #3c8dbc;
        background: #fff;
    }

    .custom-table {
        margin-bottom: 0;
    }

    .custom-table thead th {
        background-color: #f4f6f9;
        color: #333;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        padding: 12px 15px;
        border-bottom: 2px solid #ddd;
    }

    .custom-table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
        font-size: 14px;
        border-bottom: 1px solid #f0f0f0;
    }

    .custom-table tbody tr:hover {
        background-color: #f9fafc;
    }

    .status-badge {
        font-size: 12px;
        font-weight: 600;
        padding: 5px 10px;
        border-radius: 20px;
    }

    .status-badge.danger {
        background-color: #ffeaea;
        color: #d9534f;
    }

    .status-badge.success {
        background-color: #eafaf1;
        color: #28a745;
    }

    /* List view untuk mobile */
    .mobile-list-view {
        display: none;
    }

    .mobile-item-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }

    .mobile-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px dashed #eee;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }

    .mobile-item-title {
        font-weight: 600;
        font-size: 15px;
        color: #333;
    }

    .mobile-detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 13px;
    }

    .mobile-detail-label {
        color: #777;
    }

    .mobile-detail-value {
        font-weight: 500;
        color: #333;
    }

    .mobile-detail-value.sisa {
        color: #d9534f;
        font-weight: 600;
    }

    @media (max-width: 767px) {
        .desktop-table-view {
            display: none;
        }
        .mobile-list-view {
            display: block;
        }
        .box-header {
            padding: 15px;
        }
    }
</style>

<section class="content">
    <div class="box custom-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-money" style="margin-right: 8px;"></i> Tabel Biaya Kuliah</h3>
        </div>
        <div class="box-body">

            <?php
                // Pre-computation logic can go here if needed
            ?>

            <!-- Mobile View (Card List) -->
            <div class="mobile-list-view">
                <?php $no = 1; ?>
                <?php $__currentLoopData = $itembayar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $harga = 0;
                        $persen_beasiswa = 0;
                        $telah_dibayar = 0;
                        $itemName = $item->item;

                        if ($itemName == 'Pendaftaran') {
                            $harga = $biaya->daftar;
                            $persen_beasiswa = $cb ? $cb->daftar : 0;
                            $telah_dibayar = $sisadaftar;
                        } elseif ($itemName == 'Perlengkapan Awal') {
                            $harga = $biaya->awal;
                            $persen_beasiswa = $cb ? $cb->awal : 0;
                            $telah_dibayar = $sisaawal;
                        } elseif ($itemName == 'Dana Pengembangan') {
                            $harga = $biaya->dsp;
                            $persen_beasiswa = $cb ? $cb->dsp : 0;
                            $telah_dibayar = $sisadsp;
                        } elseif ($itemName == 'Biaya SPP 1') {
                            $harga = $biaya->spp1;
                            $persen_beasiswa = $cb ? $cb->spp1 : 0;
                            $telah_dibayar = $sisaspp1;
                        } elseif ($itemName == 'Biaya SPP 2') {
                            $harga = $biaya->spp2;
                            $persen_beasiswa = $cb ? $cb->spp2 : 0;
                            $telah_dibayar = $sisaspp2;
                        } elseif ($itemName == 'Biaya SPP 3') {
                            $harga = $biaya->spp3;
                            $persen_beasiswa = $cb ? $cb->spp3 : 0;
                            $telah_dibayar = $sisaspp3;
                        } elseif ($itemName == 'Biaya SPP 4') {
                            $harga = $biaya->spp4;
                            $persen_beasiswa = $cb ? $cb->spp4 : 0;
                            $telah_dibayar = $sisaspp4;
                        } elseif ($itemName == 'Biaya SPP 5') {
                            $harga = $biaya->spp5;
                            $persen_beasiswa = $cb ? $cb->spp5 : 0;
                            $telah_dibayar = $sisaspp5;
                        } elseif ($itemName == 'Biaya SPP 6') {
                            $harga = $biaya->spp6;
                            $persen_beasiswa = $cb ? $cb->spp6 : 0;
                            $telah_dibayar = $sisaspp6;
                        } elseif ($itemName == 'Biaya SPP 7') {
                            $harga = $biaya->spp7;
                            $persen_beasiswa = $cb ? $cb->spp7 : 0;
                            $telah_dibayar = $sisaspp7;
                        } elseif ($itemName == 'Biaya SPP 8') {
                            $harga = $biaya->spp8;
                            $persen_beasiswa = $cb ? $cb->spp8 : 0;
                            $telah_dibayar = $sisaspp8;
                        } elseif ($itemName == 'Biaya SPP 9') {
                            $harga = $biaya->spp9;
                            $persen_beasiswa = $cb ? $cb->spp9 : 0;
                            $telah_dibayar = $sisaspp9;
                        } elseif ($itemName == 'Biaya SPP 10') {
                            $harga = $biaya->spp10;
                            $persen_beasiswa = $cb ? $cb->spp10 : 0;
                            $telah_dibayar = $sisaspp10;
                        } elseif ($itemName == 'Biaya SPP 11') {
                            $harga = $biaya->spp11 ?? 0;
                            $persen_beasiswa = $cb ? ($cb->spp11 ?? 0) : 0;
                            $telah_dibayar = $sisaspp11 ?? 0;
                        } elseif ($itemName == 'Biaya SPP 12') {
                            $harga = $biaya->spp12 ?? 0;
                            $persen_beasiswa = $cb ? ($cb->spp12 ?? 0) : 0;
                            $telah_dibayar = $sisaspp12 ?? 0;
                        } elseif ($itemName == 'Biaya SPP 13') {
                            $harga = $biaya->spp13 ?? 0;
                            $persen_beasiswa = $cb ? ($cb->spp13 ?? 0) : 0;
                            $telah_dibayar = $sisaspp13 ?? 0;
                        } elseif ($itemName == 'Biaya SPP 14') {
                            $harga = $biaya->spp14 ?? 0;
                            $persen_beasiswa = $cb ? ($cb->spp14 ?? 0) : 0;
                            $telah_dibayar = $sisaspp14 ?? 0;
                        } elseif ($itemName == 'Prakerin') {
                            $harga = $biaya->prakerin;
                            $persen_beasiswa = $cb ? $cb->prakerin : 0;
                            $telah_dibayar = $sisaprakerin;
                        } elseif ($itemName == 'Magang 1') {
                            $harga = $biaya->magang1;
                            $persen_beasiswa = $cb ? ($cb->magang1 ?? 0) : 0;
                            $telah_dibayar = $sisamagang1;
                        } elseif ($itemName == 'Magang 2') {
                            $harga = $biaya->magang2;
                            $persen_beasiswa = $cb ? ($cb->magang2 ?? 0) : 0;
                            $telah_dibayar = $sisamagang2;
                        } elseif ($itemName == 'Seminar') {
                            $harga = $biaya->seminar;
                            $persen_beasiswa = $cb ? $cb->seminar : 0;
                            $telah_dibayar = $sisaseminar;
                        } elseif ($itemName == 'Sidang') {
                            $harga = $biaya->sidang;
                            $persen_beasiswa = $cb ? $cb->sidang : 0;
                            $telah_dibayar = $sisasidang;
                        } elseif ($itemName == 'Wisuda') {
                            $harga = $biaya->wisuda;
                            $persen_beasiswa = $cb ? $cb->wisuda : 0;
                            $telah_dibayar = $sisawisuda;
                        }

                        $harga = (float) $harga;
                        $telah_dibayar = (float) $telah_dibayar;
                        $nilai_beasiswa = ($harga * $persen_beasiswa) / 100;
                        $total_bayar = $harga - $nilai_beasiswa;
                        $sisa_pembayaran = $total_bayar - $telah_dibayar;
                        
                        $statusColor = ($sisa_pembayaran > 0) ? 'danger' : 'success';
                        $statusText = ($sisa_pembayaran > 0) ? 'Belum Lunas' : 'Lunas';
                    ?>
                    <div class="mobile-item-card">
                        <div class="mobile-item-header">
                            <span class="mobile-item-title"><?php echo e($no++); ?>. <?php echo e($item->item); ?></span>
                            <span class="status-badge <?php echo e($statusColor); ?>"><?php echo e($statusText); ?></span>
                        </div>
                        <div class="mobile-detail-row">
                            <span class="mobile-detail-label">Biaya Asli</span>
                            <span class="mobile-detail-value">Rp. <?php echo number_format($harga, 0, ',', '.'); ?></span>
                        </div>
                        <div class="mobile-detail-row">
                            <span class="mobile-detail-label">Beasiswa (<?php echo e($persen_beasiswa); ?>%)</span>
                            <span class="mobile-detail-value">Rp. <?php echo number_format($nilai_beasiswa, 0, ',', '.'); ?></span>
                        </div>
                        <div class="mobile-detail-row">
                            <span class="mobile-detail-label">Total Pembayaran</span>
                            <span class="mobile-detail-value">Rp. <?php echo number_format($total_bayar, 0, ',', '.'); ?></span>
                        </div>
                        <div class="mobile-detail-row">
                            <span class="mobile-detail-label">Telah Dibayarkan</span>
                            <span class="mobile-detail-value">Rp. <?php echo number_format($telah_dibayar, 0, ',', '.'); ?></span>
                        </div>
                        <div class="mobile-detail-row">
                            <span class="mobile-detail-label">Sisa Pembayaran</span>
                            <span class="mobile-detail-value sisa">Rp. <?php echo number_format($sisa_pembayaran, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <!-- Desktop View (Table) -->
            <div class="table-responsive desktop-table-view">
                <table class="table custom-table table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">No.</th>
                            <th width="20%">Item Bayar</th>
                            <th class="text-right">Biaya Asli</th>
                            <th class="text-center">Beasiswa</th>
                            <th class="text-right">Total Pembayaran</th>
                            <th class="text-right">Telah Dibayarkan</th>
                            <th class="text-right">Sisa Pembayaran</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $itembayar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $harga = 0;
                                $persen_beasiswa = 0;
                                $telah_dibayar = 0;
                                $itemName = $item->item;

                                if ($itemName == 'Pendaftaran') {
                                    $harga = $biaya->daftar;
                                    $persen_beasiswa = $cb ? $cb->daftar : 0;
                                    $telah_dibayar = $sisadaftar;
                                } elseif ($itemName == 'Perlengkapan Awal') {
                                    $harga = $biaya->awal;
                                    $persen_beasiswa = $cb ? $cb->awal : 0;
                                    $telah_dibayar = $sisaawal;
                                } elseif ($itemName == 'Dana Pengembangan') {
                                    $harga = $biaya->dsp;
                                    $persen_beasiswa = $cb ? $cb->dsp : 0;
                                    $telah_dibayar = $sisadsp;
                                } elseif ($itemName == 'Biaya SPP 1') {
                                    $harga = $biaya->spp1;
                                    $persen_beasiswa = $cb ? $cb->spp1 : 0;
                                    $telah_dibayar = $sisaspp1;
                                } elseif ($itemName == 'Biaya SPP 2') {
                                    $harga = $biaya->spp2;
                                    $persen_beasiswa = $cb ? $cb->spp2 : 0;
                                    $telah_dibayar = $sisaspp2;
                                } elseif ($itemName == 'Biaya SPP 3') {
                                    $harga = $biaya->spp3;
                                    $persen_beasiswa = $cb ? $cb->spp3 : 0;
                                    $telah_dibayar = $sisaspp3;
                                } elseif ($itemName == 'Biaya SPP 4') {
                                    $harga = $biaya->spp4;
                                    $persen_beasiswa = $cb ? $cb->spp4 : 0;
                                    $telah_dibayar = $sisaspp4;
                                } elseif ($itemName == 'Biaya SPP 5') {
                                    $harga = $biaya->spp5;
                                    $persen_beasiswa = $cb ? $cb->spp5 : 0;
                                    $telah_dibayar = $sisaspp5;
                                } elseif ($itemName == 'Biaya SPP 6') {
                                    $harga = $biaya->spp6;
                                    $persen_beasiswa = $cb ? $cb->spp6 : 0;
                                    $telah_dibayar = $sisaspp6;
                                } elseif ($itemName == 'Biaya SPP 7') {
                                    $harga = $biaya->spp7;
                                    $persen_beasiswa = $cb ? $cb->spp7 : 0;
                                    $telah_dibayar = $sisaspp7;
                                } elseif ($itemName == 'Biaya SPP 8') {
                                    $harga = $biaya->spp8;
                                    $persen_beasiswa = $cb ? $cb->spp8 : 0;
                                    $telah_dibayar = $sisaspp8;
                                } elseif ($itemName == 'Biaya SPP 9') {
                                    $harga = $biaya->spp9;
                                    $persen_beasiswa = $cb ? $cb->spp9 : 0;
                                    $telah_dibayar = $sisaspp9;
                                } elseif ($itemName == 'Biaya SPP 10') {
                                    $harga = $biaya->spp10;
                                    $persen_beasiswa = $cb ? $cb->spp10 : 0;
                                    $telah_dibayar = $sisaspp10;
                                } elseif ($itemName == 'Biaya SPP 11') {
                                    $harga = $biaya->spp11 ?? 0;
                                    $persen_beasiswa = $cb ? ($cb->spp11 ?? 0) : 0;
                                    $telah_dibayar = $sisaspp11 ?? 0;
                                } elseif ($itemName == 'Biaya SPP 12') {
                                    $harga = $biaya->spp12 ?? 0;
                                    $persen_beasiswa = $cb ? ($cb->spp12 ?? 0) : 0;
                                    $telah_dibayar = $sisaspp12 ?? 0;
                                } elseif ($itemName == 'Biaya SPP 13') {
                                    $harga = $biaya->spp13 ?? 0;
                                    $persen_beasiswa = $cb ? ($cb->spp13 ?? 0) : 0;
                                    $telah_dibayar = $sisaspp13 ?? 0;
                                } elseif ($itemName == 'Biaya SPP 14') {
                                    $harga = $biaya->spp14 ?? 0;
                                    $persen_beasiswa = $cb ? ($cb->spp14 ?? 0) : 0;
                                    $telah_dibayar = $sisaspp14 ?? 0;
                                } elseif ($itemName == 'Prakerin') {
                                    $harga = $biaya->prakerin;
                                    $persen_beasiswa = $cb ? $cb->prakerin : 0;
                                    $telah_dibayar = $sisaprakerin;
                                } elseif ($itemName == 'Magang 1') {
                                    $harga = $biaya->magang1;
                                    $persen_beasiswa = $cb ? ($cb->magang1 ?? 0) : 0;
                                    $telah_dibayar = $sisamagang1;
                                } elseif ($itemName == 'Magang 2') {
                                    $harga = $biaya->magang2;
                                    $persen_beasiswa = $cb ? ($cb->magang2 ?? 0) : 0;
                                    $telah_dibayar = $sisamagang2;
                                } elseif ($itemName == 'Seminar') {
                                    $harga = $biaya->seminar;
                                    $persen_beasiswa = $cb ? $cb->seminar : 0;
                                    $telah_dibayar = $sisaseminar;
                                } elseif ($itemName == 'Sidang') {
                                    $harga = $biaya->sidang;
                                    $persen_beasiswa = $cb ? $cb->sidang : 0;
                                    $telah_dibayar = $sisasidang;
                                } elseif ($itemName == 'Wisuda') {
                                    $harga = $biaya->wisuda;
                                    $persen_beasiswa = $cb ? $cb->wisuda : 0;
                                    $telah_dibayar = $sisawisuda;
                                }

                                $harga = (float) $harga;
                                $telah_dibayar = (float) $telah_dibayar;
                                $nilai_beasiswa = ($harga * $persen_beasiswa) / 100;
                                $total_bayar = $harga - $nilai_beasiswa;
                                $sisa_pembayaran = $total_bayar - $telah_dibayar;
                                
                                $statusColor = ($sisa_pembayaran > 0) ? 'danger' : 'success';
                                $statusText = ($sisa_pembayaran > 0) ? 'Belum Lunas' : 'Lunas';
                            ?>
                            <tr>
                                <td class="text-center"><?php echo e($no++); ?></td>
                                <td><strong><?php echo e($item->item); ?></strong></td>
                                <td class="text-right">Rp. <?php echo number_format($harga, 0, ',', '.'); ?></td>
                                <td class="text-center"><?php echo e($persen_beasiswa); ?>%</td>
                                <td class="text-right"><strong>Rp. <?php echo number_format($total_bayar, 0, ',', '.'); ?></strong></td>
                                <td class="text-right text-success">Rp. <?php echo number_format($telah_dibayar, 0, ',', '.'); ?></td>
                                <td class="text-right text-danger"><strong>Rp. <?php echo number_format($sisa_pembayaran, 0, ',', '.'); ?></strong></td>
                                <td class="text-center">
                                    <span class="status-badge <?php echo e($statusColor); ?>"><?php echo e($statusText); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/keuangan/tabel_biaya.blade.php ENDPATH**/ ?>