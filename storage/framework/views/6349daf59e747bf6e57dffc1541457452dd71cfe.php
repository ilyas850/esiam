<style media="screen">
    table {
        border-collapse: collapse;
    }

    tr.b {
        line-height: 80px;
    }
</style>

<body>
    <table width="100%">
        <tr>
            <td>
                <img src="images/logo meta png.png" width="200" height="75" alt="" align="left">
            </td>
            <td>
                <center>
                    <img src="images/kop.png" width="200" height="70" alt="" align="right">
                </center>
            </td>
        </tr>
    </table>
    <br><br><br><br>
    <table width="100%">
        <tr>
            <td>Matakuliah</td>
            <td>:</td>
            <td><?php echo e($bap->makul); ?> - <?php echo e($bap->akt_sks); ?> SKS</td>
            <td>Tahun Akademik</td>
            <td>:</td>
            <td><?php echo e($bap->periode_tahun); ?> <?php echo e($bap->periode_tipe); ?></td>
        </tr>
        <tr>
            <td>Waktu / Ruangan</td>
            <td>:</td>
            <td><?php echo e($bap->hari); ?>,
                <?php if($bap->id_kelas == 1): ?>
                    <?php echo e(date('H:i', strtotime($bap->jam))); ?> -
                    <?php echo e(date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 120)); ?>

                <?php elseif($bap->id_kelas == 2): ?>
                    <?php echo e(date('H:i', strtotime($bap->jam))); ?> -
                    <?php echo e(date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90)); ?>

                <?php elseif($bap->id_kelas == 3): ?>
                    <?php echo e(date('H:i', strtotime($bap->jam))); ?> -
                    <?php echo e(date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90)); ?>

                <?php endif; ?>
                / <?php echo e($bap->nama_ruangan); ?>

            </td>
            <td>Program Studi</td>
            <td>:</td>
            <td><?php echo e($bap->prodi); ?></td>
        </tr>
        <tr>
            <td>Dosen</td>
            <td>:</td>
            <td><?php echo e($bap->nama); ?>, <?php echo e($bap->akademik); ?></td>
            <td>Kelas</td>
            <td>:</td>
            <td><?php echo e($bap->kelas); ?></td>
        </tr>
    </table>
    <br>
    <table border="1" width="100%">
        <thead>
            <tr>
                <th>
                    <center>No</center>
                </th>
                <th>
                    <center>Tanggal </center>
                </th>
                <th>
                    <center>Jam</center>
                </th>
                <th>
                    <center>Materi</center>
                </th>
                <th>
                    <center>Paraf Dosen</center>
                </th>
                <th>
                    <center>Validasi</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <center><?php echo e($no++); ?></center>
                    </td>
                    <td>
                        <center><?php echo e(Carbon\Carbon::parse($item->tanggal)->format('d-m-Y')); ?></center>
                    </td>
                    <td>
                        <center><?php echo e($item->jam_mulai ? date('H:i', strtotime($item->jam_mulai)) : ''); ?> - <?php echo e($item->jam_selsai ? date('H:i', strtotime($item->jam_selsai)) : ''); ?></center>
                    </td>
                    <td><?php echo e($item->materi_kuliah); ?></td>
                    <td>
                        <center>By System</center>
                    </td>
                    <td>
                        <center>
                            <?php if($item->tanggal_validasi == '2001-01-01'): ?>
                                BELUM
                            <?php else: ?>
                                SUDAH
                            <?php endif; ?>
                        </center>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <table width="100%">
        <tr>
            <td width="67%"><span style="font-size:85%">*) Validasi dilakukan oleh Prodi (Sekretaris Prodi) setiap
                    hari</span></td>
            <td width="33%"></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="3%"></td>
            <td width="50%"></td>
            <td width="47%"><span style="font-size:85%">Cikarang, .........................</span></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="3%"></td>
            <td width="50%"><span style="font-size:85%">Kepala Program Studi <?php echo e($bap->prodi); ?></span></td>
            <td width="47%"><span style="font-size:85%">Dosen Pengampu</span></td>
        </tr>
    </table>
    <br><br><br>
    <table width="100%">
        <tr>
            <td width="3%"></td>
            <td width="50%"><span style="font-size:85%"><?php echo e($cekkprd->nama); ?>, <?php echo e($cekkprd->akademik); ?></td>
            <td width="47%"><span style="font-size:85%"><?php echo e($bap->nama); ?>, <?php echo e($bap->akademik); ?></span></td>
        </tr>
    </table>
   
</body>
<?php /**PATH /var/www/html/resources/views/dosen/download/jurnal_perkuliahan_pdf.blade.php ENDPATH**/ ?>