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
                <a href="/data_pkl_mahasiswa" class="btn btn-info">Data PKL</a>
                <a href="/data_magang_mahasiswa" class="btn btn-success">Data Magang</a>
                <a href="/data_magang2_mahasiswa" class="btn btn-warning">Data Magang 2</a>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data PKL</h3>
            </div>
            <div class="box-body">
                <table id="example4" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Mahasiswa/NIM</th>
                            <th colspan="2">
                                <center>Dosen</center>
                            </th>
                            <th rowspan="2">
                                <center>Pengajuan</center>
                            </th>
                            <th colspan="2">
                                <center>Tanggal Aktual</center>
                            </th>
                            <th rowspan="2">Batas Waktu</th>
                            <th rowspan="2">Due Date</th>
                            <th rowspan="2">
                                <center>Jam Seminar</center>
                            </th>
                            <th rowspan="2">
                                <center>Acc. PraUSTA</center>
                            </th>
                            <th rowspan="2" width="6%">Aksi</th>
                        </tr>
                        <tr>
                            <th>
                                <center>Pembimbing</center>
                            </th>
                            <th>
                                <center>Penguji</center>
                            </th>
                            <th>
                                <center>Mulai</center>
                            </th>

                            <th>
                                <center>Selesai</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td align="center"><?php echo e($no++); ?></td>
                                <td><?php echo e($key->nama); ?>/<?php echo e($key->nim); ?></td>
                                <td><?php echo e($key->dosen_pembimbing); ?></td>
                                <td><?php echo e($key->dosen_penguji_1); ?></td>
                                <td align="center">
                                    <?php echo e($key->tgl_pengajuan); ?>

                                </td>
                                <td>
                                    <center><?php echo e($key->tanggal_mulai); ?></center>
                                </td>

                                <td>
                                    <center><?php echo e($key->tanggal_selesai); ?></center>
                                </td>
                                <td align="center">
                                    <?php echo e($key->batas_waktu); ?> hari
                                </td>
                                <td>
                                    <?php if($key->tgl_pengajuan == null): ?>
                                        <?php if(floor((strtotime($key->set_waktu_akhir) - $akhir) / (60 * 60 * 24)) > 0): ?>
                                            <span
                                                class="label label-info"><?php echo e(floor((strtotime($key->set_waktu_akhir) - $akhir) / (60 * 60 * 24))); ?>

                                                hari lagi</span>
                                        <?php else: ?>
                                            <span class="label label-danger">EXP. (
                                                <?php echo e(floor(($akhir - strtotime($key->set_waktu_akhir)) / (60 * 60 * 24))); ?>

                                                hari
                                                )</span>
                                        <?php endif; ?>
                                    <?php elseif(strtotime($key->tgl_pengajuan) > strtotime($key->set_waktu_akhir)): ?>
                                        <span class="label label-warning">Terlambat</span>
                                    <?php elseif(strtotime($key->tgl_pengajuan) < strtotime($key->set_waktu_akhir)): ?>
                                        <span class="label label-success">Tepat waktu</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <center><?php echo e($key->jam_mulai_sidang); ?> - <?php echo e($key->jam_selesai_sidang); ?></center>
                                </td>
                                <td>
                                    <center>
                                        <?php if($key->file_laporan_revisi == null): ?>
                                            <?php echo e($key->acc_seminar_sidang); ?>

                                        <?php elseif($key->file_laporan_revisi != null): ?>
                                            Selesai
                                        <?php endif; ?>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-xs">Pilih</button>
                                            <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                data-toggle="dropdown">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>
                                                    <a
                                                        href="/atur_prakerin/<?php echo e($key->id_settingrelasi_prausta); ?>">Setting</a>
                                                </li>
                                                <?php if($key->status == 'ACTIVE'): ?>
                                                    <li><a href="/nonatifkan_prausta_prakerin/<?php echo e($key->id_settingrelasi_prausta); ?>"
                                                            onclick="return confirm('anda yakin akan menonaktifkan?')">Nonaktifkan</a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/prausta/prakerin/data_pkl_mahasiswa.blade.php ENDPATH**/ ?>