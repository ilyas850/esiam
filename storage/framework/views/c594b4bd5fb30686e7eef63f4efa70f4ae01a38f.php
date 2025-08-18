<!DOCTYPE html>
<html>
<head>
    <title>Politeknik META Industri Cikarang</title>
</head>
<body>
    <table width="100%">
		<tr>
			<td>
					<img src="<?php echo e(asset('images/logo meta png.png')); ?>" width="200" height="75" alt="" align="left"/>
			</td>
			<td><center>
					<img src="<?php echo e(asset('images/kop.png')); ?>" width="200" height="70" alt="" align="right"/>
        </center>
			</td>
		</tr>
	</table><br>
    <center>
        <h2 class="box-title">Laporan Pembelajaran Daring Prodi <?php echo e($prd); ?> </h2>
        <h3 class="box-title">Semester <?php echo e($tipe); ?> – <?php echo e($tahun); ?></h3>
    </center>
    <table border="1" width="100%">
        <tr>
            <td>Matakuliah</td>
            <td><?php echo e($data->makul); ?></td>
        </tr>
        <tr>
            <td>Nama Dosen</td>
            <td><?php echo e($data->nama); ?></td>
        </tr>
        <tr>
            <td>Kelas / Semester</td>
            <td><?php echo e($data->kelas); ?> / <?php echo e($data->semester); ?></td>
        </tr>
        <tr>
            <td>Media Pemebelajaran</td>
            <td><?php echo e($dtbp->media_pembelajaran); ?></td>
        </tr>
        <tr>
            <td>Pukul</td>
            <td><?php echo e($dtbp->jam_mulai); ?> - <?php echo e($dtbp->jam_selsai); ?></td>
        </tr>
        <tr>
            <td>Tanggal Perkuliahan</td>
            <td><?php echo e($dtbp->tanggal); ?></td>
        </tr>
        <tr>
            <td>Materi Perkuliahan</td>
            <td><?php echo e($dtbp->materi_kuliah); ?></td>
        </tr>
        <tr>
            <td>Pertemuan</td>
            <td>Ke-<?php echo e($dtbp->pertemuan); ?></td>
        </tr>
        <tr>
            <td>Mahasiswa Hadir/Tidak Hadir</td>
            <td><?php echo e($dtbp->hadir); ?> / <?php echo e($dtbp->tidak_hadir); ?></td>
        </tr>
    </table>
    <div class="form-group">
        <h4>1.	Kuliah tatap muka</h4>
        <?php if(($dtbp->file_kuliah_tatapmuka) != null): ?>
        <img src="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Kuliah Tatap Muka/<?php echo e($dtbp->file_kuliah_tatapmuka); ?>"  width="60%" height="300px" />
        <?php else: ?>
        Tidak ada lampiran
        <?php endif; ?>
    </div>
    <div class="form-group">
        <h4>2.	Materi Perkuliahan</h4>
        <?php if(($dtbp->file_materi_kuliah) != null): ?>
        <img src="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Materi Kuliah/<?php echo e($dtbp->file_materi_kuliah); ?>" type="application/pdf" width="60%" height="300px" />
        <?php else: ?>
        Tidak ada lampiran
        <?php endif; ?>
    </div>
    <div class="form-group">
        <h4>3.	Materi Tugas</h4>
        <?php if(($dtbp->file_materi_tugas) != null): ?>
          <img src="/File_BAP/<?php echo e($data->iddosen); ?>/<?php echo e($dtbp->id_kurperiode); ?>/Tugas Kuliah/<?php echo e($dtbp->file_materi_tugas); ?>" type="application/pdf" width="60%" height="300px" />
        <?php else: ?>
          Tidak ada lampiran
        <?php endif; ?>
    </div>
    <br><br><br>
    <table width="100%">
     <tr>
            <td width="20%" ><span style="font-size:85%">Cikarang, <?php echo e($d); ?> <?php echo e($m); ?> <?php echo e($y); ?></span></td>
     </tr>
    </table>
    <br><br><br><br>
	<table width="100%">
		<tr>
			<td width="30%" ><span style="font-size:85%"><?php echo e($data->nama); ?></span></td>
		</tr>
	</table>
    <script>
        window.print();
    </script>
</body>
</html>
<?php /**PATH /var/www/html/resources/views/sadmin/perkuliahan/cetak_bap.blade.php ENDPATH**/ ?>