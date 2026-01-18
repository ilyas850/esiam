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
                <img src="images/logo meta png.png" width="200" height="75" alt="" align="left" />
            </td>
            <td>
                <center>
                    <img src="images/kop.png" width="200" height="70" alt="" align="right" />
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
                    <?php echo e($bap->jam); ?> -
                    <?php echo e(date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 120)); ?>

                <?php elseif($bap->id_kelas == 2): ?>
                    <?php echo e($bap->jam); ?> -
                    <?php echo e(date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 45 + 60 * $bap->akt_sks_praktek * 90)); ?>

                <?php elseif($bap->id_kelas == 3): ?>
                    <?php echo e($bap->jam); ?> -
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
                    <center>NIM </center>
                </th>
                <th>
                    <center>Nama</center>
                </th>
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
                    <center>4</center>
                </th>
                <th>
                    <center>5</center>
                </th>
                <th>
                    <center>6</center>
                </th>
                <th>
                    <center>7</center>
                </th>
                <th>
                    <center>8</center>
                </th>
                <th>
                    <center>9</center>
                </th>
                <th>
                    <center>10</center>
                </th>
                <th>
                    <center>11</center>
                </th>
                <th>
                    <center>12</center>
                </th>
                <th>
                    <center>13</center>
                </th>
                <th>
                    <center>14</center>
                </th>
                <th>
                    <center>15</center>
                </th>
                <th>
                    <center>16</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>

            <?php $__currentLoopData = $abs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $itembs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <center><?php echo e($no++); ?></center>
                    </td>
                    <td>
                        <center><?php echo e($itembs->nim); ?></center>
                    </td>
                    <td><?php echo e($itembs->nama); ?></td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs4; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs6; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs7; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs8; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs9; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs10; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs11; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs12; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs13; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs14; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs15; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                    <td>
                        <center>
                            <?php $__currentLoopData = $abs16; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($itembs->id_studentrecord == $item1->id_studentrecord): ?>
                                    <?php if($item1->absensi == 'ABSEN'): ?>
                                        (&#10003;)
                                    <?php elseif($item1->absensi == 'HADIR'): ?>
                                        (X)
                                    <?php elseif($item1->absensi == 'SAKIT'): ?>
                                        (S)
                                    <?php elseif($item1->absensi == 'ALFA'): ?>
                                        (A)
                                    <?php elseif($item1->absensi == 'IZIN'): ?>
                                        (I)
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </center>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" align="right">Paraf Dosen</td>
                <td>

                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <table width="100%">
        <tr>
            <td width="76%" align=center><span style="font-size:85%"></span></td>
            <td width="24%"><span style="font-size:85%">Cikarang, </span></td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td width="76%" align=center><span style="font-size:85%"></span></td>
            <td width="24%" align=center><span style="font-size:85%"></span></td>
        </tr>
    </table>
    <br><br><br>
    <table width="100%">
        <tr>
            <td width="76%" align=center><span style="font-size:85%"></span></td>
            <td width="24%"><span style="font-size:85%"><?php echo e($bap->nama); ?>, <?php echo e($bap->akademik); ?></td>
        </tr>
    </table>

</body>
<?php /**PATH /var/www/html/resources/views/dosen/download/absensi_perkuliahan_pdf.blade.php ENDPATH**/ ?>