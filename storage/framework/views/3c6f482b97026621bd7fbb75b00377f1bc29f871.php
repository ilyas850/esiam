<style media="screen">
	table {
		border-collapse: collapse;
	}
	tr.b{
		line-height:80px;
	}

</style>
<body>
    <table width="100%">
        <tr>
            <td width="50%">
                <img src="images/logo meta png.png" height="50" alt="" align="left" />
            </td>
            <td width="50%" align="right">
                <img src="images/kop.png" height="45" alt="" align="right" />
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 5px; margin-bottom: 5px;">
        <tr>
            <td align="center">
                <h4 style="margin: 2px 0;"><b>DAFTAR NILAI AKHIR</b></h4>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-bottom: 10px;">
        <tr>
            <td width="15%"><b><span style="font-size:85%">Kode Matakuliah </span></b></td>
            <td width="2%"> : </td>
            <td width="33%"><b><span style="font-size:85%"><u><?php echo e($data->kode); ?></u></span></b></td>
            <td width="15%"><b><span style="font-size:85%">Tahun Akademik </span></b></td>
            <td width="2%"> : </td>
            <td width="33%"><b><span style="font-size:85%"><u><?php echo e($data->periode_tahun); ?> <?php echo e($data->periode_tipe); ?></u></span></b></td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Matakuliah</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u><?php echo e($data->makul); ?> - <?php echo e($data->akt_sks); ?> SKS</u></span></b></td>
            <td><b><span style="font-size:85%">Program Studi</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u><?php echo e($data->prodi); ?></u></span></b></td>
        </tr>
        <tr>
            <td><b><span style="font-size:85%">Dosen</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u><?php echo e($data->nama); ?>, <?php echo e($data->akademik); ?></u></span></b></td>
            <td><b><span style="font-size:85%">Kelas</span></b></td>
            <td> : </td>
            <td><b><span style="font-size:85%"><u><?php echo e($data->kelas); ?></u></span></b></td>
        </tr>
    </table>
    <table border="1" width="100%" cellpadding="4">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th width="4%"><span style="font-size:85%">No</span></th>
                <th width="14%"><span style="font-size:85%">NIM</span></th>
                <th width="32%"><span style="font-size:85%">Nama Mahasiswa</span></th>
                <th width="10%"><span style="font-size:85%">Nilai KAT</span></th>
                <th width="10%"><span style="font-size:85%">Nilai UTS</span></th>
                <th width="10%"><span style="font-size:85%">Nilai UAS</span></th>
                <th width="10%"><span style="font-size:85%">Nilai AKHIR</span></th>
                <th width="10%"><span style="font-size:85%">Nilai HURUF</span></th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; ?>
            <?php $__currentLoopData = $tb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td style="font-size:85%">
                        <center><?php echo e($i++); ?></center>
                    </td>
                    <td style="font-size:85%">
                        <center><?php echo e($item->nim); ?></center>
                    </td>
                    <td style="font-size:85%"><?php echo e($item->nama); ?></td>
                    <td style="font-size:85%">
                        <center><?php echo e($item->nilai_KAT); ?></center>
                    </td>
                    <td style="font-size:85%">
                        <center><?php echo e($item->nilai_UTS); ?></center>
                    </td>
                    <td style="font-size:85%">
                        <center><?php echo e($item->nilai_UAS); ?></center>
                    </td>
                    <td style="font-size:85%">
                        <center><?php echo e(floor((float)$item->nilai_AKHIR_angka) == (float)$item->nilai_AKHIR_angka ? (int)$item->nilai_AKHIR_angka : round((float)$item->nilai_AKHIR_angka, 2)); ?></center>
                    </td>
                    <td style="font-size:85%">
                        <center><?php echo e($item->nilai_AKHIR); ?></center>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <br>
    <div style="page-break-inside: avoid; break-inside: avoid; margin-top: 20px;">
        <table width="100%">
            <tr>
                <td width="65%"></td>
                <td width="35%" align="left">
                    <span style="font-size:85%">Cikarang, ..............................</span><br>
                    <span style="font-size:85%">Dosen Pengampu</span>
                    <br><br><br><br><br>
                    <span style="font-size:85%"><b>(<?php echo e($data->nama); ?>, <?php echo e($data->akademik); ?>)</b></span>
                </td>
            </tr>
        </table>
    </div>
</body>
<?php /**PATH /var/www/html/resources/views/dosenluar/unduh_nilai_pdf.blade.php ENDPATH**/ ?>