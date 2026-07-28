<?php $__env->startSection('side'); ?>

  <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
        Jurnal Perkuliahan
        </h1>
        <ol class="breadcrumb">
        <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="<?php echo e(url('history_makul_dsn')); ?>"> History Matakuliah yang diampu</a></li>
        <li><a href="/view_bap_his/<?php echo e($bap->id_kurperiode); ?>">History BAP</a></li>
        <li class="active">Jurnal Perkuliahan </li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="content">
    <div class="box box-info">
        <div class="box-header with-border">
            <table width="100%">
                <tr>
                    <td>Matakuliah</td><td>:</td>
                    <td><?php echo e($bap->makul); ?> - <?php echo e($bap->akt_sks); ?> SKS</td>
                    <td>Tahun Akademik</td><td>:</td>
                    <td><?php echo e($bap->periode_tahun); ?> <?php echo e($bap->periode_tipe); ?></td>
                </tr>
                <tr>
                    <td>Waktu / Ruangan</td><td>:</td>
                    <td><?php echo e($bap->hari); ?>, 
                        <?php if($bap->id_kelas == 1): ?>
                            <?php echo e(date('H:i', strtotime($bap->jam))); ?> - <?php echo e(date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 50) + (60*$bap->akt_sks_praktek * 120))); ?>

                        <?php elseif($bap->id_kelas == 2): ?>
                            <?php echo e(date('H:i', strtotime($bap->jam))); ?> - <?php echo e(date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 45) + (60*$bap->akt_sks_praktek * 90))); ?>

                        <?php elseif($bap->id_kelas == 3): ?>
                            <?php echo e(date('H:i', strtotime($bap->jam))); ?> - <?php echo e(date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 45) + (60*$bap->akt_sks_praktek * 90))); ?>

                        <?php endif; ?>
                    / <?php echo e($bap->nama_ruangan); ?></td>
                    <td>Program Studi</td><td>:</td>
                    <td><?php echo e($bap->prodi); ?></td>
                </tr>
                <tr>
                    <td>Dosen</td><td>:</td>
                    <td><?php echo e($bap->nama); ?>, <?php echo e($bap->akademik); ?></td>
                    <td>Kelas</td><td>:</td>
                    <td><?php echo e($bap->kelas); ?></td>
                </tr>  
            </table>
        </div>
        <div class="box-body">
            <a href="/print_jurnal/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-success" target="_blank">Print</a>
            <a href="/download_jurnal/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-info">Download</a>
            <br><br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th ><center>No</center></th>
                        <th ><center>Tanggal </center></th>
                        <th ><center>Jam</center></th>
                        <th ><center>Materi</center></th>
                        <th ><center>Paraf Dosen</center></th>
                        <th ><center>Validasi</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1; ?>
                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><center><?php echo e($no++); ?></center></td>
                            <td><center><?php echo e($item->tanggal ? date('Y-m-d', strtotime($item->tanggal)) : ''); ?></center></td>
                            <td><center><?php echo e($item->jam_mulai ? date('H:i', strtotime($item->jam_mulai)) : ''); ?> - <?php echo e($item->jam_selsai ? date('H:i', strtotime($item->jam_selsai)) : ''); ?></center></td>
                            <td><?php echo e($item->materi_kuliah); ?></td>
                            <td><center>By System</center></td>
                            <td><center><?php echo e($item->payroll_check); ?></center></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/jurnal_perkuliahan_his.blade.php ENDPATH**/ ?>