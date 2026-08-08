<?php $__env->startSection('side'); ?>

  <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
  <section class="content-header">
      <h1>
        View Berita Acara Perkuliahan
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
        <li><a href="<?php echo e(url('rekap_perkuliahan_kprd')); ?>"> Rekap perkuliahan</a></li>
        <li><a href="/cek_rekapan_kprd/<?php echo e($dtbp->id_kurperiode); ?>">Cek BAP</a></li>
        <li class="active">View BAP</li>
      </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-body">
                <a class="btn btn-success" href="/cek_rekapan_kprd/<?php echo e($dtbp->id_kurperiode); ?>">Kembali</a>

                <a class="btn btn-warning" href="/cek_print_bap_kprd/<?php echo e($dtbp->id_bap); ?>" target="_blank"><i class="fa fa-print"></i> PRINT</a>

                    <center>
                        <h2 class="box-title">Laporan Pembelajaran Daring Prodi <?php echo e($prd); ?> </h2>
                        <h3 class="box-title">Semester <?php echo e($tipe); ?> – <?php echo e($tahun); ?></h3>
                    </center>
                    <table class="table table-bordered table-striped">
                        <tr>
                            <td>Matakuliah</td>
                            <td><?php echo e($data->makul); ?></td>
                        </tr>
                        <tr>
                            <td>Nama Dosen</td>
                            <td><?php echo e($data->nama); ?></td>
                        </tr>
                        <tr>
                            <td>Kelas / Semester</td>
                            <td><?php echo e($data->kelas); ?> / <?php echo e($data->semester); ?></td>
                        </tr>
                        <tr>
                            <td>Media Pemebelajaran</td>
                            <td><?php echo e($dtbp->media_pembelajaran); ?></td>
                        </tr>
                        <tr>
                            <td>Pukul</td>
                            <td><?php echo e($dtbp->jam_mulai); ?> - <?php echo e($dtbp->jam_selsai); ?></td>
                        </tr>
                        <tr>
                            <td>Tanggal Perkuliahan</td>
                            <td><?php echo e($dtbp->tanggal); ?></td>
                        </tr>
                        <tr>
                            <td>Materi Perkuliahan</td>
                            <td><?php echo e($dtbp->materi_kuliah); ?></td>
                        </tr>
                        <tr>
                            <td>Pertemuan</td>
                            <td>Ke-<?php echo e($dtbp->pertemuan); ?></td>
                        </tr>
                        <tr>
                            <td>Mahasiswa Hadir/Tidak Hadir</td>
                            <td><?php echo e($dtbp->hadir); ?> / <?php echo e($dtbp->tidak_hadir); ?></td>
                        </tr>
                    </table>

                <div class="form-group">
                    <h4>1.	Kuliah tatap muka</h4>
                    <?php if(($dtbp->file_kuliah_tatapmuka) != null): ?>
                    <a href="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Kuliah Tatap Muka/<?php echo e($dtbp->file_kuliah_tatapmuka); ?>"  target="_blank"> Tatap Muka Perkuliahan</a>
                    <?php else: ?>
                    Tidak ada lampiran
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <h4>2.	Materi Perkuliahan</h4>
                    <?php if(($dtbp->file_materi_kuliah) != null): ?>
                    <a href="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Materi Kuliah/<?php echo e($dtbp->file_materi_kuliah); ?>"  target="_blank"> Materi Perkuliahan</a>
                    <?php else: ?>
                    Tidak ada lampiran
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <h4>3.	Materi Tugas</h4>
                    <?php if(($dtbp->file_materi_tugas) != null): ?>
                    <a href="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Tugas Kuliah/<?php echo e($dtbp->file_materi_tugas); ?>" target="_blank"> Tugas Perkuliahan</a>
                    <?php else: ?>
                    Tidak ada lampiran
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/kaprodi/perkuliahan/view_bap.blade.php ENDPATH**/ ?>