<?php $__env->startSection('side'); ?>

  <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
        Absensi Perkuliahan
        </h1>
        <ol class="breadcrumb">
        <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="<?php echo e(url('history_makul_dsn')); ?>"> History Matakuliah yang diampu</a></li>
        <li><a href="/view_bap_his/<?php echo e($bap->id_kurperiode); ?>">History BAP</a></li>
        <li class="active">Absensi Perkuliahan </li>
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
                            <?php echo e($bap->jam); ?> - <?php echo e(date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 50) + (60*$bap->akt_sks_praktek * 120))); ?>

                        <?php elseif($bap->id_kelas == 2): ?>
                            <?php echo e($bap->jam); ?> - <?php echo e(date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 45) + (60*$bap->akt_sks_praktek * 90))); ?>

                        <?php elseif($bap->id_kelas == 3): ?>
                            <?php echo e($bap->jam); ?> - <?php echo e(date('H:i', strtotime($bap->jam) + (60*$bap->akt_sks_teori * 45) + (60*$bap->akt_sks_praktek * 90))); ?>

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
                <a href="/print_absensi/<?php echo e($bap->id_kurperiode); ?>" class="btn btn-success" target="_blank">Print</a>
                <a href="<?php echo e(url('download_absensi/' . $bap->id_kurperiode)); ?>" class="btn btn-warning btn-sm">
                        <i class="fa fa-file-excel-o"></i> Download
                    </a>
                <br><br>
                <table class="table table-bordered">
                     <thead>
                        <tr>
                            <th ><center>No</center></th>
                            <th ><center>NIM </center></th>
                            <th ><center>Nama</center></th>
                            <th ><center>1</center></th>
                            <th ><center>2</center></th>
                            <th ><center>3</center></th>
                            <th ><center>4</center></th>
                            <th ><center>5</center></th>
                            <th ><center>6</center></th>
                            <th ><center>7</center></th>
                            <th ><center>8</center></th>
                            <th ><center>9</center></th>
                            <th ><center>10</center></th>
                            <th ><center>11</center></th>
                            <th ><center>12</center></th>
                            <th ><center>13</center></th>
                            <th ><center>14</center></th>
                            <th ><center>15</center></th>
                            <th ><center>16</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; ?>
                        
                        <?php $__currentLoopData = $abs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itembs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                            <tr>
                                <td><center><?php echo e($no++); ?></center></td>
                                <td><center><?php echo e($itembs->nim); ?></center></td>
                                <td><?php echo e($itembs->nama); ?></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs4; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs6; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs8; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs9; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs10; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs11; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs13; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs14; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs15; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                                <td><center>
                                    <?php $__currentLoopData = $abs16; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                            <?php if($item1->absensi == 'ABSEN'): ?>
                                                (&#10003;)
                                            <?php elseif($item1->absensi == 'HADIR'): ?>
                                                (x)
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>        
                                </center></td>
                            </tr>
                             
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
    </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/absensi_perkuliahan_his.blade.php ENDPATH**/ ?>