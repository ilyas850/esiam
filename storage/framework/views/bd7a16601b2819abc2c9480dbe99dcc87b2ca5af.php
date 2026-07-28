<?php $__env->startSection('side'); ?>

  <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            Berita Acara Perkuliahan
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('history_makul_dsn')); ?>"> History Matakuliah yang diampu</a></li>
            <li class="active">History BAP</li>
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
                        <td><?php echo e($bap->makul); ?></td>
                        <td>Program Studi</td><td>:</td>
                        <td><?php echo e($bap->prodi); ?></td>
                    </tr>
                    <tr>
                        <td>Kelas</td><td>:</td>
                        <td><?php echo e($bap->kelas); ?></td>
                        <td>Semester</td><td>:</td>
                        <td><?php echo e($bap->semester); ?></td>
                    </tr>
                </table>
            </div>
        
            <div class="box-body">
                
                <a href="/sum_absen_his/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-info">Absensi Perkuliahan</a>
                <a href="/jurnal_bap_his/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-warning">Jurnal Perkuliahan</a>
                <br><br>
                <table id="example6" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            
                            <th rowspan="2"><center>Pertemuan</center></th>
                            <th rowspan="2"><center>Tanggal</center></th>
                            <th rowspan="2"><center>Jam</center></th>
                            <th rowspan="2"><center>Materi Kuliah</center></th>
                            <th colspan="3"><center>Kuliah</center></th>
                            <th colspan="2"><center>Absen Mahasiswa</center></th>
                            
                            <th rowspan="2"><center>Action</center></th>
                        </tr>
                        <tr>
                            <th><center>Tipe</center></th>
                            <th><center>Jenis</center></th>
                            <th><center>Metode</center></th>
                            <th><center>Hadir</center></th>
                            <th><center>Tidak</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><center>Ke-<?php echo e($item->pertemuan); ?></center></td>
                                <td><center><?php echo e($item->tanggal); ?></center></td>
                                <td><center><?php echo e($item->jam_mulai); ?> - <?php echo e($item->jam_selsai); ?></center></td>
                                <td><?php echo e($item->materi_kuliah); ?></td>
                                <td><center><?php echo e($item->tipe_kuliah); ?></center></td>
                                <td><center><?php echo e($item->jenis_kuliah); ?></center></td>
                                <td><center><?php echo e($item->metode_kuliah); ?></center></td>
                                <td><center><?php echo e($item->hadir); ?></center></td>
                                <td><center><?php echo e($item->tidak_hadir); ?></center></td>
                                <td><center>
                                    <a href="/view_history_bap/<?php echo e($item->id_bap); ?>" class="btn btn-info btn-xs" title="klik untuk lihat"> <i class="fa fa-eye"></i></a>  
                                </center></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/view_bap_his.blade.php ENDPATH**/ ?>