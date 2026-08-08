<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Politeknik META Industri</title>
    <style>
        /* CSS untuk mengatur tampilan PDF */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .content {
            margin: 20px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            padding: 10px;
        }

        table {
            border-collapse: collapse;
        }

        tr.b {
            line-height: 80px;
        }
    </style>
</head>

<body>
    <div class="header">
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
    </div>
    <br><br><br>
    <div class="content">
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
                        <?php echo e(date('H:i', strtotime($bap->jam) + 60 * $bap->akt_sks_teori * 50 + 60 * $bap->akt_sks_praktek * 170)); ?>

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
                    <th width="4%">
                        <center>No</center>
                    </th>
                    <th width="10%">
                        <center>NIM </center>
                    </th>
                    <th>
                        <center>Nama</center>
                    </th>
                    <th width="3%">
                        <center>1</center>
                    </th>
                    <th width="3%">
                        <center>2</center>
                    </th>
                    <th width="3%">
                        <center>3</center>
                    </th>
                    <th width="3%">
                        <center>4</center>
                    </th>
                    <th width="3%">
                        <center>5</center>
                    </th>
                    <th width="3%">
                        <center>6</center>
                    </th>
                    <th width="3%">
                        <center>7</center>
                    </th>
                    <th width="3%">
                        <center>8</center>
                    </th>
                    <th width="3%">
                        <center>9</center>
                    </th>
                    <th width="3%">
                        <center>10</center>
                    </th>
                    <th width="3%">
                        <center>11</center>
                    </th>
                    <th width="3%">
                        <center>12</center>
                    </th>
                    <th width="3%">
                        <center>13</center>
                    </th>
                    <th width="3%">
                        <center>14</center>
                    </th>
                    <th width="3%">
                        <center>15</center>
                    </th>
                    <th width="3%">
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
                                <?php if($itembs->absn1 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn1 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn1 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn1 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn1 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn2 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn2 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn2 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn2 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn2 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn3 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn3 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn3 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn3 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn3 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn4 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn4 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn4 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn4 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn4 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn5 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn5 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn5 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn5 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn5 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn6 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn6 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn6 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn6 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn6 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn7 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn7 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn7 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn7 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn7 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn8 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn8 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn8 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn8 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn8 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn9 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn9 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn9 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn9 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn9 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn10 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn10 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn10 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn10 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn10 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn11 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn11 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn11 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn11 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn11 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn12 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn12 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn12 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn12 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn12 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn13 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn13 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn13 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn13 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn13 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn14 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn14 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn14 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn14 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn14 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn15 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn15 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn15 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn15 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn15 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
                            </center>
                        </td>
                        <td>
                            <center>
                                <?php if($itembs->absn16 == 'ABSEN'): ?>
                                    (V)
                                <?php elseif($itembs->absn16 == 'HADIR'): ?>
                                    (X)
                                <?php elseif($itembs->absn16 == 'SAKIT'): ?>
                                    (S)
                                <?php elseif($itembs->absn16 == 'ALFA'): ?>
                                    (A)
                                <?php elseif($itembs->absn16 == 'IZIN'): ?>
                                    (I)
                                <?php endif; ?>
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
        <br>
        <table width="100%">
            <tr>
                <td width="70%" align=center></td>
                <td width="30%">Cikarang, </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="70%" align=center></td>
                <td width="30%" align=center></td>
            </tr>
        </table>
        <br><br><br>
        <table width="100%">
            <tr>
                <td width="70%" align=center></td>
                <td width="30%"><?php echo e($bap->nama); ?>, <?php echo e($bap->akademik); ?></td>
            </tr>
        </table>
    </div>

    <div class="footer">

    </div>
</body>

</html>
<?php /**PATH /var/www/html/resources/views/sadmin/download/pdf_absensi_perkuliahan.blade.php ENDPATH**/ ?>