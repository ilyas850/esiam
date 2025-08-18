<?php $__env->startSection('content'); ?>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-6 order-md-2">
                    <img src="<?php echo e(asset('dsg_login/images/login 1.png')); ?>" alt="Image" class="img-fluid">
                </div>
                <div class="col-md-6 contents">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h3>Log In to <strong>eSIAM</strong></h3>
                                <p class="mb-4">Empowering to Industry</p>
                            </div>
                            <form method="POST" action="<?php echo e(route('login')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="form-group first">
                                    <label>Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="<?php echo e(old('username')); ?>" required autocomplete="username" autofocus>
                                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="form-group last mb-4">
                                    <label>Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required
                                        autocomplete="current-password">
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="d-flex mb-5 align-items-center">
                                    
                                </div>

                                <input type="submit" value="Log In" class="btn text-white btn-block btn-primary">

                                <span class="d-block text-left my-4 text-muted"> <a href="/">Back to
                                        HOME</a></span>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master_auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/auth/masuk.blade.php ENDPATH**/ ?>