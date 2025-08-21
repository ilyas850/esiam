<?php $__env->startSection('side'); ?>

  <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
  <section class="content">
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title"><b>Edit Data Diri</b></h3>
          </div>

          <form class="form-horizontal" role="form" method="POST" action="<?php echo e(url('save_update')); ?>">
            <?php echo e(csrf_field()); ?>

            <input id="role" type="hidden" class="form-control" name="id_mhs" value="<?php echo e($mhs->idstudent); ?>">
            <input id="role" type="hidden" class="form-control" name="nim_mhs" value="<?php echo e($mhs->nim); ?>">
            <div class="box-body">
              <div class="form-group">
                  <label class="col-sm-4 control-label">No HP baru</label>

                  <div class="col-sm-7">
                      <input type="text" class="form-control" name="hp_baru" placeholder="Masukan No HP baru anda">

                      <?php if($errors->has('hp_baru')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('hp_baru')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                  <label for="password" class="col-sm-4 control-label">E-mail baru</label>

                  <div class="col-sm-7">
                      <input type="email" class="form-control" name="email_baru" placeholder="Masukan E-mail baru anda">

                      <?php if($errors->has('email_baru')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('email_baru')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

            </div>
            <div class="box-footer">
              <button class="btn btn-info pull-right" type="submit">Simpan</button>
              <a href="<?php echo e(url('home')); ?>" class="btn btn-default">Kembali</a>
            </div>
          </form>
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/update.blade.php ENDPATH**/ ?>