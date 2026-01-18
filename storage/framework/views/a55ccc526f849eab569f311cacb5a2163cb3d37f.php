<?php $__env->startSection('side'); ?>
    <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<style>
    /* 1. Sembunyikan titik radio button asli (tetap sama) */
    #tabel-absensi .btn-group label input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    /* 2. Style dasar untuk SEMUA tombol (mode "outline") */
    #tabel-absensi .btn-group label.btn {
        background-color: #fff; /* Latar belakang putih */
        border: 1px solid;      /* Garis pinggir solid */
        font-weight: bold;
        transition: all 0.2s ease-in-out;
    }

    /* 3. Atur warna TULISAN dan GARIS PINGGIR untuk setiap status */
    #tabel-absensi .btn-group label.btn-success { color: #00a65a; }
    #tabel-absensi .btn-group label.btn-warning { color: #f39c12; }
    #tabel-absensi .btn-group label.btn-info    { color: #00c0ef; }
    #tabel-absensi .btn-group label.btn-danger  { color: #dd4b39; }

    /* 4. Style untuk tombol yang AKTIF atau di-hover (mode "fill") */
    #tabel-absensi .btn-group label.btn.active,
    #tabel-absensi .btn-group label.btn:hover {
        color: #fff !important; /* Tulisan menjadi putih */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transform: translateY(-1px);
    }
    
    /* 5. Atur warna LATAR BELAKANG untuk tombol yang AKTIF atau di-hover */
    #tabel-absensi .btn-group label.btn-success.active,
    #tabel-absensi .btn-group label.btn-success:hover {
        background-color: #00a65a;
    }
    #tabel-absensi .btn-group label.btn-warning.active,
    #tabel-absensi .btn-group label.btn-warning:hover {
        background-color: #f39c12;
    }
    #tabel-absensi .btn-group label.btn-info.active,
    #tabel-absensi .btn-group label.btn-info:hover {
        background-color: #00c0ef;
    }
    #tabel-absensi .btn-group label.btn-danger.active,
    #tabel-absensi .btn-group label.btn-danger:hover {
        background-color: #dd4b39;
    }
</style>


<?php $__env->startSection('content'); ?>
    <section class="content">
        <div class="box box-primary"> 
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-check-square-o"></i> Absensi Mahasiswa</h3>
                
                
            </div>
            <form action="<?php echo e(url('save_absensi')); ?>" method="post">
                <?php echo e(csrf_field()); ?>

                <input type="hidden" name="id_kurperiode" value="<?php echo e($idk); ?>">
                <input type="hidden" name="id_bap" value="<?php echo e($id); ?>">

                <div class="box-body">
                    <div class="callout callout-info">
                        <h4><i class="fa fa-info-circle"></i> Petunjuk Pengisian</h4>
                        <p>Secara default, semua mahasiswa ditandai <strong>HADIR</strong>. Silakan ubah status bagi
                            mahasiswa yang tidak hadir.</p>
                    </div>

                    <table class="table table-bordered table-hover" id="tabel-absensi"> 
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Mahasiswa (Nama & NIM)</th>
                                <th class="text-center" width="15%">Program Studi</th>
                                <th class="text-center" width="10%">Kelas</th>
                                <th class="text-center" width="35%"> 
                                    Status Kehadiran
                                    
                                    <div class="btn-group btn-group-xs pull-right">
                                        <button type="button" class="btn btn-default" id="tandaiHadirSemua">Hadir
                                            Semua</button>
                                        <button type="button" class="btn btn-default" id="tandaiAlpaSemua">Alpa
                                            Semua</button>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $absen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td class="text-center"><?php echo e($loop->iteration); ?></td>
                                    <td>
                                        <strong><?php echo e($item->nama); ?></strong>
                                        <br>
                                        <small class="text-muted"><?php echo e($item->nim); ?></small>
                                    </td>
                                    <td><?php echo e($item->prodi); ?></td>
                                    <td class="text-center"><?php echo e($item->kelas); ?></td>
                                    <td class="text-center">
                                        
                                        <div class="btn-group btn-group-sm">
                                            <label class="btn btn-success active">
                                                <input type="radio" name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,ABSEN" checked> Hadir
                                            </label>
                                            <label class="btn btn-warning">
                                                <input type="radio" name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,IZIN"> Izin
                                            </label>
                                            <label class="btn btn-info">
                                                <input type="radio" name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,SAKIT"> Sakit
                                            </label>
                                            <label class="btn btn-danger">
                                                <input type="radio" name="absensi_radio[<?php echo e($item->id_studentrecord); ?>]"
                                                    value="<?php echo e($item->id_studentrecord); ?>,ALFA"> Alpa
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <p class="text-muted">Tidak ada data mahasiswa untuk kelas ini.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="box-footer">
                    <button id="simpan" class="btn btn-primary btn-lg pull-right" type="submit"><i class="fa fa-save"></i>
                        Simpan Absensi</button>
                </div>
            </form>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?> 
    <script>
        $(document).ready(function () {

            // =================================================================
            // LOGIKA BARU UNTUK MEMBUAT TOMBOL BERFUNGSI SECARA MANUAL
            // =================================================================
            // Saat sebuah label (tombol) di dalam grup tombol absensi diklik...
            $('#tabel-absensi .btn-group label').on('click', function () {
                var label = $(this);
                var radio = label.find('input[type=radio]');

                // 1. Pastikan radio button di dalamnya benar-benar terpilih
                radio.prop('checked', true);

                // 2. Hapus class 'active' dari semua tombol lain dalam grup yang sama
                label.siblings().removeClass('active');

                // 3. Tambahkan class 'active' ke tombol yang baru saja diklik
                label.addClass('active');
            });


            // =================================================================
            // FUNGSI "TANDAI SEMUA" YANG DISESUAIKAN DENGAN LOGIKA BARU
            // =================================================================
            $('#tandaiHadirSemua').click(function () {
                // Cari semua label/tombol "Hadir" dan picu (trigger) event klik
                $('#tabel-absensi .btn-group label.btn-success').click();
            });

            $('#tandaiAlpaSemua').click(function () {
                // Cari semua label/tombol "Alpa" dan picu (trigger) event klik
                $('#tabel-absensi .btn-group label.btn-danger').click();
            });


            // =================================================================
            // FUNGSI UNTUK MENCEGAH DOUBLE SUBMIT (TETAP SAMA)
            // =================================================================
            $('form').submit(function () {
                $('#simpan').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
            });

        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/dosen/absensi.blade.php ENDPATH**/ ?>