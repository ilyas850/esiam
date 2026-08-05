<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Pilih Tipe</h3>
            </div>
            <div class="box-body">
                <a href="/data_nilai_pkl_mahasiswa" class="btn btn-info">Data Nilai PKL</a>
                <a href="/data_nilai_magang_mahasiswa" class="btn btn-success">Data Nilai Magang</a>
                <a href="/data_nilai_magang2_mahasiswa" class="btn btn-warning">Data Nilai Magang 2</a>
            </div>
        </div>
        <div class="box box-warning">
            <div class="box-header">
                <h3 class="box-title">Data Nilai Magang 2 Mahasiswa</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Tanggal Seminar</center>
                            </th>
                            <th rowspan="2">
                                <center>NIM</center>
                            </th>
                            <th rowspan="2">
                                <center>Nama Mahasiswa</center>
                            </th>

                            <th rowspan="2">
                                <center>Program Studi</center>
                            </th>
                            <th rowspan="2">
                                <center>Kelas</center>
                            </th>
                            <th colspan="4">
                                <center>Nilai</center>
                            </th>
                            <th rowspan="2">
                                <center>Unduh Form</center>
                            </th>
                            <th rowspan="2">
                                <center>Aksi</center>
                            </th>
                        </tr>
                        <tr>
                            <th>
                                <center>1</center>
                            </th>
                            <th>
                                <center>2</center>
                            </th>
                            <th>
                                <center>3</center>
                            </th>
                            <th>
                                <center>Huruf</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <center><?php echo e($no++); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->tanggal_selesai); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->nim); ?></center>
                                </td>
                                <td><?php echo e($key->nama); ?></td>
                                <td>
                                    <center><?php echo e($key->prodi); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->kelas); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->nilai_1); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->nilai_2); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->nilai_3); ?></center>
                                </td>
                                <td>
                                    <center><?php echo e($key->nilai_huruf); ?></center>
                                </td>
                                <td>
                                    <center>
                                        <a href="/unduh_nilai_prakerin_b/<?php echo e($key->id_settingrelasi_prausta); ?>"
                                            class="btn btn-info btn-xs">Pembimbing</a>
                                        <a href="/unduh_nilai_prakerin_c/<?php echo e($key->id_settingrelasi_prausta); ?>"
                                            class="btn btn-success btn-xs">Seminar</a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="edit_nilai_magang2/<?php echo e($key->id_settingrelasi_prausta); ?>"
                                            class="btn btn-warning btn-xs" title="klik untuk edit"><i
                                                class="fa fa-edit"></i></a>
                                        <?php if($key->validasi == 0): ?>
                                            <a href="validate_nilai_magang2/<?php echo e($key->id_settingrelasi_prausta); ?>"
                                                class="btn btn-primary btn-xs" title="klik untuk validasi"><i
                                                    class="fa fa-check"></i></a>
                                        <?php else: ?>
                                            <a href="unvalidate_nilai_magang2/<?php echo e($key->id_settingrelasi_prausta); ?>"
                                                class="btn btn-danger btn-xs" title="klik untuk batal validasi"><i
                                                    class="fa fa-close"></i></a>
                                        <?php endif; ?>
                                    </center>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/prakerin/nilai_magang2.blade.php ENDPATH**/ ?>