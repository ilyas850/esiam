<style media="screen">
    body {
        font-family: "Times New Roman", Times, serif;
    }

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

                <?php elseif($bap->id_kelas == 2 || $bap->id_kelas == 3): ?>
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
                <th width="4%">
                    <center>No</center>
                </th>
                <th width="12%" nowrap="nowrap" style="white-space: nowrap;">
                    <center>Tanggal </center>
                </th>
                <th width="14%" nowrap="nowrap" style="white-space: nowrap;">
                    <center>Jam</center>
                </th>
                <th>
                    <center>Materi</center>
                </th>
                <th width="12%" nowrap="nowrap" style="white-space: nowrap;">
                    <center>Paraf Dosen</center>
                </th>
                <th width="10%" nowrap="nowrap" style="white-space: nowrap;">
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
                    <td nowrap="nowrap" style="white-space: nowrap;">
                        <center><?php echo e($item->tanggal ? Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : ''); ?></center>
                    </td>
                    <td nowrap="nowrap" style="white-space: nowrap;">
                        <center><?php echo e($item->jam_mulai ? date('H:i', strtotime($item->jam_mulai)) : ''); ?> - <?php echo e($item->jam_selsai ? date('H:i', strtotime($item->jam_selsai)) : ''); ?></center>
                    </td>
                    <td><?php echo e($item->materi_kuliah); ?></td>
                    <td nowrap="nowrap" style="white-space: nowrap;">
                        <center>By System</center>
                    </td>
                    <td nowrap="nowrap" style="white-space: nowrap;">
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
    <?php
        $uasItem = $data->first(function ($item) {
            $materi = isset($item->materi_kuliah) ? strtoupper(trim($item->materi_kuliah)) : '';
            $jenis = isset($item->jenis_kuliah) ? strtoupper(trim($item->jenis_kuliah)) : '';
            $tipe = isset($item->tipe_kuliah) ? strtoupper(trim($item->tipe_kuliah)) : '';

            return $materi === 'UAS' || strpos($materi, 'UAS') !== false || $jenis === 'UAS' || $tipe === 'UAS';
        });

        $tgl_cikarang = '.........................';
        if ($uasItem && !empty($uasItem->tanggal)) {
            $bulanArr = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $carbonTgl = \Carbon\Carbon::parse($uasItem->tanggal)->addDays(7);
            $tgl_cikarang = $carbonTgl->format('d') . ' ' . $bulanArr[(int)$carbonTgl->format('m')] . ' ' . $carbonTgl->format('Y');
        }
    ?>
    <div style="page-break-inside: avoid; break-inside: avoid;">
        <table width="100%">
            <tr>
                <td width="3%"></td>
                <td width="50%"></td>
                <td width="47%"><span style="font-size:85%">Cikarang, <?php echo e($tgl_cikarang); ?></span></td>
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
                <td width="50%"><span style="font-size:85%"><?php echo e($cekkprd ? $cekkprd->nama . ', ' . $cekkprd->akademik : ''); ?></span></td>
                <td width="47%"><span style="font-size:85%"><?php echo e($bap->nama); ?>, <?php echo e($bap->akademik); ?></span></td>
            </tr>
        </table>
    </div>
</body>
<?php /**PATH /var/www/html/resources/views/sadmin/download/pdf_bap_perkuliahan.blade.php ENDPATH**/ ?>