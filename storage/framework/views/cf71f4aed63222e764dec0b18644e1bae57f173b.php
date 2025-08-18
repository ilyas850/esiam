<?php $__env->startSection('side'); ?>

  <?php echo $__env->make('layouts.side', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <div class="row">
    <div class="col-md-6 col-md-offset-3">
       <br>
        <div class="box box-info">

          <div class="box-header with-border">
            <span class="fa fa-graduation-cap"></span>
            <h3 class="box-title">Selamat Datang Mahasiswa Politeknik META Industri Cikarang</h3>
          </div>

          <form class="form-horizontal" role="form" method="POST" action="/pwd/<?php echo e($id); ?>/store">
            <?php echo e(csrf_field()); ?>

            <input id="role" type="hidden" class="form-control" name="role" value="3">
            <div class="box-body">
              <div class="form-group<?php echo e($errors->has('oldpassword') ? ' has-error' : ''); ?>">
                  <label class="col-sm-4 control-label">Password lama</label>

                  <div class="col-sm-7">
                      <input type="number" class="form-control" name="oldpassword" value="<?php echo e(old('oldpassword')); ?>" placeholder="Masukan NIM anda" required autofocus>

                      <?php if($errors->has('oldpassword')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('oldpassword')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
                  <label for="password" class="col-sm-4 control-label">Password Baru</label>

                  <div class="col-sm-7">
                      <input id="password" type="password" class="form-control" name="password" placeholder="Password Min. 7 karakter" required>

                      <?php if($errors->has('password')): ?>
                          <span class="help-block">
                              <strong><?php echo e($errors->first('password')); ?></strong>
                          </span>
                      <?php endif; ?>
                  </div>
              </div>

              <div class="form-group">
                  <label for="password-confirm" class="col-sm-4 control-label">Konfirmasi Password</label>

                  <div class="col-sm-7">
                      <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Konfirmasi password" required>
                  </div>
              </div>
            </div>
            <div class="box-footer">
              <button class="btn btn-info pull-right" type="submit">Simpan</button>
              <input type="hidden" name="_method" value="PUT">
              <a class="btn btn-default" href="<?php echo e(url('home')); ?>">Batal</a>
              
            </div>
          </form>
        </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/mhs/new_pwd.blade.php ENDPATH**/ ?>