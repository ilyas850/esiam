<?php $__env->startSection('side'); ?>
  <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <section class="content">
    <!-- Filter Box -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-filter"></i> Filter Tahun Akademik & Semester</h3>
      </div>
      <div class="box-body">
        <form class="form" role="form" action="<?php echo e(url('nilai_mhs_kprd')); ?>" method="GET">
          <div class="row">
            <div class="col-md-4 col-sm-6">
              <label>Periode Tahun Akademik</label>
              <select class="form-control" name="id_periodetahun" required>
                <option value="">-- Pilih Tahun Akademik --</option>
                <?php $__currentLoopData = $periode_tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($pt->id_periodetahun); ?>" <?php echo e((isset($tahun) && $tahun == $pt->id_periodetahun) ? 'selected' : ''); ?>>
                    <?php echo e($pt->periode_tahun); ?>

                  </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="col-md-4 col-sm-6">
              <label>Tipe Semester</label>
              <select class="form-control" name="id_periodetipe" required>
                <option value="">-- Pilih Tipe Semester --</option>
                <?php $__currentLoopData = $periode_tipe; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ptip): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($ptip->id_periodetipe); ?>" <?php echo e((isset($tipe) && $tipe == $ptip->id_periodetipe) ? 'selected' : ''); ?>>
                    <?php echo e($ptip->periode_tipe); ?>

                  </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
            </div>
            <div class="col-md-4 col-sm-12" style="margin-top: 25px;">
              <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Tampilkan Data</button>
              <a href="<?php echo e(url('nilai_mhs_kprd')); ?>" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Header Summary Widgets -->
    <div class="row">
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-aqua">
          <span class="info-box-icon"><i class="fa fa-book"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Matakuliah</span>
            <span class="info-box-number"><?php echo e(count($nilai)); ?></span>
            <span class="progress-description">
              Periode <?php echo e($thn->periode_tahun ?? ''); ?> (<?php echo e($tp->periode_tipe ?? ''); ?>)
            </span>
          </div>
        </div>
      </div>
      
      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-green">
          <span class="info-box-icon"><i class="fa fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Mahasiswa</span>
            <span class="info-box-number"><?php echo e($nilai->sum('jml_mhs')); ?></span>
            <span class="progress-description">
              Mahasiswa Terdaftar
            </span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box bg-yellow">
          <span class="info-box-icon"><i class="fa fa-credit-card"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total SKS</span>
            <span class="info-box-number"><?php echo e($nilai->sum(function($i){ return $i->akt_sks_teori + $i->akt_sks_praktek; })); ?></span>
            <span class="progress-description">
              SKS Diajarkan
            </span>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-6 col-xs-12">
        <?php 
          $lengkap = $nilai->filter(function($i){ return $i->jml_mhs > 0 && $i->jml_terisi == $i->jml_mhs; })->count();
        ?>
        <div class="info-box <?php echo e($lengkap == count($nilai) && count($nilai) > 0 ? 'bg-green' : 'bg-red'); ?>">
          <span class="info-box-icon"><i class="fa fa-check-square-o"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Status Input Nilai</span>
            <span class="info-box-number"><?php echo e($lengkap); ?> / <?php echo e(count($nilai)); ?></span>
            <span class="progress-description">
              MK Lengkap Dinilai
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Table Box -->
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-list"></i> Rekap Nilai Mahasiswa per Matakuliah</h3>
        <?php if(isset($nilai[0])): ?>
          <span class="label label-primary pull-right" style="font-size: 13px; padding: 6px 10px;">
            <i class="fa fa-university"></i> <?php echo e($nilai[0]->prodi); ?>

          </span>
        <?php endif; ?>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th width="4%"><center>No</center></th>
                <th width="10%"><center>Kode MK</center></th>
                <th>Nama Matakuliah</th>
                <th width="7%"><center>SKS</center></th>
                <th width="10%"><center>Semester</center></th>
                <th width="10%"><center>Kelas</center></th>
                <th>Dosen Pengampu</th>
                <th width="8%"><center>Jml Mhs</center></th>
                <th width="14%"><center>Progres Nilai</center></th>
                <th width="9%"><center>Aksi</center></th>
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; ?>
              <?php $__currentLoopData = $nilai; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php 
                  $sks = $key->akt_sks_teori + $key->akt_sks_praktek;
                  $isLengkap = ($key->jml_mhs > 0 && $key->jml_terisi == $key->jml_mhs);
                  $isKosong = ($key->jml_terisi == 0);
                  $persen = $key->jml_mhs > 0 ? round(($key->jml_terisi / $key->jml_mhs) * 100) : 0;
                ?>
                <tr>
                  <td align="center"><?php echo e($no++); ?></td>
                  <td align="center">
                    <span class="label label-default"><?php echo e($key->kode); ?></span>
                  </td>
                  <td><strong><?php echo e($key->makul); ?></strong></td>
                  <td align="center">
                    <span class="badge bg-purple" title="Teori: <?php echo e($key->akt_sks_teori); ?> | Praktek: <?php echo e($key->akt_sks_praktek); ?>">
                      <?php echo e($sks); ?> SKS
                    </span>
                  </td>
                  <td align="center">
                    <span class="label label-warning"><?php echo e(Str::startsWith($key->semester, 'Semester') ? $key->semester : 'Semester ' . $key->semester); ?></span>
                  </td>
                  <td align="center">
                    <span class="label label-info"><?php echo e($key->kelas); ?></span>
                  </td>
                  <td><?php echo e($key->nama); ?></td>
                  <td align="center">
                    <span class="badge bg-blue"><?php echo e($key->jml_mhs); ?></span>
                  </td>
                  <td align="center">
                    <?php if($isLengkap): ?>
                      <span class="label label-success" style="font-size: 11px;">
                        <i class="fa fa-check-circle"></i> Lengkap (<?php echo e($key->jml_terisi); ?>/<?php echo e($key->jml_mhs); ?>)
                      </span>
                    <?php elseif($isKosong): ?>
                      <span class="label label-danger" style="font-size: 11px;">
                        <i class="fa fa-times-circle"></i> Belum (0/<?php echo e($key->jml_mhs); ?>)
                      </span>
                    <?php else: ?>
                      <span class="label label-warning" style="font-size: 11px;">
                        <i class="fa fa-clock-o"></i> <?php echo e($key->jml_terisi); ?>/<?php echo e($key->jml_mhs); ?> (<?php echo e($persen); ?>%)
                      </span>
                    <?php endif; ?>
                  </td>
                  <td align="center">
                    <a href="cek_nilai_mhs_kprd/<?php echo e($key->ids_kurperiode); ?>" class="btn btn-info btn-xs" title="Cek detail nilai mahasiswa">
                      <i class="fa fa-eye"></i> Cek Nilai
                    </a>
                  </td>
                </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/kaprodi/master/rekap_nilai.blade.php ENDPATH**/ ?>