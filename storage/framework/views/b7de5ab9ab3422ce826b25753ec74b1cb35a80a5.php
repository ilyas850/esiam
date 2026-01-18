<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content_header'); ?>
    <section class="content-header">
        <h1>
            Data List Mahasiswa
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo e(url('home')); ?>"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="<?php echo e(url('makul_diampu_dsn')); ?>"> Data Matakuliah yang diampu</a></li>
            <li class="active">Data List Mahasiswa </li>
        </ol>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data List Mahasiswa</h3>
            </div>
            <div class="box-body">
                <?php if($nilai == null): ?>
                    <button type="button" class="btn btn-primary mr-5" data-toggle="modal" data-target="#addsettingnilai">
                        Setting Persentase (%) Nilai
                    </button>
                <?php else: ?>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                        data-target="#editsettingnilai<?php echo e($nilai->id_settingnilai); ?>">
                        Edit Setting Persentase (%) Nilai
                    </button>
                    <a href="/input_kat_dsn/<?php echo e($ids); ?>" class="btn btn-success btn-sm">Input Nilai KAT
                        (<?php echo e($nilai->kat); ?>%)</a>
                    <a href="/input_uts_dsn/<?php echo e($ids); ?>" class="btn btn-info btn-sm">Input Nilai UTS
                        (<?php echo e($nilai->uts); ?>%)</a>
                    <a href="/input_uas_dsn/<?php echo e($ids); ?>" class="btn btn-warning btn-sm">Input Nilai UAS
                        (<?php echo e($nilai->uas); ?>%)</a>
                    
                    <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modal-danger">
                        Generate Nilai Akhir
                    </button>

                    <div class="modal fade" id="editsettingnilai<?php echo e($nilai->id_settingnilai); ?>" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="post" action="/put_settingnilai_dsn_dlm/<?php echo e($nilai->id_settingnilai); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('put'); ?>
                                <input type="hidden" value="<?php echo e($nilai->id_kurperiode); ?>" name="id_kurperiode">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Edit Setting Nilai Matakuliah</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nilai KAT (%)</label>
                                                    <input type="number" name="kat" class="form-control"
                                                        value="<?php echo e($nilai->kat); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nilai UTS (%)</label>
                                                    <input type="number" name="uts" class="form-control"
                                                        value="<?php echo e($nilai->uts); ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nilai UAS (%)</label>
                                                    <input type="number" name="uas" class="form-control"
                                                        value="<?php echo e($nilai->uas); ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
                <br><br>
                <div class="modal modal-danger fade" id="modal-danger">
                    <div class="modal-dialog">
                        <form action="<?php echo e(url('generate_nilai_akhir_dsn_dlm')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="id_kurperiode" value="<?php echo e($ids); ?>">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Generate Nilai Akhir</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Anda yakin akan menyimpan nilai matakuliah ini ? &hellip;</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-outline">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal fade" id="addsettingnilai" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="<?php echo e(url('post_settingnilai_dsn_dlm')); ?>"
                            enctype="multipart/form-data">
                            <?php echo e(csrf_field()); ?>

                            <input type="hidden" name="id_kurperiode" value="<?php echo e($ids); ?>">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Setting Nilai Matakuliah</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nilai KAT (%)</label>
                                                <input type="number" name="kat" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nilai UTS (%)</label>
                                                <input type="number" name="uts" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Nilai UAS (%)</label>
                                                <input type="number" name="uas" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4%">
                                <center>No</center>
                            </th>
                            <th width="8%">
                                <center>NIM </center>
                            </th>
                            <th width="20%">
                                <center>Nama</center>
                            </th>
                            <th width="15%">
                                <center>Program Studi</center>
                            </th>
                            <th width="8%">
                                <center>Kelas</center>
                            </th>
                            <th width="8%">
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Nilai KAT</center>
                            </th>
                            <th>
                                <center>Nilai UTS</center>
                            </th>
                            <th>
                                <center>Nilai UAS</center>
                            </th>
                            <th>
                                <center>Nilai AKHIR</center>
                            </th>
                            <th>
                                <center>Nilai HURUF</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $ck; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center><?php echo e($no++); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->nim); ?></center>
                                </td>
                                <td><?php echo e($item->nama); ?></td>
                                <td><?php echo e($item->prodi); ?></td>
                                <td>
                                    <center><?php echo e($item->kelas); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->angkatan); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->nilai_KAT); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->nilai_UTS); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->nilai_UAS); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->nilai_AKHIR_angka); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($item->nilai_AKHIR); ?></center>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/list_mhs_dsn.blade.php ENDPATH**/ ?>