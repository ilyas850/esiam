<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Upload SK Pengajaran</h3>
            </div>
            <div class="box-body">
                <form action="<?php echo e(url('save_sk_pengajaran')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo e(csrf_field()); ?>

                    <div class="row">
                        <div class="col-md-3">
                            <label>Tahun Akademik</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option value="">-- Pilih Tahun --</option>
                                <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $thn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($thn->id_periodetahun); ?>"><?php echo e($thn->periode_tahun); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Semester</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option value="">-- Pilih Semester --</option>
                                <?php $__currentLoopData = $tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($tp->id_periodetipe); ?>"><?php echo e($tp->periode_tipe); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Program Studi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option value="">-- Pilih Prodi --</option>
                                <?php $__currentLoopData = $prodi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($prd->kodeprodi); ?>"><?php echo e($prd->prodi); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>File SK (.pdf)</label>
                            <input type="file" name="file" class="form-control" accept=".pdf" required>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-info btn-block"><i class="fa fa-upload"></i> Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">SK Pengajaran Dosen Politeknik META Industri Cikarang</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th class="text-center">Tahun Akademik</th>
                            <th class="text-center">Prodi</th>
                            <th width="15%" class="text-center">File</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="text-center"><?php echo e($no++); ?></td>
                                <td><?php echo e($item->periode_tahun); ?> - <?php echo e($item->periode_tipe); ?></td>
                                <td><?php echo e($item->prodi); ?></td>
                                <td class="text-center">
                                    <?php if($item->file): ?>
                                        <a href="<?php echo e(asset('SK-Mengajar/' . $item->file)); ?>" target="_blank" class="btn btn-xs btn-primary">
                                            <i class="fa fa-file-pdf-o"></i> Lihat File
                                        </a>
                                    <?php else: ?>
                                        <span class="label label-danger">Tidak ada file</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?php echo e(url('delete_sk_pengajaran/' . $item->id_sk_pengajaran)); ?>"
                                       class="btn btn-xs btn-danger"
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus SK ini?')">
                                        <i class="fa fa-trash"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/adminprodi/perkuliahan/sk_pengajaran.blade.php ENDPATH**/ ?>